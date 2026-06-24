<?php


namespace Common\Model;
use Common\Model\BaseModel;

class NotificationModel extends BaseModel
{
    protected $tableName = 'notification';
    protected $tablePrefix = 'seo_';
    protected $_validate = array(
        array('dingding_status', 'require', '必填项缺失！', 1 ),
        array('cloudchat_status', 'require', '必填项缺失！', 1 ),
        array('telegram_status', 'require', '必填项缺失！', 1 ),
        array('feishu_status', 'require', '必填项缺失！', 1 ),
        array('email_status', 'require', '必填项缺失！', 1 ),
    );

    protected $_auto = array(
        array('last_updateTime','time',2,'function')
    );

    public function updateSetting($data = array()){
        if(!$this->create($data)){
            exit($this->getError());
        }else{
            $this->add($data);
        }
    }

    public function selectSetting(){
        return $this->select();
    }
}