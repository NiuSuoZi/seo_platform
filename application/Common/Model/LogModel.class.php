<?php


namespace Common\Model;
use Think\Model;

class LogModel extends Model
{
    protected $tableName = 'spider_log';
    protected $pk = 'sid';
    private $CacheNameList =
        array(
            1 => 'log_baiduspider_count',
            2 => 'log_sogouspider_count',
            3 => 'log_360spider_count',
            4 => 'log_bingbot_count',
            5 => 'log_googlebot_count',
            6 => 'log_smspider_count'
        );
    public function record($types = 0)
    {
        $today = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
        $startTime = mktime(0, 0, 0, date('m'), date('d') - 1, date('Y'));
        if (!S('startTime')) {
            S('startTime', $startTime);
            S('log_page_count', 0);
            for ($i = 1; $i <= 6; $i++) {
                S($this->CacheNameList[$i], 0);
            }
        }
        $Cache_today = S('startTime');
        // 写redis缓存
        if ($today >= $Cache_today and $today <= $Cache_today) {
            $value = S($this->CacheNameList[$types]);
            S($this->CacheNameList[$types], $value + 1);

        } else {
            $this->insertData();
        }
    }

    protected function insertData()
    {
        $_data = array(
            'day' => mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')),
            'baiduspider_count' => S('log_baiduspider_count'),
            'sogouspider_count' => S('log_sogouspider_count'),
            '360spider_count' => S('log_360spider_count'),
            'smspider_count' => S('log_bingbot_count'),
            'googlebot_count' => S('log_googlebot_count'),
            'bingbot_count' => S('log_smspider_count')
        );
        $addData = $this->add($_data);
        if ($addData !== false) {
            // 清空已计数的缓存
            foreach ($this->CacheNameList as $value){
                S($value,null);
            }
            S('startTime', null);

        }
    }
}