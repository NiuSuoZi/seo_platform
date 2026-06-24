<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/25
 * Time: 0:10
 */

namespace Platform\Controller;
use Common\Controller\PlatformBaseController;
class ServerController extends PlatformBaseController
{
    public function index(){
            //$this->display();
        // 841756743:AAH5Q5b9MBBii9gCe1_ncJCqnp7IPU_ABcU
        // -631318485
        //https://api.telegram.org/bot841756743:AAH5Q5b9MBBii9gCe1_ncJCqnp7IPU_ABcU/sendMessage?chat_id=-631318485&text=ddd
        var_dump(curl_post_proxy('https://api.telegram.org/bot841756743:AAH5Q5b9MBBii9gCe1_ncJCqnp7IPU_ABcU/sendMessage',array(
            'chat_id'=>'-631318485',
            'text'=>date('Ymd H:i:s') . '消息测试!!'
        )));
    }

    public function keywords_group(){
        // 查询关键词库
        // D('Keywords')->getRandskeywords(3,'title',15);
        $getAlltable = S('getAlltable');
        $datas = [];
        // 如果缓存结果为空，则查询数据库
        if(empty($getAlltable)){
            $KeywordsModel = new \Common\Model\KeywordsModel();
            $datas = $KeywordsModel->getAlltable();
            // $datas = D('Keywords')->getAlltable();
            //缓存
             S('getAlltable',$datas,120);
        }else{
            $datas = S('getAlltable');
        }

        $this->assign("datas",$datas);
        $this->display();
    }

    public function api(){
        $cached_name = 'platform_server_api_list_' . I('get.page');
        $action = I('get.action');
        $data = I('post.');
        if(IS_POST){
            // ajax查询某个ID接口是否启用
            if($action === 'select_domain'){
                $ApiModel = new \Common\Model\ApiModel();
                $ids = I('post.interfaces_id');
                $status = $ApiModel->get_status($ids);
                $this->ajaxReturn($status);
            }

            if($action === 'add'){
                $ApiModel = new \Common\Model\ApiModel();
                $info = $ApiModel->add_api_info($data);
                if(true !== $info){
                    $this->error($info,U('platform/server/api'));
                    exit;
                }else{
                    $this->success('新增接口成功',U('platform/server/api'));
                    S($cached_name,null);
                    exit;
                }

            }
        }
        if(empty(S($cached_name)) !== null) {
            $ApiModel = new \Common\Model\ApiModel();
            $list = $ApiModel->select_list();
            S($cached_name,$list,3600);
        }else{
            $list = S($cached_name);
        }
            $this->assign('data',$list['data']);
            $this->assign('page',$list['page']);
            $this->display();
    }

    public function import_keywords(){
        $this->display();
    }

    public function article(){
        $this->display();
    }

    public function templates(){
        $action = I('get.action');

        if($action == ''){
            $TemplatesModels = New \Common\Model\TemplateModel();
            $TemplateList = $TemplatesModels->get_templates_list();
            if(is_array($TemplateList))
            {
                $this->assign('TemplateList',$TemplateList);
            }
            $this->display();
        }


        if($action == 'add'){

        }


        if($action == 'edit'){

        }

        if($action == 'show'){
            $tid = I('post.tid');
            if(IS_AJAX){
                $TemplatesModels = new \Common\Model\TemplateModel();
                $TemplateComelate = $TemplatesModels->get_templates($tid);
                $TemplateComelate[0]['t_source'] = htmlentities($TemplateComelate[0]['t_source']);
                $this->ajaxReturn($TemplateComelate);
            }
        }
    }
}