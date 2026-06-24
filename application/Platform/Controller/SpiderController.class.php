<?php
namespace Platform\Controller;

use Common\Controller\PlatformBaseController;

class SpiderController extends PlatformBaseController
{

    public function index()
    {
        $this->display();
    }

    /**
     *  今日蜘蛛统计
     */
    public function show_today(){
        // 搜索引擎分类
        $spider = (int)I('get.types','','int');
        $pages = (int)I('get.pages',1,'int');
        $search_method = I('get.search_method');
        $search_allow = array('domain','inurl','class');
        if(in_array($search_method,$search_allow)){
            echo '搜索';
            exit;
        }
        $SpiderModel = new \Common\Model\SpiderLogModel();
        $dump = $SpiderModel->select_today($spider);
         // echo $SpiderModel->getLastSql();
        //var_dump($dump['data']);
        // echo $dump['page'];
        $list = array(
            1 => '百度蜘蛛',
            2 => '搜狗蜘蛛',
            3 => '360蜘蛛',
            4 => '必应蜘蛛',
            5 => '谷歌蜘蛛',
            6 => '神马蜘蛛',
            7 => 'Yandex蜘蛛',
            8 => 'Coccoc蜘蛛',
            9 => 'Naver蜘蛛'
        );


        $count = array(
            'baidu' => empty(S('log_baiduspider_count')) ? 0 : S('log_baiduspider_count'),
            'sogou' =>empty(S('log_sogouspider_count')) ? 0 : S('log_sogouspider_count'),
            '360' => empty(S('log_360spider_count')) ? 0 : S('log_360spider_count'),
            'bing' => empty(S('log_bingbot_count')) ? 0 : S('log_bingbot_count'),
            'google' => empty(S('log_googlebot_count')) ? 0 : S('log_googlebot_count'),
            'sm' => empty(S('log_smspider_count')) ? 0 : S('log_smspider_count'),
        );
        $total = 0;
        foreach ($count as $value){
            $total += $value;
        }
        $count['total'] = $total;

        $this->assign('count',$count);
        $this->assign('data',$dump['data']);
        $this->assign('list',$list);
        $this->assign('page',$dump['page']);
        $this->display();
    }

    /**
     *  昨日蜘蛛统计
     */
    public function show_yesterday()
    {
        // 搜索引擎分类
        $spider = (int)I('get.types', '', 'int');
            $SpiderModel = new \Common\Model\SpiderLogModel();
            $dump = $SpiderModel->select_yesterday($spider);
        // echo $SpiderModel->getLastSql();
        //var_dump($dump['data']);
        // echo $dump['page'];
        $list = array(
            1 => '百度蜘蛛',
            2 => '搜狗蜘蛛',
            3 => '360蜘蛛',
            4 => '必应蜘蛛',
            5 => '谷歌蜘蛛',
            6 => '神马蜘蛛',
            7 => 'Yandex蜘蛛',
            8 => 'Coccoc蜘蛛',
            9 => 'Naver蜘蛛'
        );
        $this->assign('list',$list);
        $this->assign('data',$dump['data']);
        $this->assign('page',$dump['page']);
        $this->display();
    }

    // 蜘蛛排行
    public function ranking(){
        $model = new \Common\Model\SpiderLogModel();
         dump($model->ranking());
        $this->display();
    }

    /**
     * AJAX 获取某个域名的收录/入站时间等信息 返回包含上述信息的JSON数据格式
     */
    public function get_domain_info(){
        if(IS_AJAX){
            $domain = I('post.domains');
        }
    }

    /**
     *   今日蜘蛛总汇
     */
    public function information(){
        $InformationModel = new \Common\Model\InformationModel();
        $data = $InformationModel->lists();
        $this->assign('data',$data['data']);
        $this->assign('page',$data['page']);
        $this->display();
    }

    public function tracking(){
        $this->display();
    }

}