<?php


namespace Common\Model;
use Think\Model;

class ZqmanagerModel extends Model
{

    protected $tableName = 'zqmanager_log';
    protected $pk = 'zq_id';

    protected $_validate = array(
        array('zq_spider_types','1,2,3,4','types error',2,'in'),
        array('zq_request_uri','chenk_urls','url is not.',0,'function'),
        array('zq_domain','chenk_domain','domain is error.',0,'function'),
        array('zq_real_ip','require','types is error.'),
        array('zq_remark','require','types is error.')
    );


    public function __construct($name = '', $tablePrefix = '', $connection = '')
    {
        parent::__construct($name, $tablePrefix, $connection);
    }


    /**
     * 获取今日来访列表
     * @param int $types
     * @return array
     */
    public function getList($types = 0){
        $spider_types = $types;
        $map = array();
        $data = array();
        if($types == 0){
            $map['zq_spider_types'] = array('in','1,2,3,4');
        }else{
            if($types > 4 or $types < 1)
            {
                $map['zq_spider_types'] = array('in','1,2,3,4');
            }else {
                $map['zq_spider_types'] = array('eq',$spider_types);
            }
        }

        $map['zq_request_times'] = array(
            array('egt',mktime(0,0,0,date('m'),date('d'),date('Y'))),
            array('lt',mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1)
        );
        $Page_count = $this->table('seo_zqmanager_log')->where($map)->order('zq_request_times DESC')->count();
        $Page = new \Think\Page($Page_count,40);
        $data['data'] = $this->table('seo_zqmanager_log')->where($map)->order('zq_request_times DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data['page'] = $Page->show();

        return $data;
    }

    public function addData($data = array()){
        if(!$this->create($data)){
            exit ($this->getError());
        }else{
            $this->add($data);
            return true;
        }
    }

}