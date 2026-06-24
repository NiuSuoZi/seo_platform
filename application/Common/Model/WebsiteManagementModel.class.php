<?php

namespace Common\Model;

use Think\Model;

class WebsiteManagementModel extends Model
{
    protected $tableName = 'website_lists';

    protected $_validate = array(
        array('display_name','require','名称必须填写！'), //默认情况下用正则进行验证
        array('display_name', '/^[a-zA-Z0-9_\p{Han}\s]+$/u', '名称不能包含非法字符！', 0, 'regex'),
        array('display_name', '', '名称已存在！', 0, 'unique', 1), // 唯一值验证
        array('display_name', '3,25', '类别名称长度必须在3到25个字符之间！', 0, 'length'),
        array('category_id','require','必须选择一个分类'), //默认情况下用正则进行验证
        array('profile_id','require','必须选择TDK调用模板'), //默认情况下用正则进行验证
        array('category_id', 'checkCategory', '分类不存在', 0, 'callback'),
        array('profile_id', 'checkProfile', 'TDK模板不存在', 0, 'callback'),
        array('bind_domains','require','绑定的域名为空！'), //默认情况下用正则进行验证
        array('bind_domains', 'validateBindDomains', '绑定的域名包含非法字符或格式错误', self::MUST_VALIDATE, 'callback'),
        array('bind_domains', 'checkBindRepeated', '存在重复域名，请删除重复的域名后再提交！', self::MUST_VALIDATE, 'callback'), // 添加域名存在性检测
        array('bind_domains', 'checkBindDomainsUnique', '绑定的域名已存在与其他站群组中，无法重复绑定！', self::MUST_VALIDATE, 'callback'), // 添加域名存在性检测
    );

    protected $_auto = array(
        array('lastedite_time', 'time', self::MODEL_BOTH, 'function'),
        array('clsid','uuid',self::MODEL_INSERT,'function'),
        array('bind_domains', 'serializeBindDomains', self::MODEL_BOTH, 'callback'), // 自动序列化 bind_domains
    );

    protected function serializeBindDomains($bind_domains)
    {
        // 将域名按行拆分为数组
        $domains = explode("\n", trim($bind_domains));
        $domains = array_map('trim', $domains); // 去除每行的多余空格
        $domains = array_filter($domains); // 移除空行

        // 序列化数组
        return serialize($domains);
    }

    protected function validateBindDomains($bind_domains)
    {
        // 拆分多行文本为数组
        $domains = explode("\n", $bind_domains);

        // 去重并移除空值
        $domains = array_filter(array_unique(array_map('trim', $domains)));

        // 非法字符的正则表达式
        $invalidCharPattern = "/[\/';*&^]/";

        // 域名验证正则表达式
        $domainPattern = "/^([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/";

        foreach ($domains as $domain) {
            // 检查是否包含非法字符
            if (preg_match($invalidCharPattern, $domain)) {
                return false; // 验证失败
            }

            // 检查是否是有效的域名
            if (!preg_match($domainPattern, $domain)) {
                return false; // 验证失败
            }
        }

        return true; // 验证通过
    }

    protected function checkBindDomainsUnique($bind_domains) {
        // 将传入的域名按行拆分为数组
        $domains = explode("\n", trim($bind_domains));
        $domains = array_map('trim', $domains);
        $domains = array_filter($domains);

        // 获取所有记录的 bind_domains 字段
        $allRecords = $this->field('id, bind_domains')->select();

        foreach ($domains as $domain) {
            foreach ($allRecords as $record) {
                // 跳过当前记录（修改时）
                if (!empty($this->data['id']) && $record['id'] == $this->data['id']) {
                    continue;
                }

                // 反序列化数据库中的 bind_domains
                $storedDomains = unserialize($record['bind_domains']);
                if (is_array($storedDomains) && in_array($domain, $storedDomains)) {
                    return false; // 存在重复域名
                }
            }
        }

        return true; // 不存在重复域名
    }

    protected function checkBindRepeated($bind_domains) {
        // 将传入的域名按行拆分为数组
        $domains = explode("\n", trim($bind_domains));
        $domains = array_map('trim', $domains); // 去除多余空格
        $domains = array_filter($domains);     // 移除空行

        // 找出重复项
        $repeated = array_unique(array_diff_assoc($domains, array_unique($domains)));

        // 如果存在重复项，动态设置错误信息并返回 false
        if (!empty($repeated)) {
            $this->error = '域名存在重复项目：' . implode(', ', $repeated) . '，请删除重复的域名后再提交！';
            return false;
        }

        return true; // 验证通过
    }



    protected function checkCategory($value) {
        $WebsiteCategoryModel = new WebsiteCategoryModel();
        return $WebsiteCategoryModel->where(['category_id' => $value])->count() > 0;
    }

    protected function checkProfile($value) {
        $WebsiteProfileModel = new WebsiteProfileModel();
        return $WebsiteProfileModel->where(['id' => $value])->count() > 0;
    }

    protected function _before_insert(&$data, $options) {
        $data['lastedite_time'] = time(); // Set the current timestamp for 'update_time' during insert
        $data['clsid'] = uuid();
        return true;
    }

    protected function _before_update(&$data, $options) {
        $data['lastedite_time'] = time(); // Set the current timestamp for 'update_time' during update
        return true;
    }



    public function getWebsiteById($id) {
        return $this->where(['id' => intval($id)])->find();
    }

    public function setWebsiteById($id, $data) {
        // 调用模型的验证规则和自动完成功能
        if ($this->auto($this->_auto)->token(false)->create($data)) {
            // 执行保存
            $this->field('profile_id,bind_domains,category_id')->where(['id' => $id])->save($this->data);
            return true;
        } else {
            // 返回验证错误信息
            return $this->getError();
        }
    }


    public function getWebsiteLists($where = []) {
        // 获取总记录数
        $Page_count = $this->alias('w')
            ->join('seo_website_category c ON w.category_id = c.category_id', 'LEFT')
            ->join('seo_website_profile p ON w.profile_id = p.id', 'LEFT')
            ->where($where)
            ->count();

        // 分页设置，每页显示 30 条
        $Page = new \Think\Page($Page_count, 30);

        // 构建分页查询
        $query = $this->alias('w')
            ->join('seo_website_category c ON w.category_id = c.category_id', 'LEFT')
            ->join('seo_website_profile p ON w.profile_id = p.id', 'LEFT')
            ->field('w.*, c.category_name, p.display_name AS profile_name, p.category_keyword, p.category_content, p.category_template')
            ->where($where)
            ->order('w.lastedite_time DESC')
            ->limit($Page->firstRow . ',' . $Page->listRows);

        // 执行查询
        $data['data'] = $query->select();

        // 获取分页HTML
        $data['page'] = $Page->show();

        return $data;
    }

    public function createWebsites($data) {
        if ($this->token(false)->create($data)) {
            // 调用 add() 保存数据
            $this->add();
            return true;
        } else {
            // 返回错误信息
            return $this->getError();
        }
    }



}