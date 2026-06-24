<?php


class Signature
{
    private $salt = ''; // 密钥
    private $nowDate = ''; // 现在时间
    private $o_salt; // 第一次MD5加密
    private $t_salt; // 第二次MD5加密
    private $s_salt; // 第三次sha1加密
    private $Token;


    public function __construct()
    {
        $this->setTime();
    }


    public function get_ak($salt){
        $this->salt = isset($salt) ? C('SEO_CONTROL_API_ACCESS_KEY') : $salt;
        $this->o_salt = md5($this->nowDate);
        $this->t_salt = md5($this->salt . $this->o_salt );
        $this->t_salt = md5($this->t_salt);
        $this->s_salt = sha1($this->t_salt);
        $ak = substr($this->s_salt,2,20);
        return $ak;

    }


    /**
     *  设置本地时间
     */
    private function setTime(){
        $this->nowDate = strtotime ( date ( 'Y-m-d H:i' ) );
    }
}