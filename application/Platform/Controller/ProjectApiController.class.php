<?php

namespace Platform\Controller;


use Common\Controller\PlatformBaseController;

class ProjectApiController extends PlatformBaseController
{


    // 聚合API 为了节省资源将API数据统一返回给前端
    public function mix()
    {
        $keyword_type = isset($_GET['type']) ? 'tl' : 'nor'; // 判断获取词库的类型
        $response = array(); // 返回的json格式

        // var_dump($keyword_type);


        // 词库处理逻辑
        $cache = S('keyword_list');
        if (!$cache) {
            $html = Api('keyword/list', []);
            $to_array = json_decode($html, true);
            S('keyword_list', $to_array);
            $list = $to_array;
        } else {
            if (empty($cache[2])) {
                S('keyword_list', null);
                $list = array();
            }
            $list = $cache;
        }
        foreach ($list as $key => $value) {
            if ($keyword_type == 'tl') {
                if (strpos($value['table'], 'seo_tl') !== false) {
                    $data[] = [
                        'name' => $value['table'],
                        'value' => $value['table']
                    ];
                }
            }else {
                if (strpos($value['table'], 'seo_tl') === false) {
                    // If the 'table' value does NOT contain 'seo_tl', add it to the $data array
                    $data[] = [
                        'name' => $value['table'],
                        'value' => $value['table']
                    ];
                }
            }
        }

        $response['keyword'] = array_values($data);



        $template_cache = S('template_cache');
        if(!$template_cache) {
            $list = Api('tpl/list');
            $list = json_decode($list,true);
            S('template_cache',$list);
        }else{
            $list = $template_cache;
        }


        $json_to_array = $list;
        $normal = $json_to_array['data'];

        $pjax = null;
        if(is_null($normal) || empty($normal)){
            $this->ajaxReturn(['data'=>[],'code'=>500]);
        }

        foreach ($normal as $key=>$value){
            $pjax[$key]['name'] = $value['name'];
            $pjax[$key]['value'] = $value['name'];
        }


        $response['template'] = $pjax;

        $content = Api('content/list');

        $response['content'] = $content;

        $pic = Api('pic/list');
        $response['pic'] = $pic;

        $response['rules'] = get_rules_list($keyword_type === 'tl');










        $this->ajaxReturn(['data' => $response, 'code' => 200]);

    }




    public function keyword()
    {
        if (IS_GET) {
            $cache = S('keyword_list');
            if (!$cache) {
                $html = Api('keyword/list', []);
                $to_array = json_decode($html, true);
                S('keyword_list', $to_array);
                $list = $to_array;
            } else {
                if (empty($cache[2])) {
                    S('keyword_list', null);
                    $list = array();
                }
                $list = $cache;
            }

            if (!$list) {
                $this->assign('datas', []);
                $this->display();
                exit;
            }

            if ($_GET['type'] == 'tl') {
                foreach ($list as $key => $value) {
                    if (strpos($value['table'], 'seo_tl') !== false) {
                        $data[] = [
                            'name' => $value['table'],
                            'value' => $value['table']
                        ];
                    }
                }

                $this->ajaxReturn(['data' => array_values($data)]);
            }else {
                // ajax获取
                foreach ($list as $key => $value) {
                    if (strpos($value['table'], 'seo_tl') === false) {
                        // If the 'table' value does NOT contain 'seo_tl', add it to the $data array
                        $data[] = [
                            'name' => $value['table'],
                            'value' => $value['table']
                        ];
                    }
                }

                $this->ajaxReturn(['data' => $data, 'code' => 200]);

            }
        }
    }

    public function template(){
        $template_cache = S('template_cache');
        if(!$template_cache) {
            $list = Api('tpl/list');
            $list = json_decode($list,true);
            S('template_cache',$list);
        }else{
            $list = $template_cache;
        }


        $json_to_array = $list;
        $normal = $json_to_array['data'];

            $pjax = null;
            if(is_null($normal) || empty($normal)){
                $this->ajaxReturn(['data'=>[],'code'=>500]);
            }

            foreach ($normal as $key=>$value){
                $pjax[$key]['name'] = $value['name'];
                $pjax[$key]['value'] = $value['name'];
            }
            $this->ajaxReturn(['data'=>$pjax,'code'=>200]);
    }

    public function content(){
        $source = Api('content/list');
        exit($source);
    }

    public function pic(){
        $source = Api('pic/list');
        exit($source);
    }
}