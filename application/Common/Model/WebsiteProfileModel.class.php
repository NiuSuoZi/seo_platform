<?php

namespace Common\Model;

use Think\Model;

class WebsiteProfileModel extends Model
{
    protected $tableName = 'website_profile';

    protected $_validate = array(
        array('display_name','require','显示名称必须填写！'), //默认情况下用正则进行验证
        array('display_name', '/^[a-zA-Z0-9_\p{Han}\s]+$/u', '显示名称不能包含特殊字符！', 0, 'regex'), // 验证不含特殊字符
        array('category',array(1,2),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
        array('category_template','require','站点模板不能为空！'), //默认情况下用正则进行验证
        array('category_template', '/^(?!.*(\.\.|\/|\\\))[a-zA-Z0-9_-]+\.html$/', '文件名仅支持 a-Z, 0-9, -, _ 且不能包含特殊字符或路径字符！', 0, 'regex'), // 验证不含特殊字符
        array('ad_url','require','广告URL JS 不能为空！'), //默认情况下用正则进行验证
        array('ad_url', '/^(https?:\/\/)[^\s]+$/', '广告URL 必须以 http:// 或 https:// 开头！', 0, 'regex'),
        array('category_keyword','require','关键词不能为空！'), //默认情况下用正则进行验证
        array('category_keyword', '/^seo_tl_[A-Za-z0-9+_-]+$/', '关键词必须以 "seo_tl_" 开头，并且仅支持字母、数字、+、_ 和 - 的组合！', 0, 'regex')
    );

    public function getWebsiteProfileName(){
        return $this->field('display_name,id')->order('id DESC')->select();
    }

    public function getWebsiteProfileData(){
        return $this->order('id DESC')->select();
    }

//    public function getWebsiteProfileLists()
//    {
//        $Page_count = $this->order('last_edit_time DESC')->count();
//        $Page = new \Think\Page($Page_count,30);
//        $data['data'] = $this->order('last_edit_time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
//        $data['page'] = $Page->show();
//        return $data;
//    }

    public function getWebsiteProfileLists()
    {
        $Page_count = $this->count(); // 当前表的总记录数
        $Page = new \Think\Page($Page_count, 30); // 分页

        // 使用左连接统计 profile_id 的引用次数
        $profileLists = $this->alias('p') // 为当前表设置别名
        ->field('p.*, COUNT(l.profile_id) AS usage_count')
            ->join('LEFT JOIN seo_website_lists l ON p.id = l.profile_id') // 左连接
            ->group('p.id') // 分组统计
            ->order('p.last_edit_time DESC') // 排序
            ->limit($Page->firstRow . ',' . $Page->listRows) // 分页
            ->select();

        $data['data'] = $profileLists;
        $data['page'] = $Page->show();

        return $data;
    }

    public function createWebsiteProfile($data){
        if($this->token(false)->create($data)){
            $data['last_edit_time'] = time();
            $this->data($data)->add();
            return true;
        }else{
            return $this->getError();
        }
    }

    public function getWebsiteProfileById($id){
        return $this->where(array('id' => intval($id)))->find();
    }




}