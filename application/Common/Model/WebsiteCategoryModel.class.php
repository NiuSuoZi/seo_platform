<?php

namespace Common\Model;

use Think\Model;

class WebsiteCategoryModel extends Model
{
    protected $tableName = 'website_category';

    protected $_validate = array(
        array('category_name','require','类别名称必须填写！'), //默认情况下用正则进行验证
        array('category_name', '/^[a-zA-Z0-9_\p{Han}\s]+$/u', '分类名称不能包含非法字符！', 0, 'regex'),
        array('category_name', '', '类别名称已存在！', 0, 'unique', 1), // 唯一值验证
        array('category_name', '3,25', '类别名称长度必须在3到25个字符之间！', 0, 'length'),
    );


    public function createCategory($data)
    {
        if($this->token(false)->create($data)){
            $this->data($data)->add();
            return true;
        }else{
            return $this->getError();
        }
    }

    public function getCategory()
    {
        return $this->order('category_id DESC')->select();
    }
}