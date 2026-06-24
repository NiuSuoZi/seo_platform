<?php

namespace Platform\Controller;

use Common\Controller\PlatformBaseController;
use Common\Model\ControlModel;

class ControlController extends PlatformBaseController
{
    private $tags_static = array('{数字}', '{字母}', '{大写字母}', '{大小写字母}', '{大写字母数字}', '{大小写字母数字}', '{数字字母}', '{随机字符}', '{随机泰语}', '{日期}', '{数字日期}', '{年}', '{月}', '{日}', '{时}', '{分}', '{秒}');

    public function index()
    {
        $controllModule = new ControlModel();
        $DATA = $controllModule->get_list();
        $this->assign('data', $DATA['data']);
        $this->assign('page', $DATA['page']);
        $this->display();

    }

    public function create()
    {
        $data = I('post.');
        $controllModule = new ControlModel();
        if (!$controllModule->create($data)) {
            $this->ajaxReturn(['code' => 404, 'data' => array('msg' => $controllModule->getError())]);
        } else {
            $mid = $controllModule->add($data);
            $this->ajaxReturn(['code' => 200, 'data' => array('msg' => '添加成功,正在跳转详细配置页...', 'redirect' => U('platform/control/edit?cid=' . $mid))]);
        }
    }

    public function edit()
    {

        $cid = I('get.cid', 'intval');

        if (IS_POST && IS_AJAX) {
            $_request = I('post.');
            $_data = [];

            $_data['keyword'] = $_request['keyword'];

            $_data['seo_option'] = serialize(array(
                'project_template' => $_request['project_template'],
                'encode_style' => is_array($_request['encode_style']) ? implode(',', $_request['encode_style']) : $_request['encode_style'],
                'page_compress' => empty($_request['page_compress']) ? 'off' : $_request['page_compress'],
                'disable_snapshots' => empty($_request['disable_snapshots']) ? 'off' : $_request['disable_snapshots'],
                'title_style' => $_request['title_style'],
                'keyword_style' => $_request['keyword_style'],
                'description_style' => $_request['description_style'],
                'article_source' => $_request['article_source'],
                'title_source' => $_request['title_source'],
                'pic_source' => $_request['pic_source'],
                'page_language' => $_request['page_language'],
                'tdk_encode' => $_request['tdk_encode'],
                'tdk_encode_style' => is_array($_request['tdk_encode_style']) ? implode(',', $_request['tdk_encode_style']) : $_request['tdk_encode_style'],
            ));

            $_data['advert_option'] = serialize(array(
                'ad_js' => strip_tags($_request['ad_js'])
            ));

            $_data['cache_option'] = serialize(array(
                'cache_on' => empty($_request['cache_on']) ? 'off' : $_request['cache_on'],
                'cache_type' => empty($_request['cache_type']) ? 1 : intval($_request['cache_type']),
                'cache_expires' => empty($_request['cache_expires']) ? 0 : intval($_request['cache_expires']),
            ));


            $lines = explode("\n", $_request['ilink_rules']);
            // 普通URL的检查
            $ControllModule = new ControlModel();
            $current_type = (int)$ControllModule->field('type')->where(['mid'=>$cid])->find()['type'];
            if($current_type == 0){
            foreach ($lines as $item => $value) {
                $line = $item + 1;
                // 循环每一行 检查每一行是否包含 $url_rules 标签
                $flag = 0;
                for ($i = 0; $i < count($this->tags_static); $i++) {
                    // 计算$url_rules 数组长度 循环检查是否包含特定字符串
                    if (strstr($value, $this->tags_static[$i]) || preg_match('/\{(数字\d{1,2}|数字(\d)\-(\d{1,5})|字母\d{1,2}|字母(\d)\-(\d{1,5}))\}/', $value)) {
                        $flag++;
                    }
                }
                if ($flag === 0) {
                    return $this->ajaxReturn(['code' => 404, 'msg' => '当前URL规则第【' . $line . '】未通过程序检验，检查后重试~']);
                }
                }
            }else {
                foreach ($lines as $item => $value) {
                    $line = $item + 1;
                    // 循环每一行 检查每一行是否包含 $url_rules 标签
                    $flag = 0;
                    if (strstr($value, '{tl}')) {
                        $flag++;
                    }
                    if ($flag === 0) {
                        return $this->ajaxReturn(['code' => 404, 'msg' => '当前URL规则第【' . $line . '】未通过程序检验，检查后重试~']);
                    }
                }
            }



            $_data['ilink_rules'] =  base64_encode(serialize($_request['ilink_rules']));
            $controllModule = new ControlModel();
            if(!$controllModule->token(false)->create($_data)){
                $this->ajaxReturn(['code'=>404,'msg'=>$controllModule->getError()]);
            }else{
                $controllModule->where(['mid'=>intval($cid)])->save($_data);
                // 查询绑定的域名
                $current_domain = $controllModule->field('bind_domain')->where(['mid'=>intval($cid)])->find();
                Api('sync/update',array('siteId'=>$current_domain['bind_domain']));
                $this->ajaxReturn(['code'=>200,'msg'=>'修改成功！！']);
            }
        }


            $controllModule = new ControlModel();
            $data = $controllModule->where(['mid' => intval($cid)])->find();
            if (!$data) {
                $this->error('未找到当前配置!', U('platform/control/index'));
            }
            $current_type = (int)$controllModule->field('type')->where(['mid'=>$cid])->find()['type'];
            $data['seo_option'] = unserialize($data['seo_option']);
            $data['cache_option'] = unserialize($data['cache_option']);
            $data['advert_option'] = unserialize($data['advert_option']);
            if(I('get.kwa') == '1'){
                $this->ajaxReturn([

                    'keyword'=>$current_type == 1 ? $data['keyword'] : explode(',',$data['keyword']),
                    'encode_style' => isset($data['seo_option']['keyword_style']) && !empty($data['seo_option']['title_style']) ? $data['seo_option']['encode_style'] : '',
                    'project_template'=>explode(',',$data['seo_option']['project_template']),
                    'cache_type'=>$data['seo_option']['cache_option']['cache_type'],
                    'title_style' => isset($data['seo_option']['keyword_style']) && !empty($data['seo_option']['title_style']) ? explode(",",$data['seo_option']['title_style']) : ['0'],
                    'keyword_style'=>isset($data['seo_option']['keyword_style']) && !empty($data['seo_option']['keyword_style']) ? explode(",",$data['seo_option']['keyword_style']) : ['0'],
                    'description_style' => isset($data['seo_option']['description_style']) && !empty($data['seo_option']['description_style']) ? explode(",",$data['seo_option']['description_style']) : ['0'],
                    'title_source'=>isset($data['seo_option']['title_source']) && !empty($data['seo_option']['title_source']) ? $data['seo_option']['title_source'] : '',
                    'article_source'=>isset($data['seo_option']['article_source']) && !empty($data['seo_option']['article_source']) ? $data['seo_option']['article_source'] : '',
                    'pic_source'=> isset($data['seo_option']['pic_source']) && !empty($data['seo_option']['pic_source']) ? $data['seo_option']['pic_source'] : '',
                    'tdk_encode'=> isset($data['seo_option']['tdk_encode']) && !empty($data['seo_option']['tdk_encode']) ? $data['seo_option']['tdk_encode'] : '',
                    'tdk_encode_style'=> isset($data['seo_option']['tdk_encode_style']) && !empty($data['seo_option']['tdk_encode_style']) ? $data['seo_option']['tdk_encode_style'] : '',
                    ]);
            }

        if(I('get.info') == 'cat'){
            IS_AJAX ? $this->ajaxReturn(json_decode(Api('cache/hosting',['clsid'=>$data['clsid']]),true)) : $this->redirect(U('Platform/Control/index'));
        }
        if(I('get.info') == 'total'){
            IS_AJAX ? $this->ajaxReturn(json_decode(Api('cache/total',['siteId'=>$data['clsid']]),true)) : $this->redirect(U('Platform/Control/index'));
        }
        if(I('get.info') == 'get_total'){
            IS_AJAX ? $this->ajaxReturn(json_decode(Api('cache/hosting_total',['clsid'=>$data['clsid']]),true)) : $this->redirect(U('Platform/Control/index'));
        }


            $this->assign('info', $data);
            $this->display();


    }
}
