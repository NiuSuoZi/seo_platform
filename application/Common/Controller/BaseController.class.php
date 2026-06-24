<?php
namespace Common\Controller;
use Think\Controller;
/**
 * Base基类控制器
 */
class BaseController extends Controller{
    /**
     * 初始化方法
     */
    public function _initialize(){
        
    }

    public function createRedisList($message = array(),$listKey = 'message01')
    {
        $redis = new \Redis();
        $redis->pconnect(C('REDIS_HOST'),C('REDIS_PORT'));
        if ('' != C('REDIS_PASSWORD')) {
            $redis->auth(C('REDIS_PASSWORD'));
        }
        $redis->select(1);
        $rPushResul = $redis->rPush($listKey, json_encode($message)); //执行成功后返回当前列表的长度 9
        return $rPushResul;
    }

    public function RedisSaveToMysql($listKey = 'message01')
    {
        if (empty($listKey)) {
            $result = ["errcode" => 500, "errmsg" => "this parameter is empty!"];
            exit(json_encode($result));
        }
        print '后台消息队列开启...' . "\n";
        $list = array(
            1 => '百度蜘蛛',
            2 => '搜狗蜘蛛',
            3 => '360蜘蛛',
            4 => '必应蜘蛛',
            5 => '谷歌蜘蛛',
            6 => '神马蜘蛛'
        );
        $redis = new \Redis();
        $redis->pconnect(C('REDIS_HOST'),C('REDIS_PORT'));
        if ('' != C('REDIS_PASSWORD')) {
            $redis->auth(C('REDIS_PASSWORD'));
        }
        $redis->select(1);
        $model = new \Common\Model\SpiderLogModel();
        while (1){
            $var = $redis->lPop($listKey);
            if($var){
                // 消费
                $data = json_decode($var,true);
                C('TOKEN_ON',false);
                $ok = $model->createData($data);
                if(!$ok){
                    echo '[' . date('Y/m/d H:i:s') . ']' . $ok .  "\n";
                }else{
                    echo '[' . date('Y/m/d H:i:s') . ']' . 'URL:'. $data['spider_url'] . '  ' . $list[$data['spider_types']] . "\n";
                }
            }else{
                echo '[' . date('Y/m/d H:i:s') . ']' . '等待队列数据...' . "\n";
                sleep(1);
            }
        }
    }


    public function auth(){

        $ApiModel = new \Common\Model\ApiModel();
        $status = $ApiModel->auth_keys();
        return $status;

    }

    public function api_auth(){
        $request_list = getallheaders();
        $http_referer = empty($_SERVER['HTTP_REFERER']) ? '' : $_SERVER['HTTP_REFERER'];
        $AUTH_KEYS = empty($request_list['PLATFORM_AUTH_KEYS']) ? '' : $request_list['PLATFORM_AUTH_KEYS'];
        //$AUTH_KEYS = empty($_SERVER['HTTP_ACCEPT_ENCODING']) ? '' : $_SERVER['HTTP_ACCEPT_ENCODING'];
        if($http_referer === '' || $AUTH_KEYS === '' || strlen($AUTH_KEYS) !== 32){
                send_http_status(500);
                exit('Platform Auth Error!');
        }else{
            $ApiModel = new \Common\Model\ApiModel();
            $status = $ApiModel->auth_keys($AUTH_KEYS);
            if(false === $status ||$status['domain'] !== $http_referer){
                send_http_status(404);
                exit('Platform Auth Info Error!');
            }else if($status['status'] === 0){
                exit('Platform Interfaces Disable');
            }
        }
    }

}
