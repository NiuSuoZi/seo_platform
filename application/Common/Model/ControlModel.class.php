<?php

namespace Common\Model;

use Common\Model\BaseModel;

class ControlModel extends BaseModel
{
    protected $tableName = 'controll';
    protected $tablePrefix = 'seo_';
    protected $pk = 'mid';
    protected $_validate = array(
        array('bind_domain','require','绑定域名必须填写！'), //默认情况下用正则进行验证
        array('bind_domain','','绑定域名不可重复绑定！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        array('remark','require','备注必须填写！'), //默认情况下用正则进行验证
        array('value',array(0,1),'值的范围不正确！',2,'in'),
    );

    protected $_auto = array (
        array('update_time','time',3,'function'), // 对update_time字段在更新的时候写入当前时间戳
        array('clsid','uuid',1,'function'), // 对update_time字段在更新的时候写入当前时间戳
    );

    protected function _before_insert(&$data, $options) {
        $data['update_time'] = time(); // Set the current timestamp for 'update_time' during insert
        $data['clsid'] = uuid();
        return true;
    }

    protected function _before_update(&$data, $options) {
        $data['update_time'] = time(); // Set the current timestamp for 'update_time' during update
        return true;
    }

    public function get_list(){
        $Page_count = $this->table(C('DB_PREFIX') . 'controll')->order('update_time DESC')->count();
        $Page = new \Think\Page($Page_count,30);
        $data['data'] = $this->table(C('DB_PREFIX') . 'controll')->order('update_time DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data['page'] = $Page->show();
        return $data;
    }

    public function switch_type($id,$type)
    {
        // 判断传过来的值是否和现在的数据库一样
        $map = [
            'mid' => (int)$id,
        ];
        $_before = $this->table(C('DB_PREFIX') . 'controll')->where($map)->field('mid,type')->find();
        if (in_array((string)$type, ['0', '1'])) {
            // $_before['type'] 是数据库中的type字段值 需要判断是否和 $type 一致，如果一致则返回false
            if ($_before['type'] === (string)$type) {
                return false;
            } else {
                if($this->where($map)->save(['type' => $type])){
                    return true;
                }
        }

        }

        return false;
    }


    /**
     * 检查 AMP 域名是否已存在于 seo_option 中
     * @param string $domain 要检查的域名
     * @param string|null $excludeClsid 可选，排除指定 clsid 的记录（编辑时用）
     * @return bool true=已存在，false=唯一
     */
    public function ampDomainExists($domain, $excludeClsid = null)
    {
        // 转义 / 为 \/，确保能在序列化字段中匹配
        $escapedDomain = addcslashes($domain, '/');

        $map['seo_option'] = array('like', '%' . $escapedDomain . '%');

        if (!empty($excludeClsid)) {
            $map['clsid'] = ['neq', $excludeClsid];
        }
        $records = $this->where($map)->select();

        // var_dump($this->getLastSql());
        if (!$records) return false;

        foreach ($records as $row) {
            $options = @unserialize($row['seo_option']);
            if (
                is_array($options) &&
                isset($options['amp_domain']) &&
                $options['amp_domain'] === $domain
            ) {
                return true;
            }
        }

        return false;
    }

    public function getCurrentClsid($mid = 0){
        $info = $this->field('clsid')->where(['mid'=>intval($mid)])->find();
        return isset($info['clsid']) ? $info['clsid'] : null;
    }

}