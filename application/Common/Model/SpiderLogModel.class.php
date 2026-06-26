<?php
namespace Common\Model;
use Common\Model\BaseModel;
use Think\Exception;
use Think\Model\AdvModel;

class SpiderLogModel extends AdvModel {
    protected $connection = 'LOG_DB';
    protected $pk = 'pid';
    protected $tablePrefix = 'seo_';

    protected $_validate = array(
        array('spider_types','1,2,3,4,5,6,7,8,9','types error',2,'in'),
        array('spider_url','chenk_urls','url is not.',0,'function'),
        array('spider_domain','chenk_domain','domain is error.',0,'function'),
        array('types','require','types is error.'),
    );

    public function __construct($name = '', $tablePrefix = '', $connection = '')
    {
        parent::__construct($name, $tablePrefix, $connection);
    }
    // 获取表 在查询时用
//    private function getTable(){
//        $tableName = 'spider_log'. date('Ymd'); // 拼接完整表名 seo_spider_20211129
//        $fullTable = $this->tablePrefix . $tableName;
//        // 判断是否存在表
//        $res = $this->query("SHOW TABLES LIKE '".$fullTable."'");
//
//        if(!$res){
//            return $this->getError();
//        }else{
//            return $this->table($fullTable);
//        }
//    }


    public function createData($data = array()){
        try {
            if(!$this->create($data)) {
                E($this->getError());
            }
            $this->getTable($data)->add();
            $this->commit();
            return true;
        }catch (\e $exception){
            $this->rollback();
            return false;
        }
    }

    public function search($type = ''){

    }

    public function select_today($types){
        $spider_types = $types;
        $map = array();
        $data = array();
        if($types == ''){
            $map['spider_types'] = array('in','1,2,3,4,5,6,7,8,9');
        }else{
            if($types > 9 or $types < 1)
            {
                $map['spider_types'] = array('in','1,2,3,4,5,6,7,8,9');
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
        $Page_Count = $this->getTable($map)->where($map)->order('spider_times DESC')->count();
//        $Page_count = $this->where($map)->order('spider_times DESC')->count();
        $Page = new \Think\Page($Page_Count,30);
        $data['data'] = $this->getTable()->where($map)->order('spider_times DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data['page'] = $Page->show();

        return $data;
    }


    // 昨日蜘蛛统计
    public function select_yesterday($types)
    {
        $spider_types = $types;
        $map = array();
        $data = array();
        if($types == ''){
            $map['spider_types'] = array('in','1,2,3,4,5,6,7,8,9');
        }else{
            if($types > 9 or $types < 1)
            {
                $map['spider_types'] = array('in','1,2,3,4,5,6,7,8,9');
            }else {
                $map['spider_types'] = array('eq',$spider_types);
            }
        }

        $fullTableName = $this->getLastDayTablesName();
        if(false === $fullTableName){
            $data['data'] = [];
            $data['page'] = '';
            return $data;
        }


        $Page_count = $this->table($fullTableName)->where($map)->order('spider_times DESC')->count();
        $Page = new \Think\Page($Page_count,30);
        $data['data'] = $this->table($fullTableName)->where($map)->order('spider_times DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data['page'] = $Page->show();

        return $data;
    }

    public function getAllCount()
    {
        return $this->getTable()->where(['pid' => ['neq', '-1']])  // 排除 pid 为 -1 的记录
        ->field('COUNT(pid) as count, spider_types')  // 只查询 spider_types 和对应的 COUNT
        ->group('spider_types')  // 按 spider_types 分组
        ->select();  // 获取查询结果
    }

    public function getCountByHours()
    {
        return $this->getTable()->where([
            'pid' => ['neq', '-1']
        ])
            ->field('hour, COUNT(*) as total')  // 使用虚拟列 hour 统计每个小时的条数
            ->group('hour')  // 按 hour 分组
            ->order('hour')  // 按 hour 升序排序
            ->select();
    }

    public function getFormattedSpiderStats()
    {
        // 定义爬虫类型映射
        $spider_list = [
            1 => 'baidu',
            2 => 'sogou',
            3 => 'so360',
            4 => 'bing',
            5 => 'google',
            6 => 'shenma',
            7 => 'yandex',
            8 => 'coccoc',
            9 => 'yeti',
        ];

        // 初始化结果格式
        $result = [
            'hours' => [],
            'hourly' => [],
        ];

        // 初始化爬虫统计数据
        foreach ($spider_list as $spider_name) {
            $result[$spider_name] = 0; // 总量初始化
            $result['hourly'][$spider_name] = array_fill(0, 24, 0); // 每小时数据初始化为0
        }

        // 获取每小时每种spider_type的统计数据
        $rawData = $this->getTable()->where([
            'pid' => ['neq', '-1']
        ])
            ->field('hour, spider_types, COUNT(*) as total')
            ->group('hour, spider_types')
            ->order('hour, spider_types')
            ->select();

        // 处理原始数据
        foreach ($rawData as $row) {
            $hour = (int)$row['hour'];
            $spider_type = (int)$row['spider_types'];
            $total = $row['total'];

            // 确认spider_type是否在列表中
            if (isset($spider_list[$spider_type])) {
                $spider_name = $spider_list[$spider_type];

                // 累加到对应爬虫的总量
                $result[$spider_name] += $total;

                // 填充每小时数据
                $result['hourly'][$spider_name][$hour] += $total;
            }
        }

        // 构造小时列表
        for ($h = 0; $h < 24; $h++) {
            $result['hours'][] = sprintf('%d:00', $h); // 格式化小时
        }

        return $result;
    }

    public function getLastDayTablesName(){
        $tableName = 'spider_log_'. date(date("Ymd",strtotime("-1 day"))); // 获取前一天表名
        $fullTable = $this->tablePrefix . $tableName;
        // 判断是否存在表、
        $res = $this->query("SHOW TABLES LIKE '".$fullTable."'");
        if(!$res){
            return false;
        }

        return $fullTable;
    }


    public function ranking(){
        $data = [];
        // 根据域名查询蜘蛛量统计
        $data[] = $this->getTable()->field('spider_types')->count();
        $data[] = $this->getTable()->field('DISTINCT(spider_domain),COUNT(*) as counts')->group('spider_domain')->select();
        return $data;
    }


    protected function getTableNames(){
        return $this->tablePrefix . 'spider_log_'. date('Ymd');;
    }

    // 获取表
    private function getTable($data = array()){
        $tableName = 'spider_log_'. date('Ymd'); // 拼接完整表名 seo_spider_20211129
        $fullTable = $this->tablePrefix . $tableName;
        // 判断是否存在表
        $res = $this->query("SHOW TABLES LIKE '".$fullTable."'");
        if(!$res){
            $this->createTable($fullTable);
        }

        return $this->table($fullTable);
    }

    private function createTable($table = ''){
        $_sql = <<<EOF
CREATE TABLE `{$table}` (
    `pid` bigint(19) NOT NULL AUTO_INCREMENT,
    `spider_domain` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `spider_types` tinyint(1) NOT NULL,
    `spider_url` TEXT  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `spider_cached` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `spider_times` int(11) NOT NULL,
    `types` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
    `spider_first` tinyint(1) NOT NULL,
    `ip_source` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    `hour` int(2) AS (HOUR(FROM_UNIXTIME(spider_times))) STORED, -- 新增生成列：存储小时
    PRIMARY KEY (`pid`) USING BTREE,
    INDEX `spider_types` (`spider_types`) USING BTREE,
    INDEX `spider_times` (`spider_times`) USING BTREE,
    INDEX `spider_domain` (`spider_domain`) USING BTREE,
    INDEX `spider_cached` (`spider_cached`) USING BTREE,
    INDEX `types` (`types`) USING BTREE,
    INDEX `spider_first` (`spider_first`) USING BTREE,
    INDEX `hour` (`hour`) USING BTREE  -- 新增索引：针对生成列 `hour`
) ENGINE = InnoDB 
AUTO_INCREMENT = 1 
DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_unicode_ci 
ROW_FORMAT = DYNAMIC;
EOF;
    return $this->execute($_sql);
    }
}