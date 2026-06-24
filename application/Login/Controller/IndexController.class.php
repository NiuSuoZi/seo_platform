<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/8
 * Time: 2:19
 */
namespace Login\Controller;
use Common\Controller\BaseController;
class IndexController extends BaseController
{
    // 如有登陆 直接跳转后台管理页面
    public function _initialize()
    {
        if(check_login()){
            $this->redirect(U('platform/index/index'),'',0);
        }
    }
    // 二次验证
    public function ga_verify(){
        // 有session说明用户名和密码都正确
        if(!empty($_SESSION['two_step'])){
            if(IS_POST && IS_AJAX){
                // 用户提交 开始验证
                $verify_code = I('post.ga_code');
                // 加载google验证器
                vendor('GoogleAuthenticator.GoogleAuthenticator');
                $ga = new \GoogleAuthenticator();
                $is_login = $ga->verifyCode($_SESSION['two_step']['user']['google_keys'],$verify_code);
                if($is_login){
                    $_SESSION['user'] = $_SESSION['two_step']['user'];
                    $this->ajaxReturn(['code'=>200,'msg'=>'正在跳转到后台主页……','redirect'=>U('platform/index/index')]);
                }else{
                    $this->ajaxReturn(['code'=>404,'msg'=>'验证码错误，请检查时间是否一致！','redirect'=>U('login/index/index')]);
                }
            }
            // 没有绑定验证器 跳转到绑定页面
            if($_SESSION['two_step']['user']['google_keys'] == ''){
                $this->redirect(U('login/index/sign_code'),'',0);
            }
            $this->assign('login_uns',$_SESSION['two_step']['user']['username']);
            $this->display('google_verification');
        }else{
            $this->error("会话失效，请重新登录……", U('login/index/index'));
        }
    }

    // 注销登录
    public function logout(){
        session('user',null);
        session(null);
        $this->success("正在注销，请稍后...", U('login/index/index'));
    }

    //页面
    public function index()
    {
        if(!empty($_SESSION['two_step'])){
            $this->success("正在登录，请稍后...", U('login/index/ga_verify'));
            exit;
        }
        if (IS_POST && IS_AJAX) {
            $loginInfo_username = I('post.username');
            $loginInfo_password = I('post.password');
            $loginInfo_verify = I('post.recaptcha');
            if (trim($loginInfo_username) !== '' && trim($loginInfo_password) !== '' && trim($loginInfo_verify) !== '') {
                     if (!$this->validate_rechapcha($loginInfo_verify)) {
                         $this->ajaxReturn(['code'=>404,'msg'=>'人机验证错误','redirect'=>U('login/index/index')]);

                     }


                $loginInfo['username'] = $loginInfo_username;
                $loginInfo['password'] = $loginInfo_password;
                $login_d = $this->login($loginInfo);
                if ($login_d !== false) {
                    $_SESSION['two_step'] = $login_d;
                    $this->ajaxReturn(['code'=>200,'msg'=>'登录成功，正在转入验证...','redirect'=>U('login/index/ga_verify')]);
                } else {
                    $this->ajaxReturn(['code'=>404,'msg'=>'用户名或密码错误！','redirect'=>U('login/index/index')]);
                }
            }
        }
        $license = C('license');
        $this->assign('license', $license);
        $this->assign('pubkey',C('SEO_GOOGLE_RECAPTCHA_PUBLIC_KEY'));
        $this->display();
    }

    // 没有Google验证码
    public function sign_code(){
        if(empty($_SESSION['two_step'])){
            $this->success("会话失效，请重新输入密码登录……", U('login/index/index'));
            exit;
        }
        $username = $_SESSION['two_step']['user']['username'];
        $data=M('Users')->where(array('username'=>$username))->find();

        if(!empty($data['google_keys']) || $data['google_keys'] !== ''){
            $this->success("已绑定验证器，正在登录……", U('login/index/index'));
            session(null);
            exit;
        }
        // 只生成一次私钥 以免重复刷新私钥会变
        $username = $_SESSION['two_step']['user']['username'];
        if(empty($_SESSION['ga_secret'])) {
            vendor('GoogleAuthenticator.GoogleAuthenticator');
            $ga = new \GoogleAuthenticator();
            $secret = $ga->createSecret();
            $_SESSION['ga_secret'] = $secret;
        }else{
            $secret = $_SESSION['ga_secret'];
        }

        if(IS_POST){
            // 开始写入数据库
//            $ga_secret = I('post.ga_secret');
            $Model = M('Users')->where(array('username'=>$username))->save(array('google_keys'=>$_SESSION['ga_secret']));
            if($Model){
                $this->success("已成功绑定验证器，正在跳转登录……", U('login/index/index'));
                exit;
            }else{
                $this->error("绑定出错，错误原因未知……", U('login/index/index'));
                session(null);
                exit;
            }
        }
        $qrcode_format = sprintf("otpauth://totp/%s?secret=%s",$username . '@x37-platform.com',$secret);
        $this->assign('secret',$secret);
        $this->assign('login_uns',$_SESSION['two_step']['user']['username']);
        $this->assign('qrcode_base64_images',$qrcode_format);
        $this->display('siup_code');
    }

    //登陆
    protected function login($auth_data){
        $data=M('Users')->where(array('username'=>$auth_data['username']))->find();
        $auth_Info = md5(md5($auth_data['password']) . $data['salt']);
        $loginData = array();
        if(!strcasecmp($auth_Info,$data['password']) != 0){

            $loginData['user']=array(
                'id'=>$data['id'],
                'username'=>$data['username'],
                'avatar'=>$data['avatar'],
                'login_time'=> time(),
                'google_keys' => $data['google_keys']
            );
            return $loginData;
        }else{
            return false;
        }
    }
    // 验证码
    public function verify(){
        $config=array(
            'codeSet'=>'1234567890abcdefghijklmnopqrstuvwxy',
            'fontSize'=>30,
            'useCurve'=>false,
            'imageH'=>60,
            'imageW'=>270,
            'length'=>4,
            'fontttf'=>'3.ttf',
        );
        show_verify($config);
    }

    private function validate_rechapcha($response)
    {
        // Verifying the user's response (https://developers.google.com/recaptcha/docs/verify)
        $verifyURL = 'https://recaptcha.net/recaptcha/api/siteverify';

        $query_data = [
            'secret' => C('SEO_GOOGLE_RECAPTCHA_PRIVATE_KEY'),
            'response' => $response,
            'remoteip' => (isset($_SERVER["HTTP_CF_CONNECTING_IP"]) ? $_SERVER["HTTP_CF_CONNECTING_IP"] : $_SERVER['REMOTE_ADDR'])
        ];

        // Collect and build POST data
        $post_data = http_build_query($query_data, '', '&');

        // Send data on the best possible way
        if (function_exists('curl_init') && function_exists('curl_setopt') && function_exists('curl_exec'))
        {
            // Use cURL to get data 10x faster than using file_get_contents or other methods
            $ch = curl_init($verifyURL);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-type: application/x-www-form-urlencoded'));
            $response = curl_exec($ch);
            curl_close($ch);
        }
        else
        {
            // If server not have active cURL module, use file_get_contents
            $opts = array('http' =>
                array(
                    'method' => 'POST',
                    'header' => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $post_data
                )
            );
            $context = stream_context_create($opts);
            $response = file_get_contents($verifyURL, false, $context);
        }

        // Verify all reponses and avoid PHP errors
        if ($response)
        {
            $result = json_decode($response);
            if ($result->success === true)
            {
                return true;
            }
            else
            {
                return $result;
            }
        }

        // Dead end
        return false;
    }
}
