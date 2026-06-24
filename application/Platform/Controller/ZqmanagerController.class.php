<?php


namespace Platform\Controller;
use Common\Controller\PlatformBaseController;


class ZqmanagerController extends PlatformBaseController
{

    // 面板首页
    public function panel(){
        $PanelModel = new \Common\Model\ZqmanngerPanelModel();
        $panelData = $PanelModel->panelData();
        $history = $PanelModel->get_history_list();
        $this->assign('data',$panelData);
        $this->assign('page',$history['page']);
        $this->assign('history',$history['data']);
        $this->display();
    }
    // 蜘蛛日志
    public function log(){
        $types = (int)I('get.types');
        $zQmanager_log_Model = new \Common\Model\ZqmanagerModel();
        $List = $zQmanager_log_Model->getList($types);
        $page = $List['page'];
        $data = $List['data'];
        $spider_list = array('全部列表','BaiduSpider','SogouSpider','360Spider','YisouSpider');
        $this->assign('lists',$spider_list);
        $this->assign('page',$page);
        $this->assign('data',$data);
        $this->display();

    }

}