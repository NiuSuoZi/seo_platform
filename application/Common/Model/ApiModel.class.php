<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 7/1/2018
 * Time: 3:58 PM
 */

namespace Common\Model;
use Common\Model\BaseModel;

class ApiModel extends BaseModel
{
    protected $tableName = 'Api';
    protected $pk = 'ids';

    // 自动验证规则
    protected $_validate = array(
        array('domain','chenk_domain','域名格式错误',1,'function'),
        array('domain','','不可重复部署域名',0,'unique',1),
        array('name','require','名称必填'),
        array('name','','部署名称重复！',0,'unique',1)
    );

    // 自动完成
    protected $autoinc = array(
        array('access_token','md5',3,'function') ,
        array('create_times','time',0,'function'),
        array('request_keywords_count','0'),
        array('request_random_news_title_count','0'),
        array('request_random_article_count','0')
    );

    /**  后台添加接口
     * @param array $data
     * @return bool|string
     */
    public function add_api_info($data = array()){
        if(is_array($data)){
            $data['create_times'] = time();
            if(!$this->create($data)){
                return $this->getError();
            }else{
                $this->add();
                return true;
            }
        }
    }

    /**
     *   接口认证
     * @param string $keys
     * @return array
     */
    public function auth_keys($keys = ''){
        $returns = array();
        // echo $this->where(['access_token'=>$keys])->limit(1)->select();
        $query_status = $this->where(['access_token'=>$keys])->limit(1)->select();
        //echo $this->getLastSql);
        //var_dump($query_status);
        $returns['status'] = intval($query_status[0]['status']);
        $returns['domain'] = $query_status[0]['domain'];
        return $returns;
    }

    /** 获取某个接口是否开启
     * @param $id
     * @return mixed
     */
    public function get_status($id){
        if(!empty($id)){
            $id = (int)$id;
            return $this->where(array('ids'=>$id))->find();
        }
    }

    public function get_domain($domain){

        $map = array(
            'url' => $domain
        );

        return $this->table(C('DB_PREFIX') . 'proxy')->where($map)->field('id,baidu_token')->order('id DESC')->find();
    }

    /** 查询列表
     * @return array
     */
    public function select_list(){
        $data = array();
        $Page_count = $this->order('create_times DESC')->count();
        $Page = new \Think\Page($Page_count,30);
        $data['data'] = $this->order('create_times DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data['page'] = $Page->show();
        return $data;
    }
}