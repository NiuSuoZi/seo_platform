<?php


namespace Common\Model;


use Think\Model;

class ProjectModel extends BaseModel
{
    protected $pk = 'id';
    protected $tableName = 'project';
    protected $_validate = array(
        array('project_name', 'require', '网站名称必填！', 1 ),
        array('project_url', 'chenk_urls', '网站URL格式错误！',0,'function'),
        array('rankname', 'require', '备注信息必填！',1),
        array('site_type',array(0,1,2,3,4),'值的范围不正确！',2,'in'),
        array('keyword', 'require', '词库类型必填！', 1 ),
        array('monitor_status', 'require', '项目监控错误！', 1 ),
        array('ilink_rules', 'require', '内链地址错误！', 1 ),
    );

    protected $_auto = array(
        array('update_time','time',3,'function') ,
    );

    /** 更新一个网站列表
     * @param null $sid
     * @param array $data
     * @return bool
     */
    public function update_sites($sid = null,$data = array()){

        return $this->table(C('DB_PREFIX') . 'project')->where(['id'=>$sid])->save($data);

    }

    /**
     * @param array $data
     * @return mixed
     */
//    protected function is_exists($url = '',$site_types = 0){
//
//        $project_info = $this->table(C('DB_PREFIX') . 'project')->where(['url'=>$url])->find();
////        var_dump($project_info);
//
//        // 如果已查到项目进行site_type比对 是否存在该项目类型
//        var_dump($project_info);
//        var_dump();
//        if($project_info){
//            if(intval($project_info['site_type']) !== $site_types){
//                return true;
//            }else{
//                return false;
//            }
//        }else{
//            return true;
//        }
//
//    }

    protected function if_exists($data = array()){

        if(!is_array($data)){
            return false;
        }
        $url = $data['project_url'];
        $project_infos = $this->table(C('DB_PREFIX') . 'project')->where(['project_url'=>$url])->find();
//        var_dump($project_info);

        if($project_infos){
            if(intval($project_infos['site_type']) === intval($data['site_type'])){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }

    }
    public function delete_sites($sid){
        return $this->table(C('DB_PREFIX') . 'project')->where(['id'=>$sid])->delete();
    }

    public function query_sid($sid){
        return $this->table(C('DB_PREFIX') . 'project')->where(['id'=>$sid])->find();
    }

    public function query_sid_basteId($sid){
        return $this->table(C('DB_PREFIX') . 'project')->where(['id'=>$sid])->field('siteid')->find();
    }

    public function QueryConfig($siteid){
        return $this->table(C('DB_PREFIX') . 'project')->where(['siteid'=>$siteid])->find();
    }

    public function get_types($domain,$types = 0){
        $map['url'] = array('like',"%{$domain}%");
        $map['site_type'] = $types;
        $data =  $this->table(C('DB_PREFIX') . 'project')->where($map)->order('add_time DESC')->field('siteid,id,url')->select();

        if(is_array($data)){
            if(isset($data[0])){
                $lhost = parse_url($data[0]['url'])['host'];
                if($lhost === $domain){
                    $_options = array('siteid'=>$data[0]['siteid'],'id'=>$data[0]['id']);
                    return $_options;
                }
            }
        }else{
            return false;
        }
    }
    
    public function get_siteid($domain){
        $map['project_url'] = array('like',"%{$domain}%");
        return $this->table(C('DB_PREFIX') . 'project')->where($map)->order('add_time DESC')->field('siteid,id')->find();
    }

    public function get_list(){
        $Page_count = $this->table(C('DB_PREFIX') . 'project')->order('update_time DESC')->count();
        $Page = new \Think\Page($Page_count,30);
        $data['data'] = $this->table(C('DB_PREFIX') . 'project')->order('update_time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data['page'] = $Page->show();
        return $data;
    }

    /**
     * @param int $types_id
     * @return mixed
     */
    public function get_pro_types($types_id = 0){
        $map = array(
            'web_status' => 0,
            'monitor_status' => 0,
            'site_type' => (int)$types_id
        );
        $project_list = $this->table(C('DB_PREFIX') . 'project')->field('project_url,project_name,ilink_option,siteid,site_type')->where($map)->order('add_time DESC')->select();
        if(!$project_list){
            return false;
        }

        $allow_type = array('directory','dynamic','global','rewrite','404');
        $list = array();
        if(is_array($project_list)){

            for($i=0;$i<count($project_list);$i++) {
                $url_rule = unserialize(base64_decode($project_list[$i]['url_rules']));
                $url_rules = explode("\n",$url_rule);
                if (is_array($url_rules)) {
                    // 随机收取一条url作为监控目标
                    $rand_monitor_url = mt_rand(0, count($url_rules) - 1);
                    $list[$i]['url_rules'] = $url_rules[$rand_monitor_url];
                    $project_list[$i]['path'] = $list[$i]['url_rules'];
                    $project_list[$i]['types'] = $allow_type[$project_list[$i]['site_type']];
                    unset($project_list[$i]['url_rules']);
                    unset($project_list[$i]['site_type']);
//                }
                }
            }
        }
//        $lists = array_merge($list,$list);
        return $project_list;
    }

    public function get_all(){
        $map = array(
            'web_status' => 0,
            'monitor_status' => 0,
        );
        $project_lists = $this->table(C('DB_PREFIX') . 'project')->field('project_url,project_name,ilink_option,siteid,site_type')->where($map)->order('add_time DESC')->select();

        if(!$project_lists){
            return false;
        }

        $allow_type = array('directory','dynamic','global','rewrite','404');
        $list = array();
        if(is_array($project_lists)){

            for($i=0;$i<count($project_lists);$i++) {
                $url_rule = unserialize(base64_decode($project_lists[$i]['url_rules']));
                $url_rules = explode("\n",$url_rule);
                if (is_array($url_rules)) {
                    // 随机收取一条url作为监控目标
                    $rand_monitor_url = mt_rand(0, count($url_rules) - 1);
                    $list[$i]['url_rules'] = $url_rules[$rand_monitor_url];
                    $project_lists[$i]['path'] = $list[$i]['url_rules'];
                    $project_lists[$i]['types'] = $allow_type[$project_lists[$i]['site_type']];
                    unset($project_lists[$i]['url_rules']);
                    unset($project_lists[$i]['site_type']);
//                }
                }
            }
        }
//        $lists = array_merge($list,$list);
        return $project_lists;
    }



    public function get_domain($domain){

        $map = array(
            'url' => $domain
        );

        return $this->table(C('DB_PREFIX') . 'project')->where($map)->field('id,baidu_token')->order('id DESC')->find();
    }


    public function site_add($data = array()){
//        $datas = array(
//            'name' => $data['title'],
//            'url' => $data['url'],
//            'rankname' => $data['rankname'],
////            'dir_prefix' => $data['dir_prefix'], // 移除目录前缀，新增内链规则选项
//            'addtime' => time(),
//            'siteid' => $data['siteid'],
//            'js_jump' => $data['js_jump'],
//            'site_type' => (int)$data['site_type'],
//            'tpl_name' => $data['tpl_name'],
//            'url_rules'=> base64_encode(serialize($data['url_rules'])), // 压缩字符串
//            'error_page' => trim($data['error_page']),
//            'no_access' => trim($data['no_access']),
//            'baidu_token' => trim($data['baidu_token']),
//            'webserver_classe' => (int)$data['webserver_classe'],
//            'web_status' => (int)0,  // 默认启用站点
//            'keyword' => trim($data['keyword']),
//            'monitor_status' => 0, //默认开启监控
//        );

        if($this->if_exists($data)){
            if($this->create($data)){
                $adData = $this->table(C('DB_PREFIX') . 'project')->add($data);
                return true;
            }else{
                return $this->getError();
            }
        }else{
            return $data['project_url'] . '该项目已存在！';
        }
//        if($this->create($datas)){
//            $adData = $this->table(C('DB_PREFIX') . 'project')->add($datas);
//            return true;
//        }else{
//            return $this->getError();
//        }

    }
}