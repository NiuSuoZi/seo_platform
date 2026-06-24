<?php


namespace Common\Model;


use Think\Model;

class UrlRulesModel extends Model
{
    protected $pk = 'ur_id';
    protected $tableName = 'url_rules';
    protected $_validate = array(
        array('ur_name', 'require', 'url规则名称必填！', 1 ),
        array('ur_content', 'require', 'url规则内容必填！',1)
    );
//    protected $_auto = array(
//        array('ur_alias','getLink_alias',3,'function') ,
//    );

    // 添加规则
    public function add_rules($data = array()){
        $add_rules = $this->create($data);
        if($add_rules){
            $this->table(C('DB_PREFIX') . 'url_rules')->add($data);
            return true;
        }else{
            return $this->getError();
        }
    }

    public function ur_alias($ur_alias = ''){
        return $this->table(C('DB_PREFIX') . 'url_rules')->where(array('ur_alias'=>$ur_alias))->field('ur_content')->find();
    }

    // 查看全部url规则
    public function view_all_rules(){
        $Page_count = $this->table(C('DB_PREFIX') . 'url_rules')->order('ur_addtime DESC')->count();
        $Page = new \Think\Page($Page_count,30);
        $data['data'] = $this->table(C('DB_PREFIX') . 'url_rules')->order('ur_addtime DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data['page'] = $Page->show();
        return $data;
    }

    public function concise_list($tl_model = false){
        if ($tl_model){
            return $this->table(C('DB_PREFIX') . 'url_rules')->field('ur_id,ur_name')->where('ur_type=1')->select();
        }else {
            return $this->table(C('DB_PREFIX') . 'url_rules')->field('ur_id,ur_name')->where('ur_type=0')->select();
        }
    }

    // 查看某个id的规则
    public function view_rules($ur_id = 0){
    $map = array(
        'ur_id' => (int)$ur_id
    );

    return $this->table(C('DB_PREFIX') . 'url_rules')->where($map)->order('ur_id desc')->find();
    }


    public function cat_rules_name($tl_model = false){
        if ($tl_model){
            return $this->table(C('DB_PREFIX') . 'url_rules')->field('ur_alias,ur_name,ur_content')->where('ur_type=1')->select();
        }else{
            return $this->table(C('DB_PREFIX') . 'url_rules')->field('ur_alias,ur_name,ur_content')->where('ur_type=0')->select();
        }
    }

    public function cat_rules($ur_id = 0){
        $map = array(
            'ur_id' => (int)$ur_id
        );

        return $this->table(C('DB_PREFIX') . 'url_rules')->where($map)->field('ur_id,ur_content')->find();
    }
    // 编辑某个规则
    public function edit_rules($ur_id = 0,$data = array()){
        $datas = array(
            'ur_content'=> trim($data['ur_content']),
            'ur_addtime' => time(),
            'ur_default'=>$data['ur_default'],
            'ur_type'=>(int)$data['ur_type'],
            'ur_from_table'=>$data['ur_from_table'],
            'ur_flink_number'=>$data['ur_flink_number'],
            'ur_filnk_enable'=>$data['ur_filnk_enable'],
            'ur_hreflang'=>$data['ur_hreflang'],
        );
        return $this->table(C('DB_PREFIX') . 'url_rules')->where(['ur_id'=>$ur_id])->save($datas);
    }
}