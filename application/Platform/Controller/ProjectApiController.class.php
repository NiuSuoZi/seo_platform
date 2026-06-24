<?php

namespace Platform\Controller;


use Common\Controller\PlatformBaseController;

class ProjectApiController extends PlatformBaseController
{


    // 聚合API 为了节省资源将API数据统一返回给前端
    public function mix()
    {
        $keyword_type = isset($_GET['type']) && $_GET['type'] === 'tl' ? 'tl' : 'nor';

        $list = platform_keyword_list();
        $response = [
            'keyword' => platform_keyword_options($list, $keyword_type),
            'template' => platform_template_options(),
            'content' => Api('content/list'),
            'pic' => Api('pic/list'),
            'rules' => get_rules_list($keyword_type === 'tl'),
        ];

        $this->ajaxReturn(['data' => $response, 'code' => 200]);
    }




    public function keyword()
    {
        if (!IS_GET) {
            return;
        }

        $list = platform_keyword_list();
        if (empty($list)) {
            $this->ajaxReturn(['data' => [], 'code' => 200]);
        }

        $type = isset($_GET['type']) && $_GET['type'] === 'tl' ? 'tl' : 'nor';
        $data = platform_keyword_options($list, $type);

        if (isset($_GET['type']) && $_GET['type'] === 'tl') {
            $this->ajaxReturn(['data' => $data]);
        }

        $this->ajaxReturn(['data' => $data, 'code' => 200]);
    }

    public function template(){
        $options = platform_template_options();
        if (empty($options)) {
            $this->ajaxReturn(['data' => [], 'code' => 500]);
        }
        $this->ajaxReturn(['data' => $options, 'code' => 200]);
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
