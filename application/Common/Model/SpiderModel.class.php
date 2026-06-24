<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 6/29/2018
 * Time: 2:32 PM
 */

namespace Common\Model;
use Think\Model\AdvModel;
class SpiderModel extends AdvModel
{
    protected $pk = 'pid';
    protected $autoinc = true;
    protected $partition = array('field'=>'uuid','type'=>'mod','num'=>'9');
    // 自动验证规则

    // 分表操作

    // protected $partition = array('field'=>'pid','type'=>'mod','num'=>'8');

    protected $_validate = array(
        array('spider_types','1,2,3,4,5,6','types error',2,'in'),
        array('spider_url','chenk_urls','url is not.',0,'function'),
        array('spider_domain','chenk_domain','domain is error.',0,'function'),
        array('spider_cached','chenk_cached','cache name is error',0,'function'),
        array('types','require','types is error.'),
        array('uuid','require','types is uuid.'),
        array('spider_kt','require','types is error.'),
    );

    protected $times;
    protected $next_times;

    // 格式化时间戳为时间字符串

    protected $format_times = '';
    protected $format_next_times = '';

    // 构造方法
    public function __construct($name = '', $tablePrefix = '', $connection = '')
    {
        parent::__construct('spider_history', 'seo_', $connection);
    }
    //

    public function getDao($data=array()) {
//     　　$data = empty($data) ? $_POST : $data;
            $table = $this->getPartitionTableName($data);
            return $this->table($table);
    }

    public function addData($data = array()){
        if(!$this->create($data)){
            $this->getError();
        }else{
            $this->getDao($data)->add();
//            $models->data($data);
            return true;
        }
    }

    // 昨日蜘蛛统计
    public function select_yesterday($types)
    {
        $spider_types = $types;
        $map = array();
        $data = array();
        if($types == ''){
            $map['spider_types'] = array('in','1,2,3,4,5,6');
        }else{
            if($types > 6 or $types < 1)
            {
                $map['spider_types'] = array('in','1,2,3,4,5,6');
            }else {
                $map['spider_types'] = array('eq',$spider_types);
            }
        }

        $map['spider_times'] = array(
            array('egt',mktime(0,0,0,date('m'),date('d')-1,date('Y'))),
            array('lt',mktime(0,0,0,date('m'),date('d'),date('Y'))-1)
        );

        $fetchSql = $this->getDao($map)->where($map)->order('spider_times DESC')->fetchSql(true)->count();
        $dump = $this->query('EXPLAIN ' . $fetchSql);
        $Page_count = $dump[0]['rows'];
        $Page = new \Think\Page($Page_count,30);
        $data['data'] = $this->getDao()->where($map)->order('spider_times DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data['page'] = $Page->show();

        return $data;
    }

    public function test(){

    }

    // 今日百度蜘蛛总量
    public function today_baidu_spider(){
        $map['spider_types'] = 1;
        $map['spider_times'] = array(
            array('egt',mktime(0,0,0,date('m'),date('d')-1,date('Y'))),
            array('lt',mktime(0,0,0,date('m'),date('d'),date('Y'))-1)
        );
        $data = $this->where($map)->count('pid');
        print_r($data);

    }

    // 今日蜘蛛统计 返回40条
    public function select_today($types){
        $spider_types = $types;
        $map = array();
        $data = array();
        if($types == ''){
            $map['spider_types'] = array('in','1,2,3,4,5,6');
            }else{
            if($types > 6 or $types < 1)
            {
                $map['spider_types'] = array('in','1,2,3,4,5,6');
            }else {
                $map['spider_types'] = array('eq',$spider_types);
            }
        }

        $map['spider_times'] = array(
            array('egt',mktime(0,0,0,date('m'),date('d'),date('Y'))),
            array('lt',mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1)
        );

//        $test = $this->getDao()->where($map)->order('spider_times DESC')->fetchSql(true)->count();
//        echo $test;
//        exit;
        $fetchSql = $this->getDao($map)->where($map)->order('spider_times DESC')->fetchSql(true)->count();
        $dump = $this->query('EXPLAIN ' . $fetchSql);
        $Page_count = $dump[0]['rows'];
//        $Page_count = $this->where($map)->order('spider_times DESC')->count();
        $Page = new \Think\Page($Page_count,30);
        $data['data'] = $this->getDao()->where($map)->order('spider_times DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data['page'] = $Page->show();

        return $data;
    }



}