<?php

namespace Platform\Controller;

use Common\Controller\PlatformBaseController;
use Common\Model\WebsiteCategoryModel;
use Common\Model\WebsiteManagementModel;
use Common\Model\WebsiteProfileModel;

class WebsiteController extends PlatformBaseController
{
    public function management(){
        $WebsiteCategoryModel = new WebsiteCategoryModel();
        $WebsiteManagementModel = new WebsiteManagementModel();
        $CategoryList = $WebsiteCategoryModel->getCategory();
        $currentCategoryId = I('get.category_id/d', 0); // 获取传入的 category_id
        $where = [];
        if ($currentCategoryId > 0) {
            $where['w.category_id'] = $currentCategoryId;
        }
        $WebsiteLists = $WebsiteManagementModel->getWebsiteLists($where);
        // 将 category_id 转换为整数
        foreach ($CategoryList as &$item) {
            $item['category_id'] = intval($item['category_id']);
        }
        $this->assign("CategoryList", $CategoryList);
        $this->assign('currentCategoryId', $currentCategoryId);
        $this->assign('WebsiteLists', $WebsiteLists);
        $this->display();
    }

    public function siteEdit()
    {
        $mid = I('get.mid/d', 0);
        $WebsiteManagementModel = new WebsiteManagementModel();
        $WebsiteData = $WebsiteManagementModel->getWebsiteById($mid);
        if(!$WebsiteData){
            $this->error('当前站群不存在');
        }

        if(IS_POST && IS_AJAX){
            $data = I('post.');
            if($WebsiteManagementModel->setWebsiteById($mid,$data) === true){
                $this->ajaxReturn(['code'=>200,'message'=>'修改成功']);
            }else{
                $this->ajaxReturn(['code'=>500,'message'=>$WebsiteManagementModel->getError()]);
            }
        }

        $WebsiteCategoryModel = new WebsiteCategoryModel();
        $WebsiteProfileModel = new WebsiteProfileModel();
        $WebsiteProfileData = $WebsiteProfileModel->getWebsiteProfileData();
        $WebsiteCategoryData = $WebsiteCategoryModel->getCategory();
        $BindDomains = implode("\n",unserialize($WebsiteData['bind_domains']));
        // var_dump($WebsiteProfileData);
        $this->assign('BindDomains', $BindDomains);
        $this->assign('WebsiteData', $WebsiteData);
        $this->assign('WebsiteProfileData', $WebsiteProfileData);
        $this->assign('WebsiteCategoryData', $WebsiteCategoryData);
        $this->assign('mid',$mid);
        $this->display();
    }

    public function tdkemplate()
    {
        $WebsiteModel = new WebsiteProfileModel();
        $WebsiteProfileLists = $WebsiteModel->getWebsiteProfileLists();
        $this->assign("data", $WebsiteProfileLists['data']);
        $this->assign("page", $WebsiteProfileLists['page']);
        $this->display();
    }

    public function tdkemplateadd(){
        if(IS_POST && IS_AJAX){
            $WebsiteModel = new WebsiteProfileModel();
            $_post = I('post.');
            if($WebsiteModel->createWebsiteProfile($_post) === true){
                $this->ajaxReturn(['code'=>200,'message'=>'添加成功']);
            }else{
                $this->ajaxReturn(['code'=>500,'message'=>$WebsiteModel->getError()]);
            }
        }
        $this->display();
    }

    public function tdkemplateedit(){
        $tid = I('get.tid/d', 0);
        $WebsiteProfileModel = new WebsiteProfileModel();
        $WebsiteProfileData = $WebsiteProfileModel->getWebsiteProfileById($tid);
        if(!$WebsiteProfileData){
            $this->error('当前TDK模板不存在');
        }
        $this->assign('WebsiteProfileData', $WebsiteProfileData);
        $this->display();
    }

    public function createCategory(){
        if(IS_POST && IS_AJAX){
            $WebsiteModel = new WebsiteCategoryModel();
            $_post = I('post.');
            if($WebsiteModel->createCategory($_post) === true){
                $this->ajaxReturn(['code'=>200,'message'=>'创建分类成功']);
            }else{
                $this->ajaxReturn(['code'=>500,'message'=>$WebsiteModel->getError()]);
            }
        }
        $this->display();
    }

    public function siteadd()
    {
        if(IS_POST && IS_AJAX){
            $WebsiteManagementModel = new WebsiteManagementModel();
            $_post = I('post.');
            if($WebsiteManagementModel->createWebsites($_post) === true){
                $this->ajaxReturn(['code'=>200,'message'=>'添加完成']);
            }else{
                $this->ajaxReturn(['code'=>500,'message'=>$WebsiteManagementModel->getError()]);
            }
        }

        $WebsiteCategoryModel = new WebsiteCategoryModel();
        $CategoryList = $WebsiteCategoryModel->getCategory();
        $WebsiteProfileModel = new WebsiteProfileModel();
        $WebsiteProfileLists = $WebsiteProfileModel->getWebsiteProfileName();
        $this->assign("ProfileList", $WebsiteProfileLists);
        $this->assign("CategoryList", $CategoryList);

        $this->display();
    }
}