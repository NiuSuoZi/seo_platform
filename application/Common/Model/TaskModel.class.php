<?php


namespace Common\Model;


use Think\Model;

class TaskModel extends Model
{
    protected $tableName = 'proxy';

    public function get_domain($domain){
        $map = array(
            'url' => $domain
        );

        return $this->table(C('DB_PREFIX') . 'proxy')->where($map)->field('id,baidu_token')->order('id DESC')->find();
    }

    public function test($domain){
        $map = array(
            'url' => $domain
        );

        return $this->table(C('DB_PREFIX') . 'proxy')->where($map)->field('id,baidu_token')->order('id DESC')->find();
    }
}