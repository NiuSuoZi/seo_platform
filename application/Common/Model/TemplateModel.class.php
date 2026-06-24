<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/24
 * Time: 5:30
 */

namespace Common\Model;
use Common\Model\BaseModel;

class TemplateModel extends BaseModel
{
    protected $tableName = 'skin';
    protected $pk = 'id';

    public function __construct($name = '', $tablePrefix = '', $connection = '')
    {
        parent::__construct($name, $tablePrefix, $connection);
    }




}