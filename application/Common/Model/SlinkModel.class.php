<?php


namespace Common\Model;
use Common\Model\BaseModel;
class SlinkModel extends BaseModel
{
    protected $pk='sid';
    protected $tableName='slink';

    // 根据uid查询连接池配置
    public function query_slink($sid){
        return $this->table(C('DB_PREFIX') . 'slink')->where(['sid'=>$sid])->find();
    }
    // 获取列表
    public function query_list(){
        $Page_count = $this->table(C('DB_PREFIX') . 'slink')->order('addtime DESC')->count();
        $Page = new \Think\Page($Page_count,30);
        $data['data'] = $this->table(C('DB_PREFIX') . 'slink')->order('addtime DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data['page'] = $Page->show();
        return $data;
    }

    // 添加到链接池
    public function add_list($data = array()){
        if(is_array($data) && !isset($data[0])){
            $addMode = $this->table(C('DB_PREFIX') . 'slink')->add($data);
            if($addMode){
                return true;
            }else{
                return false;
            }
        }
    }

    public function Api(){
        $Api = $this->table(C('DB_PREFIX') . 'slink')->field('sdomain,srules')->order('slevel ASC')->select();
        return $Api;
    }

    public function edit_slink($sid = 0,$data = ''){
        $datas = [
            'srules' => $data,
        ];

        $tm =  $this->table(C('DB_PREFIX') . 'slink')->where(['sid'=>$sid])->save($datas);
        //$bb =  $this->table(C('DB_PREFIX') . 'slink')->fetchSql()->where('sid=' . $sid)->save($datas);
        return  $tm;
    }

    public function view_slink($sid){
        return $this->table(C('DB_PREFIX') . 'slink')->field('sid,sdomain,srules')->where(['sid' => $sid])->find();
    }

    public function delete_slink($sid){
        return $this->table(C('DB_PREFIX') . 'slink')->where(['sid' => $sid])->delete();
    }
}