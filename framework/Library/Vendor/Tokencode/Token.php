<?php

class TokenCode{


    private $salt = ''; // 密钥
    private $nowDate = ''; // 现在时间
    private $onesalt; // 第一次MD5加密
    private $towsalt; // 第二次MD5加密
    private $Token;


    /**
     *  构造方法 在创建时被调用
     *  @name __construct
     *  @author y0umer <y0umer@itsec.pw>
     */
    public function __construct(){
        $this->setTime();
    }

    /**
     * 获取一个动态码,采用MD5加密算法
     * @name getToken
     * @param string $names SESSION名字
     * @param string $salt 加密密钥
     * @author y0umer <y0umer@itsec.pw>
     * @copyright ITSEC TEAM
     * @return bool
     */
    public function getToken($salt){
        $this->salt = isset($salt) ? C('LOGIN_TOKEN_KEY') : $salt;
        $this->onesalt = md5($this->nowDate);
        $this->towsalt = md5($this->salt . $this->onesalt );
        $this->towsalt = md5($this->towsalt);
        $this->Token = substr($this->towsalt,0,8);
        $_SESSION['login_token'] = $this->Token;
    }
    /**
     *  检查session中的token是否与提交过来的token一致,成功返回true，失败返回false
     *  @name make
     *  @param string $name 需要检查的session名字
     *  @category ITSEC TEAM
     *  @author y0umer <y0umer@itsec.pw>
     *  @return bool
     *
     */
    public function make($tokenName){
        if(!strcasecmp($tokenName,$_SESSION['login_token']) != 0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 设置本地时间
     * @name setTime
     * @author y0umer <y0umer@itsec.pw>
     * @access private
     * @copyright ITSEC TEAM
     * @return integer
     */
    private function setTime(){
        $this->nowDate = strtotime ( date ( 'Y-m-d H:i' ) );
    }

}

