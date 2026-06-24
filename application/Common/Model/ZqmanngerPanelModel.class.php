<?php


namespace Common\Model;
use Think\Model;

class ZqmanngerPanelModel extends Model
{
    protected $tableName = 'zqmanager';
    protected $pk = 'zq_id';

    public function panelData(){
        $_data = array(
            'zq_page_count' => S('zq_page_count'),
            'zq_baiduspider_count' => S('zq_baiduspider_count'),
            'zq_sogouspider_count' => S('zq_sogouspider_count'),
            'zq_yisouspider_count' => S('zq_yisouspider_count'),
            'zq_360spider_count' => S('zq_360spider_count'),
        );


        return $_data;
    }
    public function InputData($data = array()){
        $cacheNameList =
            array(
                1 => 'zq_baiduspider_count',
                2 => 'zq_sogouspider_count',
                3 => 'zq_360spider_count',
                4 => 'zq_yisouspider_count');
        $today = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
        $last_day = mktime(0,0,0,date('m'),date('d'),date('Y'));
        $startTime = mktime(0,0,0,date('m'),date('d')-1,date('Y'));
        if(!S('startTime')){
            S('startTime',$startTime);
            S('zq_page_count',0);
            for($i=1;$i<=4;$i++){
                S($cacheNameList[$i],0);
            }
        }
        $Cache_today = S('startTime');
        // 写redis缓存
        if($today >= $Cache_today and  $today <= $Cache_today){
            if($data['is_new_page'] == 1){
                $name = S('zq_page_count');
                S('zq_page_count',$name+1);
            }
                $value = S($cacheNameList[$data['types']]);
                S($cacheNameList[$data['types']],$value+1);

        }else{
            $this->insertData();
        }
    }

    public function get_history_list(){
        $Page_count = $this->table('seo_zqmanager')->order('zq_day_count DESC')->count();
        $Page = new \Think\Page($Page_count,40);
        $data['data'] = $this->table('seo_zqmanager')->order('zq_day_count DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        $data['page'] = $Page->show();
        return $data;
    }

    protected function insertData(){
            $_data = array(
                'zq_day_count' => mktime(0,0,0,date('m'),date('d')-1,date('Y')),
                'zq_page_count' => S('zq_page_count'),
                'zq_baiduspider_count' => S('zq_baiduspider_count'),
                'zq_sogouspider_count' => S('zq_sogouspider_count'),
                'zq_360spider_count' => S('zq_360spider_count'),
                'zq_yisouspider_count' => S('zq_yisouspider_count'),
            );
            $addData = $this->add($_data);
            if($addData !== false){
                // 清空缓存
                S('zq_page_count',null);
                S('zq_baiduspider_count',null);
                S('zq_sogouspider_count',null);
                S('zq_yisouspider_count',null);
                S('zq_360spider_count',null);
                S('startTime',null);

            }

    }
}