<?php

namespace Common\Model;

use Think\Model;

class RewriteModel extends Model
{
    protected $tableName = 'project_lite_rewrite';
    protected $tablePrefix = 'seo_';

    protected $_validate = array(
        array('keyword', 'require', '词库类型必填！', 1 ),
        array('ilink_rules', 'require', '内链必填！', 1 ),
    );
}