<?php

header("Content-type:text/html;charset=utf-8");



function extractIpAddresses(string $input, int $maxLength = 120): string
{
    // 提取 IPv4（使用 lookaround 防止粘连字母）+ IPv6（标准写法）
    preg_match_all(
        '/(?<![\d.])((?:\d{1,3}\.){3}\d{1,3})(?![\d.])|(?:(?<![:\w])(?:[a-f0-9]{1,4}:){1,7}[a-f0-9]{1,4})(?![:\w])/i',
        $input,
        $matches
    );

    // 合并 IPv4 + IPv6，过滤空值
    $allIps = array_filter(array_merge($matches[1] ?? [], $matches[0] ?? []));

    // 去重
    $allIps = array_unique($allIps);

    // 组合并限制长度
    $ipString = implode(', ', $allIps);
    $safeOutput = mb_strlen($ipString) > $maxLength
        ? mb_substr($ipString, 0, $maxLength) . '…'
        : $ipString;

    return htmlspecialchars($safeOutput, ENT_QUOTES, 'UTF-8');
}






//传递数据以易于阅读的样式格式化后输出
function p($data){
    // 定义样式
    $str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 13px;line-height: 1.42857;color: #333;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
    // 如果是boolean或者null直接显示文字；否则print
    if (is_bool($data)) {
        $show_data=$data ? 'true' : 'false';
    }elseif (is_null($data)) {
        $show_data='null';
    }else{
        $show_data=print_r($data,true);
    }
    $str.=$show_data;
    $str.='</pre>';
    echo $str;
}

/**
 * app 图片上传
 * @return string 上传后的图片名
 */
function app_upload_image($path,$maxSize=52428800){
    ini_set('max_execution_time', '0');
    // 去除两边的/
    $path=trim($path,'.');
    $path=trim($path,'/');
    $config=array(
        'rootPath'  =>'./',         //文件上传保存的根路径
        'savePath'  =>'./'.$path.'/',   
        'exts'      => array('jpg', 'gif', 'png', 'jpeg','bmp'),
        'maxSize'   => $maxSize,
        'autoSub'   => true,
        );
    $upload = new \Think\Upload($config);// 实例化上传类
    $info = $upload->upload();
    if($info) {
        foreach ($info as $k => $v) {
            $data[]=trim($v['savepath'],'.').$v['savename'];
        }
        return $data;
    }
}

/**
 * 上传文件到oss并删除本地文件
 * @param  string $path 文件路径
 * @return bollear      是否上传
 */
function oss_upload($path){
    // 获取bucket名称
    $bucket=C('ALIOSS_CONFIG.BUCKET');
    // 先统一去除左侧的.或者/ 再添加./
    $oss_path=ltrim($path,'./');
    $path='./'.$oss_path;
    if (file_exists($path)) {
        // 实例化oss类
        $oss=new_oss();
        // 上传到oss    
        $oss->uploadFile($bucket,$oss_path,$path);
        // 如需上传到oss后 自动删除本地的文件 则删除下面的注释 
        // unlink($path);
        return true;
    }
    return false;
}

/**
 * 删除oss上指定文件
 * @param  string $object 文件路径 例如删除 /Public/README.md文件  传Public/README.md 即可
 */
function oss_delet_object($object){
    // 实例化oss类
    $oss=new_oss();
    // 获取bucket名称
    $bucket=C('ALIOSS_CONFIG.BUCKET');
    $test=$oss->deleteObject($bucket,$object);
}

/**
 * app 视频上传
 * @return string 上传后的视频名
 */
function app_upload_video($path,$maxSize=52428800){
    ini_set('max_execution_time', '0');
    // 去除两边的/
    $path=trim($path,'.');
    $path=trim($path,'/');
    $config=array(
        'rootPath'  =>'./',         //文件上传保存的根路径
        'savePath'  =>'./'.$path.'/',   
        'exts'      => array('mp4','avi','3gp','rmvb','gif','wmv','mkv','mpg','vob','mov','flv','swf','mp3','ape','wma','aac','mmf','amr','m4a','m4r','ogg','wav','wavpack'),
        'maxSize'   => $maxSize,
        'autoSub'   => true,
        );
    $upload = new \Think\Upload($config);// 实例化上传类
    $info = $upload->upload();
    if($info) {
        foreach ($info as $k => $v) {
            $data[]=trim($v['savepath'],'.').$v['savename'];
        }
        return $data;
    }
}


/**
 * 返回文件格式
 * @param  string $str 文件名
 * @return string      文件格式
 */
function file_format($str){
    // 取文件后缀名
    $str=strtolower(pathinfo($str, PATHINFO_EXTENSION));
    // 图片格式
    $image=array('webp','jpg','png','ico','bmp','gif','tif','pcx','tga','bmp','pxc','tiff','jpeg','exif','fpx','svg','psd','cdr','pcd','dxf','ufo','eps','ai','hdri');
    // 视频格式
    $video=array('mp4','avi','3gp','rmvb','gif','wmv','mkv','mpg','vob','mov','flv','swf','mp3','ape','wma','aac','mmf','amr','m4a','m4r','ogg','wav','wavpack');
    // 压缩格式
    $zip=array('rar','zip','tar','cab','uue','jar','iso','z','7-zip','ace','lzh','arj','gzip','bz2','tz');
    // 文档格式
    $text=array('exe','doc','ppt','xls','wps','txt','lrc','wfs','torrent','html','htm','java','js','css','less','php','pdf','pps','host','box','docx','word','perfect','dot','dsf','efe','ini','json','lnk','log','msi','ost','pcs','tmp','xlsb');
    // 匹配不同的结果
    switch ($str) {
        case in_array($str, $image):
            return 'image';
            break;
        case in_array($str, $video):
            return 'video';
            break;
        case in_array($str, $zip):
            return 'zip';
            break;
        case in_array($str, $text):
            return 'text';
            break;
        default:
            return 'image';
            break;
    }
}
 
/**
 * 发送友盟推送消息
 * @param  integer  $uid   用户id
 * @param  string   $title 推送的标题
 * @return boolear         是否成功
 */
function umeng_push($uid,$title){
    // 获取token
    $device_tokens=D('OauthUser')->getToken($uid,2);
    // 如果没有token说明移动端没有登录；则不发送通知
    if (empty($device_tokens)) {
        return false;
    }
    // 导入友盟
    Vendor('Umeng.Umeng');
    // 自定义字段   根据实际环境分配；如果不用可以忽略
    $status=1;
    // 消息未读总数统计  根据实际环境获取未读的消息总数 此数量会显示在app图标右上角
    $count_number=1;
    $data=array(
        'key'=>'status',
        'value'=>"$status",
        'count_number'=>$count_number
        );
    // 判断device_token  64位表示为苹果 否则为安卓
    if(strlen($device_tokens)==64){
        $key=C('UMENG_IOS_APP_KEY');
        $timestamp=C('UMENG_IOS_SECRET');
        $umeng=new \Umeng($key, $timestamp);
        $umeng->sendIOSUnicast($data,$title,$device_tokens);
    }else{
        $key=C('UMENG_ANDROID_APP_KEY');
        $timestamp=C('UMENG_ANDROID_SECRET');
        $umeng=new \Umeng($key, $timestamp);
        $umeng->sendAndroidUnicast($data,$title,$device_tokens);
    }
    return true;
}
 

/**
 * 返回用户id
 * @return integer 用户id
 */
function get_uid(){
    return $_SESSION['user']['id'];
}

/**
 * 返回iso、Android、ajax的json格式数据
 * @param  array  $data           需要发送到前端的数据
 * @param  string  $error_message 成功或者错误的提示语
 * @param  integer $error_code    状态码： 0：成功  1：失败
 * @return string                 json格式的数据
 */
function ajax_return($data='',$error_message='成功',$error_code=1){
    $all_data=array(
        'error_code'=>$error_code,
        'error_message'=>$error_message,
        );
    if ($data!=='') {
        $all_data['data']=$data;
        // app 禁止使用和为了统一字段做的判断
        $reserved_words=array('id','title','price','product_title','product_id','product_category','product_number');
        foreach ($reserved_words as $k => $v) {
            if (array_key_exists($v, $data)) {
                echo 'app不允许使用【'.$v.'】这个键名 —— 此提示是function.php 中的ajax_return函数返回的';
                die;
            }
        }
    }
    // 如果是ajax或者app访问；则返回json数据 pc访问直接p出来
    echo json_encode($all_data);
    exit(0);
}

/**
 * 获取完整网络连接
 * @param  string $path 文件路径
 * @return string       http连接
 */
function get_url($path){
    // 如果是空；返回空
    if (empty($path)) {
        return '';
    }
    // 如果已经有http直接返回
    if (strpos($path, 'http://')!==false) {
        return $path;
    }
    // 判断是否使用了oss
    $alioss=C('ALIOSS_CONFIG');
    if (empty($alioss['KEY_ID'])) {
        return 'http://'.$_SERVER['HTTP_HOST'].$path;
    }else{
        return 'http://'.$alioss['BUCKET'].'.'.$alioss['END_POINT'].$path;
    }
    
}

/**
 * 检测是否登录
 * @return boolean 是否登录
 */
function check_login(){
    if (!empty($_SESSION['user']['id'])){
        return true;
    }else{
        return false;
    }
}

/**
 * 根据配置项获取对应的key和secret
 * @return array key和secret
 */
function get_rong_key_secret(){
    // 判断是需要开发环境还是生产环境的key
    if (C('RONG_IS_DEV')) {
        $key=C('RONG_DEV_APP_KEY');
        $secret=C('RONG_DEV_APP_SECRET');
    }else{
        $key=C('RONG_PRO_APP_KEY');
        $secret=C('RONG_PRO_APP_SECRET');
    }
    $data=array(
        'key'=>$key,
        'secret'=>$secret
        );
    return $data;
}

/**
 * 获取融云token
 * @param  integer $uid 用户id
 * @return integer      token
 */
function get_rongcloud_token($uid){
    // 从数据库中获取token
    $token=D('OauthUser')->getToken($uid,1);
    // 如果有token就返回
    if ($token) {
        return $token;
    }
    // 获取用户昵称和头像
    $user_data=M('Users')->field('username,avatar')->getById($uid);
    // 用户不存在
    if (empty($user_data)) {
        return false;
    }
    // 获取头像url格式
    $avatar=get_url($user_data['avatar']);
    // 获取key和secret
    $key_secret=get_rong_key_secret();
    // 实例化融云
    $rong_cloud=new \Org\Xb\RongCloud($key_secret['key'],$key_secret['secret']);
    // 获取token
    $token_json=$rong_cloud->getToken($uid,$user_data['username'],$avatar);
    $token_array=json_decode($token_json,true);
    // 获取token失败
    if ($token_array['code']!=200) {
        return false;
    }
    $token=$token_array['token'];
    $data=array(
        'uid'=>$uid,
        'type'=>1,
        'nickname'=>$user_data['username'],
        'head_img'=>$avatar,
        'access_token'=>$token
        );
    // 插入数据库
    $result=D('OauthUser')->addData($data);
    if ($result) {
        return $token;
    }else{
        return false;
    }
}

/**
 * 更新融云头像
 * @param  integer $uid 用户id
 * @return boolear      操作是否成功
 */
function refresh_rongcloud_token($uid){
    // 获取用户昵称和头像
    $user_data=M('Users')->field('username,avatar')->getById($uid);
    // 用户不存在
    if (empty($user_data)) {
        return false;
    }
    $avatar=get_url($user_data['avatar']);
    // 获取key和secret
    $key_secret=get_rong_key_secret();
    // 实例化融云
    $rong_cloud=new \Org\Xb\RongCloud($key_secret['key'],$key_secret['secret']);
    // 更新融云用户头像
    $result_json=$rong_cloud->userRefresh($uid,$user_data['username'],$avatar);
    $result_array=json_decode($result_json,true);
    if ($result_array['code']==200) {
        return true;
    }else{
        return false;
    }
}

/**
 * 删除指定的标签和内容
 * @param array $tags 需要删除的标签数组
 * @param string $str 数据源
 * @param string $content 是否删除标签内的内容 0保留内容 1不保留内容
 * @return string
 */
function strip_html_tags($tags,$str,$content=0){
    if($content){
        $html=array();
        foreach ($tags as $tag) {
            $html[]='/(<'.$tag.'.*?>[\s|\S]*?<\/'.$tag.'>)/';
        }
        $data=preg_replace($html,'',$str);
    }else{
        $html=array();
        foreach ($tags as $tag) {
            $html[]="/(<(?:\/".$tag."|".$tag.")[^>]*>)/i";
        }
        $data=preg_replace($html, '', $str);
    }
    return $data;
}

/**
 * 传递ueditor生成的内容获取其中图片的路径
 * @param  string $str 含有图片链接的字符串
 * @return array       匹配的图片数组
 */
function get_ueditor_image_path($str){
    $preg='/\/Upload\/image\/u(m)?editor\/\d*\/\d*\.[jpg|jpeg|png|bmp]*/i';
    preg_match_all($preg, $str,$data);
    return current($data);
}

/**
 * 字符串截取，支持中文和其他编码
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $suffix 截断显示字符
 * @param string $charset 编码格式
 * @return string
 */
function re_substr($str, $start=0, $length, $suffix=true, $charset="utf-8") {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    $omit=mb_strlen($str) >=$length ? '...' : '';
    return $suffix ? $slice.$omit : $slice;
}

// 设置验证码
function show_verify($config=''){
    if($config==''){
        $config=array(
            'codeSet'=>'1234567890',
            'fontSize'=>30,
            'useCurve'=>false,
            'imageH'=>60,
            'imageW'=>240,
            'length'=>4,
            'fontttf'=>'4.ttf',
            );
    }
    $verify=new \Think\Verify($config);
    return $verify->entry();
}

/**
 * @param int $length
 * @param bool $onlynum
 * @param bool $nouppLetter
 * @return bool|string
 */
function sub_string($string){
        $str = strip_tags($string);
        $str = substr_replace("　","",$string);
        if (mb_strlen($string, 'utf8') > 15) {
            return mb_substr($string, 0, 15, 'utf8') . '...';
        } else {
            return $string;
        }
}


function createRandomStr($length = 6, $onlynum = false, $nouppLetter = false)
{
    if (!($length > 0)) {
        return false;
    }
    $returnstr = '';
    if ($onlynum) {
        for ($i = 0; $i < $length; $i++) {
            $returnstr .= rand(0, 9);
        }
    } else if ($nouppLetter) {
        $strarr = array_merge(range(0, 9), range('a', 'z'));
        shuffle($strarr);
        shuffle($strarr);
        $returnstr = implode('', array_slice($strarr, 0, $length));
    } else {
        $strarr = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
        shuffle($strarr);
        shuffle($strarr);
        $returnstr = implode('', array_slice($strarr, 0, $length));
    }
    return $returnstr;
}

function get_salt(){
    return createRandomStr(6,false,true);
}

function password($password,$salt = ''){
    if($salt == ''){
        $salt = get_salt();
    }
    return md5(md5($password) . $salt);
}
// 检测验证码
function check_verify($code){
    $verify=new \Think\Verify();
    return $verify->check($code);
}

/**
 * 取得根域名
 * @param type $domain 域名
 * @return string 返回根域名
 */
function get_url_to_domain($domain) {
    $re_domain = '';
    $domain_postfix_cn_array = array("com", "net", "org", "gov", "edu", "com.cn", "cn");
    $array_domain = explode(".", $domain);
    $array_num = count($array_domain) - 1;
    if ($array_domain[$array_num] == 'cn') {
        if (in_array($array_domain[$array_num - 1], $domain_postfix_cn_array)) {
            $re_domain = $array_domain[$array_num - 2] . "." . $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
        } else {
            $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
        }
    } else {
        $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
    }
    return $re_domain;
}

/**
 * 按符号截取字符串的指定部分
 * @param string $str 需要截取的字符串
 * @param string $sign 需要截取的符号
 * @param int $number 如是正数以0为起点从左向右截  负数则从右向左截
 * @return string 返回截取的内容
 */
/*  示例
    $str='123/456/789';
    cut_str($str,'/',0);  返回 123
    cut_str($str,'/',-1);  返回 789
    cut_str($str,'/',-2);  返回 456
    具体参考 http://www.baijunyao.com/index.php/Home/Index/article/aid/18
*/
function cut_str($str,$sign,$number){
    $array=explode($sign, $str);
    $length=count($array);
    if($number<0){
        $new_array=array_reverse($array);
        $abs_number=abs($number);
        if($abs_number>$length){
            return 'error';
        }else{
            return $new_array[$abs_number-1];
        }
    }else{
        if($number>=$length){
            return 'error';
        }else{
            return $array[$number];
        }
    }
}

/**
 * 发送邮件
 * @param  string $address 需要发送的邮箱地址 发送给多个地址需要写成数组形式
 * @param  string $subject 标题
 * @param  string $content 内容
 * @return boolean       是否成功
 */
function send_email($address,$subject,$content){
    $email_smtp=C('EMAIL_SMTP');
    $email_username=C('EMAIL_USERNAME');
    $email_password=C('EMAIL_PASSWORD');
    $email_from_name=C('EMAIL_FROM_NAME');
    $email_smtp_secure=C('EMAIL_SMTP_SECURE');
    $email_port=C('EMAIL_PORT');
    if(empty($email_smtp) || empty($email_username) || empty($email_password) || empty($email_from_name)){
        return array("error"=>1,"message"=>'邮箱配置不完整');
    }
    require_once './ThinkPHP/Library/Org/Nx/class.phpmailer.php';
    require_once './ThinkPHP/Library/Org/Nx/class.smtp.php';
    $phpmailer=new \Phpmailer();
    // 设置PHPMailer使用SMTP服务器发送Email
    $phpmailer->IsSMTP();
    // 设置设置smtp_secure
    $phpmailer->SMTPSecure=$email_smtp_secure;
    // 设置port
    $phpmailer->Port=$email_port;
    // 设置为html格式
    $phpmailer->IsHTML(true);
    // 设置邮件的字符编码'
    $phpmailer->CharSet='UTF-8';
    // 设置SMTP服务器。
    $phpmailer->Host=$email_smtp;
    // 设置为"需要验证"
    $phpmailer->SMTPAuth=true;
    // 设置用户名
    $phpmailer->Username=$email_username;
    // 设置密码
    $phpmailer->Password=$email_password;
    // 设置邮件头的From字段。
    $phpmailer->From=$email_username;
    // 设置发件人名字
    $phpmailer->FromName=$email_from_name;
    // 添加收件人地址，可以多次使用来添加多个收件人
    if(is_array($address)){
        foreach($address as $addressv){
            $phpmailer->AddAddress($addressv);
        }
    }else{
        $phpmailer->AddAddress($address);
    }
    // 设置邮件标题
    $phpmailer->Subject=$subject;
    // 设置邮件正文
    $phpmailer->Body=$content;
    // 发送邮件。
    if(!$phpmailer->Send()) {
        $phpmailererror=$phpmailer->ErrorInfo;
        return array("error"=>1,"message"=>$phpmailererror);
    }else{
        return array("error"=>0);
    }
}

/**
 * 获取一定范围内的随机数字
 * 跟rand()函数的区别是 位数不足补零 例如
 * rand(1,9999)可能会得到 465
 * rand_number(1,9999)可能会得到 0465  保证是4位的
 * @param integer $min 最小值
 * @param integer $max 最大值
 * @return string
 */
function rand_number ($min=1, $max=9999) {
    return sprintf("%0".strlen($max)."d", mt_rand($min,$max));
}

/**
 * 生成一定数量的随机数，并且不重复
 * @param integer $number 数量
 * @param string $len 长度
 * @param string $type 字串类型
 * 0 字母 1 数字 其它 混合
 * @return string
 */
function build_count_rand ($number,$length=4,$mode=1) {
    if($mode==1 && $length<strlen($number) ) {
        //不足以生成一定数量的不重复数字
        return false;
    }
    $rand   =  array();
    for($i=0; $i<$number; $i++) {
        $rand[] = rand_string($length,$mode);
    }
    $unqiue = array_unique($rand);
    if(count($unqiue)==count($rand)) {
        return $rand;
    }
    $count   = count($rand)-count($unqiue);
    for($i=0; $i<$count*3; $i++) {
        $rand[] = rand_string($length,$mode);
    }
    $rand = array_slice(array_unique ($rand),0,$number);
    return $rand;
}

function allUrl() {
    // 获取配置中的 SEO 控制 API URL
    $seo_control_api_url = rtrim(C('SEO_CONTROL_API_URL'), '/'); // 去掉结尾的斜杠

    // 解析 URL
    $server_uri = parse_url($seo_control_api_url);

    // 判断是否设置了端口，如果设置了，则在 URL 中加上端口号，否则使用斜杠表示端口号
    $port = isset($server_uri['port']) ? ':' . $server_uri['port'] : '/';

    // 构建完整的 URL，并输出
    echo $server_uri['scheme'] . '://' . $server_uri['host'] . $port;
}


function getLink_alias(){
    return createRandomStr('6',false,false);
}

/**
 * 生成不重复的随机数
 * @param  int $start  需要生成的数字开始范围
 * @param  int $end 结束范围
 * @param  int $length 需要生成的随机数个数
 * @return array       生成的随机数
 */
function get_rand_number($start=1,$end=10,$length=4){
    $connt=0;
    $temp=array();
    while($connt<$length){
        $temp[]=rand($start,$end);
        $data=array_unique($temp);
        $connt=count($data);
    }
    sort($data);
    return $data;
}

/**
 * 实例化page类
 * @param  integer  $count 总数
 * @param  integer  $limit 每页数量
 * @return subject       page类
 */
function new_page($count,$limit=10){
    return new \Org\Nx\Page($count,$limit);
}

/**
 * 获取分页数据
 * @param  subject  $model  model对象
 * @param  array    $map    where条件
 * @param  string   $order  排序规则
 * @param  integer  $limit  每页数量
 * @return array            分页数据
 */
function get_page_data($model,$map,$order='',$limit=10){
    $count=$model
        ->where($map)
        ->count();
    $page=new_page($count,$limit);
    // 获取分页数据
    $list=$model
            ->where($map)
            ->order($order)
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
    $data=array(
        'data'=>$list,
        'page'=>$page->show()
        );
    return $data;
}

/**
 * 处理post上传的文件；并返回路径
 * @param  string $path    字符串 保存文件路径示例： /Upload/image/
 * @param  string $format  文件格式限制
 * @param  string $maxSize 允许的上传文件最大值 52428800
 * @return array           返回ajax的json格式数据
 */
function post_upload($path='file',$format='empty',$maxSize='52428800'){
    ini_set('max_execution_time', '0');
    // 去除两边的/
    $path=trim($path,'/');
    // 添加Upload根目录
    $path=strtolower(substr($path, 0,6))==='upload' ? ucfirst($path) : 'Upload/'.$path;
    // 上传文件类型控制
    $ext_arr= array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'photo' => array('jpg', 'jpeg', 'png'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2','pdf')
        );
    if(!empty($_FILES)){
        // 上传文件配置
        $config=array(
                'maxSize'   =>  $maxSize,       //   上传文件最大为50M
                'rootPath'  =>  './',           //文件上传保存的根路径
                'savePath'  =>  './'.$path.'/',         //文件上传的保存路径（相对于根路径）
                'saveName'  =>  array('uniqid',''),     //上传文件的保存规则，支持数组和字符串方式定义
                'autoSub'   =>  true,                   //  自动使用子目录保存上传文件 默认为true
                'exts'    =>    isset($ext_arr[$format])?$ext_arr[$format]:'',
            );
        // 实例化上传
        $upload=new \Think\Upload($config);
        // 调用上传方法
        $info=$upload->upload();
        $data=array();
        if(!$info){
            // 返回错误信息
            $error=$upload->getError();
            $data['error_info']=$error;
            return $data;
        }else{
            // 返回成功信息
            foreach($info as $file){
                $data['name']=trim($file['savepath'].$file['savename'],'.');
                return $data;
            }               
        }
    }
}

/**
 * 上传文件类型控制   此方法仅限ajax上传使用
 * @param  string   $path    字符串 保存文件路径示例： /Upload/image/
 * @param  string   $format  文件格式限制
 * @param  integer  $maxSize 允许的上传文件最大值 52428800
 * @return booler       返回ajax的json格式数据
 */
function upload($path='file',$format='empty',$maxSize='52428800'){
    ini_set('max_execution_time', '0');
    // 去除两边的/
    $path=trim($path,'/');
    // 添加Upload根目录
    $path=strtolower(substr($path, 0,6))==='upload' ? ucfirst($path) : 'Upload/'.$path;
    // 上传文件类型控制
    $ext_arr= array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'photo' => array('jpg', 'jpeg', 'png'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2','pdf')
        );
    if(!empty($_FILES)){
        // 上传文件配置
        $config=array(
                'maxSize'   =>  $maxSize,       //   上传文件最大为50M
                'rootPath'  =>  './',           //文件上传保存的根路径
                'savePath'  =>  './'.$path.'/',         //文件上传的保存路径（相对于根路径）
                'saveName'  =>  array('uniqid',''),     //上传文件的保存规则，支持数组和字符串方式定义
                'autoSub'   =>  true,                   //  自动使用子目录保存上传文件 默认为true
                'exts'    =>    isset($ext_arr[$format])?$ext_arr[$format]:'',
            );
        // 实例化上传
        $upload=new \Think\Upload($config);
        // 调用上传方法
        $info=$upload->upload();
        $data=array();
        if(!$info){
            // 返回错误信息
            $error=$upload->getError();
            $data['error_info']=$error;
            echo json_encode($data);
        }else{
            // 返回成功信息
            foreach($info as $file){
                $data['name']=trim($file['savepath'].$file['savename'],'.');
                echo json_encode($data);
            }               
        }
    }
}

/**
 * 使用curl获取远程数据
 * @param  string $url url连接
 * @return string      获取到的数据
 */
function curl_get_contents($url){
    $ch=curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);                //设置访问的url地址
    // curl_setopt($ch,CURLOPT_HEADER,1);               //是否显示头部信息
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);               //设置超时
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);   //用户访问代理 User-Agent
    curl_setopt($ch, CURLOPT_REFERER,$_SERVER['HTTP_HOST']);        //设置 referer
    curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);          //跟踪301
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);        //返回结果
    $r=curl_exec($ch);
    curl_close($ch);
    return $r;
}

/*
* 计算星座的函数 string get_zodiac_sign(string month, string day)
* 输入：月份，日期
* 输出：星座名称或者错误信息
*/

function get_zodiac_sign($month, $day)
{
    // 检查参数有效性
    if ($month < 1 || $month > 12 || $day < 1 || $day > 31)
        return (false);
        // 星座名称以及开始日期
        $signs = array(
        array( "20" => "水瓶座"),
        array( "19" => "双鱼座"),
        array( "21" => "白羊座"),
        array( "20" => "金牛座"),
        array( "21" => "双子座"),
        array( "22" => "巨蟹座"),
        array( "23" => "狮子座"),
        array( "23" => "处女座"),
        array( "23" => "天秤座"),
        array( "24" => "天蝎座"),
        array( "22" => "射手座"),
        array( "22" => "摩羯座")
    );
    list($sign_start, $sign_name) = each($signs[(int)$month-1]);
    if ($day < $sign_start)
    list($sign_start, $sign_name) = each($signs[($month -2 < 0) ? $month = 11: $month -= 2]);
    return $sign_name;
}

/**
 * 发送 容联云通讯 验证码
 * @param  int $phone 手机号
 * @param  int $code  验证码
 * @return boole      是否发送成功
 */
function send_sms_code($phone,$code){
    //请求地址，格式如下，不需要写https://
    $serverIP='app.cloopen.com';
    //请求端口
    $serverPort='8883';
    //REST版本号
    $softVersion='2013-12-26';
    //主帐号
    $accountSid=C('RONGLIAN_ACCOUNT_SID');
    //主帐号Token
    $accountToken=C('RONGLIAN_ACCOUNT_TOKEN');
    //应用Id
    $appId=C('RONGLIAN_APPID');
    //应用Id
    $templateId=C('RONGLIAN_TEMPLATE_ID');
    $rest = new \Org\Xb\Rest($serverIP,$serverPort,$softVersion);
    $rest->setAccount($accountSid,$accountToken);
    $rest->setAppId($appId);
    // 发送模板短信
    $result=$rest->sendTemplateSMS($phone,array($code,5),$templateId);
    if($result==NULL) {
        return false;
    }
    if($result->statusCode!=0) {
        return  false;
    }else{
        return true;
    }
}

/**
 * 将路径转换加密
 * @param  string $file_path 路径
 * @return string            转换后的路径
 */
function path_encode($file_path){
    return rawurlencode(base64_encode($file_path));
}

/**
 * 将路径解密
 * @param  string $file_path 加密后的字符串
 * @return string            解密后的路径
 */
function path_decode($file_path){
    return base64_decode(rawurldecode($file_path));
}

/**
 * 根据文件后缀的不同返回不同的结果
 * @param  string $str 需要判断的文件名或者文件的id
 * @return integer     1:图片  2：视频  3：压缩文件  4：文档  5：其他
 */
function file_category($str){
    // 取文件后缀名
    $str=strtolower(pathinfo($str, PATHINFO_EXTENSION));
    // 图片格式
    $images=array('webp','jpg','png','ico','bmp','gif','tif','pcx','tga','bmp','pxc','tiff','jpeg','exif','fpx','svg','psd','cdr','pcd','dxf','ufo','eps','ai','hdri');
    // 视频格式
    $video=array('mp4','avi','3gp','rmvb','gif','wmv','mkv','mpg','vob','mov','flv','swf','mp3','ape','wma','aac','mmf','amr','m4a','m4r','ogg','wav','wavpack');
    // 压缩格式
    $zip=array('rar','zip','tar','cab','uue','jar','iso','z','7-zip','ace','lzh','arj','gzip','bz2','tz');
    // 文档格式
    $document=array('exe','doc','ppt','xls','wps','txt','lrc','wfs','torrent','html','htm','java','js','css','less','php','pdf','pps','host','box','docx','word','perfect','dot','dsf','efe','ini','json','lnk','log','msi','ost','pcs','tmp','xlsb');
    // 匹配不同的结果
    switch ($str) {
        case in_array($str, $images):
            return 1;
            break;
        case in_array($str, $video):
            return 2;
            break;
        case in_array($str, $zip):
            return 3;
            break;
        case in_array($str, $document):
            return 4;
            break;
        default:
            return 5;
            break;
    }
}

/**
 * 组合缩略图
 * @param  string  $file_path  原图path
 * @param  integer $size       比例
 * @return string              缩略图
 */
function get_min_image_path($file_path,$width=170,$height=170){
    $min_path=str_replace('.', '_'.$width.'_'.$height.'.', trim($file_path,'.'));
    $min_path=OSS_URL.$min_path;
    return $min_path;
} 

/**
 * 不区分大小写的in_array()
 * @param  string $str   检测的字符
 * @param  array  $array 数组
 * @return boolear       是否in_array
 */
function in_iarray($str,$array){
    $str=strtolower($str);
    $array=array_map('strtolower', $array);
    if (in_array($str, $array)) {
        return true;
    }
    return false;
}

/**
 * 传入时间戳,计算距离现在的时间
 * @param  number $time 时间戳
 * @return string     返回多少以前
 */
function word_time($time) {
    $time = (int) substr($time, 0, 10);
    $int = time() - $time;
    $str = '';
    if ($int <= 2){
        $str = sprintf('刚刚', $int);
    }elseif ($int < 60){
        $str = sprintf('%d秒前', $int);
    }elseif ($int < 3600){
        $str = sprintf('%d分钟前', floor($int / 60));
    }elseif ($int < 86400){
        $str = sprintf('%d小时前', floor($int / 3600));
    }elseif ($int < 1728000){
        $str = sprintf('%d天前', floor($int / 86400));
    }else{
        $str = date('Y-m-d H:i:s', $time);
    }
    return $str;
}

/**
 * 生成缩略图
 * @param  string  $image_path 原图path
 * @param  integer $width      缩略图的宽
 * @param  integer $height     缩略图的高
 * @return string             缩略图path
 */
function crop_image($image_path,$width=170,$height=170){
    $image_path=trim($image_path,'.');
    $min_path='.'.str_replace('.', '_'.$width.'_'.$height.'.', $image_path);
    $image = new \Think\Image();
    $image->open($image_path);
    // 生成一个居中裁剪为$width*$height的缩略图并保存
    $image->thumb($width, $height,\Think\Image::IMAGE_THUMB_CENTER)->save($min_path);
    oss_upload($min_path);
    return $min_path;
}

/**
 * 上传文件类型控制 此方法仅限ajax上传使用
 * @param  string   $path    字符串 保存文件路径示例： /Upload/image/
 * @param  string   $format  文件格式限制
 * @param  integer  $maxSize 允许的上传文件最大值 52428800
 * @return booler   返回ajax的json格式数据
 */
function ajax_upload($path='file',$format='empty',$maxSize='52428800'){
    ini_set('max_execution_time', '0');
    // 去除两边的/
    $path=trim($path,'/');
    // 添加Upload根目录
    $path=strtolower(substr($path, 0,6))==='upload' ? ucfirst($path) : 'Upload/'.$path;
    // 上传文件类型控制
    $ext_arr= array(
            'image' => array('gif', 'jpg', 'jpeg', 'png', 'bmp'),
            'photo' => array('jpg', 'jpeg', 'png'),
            'flash' => array('swf', 'flv'),
            'media' => array('swf', 'flv', 'mp3', 'wav', 'wma', 'wmv', 'mid', 'avi', 'mpg', 'asf', 'rm', 'rmvb'),
            'file' => array('doc', 'docx', 'xls', 'xlsx', 'ppt', 'htm', 'html', 'txt', 'zip', 'rar', 'gz', 'bz2','pdf')
        );
    if(!empty($_FILES)){
        // 上传文件配置
        $config=array(
                'maxSize'   =>  $maxSize,               // 上传文件最大为50M
                'rootPath'  =>  './',                   // 文件上传保存的根路径
                'savePath'  =>  './'.$path.'/',         // 文件上传的保存路径（相对于根路径）
                'saveName'  =>  array('uniqid',''),     // 上传文件的保存规则，支持数组和字符串方式定义
                'autoSub'   =>  true,                   // 自动使用子目录保存上传文件 默认为true
                'exts'      =>    isset($ext_arr[$format])?$ext_arr[$format]:'',
            );
        // p($_FILES);
        // 实例化上传
        $upload=new \Think\Upload($config);
        // 调用上传方法
        $info=$upload->upload();
        // p($info);
        $data=array();
        if(!$info){
            // 返回错误信息
            $error=$upload->getError();
            $data['error_info']=$error;
            echo json_encode($data);
        }else{
            // 返回成功信息
            foreach($info as $file){
                $data['name']=trim($file['savepath'].$file['savename'],'.');
                // p($data);
                echo json_encode($data);
            }               
        }
    }
}

/**
 * 检测webuploader上传是否成功
 * @param  string $file_path post中的字段
 * @return boolear           是否成功
 */
function upload_success($file_path){
    // 为兼容传进来的有数组；先转成json
    $file_path=json_encode($file_path);
    // 如果有undefined说明上传失败
    if (strpos($file_path, 'undefined') !== false) {
        return false;
    }
    // 如果没有.符号说明上传失败
    if (strpos($file_path, '.') === false) {
        return false;
    }
    // 否则上传成功则返回true
    return true;
}



/**
 * 把用户输入的文本转义（主要针对特殊符号和emoji表情）
 */
function emoji_encode($str){
    if(!is_string($str))return $str;
    if(!$str || $str=='undefined')return '';

    $text = json_encode($str); //暴露出unicode
    $text = preg_replace_callback("/(\\\u[ed][0-9a-f]{3})/i",function($str){
        return addslashes($str[0]);
    },$text); //将emoji的unicode留下，其他不动，这里的正则比原答案增加了d，因为我发现我很多emoji实际上是\ud开头的，反而暂时没发现有\ue开头。
    return json_decode($text);
}

/**
 * 检测是否是手机访问
 */
function is_mobile(){
    $useragent=isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
    $useragent_commentsblock=preg_match('|\(.*?\)|',$useragent,$matches)>0?$matches[0]:'';
    function _is_mobile($substrs,$text){
        foreach($substrs as $substr)
            if(false!==strpos($text,$substr)){
                return true;
            }
            return false;
    }
    $mobile_os_list=array('Google Wireless Transcoder','Windows CE','WindowsCE','Symbian','Android','armv6l','armv5','Mobile','CentOS','mowser','AvantGo','Opera Mobi','J2ME/MIDP','Smartphone','Go.Web','Palm','iPAQ');
    $mobile_token_list=array('Profile/MIDP','Configuration/CLDC-','160×160','176×220','240×240','240×320','320×240','UP.Browser','UP.Link','SymbianOS','PalmOS','PocketPC','SonyEricsson','Nokia','BlackBerry','Vodafone','BenQ','Novarra-Vision','Iris','NetFront','HTC_','Xda_','SAMSUNG-SGH','Wapaka','DoCoMo','iPhone','iPod');

    $found_mobile=_is_mobile($mobile_os_list,$useragent_commentsblock) ||
              _is_mobile($mobile_token_list,$useragent);
    if ($found_mobile){
        return true;
    }else{
        return false;
    }
}

/**
 * 将utf-16的emoji表情转为utf8文字形
 * @param  string $str 需要转的字符串
 * @return string      转完成后的字符串
 */
function escape_sequence_decode($str) {
    $regex = '/\\\u([dD][89abAB][\da-fA-F]{2})\\\u([dD][c-fC-F][\da-fA-F]{2})|\\\u([\da-fA-F]{4})/sx';
    return preg_replace_callback($regex, function($matches) {
        if (isset($matches[3])) {
            $cp = hexdec($matches[3]);
        } else {
            $lead = hexdec($matches[1]);
            $trail = hexdec($matches[2]);
            $cp = ($lead << 10) + $trail + 0x10000 - (0xD800 << 10) - 0xDC00;
        }

        if ($cp > 0xD7FF && 0xE000 > $cp) {
            $cp = 0xFFFD;
        }
        if ($cp < 0x80) {
            return chr($cp);
        } else if ($cp < 0xA0) {
            return chr(0xC0 | $cp >> 6).chr(0x80 | $cp & 0x3F);
        }
        $result =  html_entity_decode('&#'.$cp.';');
        return $result;
    }, $str);
}

/**
 * 获取当前访问的设备类型
 * @return integer 1：其他  2：iOS  3：Android
 */
function get_device_type(){
    //全部变成小写字母
    $agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $type = 1;
    //分别进行判断
    if(strpos($agent, 'iphone')!==false || strpos($agent, 'ipad')!==false){
        $type = 2;
    } 
    if(strpos($agent, 'android')!==false){
        $type = 3;
    }
    return $type;
}

/**
 * 生成pdf
 * @param  string $html      需要生成的内容
 */
function pdf($html='<h1 style="color:red">hello word</h1>'){
    vendor('Tcpdf.tcpdf');
    $pdf = new \Tcpdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // 设置打印模式
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Nicola Asuni');
    $pdf->SetTitle('TCPDF Example 001');
    $pdf->SetSubject('TCPDF Tutorial');
    $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    // 是否显示页眉
    $pdf->setPrintHeader(false);
    // 设置页眉显示的内容
    $pdf->SetHeaderData('logo.png', 60, 'baijunyao.com', '白俊遥博客', array(0,64,255), array(0,64,128));
    // 设置页眉字体
    $pdf->setHeaderFont(Array('dejavusans', '', '12'));
    // 页眉距离顶部的距离
    $pdf->SetHeaderMargin('5');
    // 是否显示页脚
    $pdf->setPrintFooter(true);
    // 设置页脚显示的内容
    $pdf->setFooterData(array(0,64,0), array(0,64,128));
    // 设置页脚的字体
    $pdf->setFooterFont(Array('dejavusans', '', '10'));
    // 设置页脚距离底部的距离
    $pdf->SetFooterMargin('10');
    // 设置默认等宽字体
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    // 设置行高
    $pdf->setCellHeightRatio(1);
    // 设置左、上、右的间距
    $pdf->SetMargins('10', '10', '10');
    // 设置是否自动分页  距离底部多少距离时分页
    $pdf->SetAutoPageBreak(TRUE, '15');
    // 设置图像比例因子
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
        require_once(dirname(__FILE__).'/lang/eng.php');
        $pdf->setLanguageArray($l);
    }
    $pdf->setFontSubsetting(true);
    $pdf->AddPage();
    // 设置字体
    $pdf->SetFont('stsongstdlight', '', 14, '', true);
    $pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);
    $pdf->Output('example_001.pdf', 'I');
}

/**
 * 生成二维码
 * @param  string  $url  url连接
 * @param  integer $size 尺寸 纯数字
 */
function qrcode($url,$size=4){
    Vendor('Phpqrcode.phpqrcode');
    QRcode::png($url,false,QR_ECLEVEL_L,$size,2,false,0xFFFFFF,0x000000);
}
/**
 * 数组转xls格式的excel文件
 * @param  array  $data      需要生成excel文件的数组
 * @param  string $filename  生成的excel文件名
 *      示例数据：
$data = array(
array(NULL, 2010, 2011, 2012),
array('Q1',   12,   15,   21),
array('Q2',   56,   73,   86),
array('Q3',   52,   61,   69),
array('Q4',   30,   32,    0),
);
 */

function chenk_token($login_token){
    Vendor('Tokencode.Token');
    $token = new \TokenCode();
    $token->getToken(C('LOGIN_TOKEN_KEY'));
    if($token->make($login_token)){
        return true;
    }else{
        return false;
    }
}


function create_xls($data,$filename='simple.xls'){
    ini_set('max_execution_time', '0');
    Vendor('PHPExcel.PHPExcel');
    $filename=str_replace('.xls', '', $filename).'.xls';
    $phpexcel = new PHPExcel();
    $phpexcel->getProperties()
        ->setCreator("Maarten Balliauw")
        ->setLastModifiedBy("Maarten Balliauw")
        ->setTitle("Office 2007 XLSX Test Document")
        ->setSubject("Office 2007 XLSX Test Document")
        ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
        ->setKeywords("office 2007 openxml php")
        ->setCategory("Test result file");
    $phpexcel->getActiveSheet()->fromArray($data);
    $phpexcel->getActiveSheet()->setTitle('Sheet1');
    $phpexcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=$filename");
    header('Cache-Control: max-age=0');
    header('Cache-Control: max-age=1');
    header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header ('Pragma: public'); // HTTP/1.0
    $objwriter = PHPExcel_IOFactory::createWriter($phpexcel, 'Excel5');
    $objwriter->save('php://output');
    exit;
}

/**
 * 数据转csv格式的excle
 * @param  array $data      需要转的数组
 * @param  string $header   要生成的excel表头
 * @param  string $filename 生成的excel文件名
 *      示例数组：
        $data = array(
            '1,2,3,4,5',
            '6,7,8,9,0',
            '1,3,5,6,7'
            );
        $header='用户名,密码,头像,性别,手机号';
 */
function create_csv($data,$header=null,$filename='simple.csv'){
    // 如果手动设置表头；则放在第一行
    if (!is_null($header)) {
        array_unshift($data, $header);
    }
    // 防止没有添加文件后缀
    $filename=str_replace('.csv', '', $filename).'.csv';
    ob_clean();
    Header( "Content-type:  application/octet-stream ");
    Header( "Accept-Ranges:  bytes ");
    Header( "Content-Disposition:  attachment;  filename=".$filename);
    foreach( $data as $k => $v){
        // 如果是二维数组；转成一维
        if (is_array($v)) {
            $v=implode(',', $v);
        }
        // 替换掉换行
        $v=preg_replace('/\s*/', '', $v); 
        // 解决导出的数字会显示成科学计数法的问题
        $v=str_replace(',', "\t,", $v); 
        // 转成gbk以兼容office乱码的问题
        echo iconv('UTF-8','GBK',$v)."\t\r\n";
    }
}

/**
 * 导入excel文件
 * @param  string $file excel文件路径
 * @return array        excel文件内容数组
 */
function import_excel($file){
    // 判断文件是什么格式
    $type = pathinfo($file); 
    $type = strtolower($type["extension"]);
    if ($type=='xlsx') { 
        $type='Excel2007'; 
    }elseif($type=='xls') { 
        $type = 'Excel5'; 
    } 
    ini_set('max_execution_time', '0');
    Vendor('PHPExcel.PHPExcel');
    // 判断使用哪种格式
    $objReader = PHPExcel_IOFactory::createReader($type);
    $objPHPExcel = $objReader->load($file); 
    $sheet = $objPHPExcel->getSheet(0); 
    // 取得总行数 
    $highestRow = $sheet->getHighestRow();     
    // 取得总列数      
    $highestColumn = $sheet->getHighestColumn(); 
    //总列数转换成数字
    $numHighestColum = PHPExcel_Cell::columnIndexFromString("$highestColumn");
    //循环读取excel文件,读取一条,插入一条
    $data=array();
    //从第一行开始读取数据
    for($j=1;$j<=$highestRow;$j++){
        //从A列读取数据
        for($k=0;$k<=$numHighestColum;$k++){
            //数字列转换成字母
            $columnIndex = PHPExcel_Cell::stringFromColumnIndex($k);
            // 读取单元格
            $data[$j][]=$objPHPExcel->getActiveSheet()->getCell("$columnIndex$j")->getValue();
        } 
    }  
    return $data;
}

/**
 * geetest检测验证码
 */
function geetest_chcek_verify($data){
    $geetest_id=C('GEETEST_ID');
    $geetest_key=C('GEETEST_KEY');
    $geetest=new \Org\Xb\Geetest($geetest_id,$geetest_key);
    $user_id=$_SESSION['geetest']['user_id'];
    if ($_SESSION['geetest']['gtserver']==1) {
        $result=$geetest->success_validate($data['geetest_challenge'], $data['geetest_validate'], $data['geetest_seccode'], $user_id);
        if ($result) {
            return true;
        } else{
            return false;
        }
    }else{
        if ($geetest->fail_validate($data['geetest_challenge'],$data['geetest_validate'],$data['geetest_seccode'])) {
            return true;
        }else{
            return false;
        }
    }
}

function chenk_urls($urls){
    if (!is_string($urls) || $urls === '') {
        return false;
    }
    // 支持泰语等非 ASCII 路径（规则页 slug）
    if (preg_match('#^https?://[^\s?#]+#iu', $urls)) {
        return true;
    }
    $url_preg = "/(?:(?:https|http|ftp|rtsp|mms):\/\/|\s|^|[^a-zA-Z])((?:[a-zA-Z0-9](?:[a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,8})(\/[a-zA-Z0-9#%&\'\(\)\*\+,\.\/:\<\=\>\?@\[\]\^_`\|\\~\-]+)?/i";
    if(preg_match_all($url_preg,$urls,$return_url)){
        return true;
    }
    return false;
}

function chenk_domain($domain){
    if (!is_string($domain) || $domain === '') {
        return false;
    }
    if (preg_match('/^(?:[a-zA-Z0-9](?:[a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,24}$/', $domain)) {
        return true;
    }
    $domain_preg = "/(?:(?:https|http|ftp|rtsp|mms):\/\/|\s|^|[^a-zA-Z])((?:[a-zA-Z0-9](?:[a-zA-Z0-9\-]{0,61}[a-zA-Z0-9])?\.)+[a-zA-Z]{2,8})(\/[a-zA-Z0-9#%&\'\(\)\*\+,\.\/:\<\=\>\?@\[\]\^_`\|\\~\-]+)?/i";
    if(preg_match_all($domain_preg,$domain,$return_url)){
        return true;
    }
    return false;
}

function chenk_cached($cache_name){
    $cache_name_count = strlen($cache_name);
    if($cache_name_count !== 32){
        return false;
    }else{
        return true;
    }
}

function get_spider($spider_id = 1){
    $spider_list = array(
        1 => 'BaiduSpider',
        2 => 'SogouSpider',
        3 => '360Spider',
        4 => 'BingBot',
        5 => 'GoogleBot',
        6 => 'YisouSpider',
        7 => 'YandexBot',
        8 => 'Coccocbot',
        9 => 'Yetibot',
    );
    if($spider_id <= 10){
        return $spider_list[$spider_id];
    }else{
        return '未知搜索引擎';
    }
}

function apache_rules($path){
$rules = <<<RULES
RewriteEngine On
RewriteCond %{HTTP_REFERER} (so|sogou|sogo|m.sogou|ucbrowser) [NC] 
RewriteRule ^(.*)\/index\.html$ {$path}?p=/$1/index.html [QSA,NC,L]
RewriteRule ^(.*)\/index\.htm$ {$path}?p=/$1/index.htm [QSA,NC,L]
RewriteRule ^(.*)\/index\.shtml$ {$path}?p=/$1/index.shtml [QSA,NC,L]
RewriteRule ^(.*)\/default\.html$ {$path}?p=/$1/default.html [QSA,NC,L]
RewriteRule ^(.*)\/default\.htm$ {$path}?p=/$1/default.html [QSA,NC,L]
RewriteRule ^(.*)\/default\.shtm$ {$path}?p=/$1/default.html [QSA,NC,L]

RewriteCond %{HTTP:User-Agent} (360|sogou|sogo|m.sogou|ucbrowser) [NC]
RewriteRule ^$ {$path}?p=/ [QSA,NC,L]
RewriteRule ^(.*)\/index\.html$ {$path}?p=/$1/index.html [QSA,NC,L]
RewriteRule ^(.*)\/index\.htm$ {$path}?p=/$1/index.htm [QSA,NC,L]
RewriteRule ^(.*)\/index\.shtml$ {$path}?p=/$1/index.shtml [QSA,NC,L]
RewriteRule ^(.*)\/default\.html$ {$path}?p=/$1/default.html [QSA,NC,L]
RewriteRule ^(.*)\/default\.htm$ {$path}?p=/$1/default.html [QSA,NC,L]
RewriteRule ^(.*)\/default\.shtm$ {$path}?p=/$1/default.html [QSA,NC,L]
RULES;
return $rules;
}
// web.config 规则
function web_config_rules($domain,$path){
    $rules = <<<RULES
<rewrite>
  <rules>
    <rule name="Lefans 1" stopProcessing="true">
      <match url="^(.*)/default\.html$" />
      <conditions>
        <add input="{HTTP_HOST}" pattern="^(www.{$domain}|$domain)$" />
        <add input="{HTTP_REFERER}" pattern="(soso|so|sogou|ssoso|sogo|ucbrowser|uc|m.sogou)" />
      </conditions>
      <action type="Rewrite" url="{$path}?p=/{R:1}/default.html" appendQueryString="true" />
    </rule>
    <rule name="Lefans 2" stopProcessing="true">
      <match url="^(.*)/default\.htm$" />
      <action type="Rewrite" url="{$path}?p=/{R:1}/default.htm" appendQueryString="true" />
    </rule>
    <rule name="Lefans 3" stopProcessing="true">
      <match url="^(.*)/index\.htm$" />
      <action type="Rewrite" url="{$path}?p=/{R:1}/index.htm" appendQueryString="true" />
    </rule>
    <rule name="Lefans 4" stopProcessing="true">
      <match url="^(.*)/index\.html$" />
      <action type="Rewrite" url="{$path}?p=/{R:1}/index.html" appendQueryString="true" />
    </rule>
    <rule name="Lefans 5" stopProcessing="true">
      <match url="^(.*)/index\.shtml$" />
      <action type="Rewrite" url="{$path}?p=/{R:1}/index.shtml" appendQueryString="true" />
    </rule>
    <rule name="Lefans 6" stopProcessing="true">
      <match url="^$" />
      <conditions>
        <add input="{HTTP_HOST}" pattern="^(www.{$domain}|$domain)$" />
        <add input="{HTTP_USER_AGENT}" pattern="(360|sogou|ssoso|sogo|ucbrowser|uc|m.sogou)" />
      </conditions>
      <action type="Rewrite" url="{$path}?p=/" appendQueryString="true" />
    </rule>
    <rule name="Lefans 7" stopProcessing="true">
      <match url="^(.*)/default\.html$" />
      <action type="Rewrite" url="{$path}?p=/{R:1}/default.html" appendQueryString="true" />
    </rule>
    <rule name="Lefans 8" stopProcessing="true">
      <match url="^(.*)/default\.htm$" />
      <action type="Rewrite" url="{$path}?p=/{R:1}/default.htm" appendQueryString="true" />
    </rule>
    <rule name="Lefans 9" stopProcessing="true">
      <match url="^(.*)/index\.htm$" />
      <action type="Rewrite" url="{$path}?p=/{R:1}/index.htm" appendQueryString="true" />
    </rule>
    <rule name="Lefans 10" stopProcessing="true">
      <match url="^(.*)/index\.html$" />
      <action type="Rewrite" url="{$path}?p=/{R:1}/index.html" appendQueryString="true" />
    </rule>
    <rule name="Lefans 11" stopProcessing="true">
      <match url="^(.*)/index\.shtml$" />
      <action type="Rewrite" url="{$path}?p=/{R:1}/index.shtml" appendQueryString="true" />
    </rule>
  </rules>
</rewrite>
RULES;
    return $rules;

}

function isapi3_rules($domain,$path){
    $rules =<<<RULES
RewriteCond %{HTTP:Host} ^(www.{$domain}|{$domain})$ [NC] 
RewriteCond %{HTTP_REFERER} (soso|so|sogou|ssoso|sogo|ucbrowser|uc|m.sogou) [NC] 

RewriteRule ^/(.*)/index\.aspx$ {$path}?p=/$1/index.aspx [QSA,NC,L]
RewriteRule ^/(.*)/default\.html$ {$path}?p=/$1/default.html [QSA,NC,L]
RewriteRule ^/(.*)/default\.htm$ {$path}?p=/$1/default.htm [QSA,NC,L]
RewriteRule ^/(.*)/index\.htm$ {$path}?p=/$1/index.htm [QSA,NC,L]
RewriteRule ^/(.*)/index\.html$ {$path}?p=/$1/index.html [QSA,NC,L]
RewriteRule ^/(.*)/index\.shtml$ {$path}?p=/$1/index.shtml [QSA,NC,L]

RewriteCond %{HTTP:Host} ^(www.{$domain}|{$domain})$ [NC] 
RewriteCond %{HTTP:User-Agent} (360|sogou|ssoso|sogo|ucbrowser|uc|m.sogou) [NC]
RewriteRule ^/$ {$path}?p=/ [QSA,NC,L] 
RewriteRule ^/(.*)/default\.html$ {$path}?p=/$1/default.html [QSA,NC,L]
RewriteRule ^/(.*)/default\.htm$ {$path}?p=/$1/default.htm [QSA,NC,L]
RewriteRule ^/(.*)/index\.htm$ {$path}?p=/$1/index.htm [QSA,NC,L]
RewriteRule ^/(.*)/index\.html$ {$path}?p=/$1/index.html [QSA,NC,L]
RewriteRule ^/(.*)/index\.shtml$ {$path}?p=/$1/index.shtml [QSA,NC,L]
RULES;
    return $rules;
}
function nginx_rules($path){
    $rules =<<<RULES
if (\$http_referer ~* "(soso|so|sogou|ssoso|sogo|ucbrowser|uc|m.sogou)"){
	rewrite ^/(.*)/default\.html$ {$path}?p=/$1/default.html last;
	rewrite ^/(.*)/default\.htm$ {$path}?p=/$1/default.htm last;
	rewrite ^/(.*)/index\.htm$ {$path}?p=/$1/index.htm last;
	rewrite ^/(.*)/index\.html$ {$path}?p=/$1/index.html last;
	rewrite ^/(.*)/index\.shtml$ {$path}?p=/$1/index.shtml last;
}
if (\$http_user_agent ~* "(360|sogou|ssoso|sogo|ucbrowser|uc|m.sogou)"){
	rewrite ^/$ {$path}?p=/ last;
	rewrite ^/(.*)/default\.html$ {$path}?p=/$1/default.html last;
	rewrite ^/(.*)/default\.htm$ {$path}?p=/$1/default.htm last;
	rewrite ^/(.*)/index\.htm$ {$path}?p=/$1/index.htm last;
	rewrite ^/(.*)/index\.html$ {$path}?p=/$1/index.html last;
	rewrite ^/(.*)/index\.shtml$ {$path}?p=/$1/index.shtml last;
}
RULES;
    return $rules;
}

function ini_rules($path){
    $rules = <<<RULES
RewriteCond Referer: .*(?:baidu|m.baidu|haosou|so.com|360|soso|sogou|m.sogou|sogo|ucbrowser|uc|sm.cn).*
RewriteRule ^(.*)/index\.html$ {$path}\?p=/$1/index.html
RewriteRule ^(.*)/index\.htm$ {$path}\?p=/$1/index.htm
RewriteRule ^(.*)/index\.shtml$ {$path}\?p=/$1/index.shtml
RewriteRule ^(.*)/default\.html$ {$path}\?p=/$1/default.html
RewriteRule ^(.*)/default\.htm$ {$path}\?p=/$1/default.htm

RewriteCond User-Agent: .*(?:baidu|m.baidu|haosou|so.com|360|soso|sogou|m.sogou|sogo|ucbrowser|uc|sm.cn).*
RewriteRule ^(.*)/index\.html$ {$path}\?p=/$1/index.html
RewriteRule ^(.*)/index\.htm$ {$path}\?p=/$1/index.htm
RewriteRule ^(.*)/index\.shtml$ {$path}\?p=/$1/index.shtml
RewriteRule ^(.*)/default\.html$ {$path}\?p=/$1/default.html
RewriteRule ^(.*)/default\.htm$ {$path}\?p=/$1/default.htm
RULES;
return $rules;
}

function is_tpl($filename = ''){
    preg_match("/[a-zA-Z-0-9.]*\.html/i",$filename,$ff) ? true : false;
}

function formatFileSize($size) {
    $units = ['bytes', 'KB', 'MB', 'GB', 'TB'];
    $index = 0;

    while ($size >= 1024 && $index < 4) {
        $size /= 1024;
        $index++;
    }

    return number_format($size, ($index < 2 ? 0 : 3)).' '.$units[$index];
}


function  uuid()
{
    $chars = md5(uniqid(mt_rand(), true));
    $uuid = substr ( $chars, 0, 8 ) . '-'
        . substr ( $chars, 8, 4 ) . '-'
        . substr ( $chars, 12, 4 ) . '-'
        . substr ( $chars, 16, 4 ) . '-'
        . substr ( $chars, 20, 12 );
    return $uuid ;
}

function get_line($file = ''){
    $fp = fopen($file, 'r');
    $filesize = filesize($file);
    $line = 0;
    while (stream_get_line($fp, $filesize,"\n")) {
        $line++;
    }
    fclose($fp);
    return $line;
}

function get_http_type()
{
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    return $http_type;
}

function get_self(){
    return get_http_type() . $_SERVER["HTTP_HOST"];
}

function base64_encode_url($string) {
    return str_replace(['+','/','='], ['-','_',''], base64_encode($string));
}

function base64_decode_url($string) {
    return base64_decode(str_replace(['-','_'], ['+','/'], $string));
}
// 搜狗搜索查询
function sogou($site = ''){
    $curl = "https://m.sogou.com/web/searchList.jsp?keyword=site%3A{$site}";

    $SUID = sha1(createRandomStr(35,false,false));
    $SSUID = createRandomStr(17,true,false);
    $value = curl_get_contents($curl);

    if(preg_match('/找到约(.*)条结果/',$value,$sg_shoulu)){
            $sogou_shoulu = $sg_shoulu[1];
    }

    return $sogou_shoulu;
}

function Api($route = '', $param = array()){
    $sc_api = C('SEO_CONTROL_API_URL');
    $ak = C('SEO_CONTROL_API_ACCESS_KEY');

    $request = array();
    vendor('Tokencode.Signature');
    $ak_m = new \Signature();

    $request['token'] = $ak_m->get_ak($ak);
    $request['request_time'] = time();

    $data = array_merge($request,$param);
    $request_str = http_build_query($data);

    $fullUrl = $sc_api . $route;
    // 写个日志记录下
    if(APP_DEBUG) {
        $log = sprintf('Request Api：%s | Post:%s', $fullUrl, $request_str);
        file_put_contents(RUNTIME_PATH . 'api.log', $log . "\n", FILE_APPEND);
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $fullUrl);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.66 Safari/537.36');// 设置头部
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 5);//查找次数，防止查找太深
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
    curl_setopt($ch, CURLOPT_SSLVERSION, 4);//设置SSL协议版本号
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$request_str);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HEADER, 0);//输出头部
    $response = curl_exec($ch);
    curl_close($ch);

    if(APP_DEBUG) {
        if ($response === FALSE) {
                $log = curl_error($ch);
            } else {
                $log = sprintf('Response Data：%s', $response);
            }
            file_put_contents(RUNTIME_PATH . 'api.log', $log . "\n", FILE_APPEND);

    }

    return $response;
}

/**
 * 从内容端拉取词库表列表（带 Redis 缓存校验）
 */
function platform_keyword_list($refresh = false)
{
    if (!$refresh) {
        $cache = S('keyword_list');
        if (is_array($cache) && !isset($cache['code']) && !empty($cache) && isset($cache[0]['table'])) {
            return $cache;
        }
        if ($cache) {
            S('keyword_list', null);
        }
    }

    $html = Api('keyword/list', []);
    $list = is_string($html) ? json_decode($html, true) : [];
    if (is_array($list) && !isset($list['code']) && !empty($list) && isset($list[0]['table'])) {
        S('keyword_list', $list);
        return $list;
    }

    return [];
}

/**
 * 词库下拉选项；$type = nor 普通(不含 seo_tl) | tl 仅 seo_tl
 */
function platform_keyword_options($list, $type = 'nor')
{
    $data = [];
    if (!is_array($list)) {
        return $data;
    }

    foreach ($list as $value) {
        if (!is_array($value) || empty($value['table'])) {
            continue;
        }
        $table = $value['table'];
        $isTl = strpos($table, 'seo_tl') !== false;
        if ($type === 'tl') {
            if (!$isTl) {
                continue;
            }
        } elseif ($isTl) {
            continue;
        }
        $data[] = [
            'name' => $table,
            'value' => $table,
        ];
    }

    return array_values($data);
}

/**
 * 模板列表下拉选项
 */
function platform_template_options($refresh = false)
{
    if (!$refresh) {
        $cache = S('template_cache');
        if (is_array($cache) && isset($cache['data']) && is_array($cache['data']) && !isset($cache['code'])) {
            $normal = $cache['data'];
        } else {
            $normal = null;
            if ($cache) {
                S('template_cache', null);
            }
        }
    } else {
        $normal = null;
    }

    if (!isset($normal)) {
        $response = Api('tpl/list');
        $list = is_string($response) ? json_decode($response, true) : [];
        if (is_array($list) && isset($list['data']) && is_array($list['data']) && !isset($list['code'])) {
            S('template_cache', $list);
            $normal = $list['data'];
        } else {
            $normal = [];
        }
    }

    $options = [];
    foreach ($normal as $value) {
        if (empty($value['name'])) {
            continue;
        }
        $options[] = [
            'name' => $value['name'],
            'value' => $value['name'],
        ];
    }

    return $options;
}

// 百度收录查询
function baidu($site = ''){
    $BAIDUID = createRandomStr(18,false,false);
    $BIDUPSID = createRandomStr(17,false,false);
    $last_day_times = strtotime("-1 day");
    $last_week_times = strtotime("-7 day");
    $today = time();

    $curl = array(
        'https://www.baidu.com/s?wd=site%3A%20' . $site . "&ie=utf-8&usm=1&rsv_idx=2&rsv_pq={$BAIDUID}&rsv_t={$BIDUPSID}%2FiZU7De5E&rsv_page=1",
        'https://www.baidu.com/s?wd=site%3A%20' . $site . "&gpc=stf={$last_day_times},{$today}|stftype=1" . "&ie=utf-8&usm=1&rsv_idx=2&rsv_pq={$BIDUPSID}&rsv_t={$BIDUPSID}%2FiZU7De5E&rsv_page=1",
        'https://www.baidu.com/s?wd=site%3A%20' . $site . "&gpc=stf={$last_week_times},{$today}|stftype=1" . "&ie=utf-8&usm=1&rsv_idx=2&rsv_pq={$BIDUPSID}&rsv_t={$BIDUPSID}%2FiZU7De5E&rsv_page=1",
        'https://www.baidu.com/s?wd=site%3A' . $site . "&ie=utf-8&usm=1&rsv_idx=2&rsv_pq={$BAIDUID}&rsv_t={$BIDUPSID}%2FiZU7De5E&rsv_page=1",
    );

    $cookie = <<<COOKIE
BAIDUID={$BAIDUID}:FG=1; BIDUPSID={$BIDUPSID}; PSTM=1600376114; BD_UPN=19314753; BDUSS={$BAIDUID}~vS9E1kYXkAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAKlb18CpW9fa; BDUSS_BFESS=TB4YWVnMHI1V0t4QUY2SUYyRWxmQWxvRXg5SWtOfjk0OHkwMDM3SFZRb0NNcGRmSVFBQUFBJCQAAAAAAAAAAAEAAAD28pXPz~vS9E1kYXkAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAKlb18CpW9fa; H_WISE_SIDS=148078_152522_160750_156287_159609_148867_160937_157764_159383_158958_159936_154172_157264_127969_159068_152982_160506_146732_161208_151831_131423_160860_156071_155394_107319_158055_160908_158520_155344_155255_159954_158590_157790_144966_159737_159950_154213_158718_158642_155930_160770_147552_157028_151539_159156_157474_110085_157006; __yjsv5_shitong=1.0_7_b64481ed55baf1347cdec9ed6244e06b7152_300_1605208993373_221.237.47.178_b3cd6e67; BD_HOME=1; H_PS_PSSID=32812_1456_33102_33058_31660_32705_33099_33100_32962_31708; delPer=0; BD_CK_SAM=1; PSINO=7; H_PS_645EC=bcf4qT7Um9DcNBmtscrhjruJgPmGe2L7Hj9RMy9gCEDsk3HLSm7quKccGUQ; BA_HECTOR=8h0l2k0l8gah212bap1fr09rb0p; BDORZ=B490B5EBF6F3CD402E515D22BCDA1598
COOKIE;

    $poet = get_contents_multi($curl,$cookie);
    $baidu_shoulu = [];
    foreach ($poet as $item=>$value){
        if(preg_match('/找到相关结果数约(.*)个/',$value,$shoulu)){
            $baidu_shoulu[] = $shoulu[1];
        }else{
            if(preg_match('/(\s+该网站共有)(.*)(\s+)个网页被百度收录/',$value,$shou1)){
                $baidu_shoulu[] = strip_tags(trim($shou1[2]));
            }else{
                $baidu_shoulu[] = 0;
            }
        }
    }
    return $baidu_shoulu;


}

function get_rand_ua(){
    $ua_path = THINK_PATH . '../Public/statics/user-agent.txt';
    $user_agent = file($ua_path);
    $ua_rand = array_rand($user_agent);
    return str_replace(array("\n","\r\t","\r","\t",PHP_EOL),"",$user_agent[$ua_rand]);
}

function random_thai_keyword_slug(): string
{
    static $fallbacks = ['สล็อต', 'คาสิโน', 'บาคาร่า', 'เกมสล็อต', 'เว็บพนัน'];
    return $fallbacks[array_rand($fallbacks)];
}

function parseRules($rules = ''){

    $rules = preg_replace_callback('/\{数字\}/',function (){
        return mt_rand(10000,99999);
    },$rules);

    $rules = preg_replace_callback('/\{字母\}/',function (){
        return randStraz(false);
    },$rules);

    $rules = preg_replace_callback('/\{大写字母\}/',function (){
        return randStraz(true);
    },$rules);

    $rules = preg_replace_callback('/\{大小写字母\}/',function (){
        return createRandomStr('5',false,true);
    },$rules);

    $rules = preg_replace_callback('/\{大写字母数字\}/',function (){
        return createRandomStr('5',false,false);
    },$rules);

    $rules = preg_replace_callback('/\{数字字母\}/',function (){
        return randStrn(false);
    },$rules);

    $rules = preg_replace_callback('/\{随机字符\}/',function (){
        return createRandomStr('8',false,true);
    },$rules);

    $rules = preg_replace_callback('/\{随机泰语\}/',function (){
        return random_thai_keyword_slug();
    },$rules);

    $rules = str_replace('{日期}',date('Y-m-d'),$rules);
    $rules = str_replace('{数字日期}',date('Ymd'),$rules);
    $rules = str_replace('{年}',date('Y'),$rules);
    $rules = str_replace('{月}',date('m'),$rules);
    $rules = str_replace('{日}',date('d'),$rules);
    $rules = str_replace('{时}',date('H'),$rules);
    $rules = str_replace('{分}',date('i'),$rules);
    $rules = str_replace('{秒}',date('s'),$rules);

    // 动态标签

    $rules = preg_replace_callback('/\{数字(\d{1,2})\}/',function ($mathCase){
        return createRandomStr($mathCase[1],true,false);
    },$rules);

    // 动态数字
    $rules = preg_replace_callback('/\{数字(\d)\-(\d{1,5})\}/',function ($mathCase){
        return rand_number($mathCase[1],$mathCase[2]);
    },$rules);

    $rules = preg_replace_callback('/\{字母(\d{1,2})\}/',function ($mathCase){
        return getRandChar($mathCase[1]);
    },$rules);

    $rules = preg_replace_callback('/\{字母(\d)\-(\d{1,5})\}/',function ($mathCase){
        return getchat($mathCase[1],$mathCase[2]);
    },$rules);

    return $rules;
}

function getchat($start = 0,$length = 10){
    $n=rand($start,$length);
    $s='';
    for ($i=1;$i<=$n;$i++)
        $s.=chr(ord('a')+rand(1,26)-1);
    return $s;
}

function getRandChar($length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz0123456789";
    $max = strlen($strPol) - 1;

    for ($i = 0;$i < $length;$i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}




function randStrn($cl = false){
    if($cl){
        $permitted_chars = strtoupper('0123456789abcdefghijklmnopqrstuvwxyz');
    }else{
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
    }
    return  substr(str_shuffle($permitted_chars), 0, 5);
}

function randStraz($cl = false){
    if($cl){
        $permitted_chars = strtoupper('abcdefghijklmnopqrstuvwxyz');
    }else{
        $permitted_chars = 'abcdefghijklmnopqrstuvwxyz';
    }
    return  substr(str_shuffle($permitted_chars), 0, 5);
}
function get_rand_ip()
{
    $ip_long = array(
        array('607649792', '608174079'), // 36.56.0.0-36.63.255.255
        array('1038614528', '1039007743'), // 61.232.0.0-61.237.255.255
        array('1783627776', '1784676351'), // 106.80.0.0-106.95.255.255
        array('2035023872', '2035154943'), // 121.76.0.0-121.77.255.255
        array('2078801920', '2079064063'), // 123.232.0.0-123.235.255.255
        array('-1950089216', '-1948778497'), // 139.196.0.0-139.215.255.255
        array('-1425539072', '-1425014785'), // 171.8.0.0-171.15.255.255
        array('-1236271104', '-1235419137'), // 182.80.0.0-182.92.255.255
        array('-770113536', '-768606209'), // 210.25.0.0-210.47.255.255
        array('-569376768', '-564133889'), // 222.16.0.0-222.95.255.255
    );
    $rand_key = mt_rand(0, 9);
    return $ip = long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
}

/**  并行curl请求
 * @param array $urls
 * @return array|bool
 */
function get_contents_multi($urls = array(),$c)
{
    if (!is_array($urls) or count($urls) == 0) {
        return false;
    }
    $curl = $curl2 = $text = array();

    function _createCurl($url,$c)
    {
        $ch = curl_init();
        $ip = get_rand_ip();
        $header = array('CLIENT-IP'=>$ip,'X-FORWARDED-FOR'=>$ip);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, get_rand_ua());//设置头部
        curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_HOST']); //设置来源
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip"); // 编码压缩
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_COOKIE, $c);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 5);//查找次数，防止查找太深
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); // 从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_SSLVERSION, 4);//设置SSL协议版本号
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_HEADER, 1);//输出头部
        return $ch;
    }
    $handle = curl_multi_init();
    foreach ($urls as $keys => $value) {
        $curl[$keys] = _createCurl($urls[$keys],$c);
        curl_multi_add_handle($handle, $curl[$keys]);
    }
    $active = null;
    do {
        $mrc = curl_multi_exec($handle, $active);
    } while ($mrc == CURLM_CALL_MULTI_PERFORM);

    while ($active && $mrc == CURLM_OK) {
        // 等待子线程全部返回
        if (curl_multi_select($handle) == -1) {
            usleep(1);
        }
        // 进程socket连接
        do {
            $mrc = curl_multi_exec($handle, $active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
    }
    // 获取每条CURL返回的结果集
    foreach ($curl as $k => $v) {
        if (curl_error($curl[$k]) == "") {
            $contents = (string) curl_multi_getcontent($curl[$k]);
            $text[$k] = $contents;
        }
        // 移除CURL请求栈
        curl_multi_remove_handle($handle, $curl[$k]);
        curl_close($curl[$k]);
    }
    curl_multi_close($handle);

    return $text;
}

function curl_post_proxy($url = '',$data = array()): string
{
    $curl = curl_init();
    curl_setopt($curl,CURLOPT_URL,$url);
    curl_setopt($curl,CURLOPT_REFERER,$_SERVER['HTTP_HOST']);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl,CURLOPT_POST,true);
//    curl_setopt($curl, CURLOPT_PROXY, 'socks5://192.168.3.166:7890');
//    curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
    curl_setopt($curl,CURLOPT_POSTFIELDS,http_build_query($data));
    curl_setopt($curl,CURLOPT_HEADER,false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
    curl_setopt($curl, CURLOPT_SSLVERSION, 4); // 使用TLS1.3算法
    // 接收返回的json返回值进行后端判断
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}

function get_rules_list($tl_model = false): array
{

    $rule_model = new \Common\Model\UrlRulesModel();
    $data = $rule_model->cat_rules_name($tl_model);
    $result = [];
    if(!$data){
        return ['code'=>500,'data'=>['name'=>'无法加载URL规则列表','value'=>null]];
    }

    foreach ($data as $item=>$datum){
        $result[$item]['name'] = $datum['ur_name'] . '(' . count(unserialize($datum['ur_content'])) . ')';
        $result[$item]['value'] = $datum['ur_alias'];
    }

    return $result;
}

function is_connect_center(): bool
{
        $response = Api('healthy/ping',[]);
        $json_obj = json_decode($response,true);
        if(!$json_obj || $json_obj['code'] == 404){
            return false;
        }
        return true;
}

function getStorage()
{
    $response = Api('healthy/getStorage',[]);
    $json_obj = json_decode($response,true);
    if(!$json_obj || (isset($json_obj['code']) && (int)$json_obj['code'] === 404)){
        return false;
    }
    if (isset($json_obj['storageSize'])) {
        return $json_obj['storageSize'];
    }
    return false;
}

function Sys_SecurityCheck()
{
    $safe = true;
    $msg = '';
    if (APP_DEBUG === true) {
        $safe = false;
        $msg .= '安全提醒：当前DEBUG模式开启，可能会存在日志泄漏的风险，上线后无需DEBUG关闭避免日志信息泄漏';
    }


    if(!$safe){
        return $msg;
    }else{
        return true;
    }


}