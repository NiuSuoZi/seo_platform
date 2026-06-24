<?php
namespace Platform\Controller;
use Common\Controller\PlatformBaseController;
class ToolsController extends PlatformBaseController
{
    // 首页
    public function index()
    {
        $this->display();
    }
    // .htaccess 文件生成
    public function htaccess(){
        $this->display();
    }
    // IIS 域名导出
    public function imports(){
        $this->display();
    }
    // 文件索引加密
    public function includes(){
        $this->display();
    }
    // 编码转换
    public function encode(){
        $this->display();
    }
    // 二级目录生成
    public function rules(){
        if(IS_POST){
            // 生成类型
            $rules_contents = '';
            $rules_types = (int)I("post.rules_types");
            // 解析客户端URL
            $client_addr = I("post.rules_url");
            $client_array = parse_url($client_addr);
            $client_path = $client_array['path'];
            $client_host = $client_array['host'];
            $client_host = str_replace("www.","",$client_host);
            switch ($rules_types){
                // apache
                case 0:
                    $rules_contents = apache_rules($client_path);
                break;
                // isapi3
                case 1:
                    $rules_contents = isapi3_rules($client_host,$client_path);
                break;
                case 2:
                    $rules_contents = nginx_rules($client_path);
                break;
                // web.config
                case 3:
                    $rules_contents = web_config_rules($client_host,$client_path);
                break;
                case 4:
                    $rules_contents = ini_rules($client_path);
                    break;

            }
            $this->assign('rules_contents',$rules_contents);

        }
        $this->display();
    }
    // 模拟蜘蛛抓取
    public function robot()
    {
        $spider_user_agent = array(
            0 => '360Spider',
            1 => 'HaoSouSpider',
            2 => 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)',
            3 => 'Mozilla/5.0 (Linux;u;Android 4.2.2;zh-cn;) AppleWebKit/534.46 (KHTML,like Gecko) Version/5.1 Mobile Safari/10600.6.3 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)',
            4 => 'Sogou web spider/4.0("http://www.sogou.com/docs/help/webmasters.htm#07")',
            5 => 'Sogou inst spider/4.0("http://www.sogou.com/docs/help/webmasters.htm#07")',
            6 => 'YisouSpider',
            7 => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36'
        );
        if (IS_POST) {
            //接受参数
            $robot_type = (int)I('post.robot_type');
            $robot_url = trim(I('post.robot_url'));

            $robot_model = (int)I('post.robot_model');

            if($this->_chenk_url($robot_url)) {
                $data = '抓取地址有误，不允许抓取内网IP或域名未解析!!';
                $this->assign('data', $data);
                $this->display();
                die;
            }
            // 快照模式
            if ($robot_model == '1')
            {
                $data = $this->_get_url($robot_url, $spider_user_agent[$robot_type]);
                $data = $this->strip_html_tags($data);
                // 替换换行符为空格
                $data = str_replace("\n"," ",$data);
                $data = str_replace("\r\t"," ",$data);
                if($data === ''){
                    $data = '抓取任务响应超时!';
                }
                $this->assign('data', $data);
                $this->display();
                die;
            }

            // 源码模式
            $data = htmlspecialchars($this->_get_url($robot_url, $spider_user_agent[$robot_type]), ENT_IGNORE, 'UTF-8');

            if($data === ''){
                $data = '抓取任务响应超时!';
            }
            $this->assign('data', $data);
        }
        $this->display();
    }

    // 404检测
    public function chenk(){
        //如果来自ajax的提交
        if(IS_AJAX)
        {
            $referer_type = I('post.referer_type');
            $requeset_uri = trim(htmlspecialchars_decode(I('post.requeset_uri')));
            $ua_type = I('post.ua_type');
            $_source = htmlspecialchars($this->referer_request($requeset_uri,$referer_type,$ua_type));
            echo $_source;
            exit;
        }
        $this->display();
    }
    // 站群
    public function group(){

        if(IS_AJAX){
            $spider_server = I('post.spider_server'); //快照劫持地址
            $where_jump = I('post.where_jump'); //跳转地址
            $jet_option = I('post.jet_option',NULL,'int'); // 劫持选项
            $text = I('post.text'); //域名列表

            if($spider_server !== '' and $where_jump !== '' and $jet_option !== '' and $text !== '')
            {
                // 如果没有参数的情况下
                if(strpos($spider_server,'?') === false)
                {
                    $this->ajaxReturn(array('status'=> false,'msg'=> '服务端无法获取当前URL，请检查服务端配置选项！'));
                    exit(0);
                }
                // 先获取域名条数
                $text_s = explode("\n",$text); //分割成数组
                $_return = '';

                for($text_list=0;$text_list<count($text_s);$text_list++){
                    $_return .= $this->_save_Config($spider_server,$where_jump,$text_s[$text_list]);
                }

                $this->ajaxReturn(array('status'=> true,'text'=> $_return));
                exit;

            }else{
                $this->ajaxReturn(array('status'=> false,'msg'=>'数据输入错误，请检查后重试!'));
            }
        }

        $this->display();
    }

    // js 加密 解密
    public function decode(){
        if(IS_AJAX){
            $action = I('post.action');
            $source_code = $_POST['code'];
            $action_list = array('pack','unpack','beauty','purify','uglify');
            // 避免错误的操作 引发系统的崩溃
            if(in_array($action,$action_list) and $source_code !== ''){
                $decode_data = array(
                    'operate' => $action,
                    'code' => $source_code
                );
                $_complite = $this->_post_data('https://tool.lu/js/ajax.html',$decode_data);
                $_complite_array = json_decode($_complite,true);

                if($_complite_array['status'] === true){
                    $this->ajaxReturn(array('status' => 'true','text' => $_complite_array['text']));
                }else{
                    $this->ajaxReturn(array('status' => 'false','text' => '','msg'=>'转换错误，请检查你的JS代码!!'));
                }
            }
        }
        $this->display();
    }
    protected function referer_request($url,$referer_type = 0,$ua_type = 0){
        $user_agent = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/69.0.3497.100 Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 11_0 like Mac OS X) AppleWebKit/604.1.38 (KHTML, like Gecko) Version/11.0 Mobile/15A372 Safari/604.1'
        ];
        $referer = [
            'https://m.sogou.com/web/searchList.jsp?uID=T7BQZoHYkRAVc4yA&v=5&from=index&w=1274&t=1547578599872&s_t=1547578604120&s_from=index&pg=webSearchList&inter_index=1&keyword=pk10%E8%A6%81%E4%B9%B07%E7%A0%81&sourceid=sugg&sugoq=&sugn=0&suguuid=f137ab3e-8b9b-42b0-85c8-2b94e0fab831&sugsuv=AAEnKdXMIwAAAAqUJidKKAEAkwA%3D&sugtime=1547578604121&wm=3206',
            'https://m.baidu.com/s?iscookie=1&wd=%E4%BB%8A%E6%97%A5%E5%85%AD%E5%BC%80%E5%BD%A9&pn=10&oq=%E4%BB%8A%E6%97%A5%E5%85%AD%E5%BC%80%E5%BD%A9&tn=baiduhome_pg&ie=utf-8&rsv_idx=2&rsv_pq=ee7be79500016c16&rsv_t=60a7ZCFOpP05zde1jENeLbNezURV8VvTrwiObgUC1hp5A9tzcQvI42Kqnc1jihkaw81v&rsv_page=1',
            'https://so.m.sm.cn/s?q=%E9%A6%96%E5%B0%94%E5%88%86%E5%88%86%E5%BD%A9%E5%90%88%E6%B3%95%E5%90%97&uc_param_str=dnntnwvepffrgibijbprsv&from=ucdh',
            'https://m.so.com/s?q=%E6%97%B6%E6%97%B6%E5%BD%A9%E5%90%8E%E4%BA%8C%E6%9D%80%E4%B8%A4%E7%A0%81&src=suggest_history&sug_pos=1&sug=&srcg=home_next'
        ];
        $referer_type = $referer[$referer_type];
        $ua_type = $user_agent[$ua_type];
        if($this->_chenk_url($url)){
            exit('模拟地址错误,当前网络模式不允许模拟内网机器或未找到当前解析记录!');
        }
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 设置访问超时时间，以免太久的loading
        curl_setopt($curl, CURLOPT_TIMEOUT,30);
        // 设置特定ua进行访问
        curl_setopt($curl, CURLOPT_USERAGENT, $ua_type);
        // 设置来源地址
        curl_setopt($curl,CURLOPT_REFERER,$referer_type);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 增加301 302 抓取
        curl_setopt($curl,CURLOPT_FOLLOWLOCATION,1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        //$curl_info = curl_getinfo($curl);
        curl_close($curl);
        exit($data);
        $data = $this->gbk_to_utf8($data);
        return $data;
    }
    private function _get_url($url, $ua_type)
    {
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        // 设置访问超时时间，以免太久的loading
        curl_setopt($curl, CURLOPT_TIMEOUT,30);
        // 设置特定ua进行访问
        curl_setopt($curl, CURLOPT_USERAGENT, $ua_type);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // 增加301 302 抓取
        curl_setopt($curl,CURLOPT_FOLLOWLOCATION,1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        $curl_info = curl_getinfo($curl);
        curl_close($curl);

        $data = $this->gbk_to_utf8($data);

         // 没有抓取到内容 或者空页面
        /*
        if($data == ''){
            //$http_code = curl_getinfo();
            $data = $curl_info[http_code];
        }
        */
        return $data;
    }

    private function _post_data($url = '',$datas){
            //初始化
            $curl = curl_init();
            //设置抓取的url
            curl_setopt($curl, CURLOPT_URL, $url);
            //设置头文件的信息作为数据流输出
            curl_setopt($curl, CURLOPT_HEADER, 0);
            //设置获取的信息以文件流的形式返回，而不是直接输出。
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            //设置post方式提交
            curl_setopt($curl, CURLOPT_POST, 1);
            //设置post数据
            curl_setopt($curl, CURLOPT_POSTFIELDS, $datas);
            //执行命令
            $data = curl_exec($curl);
            //关闭URL请求
            curl_close($curl);
            //显示获得的数据
           return $data;
    }

    private function gbk_to_utf8($str)
    {
        $charset = mb_detect_encoding($str,array('UTF-8','GBK','GB2312'));
        $charset = strtolower($charset);
        if('cp936' == $charset){
            $charset='GBK';
        }
        if("utf-8" != $charset){
            $str = iconv($charset,"UTF-8//IGNORE",$str);
        }
        return $str;
    }

    private function strip_html_tags( $text )
    {
            $search = array("'<script[^>]*?>.*?</script>'si",	// strip out javascript
                "'<style[^>]*?>.*?</style>'",
                "'<[\/\!]*?[^<>]*?>'si",			// strip out html tags
                "'([\r\n])[\s]+'",					// strip out white space
                "'&(quot|#34|#034|#x22);'i",		// replace html entities
                "'&(amp|#38|#038|#x26);'i",			// added hexadecimal values
                "'&(lt|#60|#060|#x3c);'i",
                "'&(gt|#62|#062|#x3e);'i",
                "'&(nbsp|#160|#xa0);'i",
                "'&(iexcl|#161);'i",
                "'&(cent|#162);'i",
                "'&(pound|#163);'i",
                "'&(copy|#169);'i",
                "'&(reg|#174);'i",
                "'&(deg|#176);'i",
                "'&(#39|#039|#x27);'",
                "'&(euro|#8364);'i",				// europe
                "'&a(uml|UML);'",					// german
                "'&o(uml|UML);'",
                "'&u(uml|UML);'",
                "'&A(uml|UML);'",
                "'&O(uml|UML);'",
                "'&U(uml|UML);'",
                "'&szlig;'i",
            );
            $replace = array(	"",
                "",
                "\\1",
                "\"",
                "&",
                "<",
                ">",
                " ",
                chr(161),
                chr(162),
                chr(163),
                chr(169),
                chr(174),
                chr(176),
                chr(39),
                chr(128),
                "ä",
                "ö",
                "ü",
                "Ä",
                "Ö",
                "Ü",
                "ß",
            );

            $text = preg_replace($search,$replace,$text);

            return $text;
        }

    private function _chenk_url($url){
        $url_rege = "/http(s)?:\/\/([\w-]+\.)+[\w-]+(\/[\w- .\/?%&=]*)?/";
        $preg = preg_match($url_rege,$url,$preg_array);
        // 预防SSRF漏洞
        // 去掉http头 http:// https:// ftp://
        $remove_http = str_replace("http://","",$preg_array[0]);
        $remove_http = str_replace("https://","",$remove_http);
        $remove_ip =  gethostbyname($remove_http);

        // 正则表达式匹配不完整的情况下
        if($this->isInnerIP(gethostbyname(parse_url($url)['host'])))
        {
            return true;
        }

        // 取正则表达式
        if(empty($remove_http) or $this->isInnerIP($remove_ip))
        {
           return true;
        }
    }

    private function isInnerIP($ip_arg)
    {
        $ip = ip2long($ip_arg);
        return ip2long('127.0.0.0') >> 24 === $ip >> 24 or
                ip2long('10.0.0.0') >> 24 === $ip >> 24 or
                ip2long('172.16.0.0') >> 20 === $ip >> 20 or
                ip2long('192.168.0.0') >> 16 === $ip >> 16;
    }

    protected function _save_Config($spider_url,$where_jump,$domin)
    {
        // 删掉协议头和主机头
        $domins = null;
        $domin = str_replace("http://","",$domin);
        $domin = str_replace("https://","",$domin);
        $domins = $domin;
        $domins = str_replace("www.","",$domins);

        // $domin = str_replace("www.","",$domin);

        $_config = <<<EOF
### for UrlRewrite3 for IIS
RewriteCond %{HTTP:Host} ^($domins|$domin)$ [NC]
RewriteCond %{HTTP:User-Agent} (?:baidu|sogou|360|haosou|so|yisou|uc|ucbrowser) [NC]
RewriteRule ^/$ $spider_url{$domin}$1 [NC,L]

RewriteCond %{HTTP:Host} ^($domins|$domin)$ [NC]
RewriteCond %{HTTP_REFERER} (?:baidu|sogou|360|haosou|so|sm|ucbrowser) [NC]
RewriteRule ^/$  $where_jump  [NC,L]
\n
EOF;
        return $_config;

    }


}