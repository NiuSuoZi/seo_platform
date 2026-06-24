<?php


namespace Platform\Controller;

use Common\Controller\PlatformBaseController;
use Common\Model\ControlModel;

class ProjectController extends PlatformBaseController
{
    private $tags_static = array('{数字}','{字母}','{大写字母}','{大小写字母}','{大写字母数字}','{大小写字母数字}','{数字字母}','{随机字符}','{随机泰语}','{日期}','{数字日期}','{年}','{月}','{日}','{时}','{分}','{秒}');
    private $tags_dynamic = array('{数字\d}','{数字\d\-\d}','{字母\d}','{字母\d\-\d}');

    private $tags_tl_static = array('{tl}');

    public function table()
    {
        $site_type_info = array('泛目录','动态寄生虫','Global','URL重写','404 Page');
        $view_site_types = array('label-success','label-info','label-default','label-inverse','label-warning');
        $url_rules = array("{string}", "{number}", "{date}");
        $action = I('get.action');
        // 添加
        if ($action === 'add') {
            if (IS_AJAX && IS_POST) {
                $_data = I('post.');
                $_respect = [];
                // 生成SITEID
                // $_respect['siteid'] = createRandomStr('8', false);
                // 基础信息
                $_respect['project_name'] = strip_tags($_data['project_name']);
                // $_respect['project_url'] = $_data['project_url'];
                $_respect['site_type'] = intval($_data['project_type']);
                $_respect['rankname'] = strip_tags($_data['rankname']);
                $_respect['project_status'] = 0;
                $_respect['keyword'] = trim($_data['project_keyword']);
                $_respect['project_charset'] = intval($_data['project_charset']);

                // SEO设置
                $_respect['seo_option'] = serialize(array(
                    'title_style' => $_data['title_style'],
                    'keyword_style'=>$_data['keyword_style'],
                    'description_style' => $_data['description_style'],
                    'project_template'=>$_data['project_template'],
                    'encode_style'=>is_array($_data['encode_style']) ? implode(',',$_data['encode_style']) : $_data['encode_style'],
                    'page_compress'=>empty($_data['page_compress']) ? 'off' : $_data['page_compress'],
                    'disable_snapshots'=>empty($_data['disable_snapshots']) ? 'off' : $_data['disable_snapshots'],
                    'title_source' => $_data['title_source'],
                    'pic_source' => $_data['pic_source'],
                    'page_language' => $_data['page_language'],
                    'tdk_encode' => $_data['tdk_encode'],
                    'tdk_encode_style' => is_array($_data['tdk_encode_style']) ? implode(',', $_data['tdk_encode_style']) : $_data['tdk_encode_style'],
                ));

                // 缓存设置
                $_respect['cache_option'] = serialize(array(
                    'cache_on'=>empty($_data['cache_option']) ? 'off' : $_data['cache_option'],
                    'cache_type'=>(int)$_data['cache_type'],
                    'cache_expires'=>(int)$_data['cache_expires']
                ));

                if($_data['ban_location_option'] === 'on'){
                    if(empty($_data['ban_location']) && $_data['ban_location'] === '') {
                        return $this->ajaxReturn(['code' => 404, 'msg' => '屏蔽地区不能为空~']);
                    }

                    if(empty($_data['error_page']) && $_data['error_page'] === ''){
                            return $this->ajaxReturn(['code' => 404, 'msg' => '错误地址不能为空~']);
                    }
                }

                if(empty($_data['ad_js']) && $_data['ad_js'] === ''){
                    return $this->ajaxReturn(['code' => 404, 'msg' => '跳转地址为空~']);
                }

                if (filter_var($_data['ad_js'], FILTER_VALIDATE_URL) === false) {
                    return $this->ajaxReturn(['code' => 404, 'msg' => '跳转地址地址格式不正确~']);
                }

                // 广告设置
                $_respect['advert_option'] = serialize(array(
                    'ban_location_option' => empty($_data['ban_location_option']) ? 'off' : $_data['ban_location_option'],
                    'ban_location'=>$_data['ban_location'],
                    'error_page' =>$_data['error_page'],
                    'ad_js'=>strip_tags($_data['ad_js'])
                ));

                // 如果选择自定义外链规则
                if($_data['elink_class'] === '1'){
                    if(empty($_data['flink_rules']) && $_data['flink_rules'] === ''){
                        return $this->ajaxReturn(['code'=>404,'msg'=>'外链规则不能为空！']);
                    }
                    $flink_rules = explode("\n", $_data['elink_rules']);
                    foreach($flink_rules as $item => $value){
                        $value = str_replace("&amp;","&",$value);
                        $line = $item + 1;
                        // 循环每一行 检查每一行是否包含 $url_rules 标签
                        $flag = 0;
                        for($i=0;$i<count($this->tags_static);$i++){
                            // 计算$url_rules 数组长度 循环检查是否包含特定字符串
                            if(strstr($value,$this->tags_static[$i]) || preg_match('/\{(数字\d{1,2}|数字(\d)\-(\d{1,5})|字母\d{1,2}|字母(\d)\-(\d{1,5}))\}/',$value)){
                                $flag++;
                            }
                        }

                        if($flag === 0){
                            return $this->ajaxReturn(['code'=>404,'msg'=>'当前自定义外链规则第【' .  $line . '】未通过程序检验，检查后重试~']);
                        }
                    }
                }

                // 外链设置
                $_respect['elink_option'] = serialize(array(
                    'elink_class'=>(int)$_data['elink_class'],
                    'elink_rules'=>!empty($data['elink_rules']) ? base64_encode(serialize($_data['elink_rules'])) : ''
                ));

                // 如果开启项目监控，必须选择至少一项监控内容
                if($_data['monitor_status'] === 'on'){
                        $monitor_content = array('jump_exception','spider_exception','website_exception');
                        $monitor_count = 0;
                        for($i=0;$i<3;$i++){
                            if($_data[$monitor_content[$i]] === 'on'){
                                $monitor_count++;
                            }
                        }

                        if($monitor_count < 1){
                            return $this->ajaxReturn(['code'=>404,'msg'=>'开启项目监控必须选择至少一个监控内容！']);
                        }
                }

                $_respect['monitor_status'] = $_data['monitor_status'] === 'on' ? 0 : 1;

                $_respect['other_option'] = serialize(array(
                    'jump_exception'=> empty($_data['jump_exception']) ? 'off' : $_data['jump_exception'],
                    'spider_exception'=> empty($_data['spider_exception']) ? 'off' : $_data['spider_exception'],
                    'website_exception'=> empty($_data['website_exception']) ? 'off' : $_data['website_exception']
                ));


                if(!empty($_data['mult_option']) && $_data['mult_option'] === '1'){
                    if($_data['shenma_push'] === 'on' || $_data['baidu_push'] == 'on'){
                        return $this->ajaxReturn(['code'=>404,'msg'=>'在批量模式下创建的项目不能开启“推送功能“，你可以在添加完成后编辑添加！']);
                    }
                }
                // 开启push后 是否填写push token
                if($_data['shenma_push'] === 'on'){
                    if($_data['shenma_push_token'] === ''){
                        return $this->ajaxReturn(['code'=>404,'msg'=>'神马推送token未填写，请检查']);
                    }
                }

                // 开启push后 是否填写push token
                if($_data['baidu_push'] === 'on'){
                    if($_data['baidu_push_token'] === ''){
                        return $this->ajaxReturn(['code'=>404,'msg'=>'百度推送token未填写，请检查']);
                    }
                }

                $_respect['push_option'] = serialize(array(
                    'shenma_push'=>empty($_data['shenma_push']) ? 'off' : $_data['shenma_push'],
                    'shenma_push_token'=>$_data['shenma_push_token'],
                    'baidu_push'=>empty($_data['baidu_push']) ? 'off' : $_data['baidu_push'],
                    'baidu_push_token'=>$_data['baidu_push_token'],
                ));


                $urls_line = explode("\n", $_data['ilink_rules']);
                foreach($urls_line as $item => $value){
                    $line = $item + 1;
                    // 循环每一行 检查每一行是否包含 $url_rules 标签
                    $flag = 0;
                    for($i=0;$i<count($this->tags_static);$i++){
                        // 计算$url_rules 数组长度 循环检查是否包含特定字符串
                        if(strstr($value,$this->tags_static[$i]) || preg_match('/\{(数字\d{1,2}|数字(\d)\-(\d{1,5})|字母\d{1,2}|字母(\d)\-(\d{1,5}))\}/',$value)){
                            $flag++;
                        }
                    }
                    if($flag === 0){
                        return $this->ajaxReturn(['code'=>404,'msg'=>'当前URL规则第【' .  $line . '】未通过程序检验，检查后重试~']);
                    }
                }

                $_respect['ilink_rules'] = base64_encode(serialize($_data['ilink_rules']));
                $ProjectModel = new \Common\Model\ProjectModel();
                C('TOKEN_ON',false);

                if(isset($_data['mult_option']) && $_data['mult_option'] == '0'){
                    $_respect['siteid'] = createRandomStr('8', false);
                    $_respect['project_url'] = $_data['singe_project_url'];
                    $_respect['add_time'] = time();
                    $_respect['update_time'] = time();
                    $create_singe = $ProjectModel->site_add($_respect);
                    if($create_singe !== true){
                        return $this->ajaxReturn(['code'=>404,'msg'=>$create_singe]);
                    }else{
                        if($_respect['site_type'] == '1'){
                            Api('sync/refresh',['domain'=>parse_url($_respect['project_url'])['host'],'projectType'=>'1']);
                        }elseif($_respect['site_type'] == '2'){
                            Api('sync/refresh',['domain'=>parse_url($_respect['project_url'])['host'],'projectType'=>2]);
                        }
                        return $this->ajaxReturn(['code'=>200,'msg'=>'添加成功!']);
                    }
                }

                if(isset($_data['mult_option']) && $_data['mult_option'] == '1'){
                    $_mult_project = explode("\n",$_data['mult_project_url']);
                    foreach($_mult_project as $key=>$v){
                        if (!filter_var($v, FILTER_VALIDATE_URL)) {
                            return $this->ajaxReturn(['code'=>404,'msg'=>$v . '不是一个url！']);
                        }
                        $_respect['project_url'] = $v;
                        $_respect['project_name'] = parse_url($v)['host'];
                        $_respect['siteid'] = createRandomStr('8', false);
                        $_respect['add_time'] = time();
                        $_respect['update_time'] = time();
                        $create_mult = $ProjectModel->site_add($_respect);
                        if($create_mult == false) {
                            return $this->ajaxReturn(['code' => 404, 'msg' => $create_mult]);
                        }
                        if($_respect['site_type'] == '1'){
                            Api('sync/refresh',['domain'=>parse_url($_respect['project_url'])['host'],'projectType'=>1]);
                        }

                        if($_respect['site_type'] == '2'){
                            Api('sync/refresh',['domain'=>parse_url($_respect['project_url'])['host'],'projectType'=>2]);
                        }

                        if($_respect['site_type'] == '0'){
                            Api('sync/refresh',['domain'=>parse_url($_respect['project_url'])['host'],'projectType'=>0]);

                        }
                    }
                    return $this->ajaxReturn(['code' => 200, 'msg' => '添加成功，共添加' . count($_mult_project)  . '项目~']);
                }
            }
        }

        // 查看
        if($action === 'view'){
                $sid = I('get.sid');
                $ProjectModule = new \Common\Model\ProjectModel();
                $site_config = $ProjectModule->query_sid($sid);
                if($site_config){
                    if(IS_AJAX && IS_POST) {
                        $data = [];
                        $data['seo_option'] = unserialize($site_config['seo_option']);
                        $data['cache_option']= unserialize($site_config['cache_option']);
                        $data['seo_option']['title_style'] = explode(",", $data['seo_option']['title_style']);
                        $data['seo_option']['keyword_style'] = explode(",", $data['seo_option']['keyword_style']);
                        $data['seo_option']['description_style'] = explode(",", $data['seo_option']['description_style']);
                        $data['seo_option']['project_template'] = explode(",", $data['seo_option']['project_template']);

                        $data['keyword'] = explode(",",$site_config['keyword']);
                        return $this->ajaxReturn(array('data'=>$data,'code'=>200));
                    }// keyword处理

                    $site_config['cache_option'] = unserialize($site_config['cache_option']);
                    $site_config['elink_option'] = unserialize($site_config['elink_option']);
                    $site_config['other_option'] = unserialize($site_config['other_option']);
                    $site_config['push_option'] = unserialize($site_config['push_option']);
                    $site_config['seo_option'] = unserialize($site_config['seo_option']);
                    $site_config['advert_option'] = unserialize($site_config['advert_option']);
                    $site_config['elink_rules'] = !empty($site_config['elink_option']['elink_rules']) ? unserialize(base64_decode($site_config['elink_option']['elink_rules'])) : '';
                    $site_config['ilink_rules'] = unserialize(base64_decode($site_config['ilink_rules']));
                    $this->assign('type_info',$site_type_info);
                    $this->assign('data',$site_config);
                    $this->display('edit_project');
                    exit;
                    //return $this->ajaxReturn(array('data'=>$site_config,'code'=>200));
                }
        }

        // 删除站点
        if($action === 'delete'){
            if(IS_AJAX){
                $sid = I('post.sid');
                $ProjectModels = new \Common\Model\ProjectModel();
                $DeleteMethod = $ProjectModels->delete_sites($sid);
                if($DeleteMethod){
                    return $this->ajaxReturn(['code'=>200,'msg'=>'删除成功!']);
                }else{
                    return $this->ajaxReturn(['code'=>404,'msg'=>'删除失败!']);
                }
            }
        }

        // 编辑站点
        if($action === 'edit') {
            if(IS_AJAX && IS_POST){
                $update = I('post.');
                $sid = I('get.sid','intval');
                // var_dump($update);

                $_respect['rankname'] = trim(strip_tags($update['rankname']));
                $_respect['project_status'] = $update['project_status'] === 'on' ? 0 : 1;
                $_respect['keyword'] = trim(strip_tags($update['project_keyword']));
                $_respect['project_charset'] = intval($update['project_charset']);



                $_respect['seo_option'] = serialize(array(
                    'title_style' => $update['title_style'],
                    'keyword_style'=>$update['keyword_style'],
                    'description_style' => $update['description_style'],
                    'project_template'=>$update['project_template'],
                    'encode_style'=>is_array($update['encode_style']) ? implode(',',$update['encode_style']) : $update['encode_style'],
                    'page_compress'=>empty($update['page_compress']) ? 'off' : $update['page_compress'],
                    'disable_snapshots'=>empty($update['disable_snapshots']) ? 'off' : $update['disable_snapshots'],
                    'title_source' => $update['title_source'],
                    'article_source' => $update['article_source'],
                    'pic_source' => $update['pic_source'],
                    'page_language' => $update['page_language'],
                    'tdk_encode' => $update['tdk_encode'],
                    'tdk_encode_style' => is_array($update['tdk_encode_style']) ? implode(',', $update['tdk_encode_style']) : $update['tdk_encode_style'],
                ));


                $_respect['cache_option'] = serialize(array(
                    'cache_on'=>empty($update['cache_on']) ? 'off' : $update['cache_on'],
                    'cache_type'=>(int)$update['cache_type'],
                    'cache_expires'=>(int)$update['cache_expires']
                ));

                if('on' === $_data['ban_location_option']){
                    if(empty($update['ban_location']) && $update['ban_location'] === '') {
                        return $this->ajaxReturn(['code' => 404, 'msg' => '屏蔽地区不能为空~']);
                    }

                    if(empty($update['error_page']) && $update['error_page'] === ''){
                        return $this->ajaxReturn(['code' => 404, 'msg' => '错误地址不能为空~']);
                    }
                }

                if(empty($update['ad_js']) && $update['ad_js'] === ''){
                    return $this->ajaxReturn(['code' => 404, 'msg' => '跳转地址为空~']);
                }

                if (filter_var($update['ad_js'], FILTER_VALIDATE_URL) === false) {
                    return $this->ajaxReturn(['code' => 404, 'msg' => '跳转地址地址格式不正确~']);
                }

                // 广告设置
                $_respect['advert_option'] = serialize(array(
                    'ban_location_option' => empty($update['ban_location_option']) ? 'off' : $update['ban_location_option'],
                    'ban_location'=>$update['ban_location'],
                    'error_page' =>$update['error_page'],
                    'ad_js'=>strip_tags($update['ad_js'])
                ));

                // 如果选择自定义外链规则
                if($update['elink_class'] === '1'){
                    if(empty($update['flink_rules']) && $update['flink_rules'] === ''){
                        return $this->ajaxReturn(['code'=>404,'msg'=>'外链规则不能为空！']);
                    }
                    $flink_rules = explode("\n", $update['elink_rules']);
                        foreach($flink_rules as $item => $value){
                            $value = str_replace("&amp;","&",$value);
                            $line = $item + 1;
                            // 循环每一行 检查每一行是否包含 $url_rules 标签
                            $flag = 0;
                            for($i=0;$i<count($this->tags_static);$i++){
                                // 计算$url_rules 数组长度 循环检查是否包含特定字符串
                                if(strstr($value,$this->tags_static[$i]) || preg_match('/\{(数字\d{1,2}|数字(\d)\-(\d{1,5})|字母\d{1,2}|字母(\d)\-(\d{1,5}))\}/',$value)){
                                    $flag++;
                                }
                            }

                            if($flag === 0){
                                return $this->ajaxReturn(['code'=>404,'msg'=>'当前自定义外链规则第【' .  $line . '】未包含链接标签，检查后重试~']);
                            }
                        }
                    }


                // 外链设置
                $_respect['elink_option'] = serialize(array(
                    'elink_class'=>(int)$update['elink_class'],
                    'elink_rules'=>!empty($update['elink_rules']) ? base64_encode(serialize($update['elink_rules'])) : ''
                ));

                // 如果开启项目监控，必须选择至少一项监控内容
                if($update['monitor_status'] === 'on'){
                    $monitor_content = array('jump_exception','spider_exception','website_exception');
                    $monitor_count = 0;
                    for($i=0;$i<3;$i++){
                        if($update[$monitor_content[$i]] === 'on'){
                            $monitor_count++;
                        }
                    }

                    if($monitor_count < 1){
                        return $this->ajaxReturn(['code'=>404,'msg'=>'开启项目监控必须选择至少一个监控内容！']);
                    }
                }

                $_respect['monitor_status'] = $update['monitor_status'] === 'on' ? 0 : 1;

                $_respect['other_option'] = serialize(array(
                    'jump_exception'=> empty($update['jump_exception']) ? 'off' : $update['jump_exception'],
                    'spider_exception'=> empty($update['spider_exception']) ? 'off' : $update['spider_exception'],
                    'website_exception'=> empty($update['website_exception']) ? 'off' : $update['website_exception']
                ));

                // 开启push后 是否填写push token
                if($update['shenma_push'] === 'on'){
                    if($update['shenma_push_token'] === ''){
                        return $this->ajaxReturn(['code'=>404,'msg'=>'神马推送token未填写，请检查']);
                    }
                }

                // 开启push后 是否填写push token
                if($update['baidu_push'] === 'on'){
                    if($update['baidu_push_token'] === ''){
                        return $this->ajaxReturn(['code'=>404,'msg'=>'百度推送token未填写，请检查']);
                    }
                }

                $_respect['push_option'] = serialize(array(
                    'shenma_push'=>empty($update['shenma_push']) ? 'off' : $update['shenma_push'],
                    'shenma_push_token'=>trim($update['shenma_push_token']),
                    'baidu_push'=>empty($update['baidu_push']) ? 'off' : $update['baidu_push'],
                    'baidu_push_token'=>trim($update['baidu_push_token']),
                ));

                $urls_line = explode("\n", $update['ilink_rules']);
                foreach($urls_line as $item => $value){
                    $value = str_replace("&amp;","&",$value);
                    $line = $item + 1;
                    // 循环每一行 检查每一行是否包含 $url_rules 标签
                    $flag = 0;
                    for($i=0;$i<count($this->tags_static);$i++){
                        // 计算$url_rules 数组长度 循环检查是否包含特定字符串
                        if(strstr($value,$this->tags_static[$i]) || preg_match('/\{(数字\d{1,2}|数字(\d)\-(\d{1,5})|字母\d{1,2}|字母(\d)\-(\d{1,5}))\}/',$value)){
                            $flag++;
                        }
                    }
                    if($flag === 0){
                        return $this->ajaxReturn(['code'=>404,'msg'=>'当前URL规则第【' .  $line . '】未通过程序检验，检查后重试~']);
                    }
                }

                $_respect['ilink_rules'] = base64_encode(serialize($update['ilink_rules']));

                $_respect['update_time'] = time();
                $ProjectModel  = new \Common\Model\ProjectModel();
                $updated = $ProjectModel->update_sites($sid,$_respect);

                if($updated !== false){
                    // 这里向内容端发生一条更新指令 让他保持最新状态
                    $siteId = $ProjectModel->query_sid_basteId($sid);
                    Api('sync/update',array('siteId'=>$siteId['siteid']));
                    return $this->ajaxReturn(['code'=>200,'msg'=>'修改成功!']);
                }else{
                    return $this->ajaxReturn(['code'=>200,'data'=>'修改失败!']);
                }
            }
        }

        if($action === 'cache_cat'){
            if(IS_POST && IS_AJAX){
                $siteId = I('post.siteId');
                if(strlen($siteId) === 8){
                    IS_AJAX ? $this->ajaxReturn(json_decode(Api('cache/total',['siteId'=>$siteId]),true)) : $this->redirect(U('Platform/Project/table'));
                }
            }
        }
        $ProjectModel  = new \Common\Model\ProjectModel();
        $DATA = $ProjectModel->get_list();
        $this->assign('data',$DATA['data']);
        $this->assign('view_site_types',$view_site_types);
        $this->assign('type_info',$site_type_info);
        $this->assign('page',$DATA['page']);
        $this->display();
    }


    public function show_data(){
        $d = I('get.domain');
        $ProjectModel  = new \Common\Model\ProjectModel();
        $data = $ProjectModel->get_siteid($d);
        if($d === ''){
            $this->error('请查询输入域名',U('platform/index/index'));
        }
        if(!is_array($data)){
            $this->error('你需要添加项目才能使用该功能!',U('platform/project/table'));
        }

        $siteConfig = $ProjectModel->QueryConfig($data['siteid']);

        $shoulu = baidu(parse_url($siteConfig['project_url'])['host']);
        $sogou = sogou(parse_url($siteConfig['project_url'])['host']);
        $this->assign('baidu_suoyin_total',$shoulu[0]);
        $this->assign('baidu_shoulu_lastday',$shoulu[1]);
        $this->assign('baidu_shoulu_lastweek',$shoulu[2]);
        $this->assign('baidu_shoulu_total',$shoulu[3]);
        $this->assign('sogou_shoulu_total',$sogou);
        $this->assign('host',parse_url($siteConfig['project_url'])['host']);
        $this->assign('creates',date("Y-m-d H:i:s",$siteConfig['add_time']));
        $this->display('show_seo_data');
    }

    public function link(){
        $action = I('get.action');
        $url_rules = array("{string}", "{number}", "{date}");
        if($action === 'add') {

            if (IS_POST) {
                $post_data = I('post.');
                $slink_line = explode("\n", $post_data['slink_rules']);
                $slink_array = array();

                // 先检查域名 $post_data['slink_domain']
                if(!chenk_urls($post_data['slink_domain'])){
                    $this->error('域名格式不正确', U('Platform/project/link'));
                    exit;
                }
                // 判断域名是否带目录符号
                $domain_len = strlen($post_data['slink_domain']);
                if(substr($post_data['slink_domain'],$domain_len - 1,$domain_len) !== '/'){
                    $this->error('Urls格式结尾必须是目录符号！', U('Platform/project/link'));
                    exit;
                }


                foreach ($slink_line as $item=>$value) {
                    $line = $item + 1;
                    $flag = 0;
                    $value = str_replace("&amp;","&",$value);
                    if(substr($value,0,1) == '/'){
                        $slink_array[] = substr($value,1);
                    }else{
                        $slink_array[] = $value;
                    }
                    for ($i = 0; $i < count($this->tags_static); $i++) {
                        // 计算$url_rules 数组长度 循环检查是否包含特定字符串
                        if (strstr($value, $this->tags_static[$i]) || preg_match('/\{(数字\d{1,2}|数字(\d)\-(\d{1,5})|字母\d{1,2}|字母(\d)\-(\d{1,5}))\}/',$value)) {
                            $flag++;
                        }
                    }
                        if($flag === 0){
                            $this->error('当前URL规则第【' .  $line . '】行未通过程序检验，检查后重试~', U('Platform/proxy/link'));
                            exit;
                    }
                }
                $slink_rules = serialize($slink_array);
                $data = array(
                    'sdomain' => $post_data['slink_domain'],
                    'srules' => $slink_rules,
                    'slevel' => $post_data['slink_level'],
                    'addtime' => time()
                );
                $slinkModel = new \Common\Model\SlinkModel();
                if ($slinkModel->add_list($data)) {
                    Api('sync/e_link',array('action'=>'add_refresh'));
                    $this->success('添加成功!!', U('Platform/project/link'));
                    exit;
                } else {
                    $this->error('添加失败!!', U('Platform/project/link'));
                    exit;
                }
            }
        }

        if($action === 'view') {
            if (IS_POST) {
                $sid = I('post.sid');
                $SlinkModuels = new \Common\Model\SlinkModel();
                $edit_data = $SlinkModuels->view_slink($sid);
                $edit_data['srules'] = implode("\n",unserialize($edit_data['srules']));
                if ($edit_data) {
                    $this->ajaxReturn(array('code' => 200, 'data' => $edit_data));
                } else {
                    $this->ajaxReturn(array('code' => 404, 'data' => null));
                }
            }
        }

        if($action === 'edit'){
                if(IS_POST){
                    $sid = I('post.sid');
                    $data = I('post.rules');
                    $SlinkModuels = new \Common\Model\SlinkModel();
                    $slink_line = explode("\n", $data);
                    $slink_array = array();
                    foreach ($slink_line as $item=>$value) {
                        $flag = 0;
                        $line = $item + 1;
                        $value = str_replace("&amp;","&",$value);
                        if(substr($value,0,1) == '/'){
                            $slink_array[] = substr($value,1);
                        }else{
                            $slink_array[] = $value;
                        }
                        for ($i = 0; $i < count($this->tags_static); $i++) {
                            // 计算$url_rules 数组长度 循环检查是否包含特定字符串
                            if (strstr($value, $this->tags_static[$i]) || preg_match('/\{(数字\d{1,2}|数字(\d)\-(\d{1,5})|字母\d{1,2}|字母(\d)\-(\d{1,5}))\}/',$value)) {
                                $flag++;
                            }
                        }
                        if($flag === 0){
                            return $this->ajaxReturn(['code'=>302,'msg'=>'当前URL规则第【' .  $line . '】未通过程序检验，检查后重试~']);
                        }

                    }
                    $slink_rules = serialize($slink_array);

                    $edit_data = $SlinkModuels->edit_slink($sid,$slink_rules);
                   if($edit_data){
                       Api('sync/e_link',array('action'=>'edit_refresh'));
                        $this->ajaxReturn(array('code'=>200,'msg'=>'修改成功!!'));
                    }else{
                        $this->ajaxReturn(array('code'=>404,'data'=>'当前规则已失效，删除后重新添加！'));
                    }
                }

        }


        if($action === 'delete'){
            if(IS_AJAX){
                $sid = (int)I('post.sid');
                $slinkModels = new \Common\Model\SlinkModel();
                $option = $slinkModels->delete_slink($sid);
                if($option){
                    Api('sync/e_link',array('action'=>'delete_refresh'));
                    $this->ajaxReturn(array('code'=>200,'msg'=>'成功删除ID为： . ' . $sid . '规则~'));
                }else{
                    $this->ajaxReturn(array('code'=>404,'msg'=>'删除失败，可能是当前！'));
                }
            }
        }

        $slinkModels = new \Common\Model\SlinkModel();
        $data = $slinkModels->query_list();
        $this->assign('data',$data['data']);
        $this->assign('page',$data['page']);
        $this->display();
    }

    public function modules(){

    }

    public function templates_list(){
        $action = I('get.action');
        if($action === 'clearCache'){
            S('template_cache',null);
            $this->ajaxReturn(['code'=>200,'msg'=>'清除模板缓存成功!']);
        }

        $list = S('template_cache');
        if(!is_array($list) || !isset($list['data']) || isset($list['code'])) {
            if ($list) {
                S('template_cache', null);
            }
            $response = Api('tpl/list');
            $list = is_string($response) ? json_decode($response, true) : [];
            if (is_array($list) && isset($list['data']) && !isset($list['code'])) {
                S('template_cache', $list);
            }
        }

        $data = (is_array($list) && isset($list['data']) && is_array($list['data'])) ? $list['data'] : [];
        $this->assign('data', $data);
        $this->display();
    }

    public function templates_add(){
        C('TOKEN_ON',false);
        $action = I('get.action');
        if($action === 'get-remote'){
            // 不允许抓取内网IP和本机IP
            $spider_user_agent = array(
                0 => '360Spider',
                1 => 'HaoSouSpider',
                2 => 'Mozilla/5.0 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)',
                3 => 'Mozilla/5.0 (Linux;u;Android 4.2.2;zh-cn;) AppleWebKit/534.46 (KHTML,like Gecko) Version/5.1 Mobile Safari/10600.6.3 (compatible; Baiduspider/2.0; +http://www.baidu.com/search/spider.html)',
                4 => 'Sogou web spider/4.0("http://www.sogou.com/docs/help/webmasters.htm#07")',
                5 => 'Sogou inst spider/4.0("http://www.sogou.com/docs/help/webmasters.htm#07")',
                6 => 'YisouSpider',
            );

            $RandUa = array_rand($spider_user_agent);

            // 开始处理
            if(IS_POST && IS_AJAX){
                $uri = urldecode(I('post.url'));
                if($this->_chenk_url($uri)) {
                    $this->ajaxReturn(['code'=>500,'msg'=>'不允许抓取内网地址，或域名未解析']);
                }

                $html = $this->_get_url($uri,$spider_user_agent[$RandUa]);

                if($html === ''){
                    $this->ajaxReturn(['code'=>500,'msg'=>'抓取任务超时']);
                }else{
                    $this->ajaxReturn(['code'=>200,'html'=>$html]);
                }
            }
        }

        // 保存模板
        if($action === 'save' && IS_POST && IS_AJAX){
            $tpl_name = I('post.name');
            $tpl_content = I('post.content');
            $tpl_class = I('post.type');
            if(!preg_match_all("/[a-zA-Z-0-9.]*\.html/i",$tpl_name,$ff)){
                $this->ajaxReturn(['code'=>404,'msg'=>'模板名称错误！']);
            }

            if(empty($tpl_content)){
                $this->ajaxReturn(['code'=>404,'msg'=>'模板内容不能为空！']);
            }

            // 先判断模板是否存在
            $save = array(
                'filename'=>$tpl_name,
                'source'=>$tpl_content
            );

            $post = base64_encode_url(gzcompress(json_encode($save)));
            $html = Api('tpl/put',array('format'=>$post));
            $to_view = json_decode($html,true);

            if($to_view['code'] !== 200){
                $this->ajaxReturn(['code'=>404,'msg'=>$to_view['message']]);
            }else if($to_view['code'] === 200){
                // 清除模板列表缓存
                S('template_cache',null);
                $this->ajaxReturn(['code'=>200,'msg'=>'模板添加成功！']);
            }
        }
        $this->display();
    }

    public function notification(){
        $act = I('get.act');
        $Notice = new \Common\Model\NotificationModel();
        $data = $Notice->selectSetting()[0];
        if(IS_AJAX && IS_POST){
            if($act == 'test'){
                $type = I('post.type');
                vendor('BotNotify.Message');
                $message = new \Message();
                $data['_text'] = '【SWP通知】如果你可以看到此消息，说明你的配置是正确的，发送时间：' . date('Y-m-d H:i:s');
                $result  = $message->SendTextMessage($type,$data);
                return $this->ajaxReturn($result);
            }
        }
        $this->assign('data',$data);
        $this->display();
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
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
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

    private function isInnerIP($ip_arg)
    {
        $ip = ip2long($ip_arg);
        return ip2long('127.0.0.0') >> 24 === $ip >> 24 or
                ip2long('10.0.0.0') >> 24 === $ip >> 24 or
                ip2long('172.16.0.0') >> 20 === $ip >> 20 or
                ip2long('192.168.0.0') >> 16 === $ip >> 16;
    }

    private function _chenk_url($url){
        $url = trim($url);
        $remove_http = str_replace("http://","",$url);
        $remove_http = str_replace("https://","",$remove_http);

        $parse_url = parse_url($url);
        if(empty($parse_url['host'])){
            return true;
        }
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


    public function create(){
        $this->display();
    }

    public function templates_view(){
        C('TOKEN_ON',false); // 关闭token

        $name = I('get.name');
        $type = I('get.type');
        $action = I('get.action');

        // 修改
        if($action === 'edit' && IS_AJAX && IS_POST){
            $name = I('post.name');
            $type = I('post.type');
            $content = I('post.content');
            $edit = array(
                'filename'=>$name,
                'type'=>$type,
                'source'=>$content
            );


            $post = base64_encode_url(gzcompress(json_encode($edit)));
            $html = Api('tpl/edit',array('edit'=>$post));
            $to_view = json_decode($html,true);

            if($to_view['code'] !== 200){
                $this->ajaxReturn(['code'=>404,'msg'=>$to_view['message']]);
            }else if($to_view['code'] === 200){
                // 清除模板列表缓存
                S('template_cache',null);
                $this->ajaxReturn(['code'=>200,'msg'=>'模板修改成功！']);
            }
        }

        // 查看
        $html = Api('tpl/view',array('name'=>$name,'type'=>$type));
        $to_view = json_decode($html,true);
        if(!empty($to_view['code']) && !empty($to_view['message'])){
            $this->ajaxReturn($to_view);
        }



        $this->assign('type',$to_view['type']);
        $this->assign('filename',$to_view['filename']);
        $this->assign('content',$to_view['content']);


        $this->display();
    }

    public function keyword(){
        $action = I('get.action');
        $list = platform_keyword_list();
        if ($action === 'list' && IS_AJAX) {
            $data = [];
            foreach ($list as $key => $value) {
                $data[$key]['name'] = $value['table'];
                $data[$key]['value'] = $value['table'];
            }
            $this->ajaxReturn(['data' => $data, 'code' => 200]);
        }

        if(empty($list)){
            $this->assign('datas',[]);
            $this->display();
            exit;
        }

        // 查看队列ID
        if($action == 'queue' && IS_AJAX && IS_POST){
            $queue_id = I('post.queue_id');
            $html = Api('keyword/queue',array('queue_id'=>$queue_id));
            $queue = json_decode($html,true);
            if($queue['message'] === 'ok'){
                // 清除缓存
                S('keyword_list',null);
                platform_keyword_list(true);
            }
            $this->ajaxReturn($queue);
        }
        // 獲取數據表結果 倒敘100行
        if($action == 'query' && IS_AJAX && IS_POST){
            $table_name = I('post.table_name');
            $html = Api('keyword/query',array('table_name'=>$table_name));
            $view = json_decode($html,true);
            //dump($view);

             $this->ajaxReturn($view);
        }

        if($action == 'upload' && IS_AJAX && IS_POST){
            // 先判断词库标识是否合法 \w{2,13}
            $kw_name = strtolower(I('post.kw_name'));
            if(!preg_match('/^[a-zA-Z0-9]+$/i',$kw_name,$kw_preg)){
                $this->ajaxReturn(['code'=>404,'msg'=>'词库标识命名错误，仅支持长度为2-13的字母加数字组合']);
            }

            $keyword_name = $kw_preg[0]; // 词库名称从这里拿

            if(strlen($keyword_name) < 2 || strlen($keyword_name) > 13){
                $this->ajaxReturn(['code'=>404,'msg'=>'词库标识命名错误，仅支持长度为2-13的字母加数字组合']);
            }
            $identification = 'seo_' . $keyword_name;
            $tb = array();

            foreach ($list as $value){
                $tb[] = $value['table'];
            }

            if(in_array($identification,$tb)){
                $this->ajaxReturn(['code'=>404,'msg'=>'词库标识名重复！']);
            }

            // 上传文件
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize = 10240000 ;// 设置附件上传大小
            $upload->exts =  array('txt');
            $upload->rootPath  =  RUNTIME_PATH . 'uploads/keyword/';
            $upload->subName = '';
            $upload->saveName = array('uuid');
            $info = $upload->uploadOne($_FILES['kw_files']);
            if(!$info) {
                $this->ajaxReturn(['code'=>404,'msg'=>$upload->getError()]);
            }else{
                // 获取文件总行数
                $txt = RUNTIME_PATH . 'uploads/keyword/' . trim($info['savepath'].$info['savename']);
                if(get_line($txt) < 10){
                    $this->ajaxReturn(['code'=>404,'msg'=>'文本总行数小于10，请手动入库！']);
                }
                $array = array(
                    'name' => $keyword_name,
                    'source' => get_self() . '/runtime/uploads/keyword/' . $info['savepath'].$info['savename']
                );
                //$string = zlib_encode();
                //$string = zlib_encode(base64_encode_url(json_encode($array)));
                $string = base64_encode_url(zlib_encode(serialize($array),ZLIB_ENCODING_DEFLATE));
                $html = Api('keyword/put',['code'=>$string]);
                $ret = json_decode($html,true);

                if($ret['code'] !== 200){

                    $this->ajaxReturn(['code'=>404,'msg'=>$ret['message']]);
                }else{
                    $this->ajaxReturn(['code'=>200,'msg'=>'提交成功，等待后台队列处理，不要关闭页面！','data'=>$ret['data']]);
                }
            }
        }


        if($action == 'ClearCache' && IS_AJAX && IS_POST){
            $Clear = json_decode(Api('keyword/clear'),true);
            if($Clear['code'] === 200){
                S('keyword_list',null); // 清理本地缓存
                $this->ajaxReturn(['code'=>200,'msg'=>$Clear['message']]);
            }else{
                $this->ajaxReturn(['code'=>404,'msg'=>'清除缓存失败！']);
            }
        }

        $this->assign('datas',$list);
        $this->display();

    }

    public function url_rules(){
        $action = I('get.action');
        $hreflang_regex = '/^(?:[a-z]{2}|[a-z]{2}-[A-Z]{2})$/';

        $url_rules_Model = new \Common\Model\UrlRulesModel();
        // $url_rules = array("{string}", "{number}", "{date}");
        if(IS_POST) {
            // ajax 获取name列表
            // 新增一个 不能获取在普通模型中获取tl模型的链接
            if ($action == 'list' && IS_AJAX) {
                $ajaxReturn = $url_rules_Model->concise_list(false);
                if(!empty($ajaxReturn)){
                    $this->ajaxReturn(['data'=>$ajaxReturn,'code'=>200]);
                }else{
                    $this->ajaxReturn(['data'=>[],'code'=>500]);
                }
            }
            // ajax获取某个链接内容
            if ($action == 'get_alias' && IS_AJAX) {
                $ur_id = I('post.ur_alias');
                $content = $url_rules_Model->ur_alias($ur_id);
                if(!empty($content)){
                    $ur_content = implode("\n",unserialize($content['ur_content']));
                    $this->ajaxReturn(['code'=>200,'data'=>$ur_content,'msg'=>'请求成功！']);
                }else{
                    $this->ajaxReturn(['code'=>404,'data'=>[],'msg'=>'该ID的规则不存在！！']);
                }

            }

            if ($action == 'get' && IS_AJAX) {
                $ur_id = (int)I('post.ur_id');
                $content = $url_rules_Model->cat_rules($ur_id);
                if(!empty($content)){
                    $ur_content = implode("\n",unserialize($content['ur_content']));
                    $this->ajaxReturn(['code'=>200,'data'=>$ur_content,'msg'=>'请求成功！']);
                }else{
                    $this->ajaxReturn(['code'=>404,'data'=>[],'msg'=>'该ID的规则不存在！！']);
                }

            }
            // ajax获取某个链接内容 编辑用
            if ($action == 'view' && IS_AJAX) {
                $ur_ids = I('post.ur_id','intval');
                $content = $url_rules_Model->view_rules($ur_ids);
                if(!empty($content)){
                    $ur_content = implode("\n",unserialize($content['ur_content']));
                    $this->ajaxReturn(['code'=>200,'data'=>array(
                        'name'=>$content['ur_name'],
                        'type'=>$content['ur_type'],
                        'from'=>$content['ur_from_table'],
                        'rule'=>$ur_content,
                        'alias'=>$content['ur_alias'],
                        'ur_id'=>$content['ur_id'],
                        'ur_default'=>$content['ur_default'],
                        'ur_flink_number'=>$content['ur_flink_number'],
                        'ur_filnk_enable'=>$content['ur_filnk_enable'],
                        'ur_hreflang'=>$content['ur_hreflang'],
                    ),'msg'=>'请求成功！']);
                }else{
                    $this->ajaxReturn(['code'=>404,'data'=>[],'msg'=>'该ID的规则不存在！！']);
                }

            }

            if($action == 'add' && IS_AJAX){
                $ur_name = I('post.ur_name');
                $ur_content = I('post.ur_content');
                $ur_type = I('post.ur_type','intval');
                $ur_from_table = I('post.ur_from_table');
                $ur_flink_number = I('post.ur_flink_number');
                $ur_filnk_enable = I('post.ur_filnk_enable');
                $ur_default = I('post.ur_default');
                $ur_hreflang = I('post.ur_hreflang');
                if(!preg_match($hreflang_regex,$ur_hreflang)){
                    return $this->ajaxReturn(['code' => 302, 'msg' => 'hreflang输入错误']);

                }

                $contents_line = explode("\n", $ur_content);
                $contents_array = array();
                if($ur_type == 0) {
                    foreach ($contents_line as $item => $value) {
                        $flag = 0;
                        $line = $item + 1;
                        $value = str_replace("&amp;", "&", $value);
                        for ($i = 0; $i < count($this->tags_static); $i++) {
                            // 计算$url_rules 数组长度 循环检查是否包含特定字符串
                            if (strstr($value, $this->tags_static[$i]) || preg_match('/\{(数字\d{1,2}|数字(\d)\-(\d{1,5})|字母\d{1,2}|字母(\d)\-(\d{1,5}))\}/', $value)) {
                                $flag++;
                            }
                        }
                        if ($flag === 0) {
                            return $this->ajaxReturn(['code' => 302, 'msg' => '当前URL规则第【' . $line . '】未通过程序检验，检查后重试~']);
                        }

                        $contents_array[] = $value;

                    }
                }else{
                    foreach ($contents_line as $item => $value) {
                        $flag = 0;
                        $line = $item + 1;
                        $value = str_replace("&amp;", "&", $value);
                        for ($i = 0; $i < count($this->tags_tl_static); $i++) {
                            // 计算$url_rules 数组长度 循环检查是否包含特定字符串
                            if (strstr($value, $this->tags_tl_static[$i])) {
                                $flag++;
                            }
                        }
                        if ($flag === 0) {
                            return $this->ajaxReturn(['code' => 302, 'msg' => '当前URL规则第【' . $line . '】未通过程序检验，检查后重试~']);
                        }

                        $contents_array[] = $value;

                        }
                    }

                if($ur_filnk_enable == '1'){
                    $ur_flink_number = intval($ur_flink_number);
                    if($ur_flink_number <= 0 || $ur_flink_number > 20){
                        $this->ajaxReturn(['code' => 302, 'msg' => '外链调用数量不正确']);
                    }
                }

                if($ur_default == '1') {
                    $is_default = $url_rules_Model->where(['ur_default' => '1'])->field('ur_id')->find();

                    if(is_array($is_default)){
                        $this->ajaxReturn(['code' => 302, 'msg' => '添加失败，已存在默认规则，可直接编辑默认规则']);
                    }
                }



                $adddata['ur_content'] = serialize($contents_array);
                $adddata['ur_addtime'] = time();
                $adddata['ur_name'] = trim(strip_tags($ur_name));
                $adddata['ur_type'] = $ur_type;
                $adddata['ur_from_table'] = $ur_type === 0 ? '' : $ur_from_table;
                $adddata['ur_alias'] = getLink_alias();
                $adddata['ur_flink_number'] = $ur_flink_number;
                $adddata['ur_filnk_enable'] = $ur_filnk_enable;
                $adddata['ur_default'] = $ur_default;
                $adddata['ur_hreflang'] = $ur_hreflang;

                C('TOKEN_ON',false); // 暂时关闭表单验证
                $add = $url_rules_Model->add_rules($adddata);
                if ($add) {
                    $this->ajaxReturn(['code'=>200,'msg'=>'添加成功！']);
                } else {
                    $this->ajaxReturn(['code'=>500,'msg'=>$add]);
                }
            }

            if($action === 'edit' && IS_AJAX){
                $ur_id = I('post.ur_id','intval');
                $ur_content = I('post.ur_content');

                $ur_flink_number = I('post.ur_flink_number/d');
                $ur_filnk_enable = I('post.ur_filnk_enable/d');
                $ur_type = I('post.ur_type','intval');
                $ur_from_table = I('post.ur_from_table');
                $ur_default = I('post.ur_default','intval');
                $content_line = explode("\n",$ur_content);
                $ur_hreflang = I('post.ur_hreflang');
                if(!preg_match_all($hreflang_regex,$ur_hreflang,$regx)){
                    return $this->ajaxReturn(['code' => 302, 'msg' => 'hreflang输入错误']);

                }
                $ur_array = [];
                if($ur_type == 0){
                    foreach ($content_line as $item => $value) {
                        $flag = 0;
                        $line = $item + 1;
                        $value = str_replace("&amp;", "&", $value);
                        for ($i = 0; $i < count($this->tags_static); $i++) {
                            // 计算$url_rules 数组长度 循环检查是否包含特定字符串
                            if (strstr($value, $this->tags_static[$i]) || preg_match('/\{(数字\d{1,2}|数字(\d)\-(\d{1,5})|字母\d{1,2}|字母(\d)\-(\d{1,5}))\}/', $value)) {
                                $flag++;
                            }
                        }
                        if ($flag === 0) {
                            return $this->ajaxReturn(['code' => 302, 'msg' => '当前URL规则第【' . $line . '】未通过程序检验，检查后重试~', 'data' => $ur_content]);
                        }

                        $ur_array[] = $value;
                    }
                }else{
                    foreach ($content_line as $item => $value) {
                        $flag = 0;
                        $line = $item + 1;
                        $value = str_replace("&amp;", "&", $value);
                        for ($i = 0; $i < count($this->tags_tl_static); $i++) {
                            // 计算$url_rules 数组长度 循环检查是否包含特定字符串
                            if (strstr($value, $this->tags_tl_static[$i])) {
                                $flag++;
                            }
                        }
                        if ($flag === 0) {
                            return $this->ajaxReturn(['code' => 302, 'msg' => '当前URL规则第【' . $line . '】未通过程序检验，检查后重试~']);
                        }

                     $ur_array[] = $value;
                    }
                }

                $ur_c = serialize($ur_array);


                if($ur_filnk_enable == '1'){
                    if($ur_flink_number <= 0 || $ur_flink_number > 20){
                        $this->ajaxReturn(['code' => 302, 'msg' => '调用数量不正确']);
                    }
                }

                if($ur_default == '1') {
                    $is_default = $url_rules_Model->where(['ur_default' => '1'])->field('ur_id')->find();
                    if($is_default) {
                        if ($is_default['ur_id'] !== $ur_id) {
                            $this->ajaxReturn(['code' => 302, 'msg' => '修改失败:已存在默认规则，可直接编辑默认规则']);
                        }
                    }
                }


                $updated = $url_rules_Model->edit_rules($ur_id,[
                    'ur_content'=>$ur_c,
                    'ur_addtime'=>time(),
                    'ur_default'=>$ur_default,
                    'ur_type' => $ur_type,
                    'ur_from_table' => $ur_type === 0 ? '' : $ur_from_table,
                    'ur_flink_number'=>$ur_flink_number,
                    'ur_filnk_enable'=>$ur_filnk_enable,
                    'ur_hreflang'=>$ur_hreflang,
                ]);
                C('TOKEN_ON',false); // 暂时关闭表单验证
                if ($updated) {
                    if($ur_default == '1') {
                        Api('sync/rules',['alias'=>'default']);
                    }else{
                        $ur_alias = $url_rules_Model->where(['ur_id'=>$ur_id])->field('ur_alias')->find();
                        Api('sync/rules',['alias'=>$ur_alias['ur_alias']]);
                    }
                    $this->ajaxReturn(['code'=>200,'msg'=>'修改成功！']);
                } else {
                    $this->ajaxReturn(['code'=>500,'msg'=>$updated]);
                }
            }
        }


        $data = $url_rules_Model->view_all_rules();
        $this->assign('data',$data['data']);
        $this->assign('page',$data['page']);
        $this->display();
    }

    public function templates_delete(){}

    public function templates_edit(){}

    public function port_index()
    {
        $controllModule = new ControlModel();
        $DATA = $controllModule->get_list();
        $this->assign('data', $DATA['data']);
        $this->assign('page', $DATA['page']);

        if(I('get.info') == 'getTotal'){
            $_clsid = I('post.clsid/s');
            return $this->ajaxReturn(Api('cache/hosting_total',['clsid'=>$_clsid]));
        }
        $this->display();

    }

    public function port_create()
    {
        $data = I('post.');
        $controllModule = new ControlModel();
        if (!$controllModule->create($data)) {
            $this->ajaxReturn(['code' => 404, 'data' => array('msg' => $controllModule->getError())]);
        } else {
            $mid = $controllModule->add($data);
            $this->ajaxReturn(['code' => 200, 'data' => array('msg' => '添加成功,正在跳转详细配置页...', 'redirect' => U('platform/project/port_edit?cid=' . $mid))]);
        }
    }

    public function port_edit()
    {

        $cid = I('get.cid/d', '0');
        $action = I('get.action');

        if($action == 'switch' && IS_POST && IS_AJAX){
            $switch = I('post.switch-type/d','','intval');
            $ControllModule = new ControlModel();
            if(!$ControllModule->switch_type($cid,$switch)){
                $this->ajaxReturn(['code'=>500,'message'=>'已是当前类型，无需切换']);
            }else{
                $this->ajaxReturn(['code'=>200,'message'=>'成功切换']);
            }
        }

        if (IS_POST && IS_AJAX) {
            $_request = I('post.');
            $_data = [];

            // 获取当前clsid

            $ControlModel = new ControlModel();

            $currentClsid = $ControlModel->getCurrentClsid($cid);
            if(!$currentClsid){
                $this->ajaxReturn(['code'=>404,'msg'=>'系统错误']);
            }



            $_data['keyword'] = $_request['keyword'];

            if (isset($_request['replace_home_link'], $_request['index_jet']) && $_request['replace_home_link'] === 'on' && $_request['index_jet'] === 'on') {
                $this->ajaxReturn(['code' => 404, 'msg'  => '替换首页内容 功能不可与 首页劫持 同时开启！']);
            }



            if(isset($_request['amp_switch']) && $_request['amp_switch'] === 'on'){
                if(!preg_match('/^https:\/\/[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',$_request['amp_domain'],$matches)){
                    $this->ajaxReturn(['code'=>404,'msg'=>'填写的 AMP 域名有错误，需要携带https协议头并域名后不能有目录符']);
                }

                if(!isset($_request['cache_on']) && $_request['cache_on'] !== 'on'){
                    $this->ajaxReturn(['code'=>404,'msg'=>'AMP 必须要开启缓存才可正常使用！']);
                }

                if($ControlModel->ampDomainExists($_request['amp_domain'],$currentClsid)){
                    $this->ajaxReturn(['code'=>404,'msg'=>'当前 AMP 域名已被其他接口项目使用，请选择其他域名！']);
                }
            }

            if(isset($_request['gsc_switch']) && $_request['gsc_switch'] === 'on'){
                if(!preg_match('/^google[a-z0-9]{10,32}\.html$/',$_request['gsc_filename'],$matches)){
                    $this->ajaxReturn(['code'=>404,'msg'=>'填写的google验证文件名有错误！']);
                }
            }

            $sitemapItemLimit = empty($_request['sitemapItemLimit']) ? 0 : intval($_request['sitemapItemLimit']);
            if($sitemapItemLimit <= 0 || $sitemapItemLimit >= 99999){
                $this->ajaxReturn(['code'=>404,'msg'=>'sitemap条数填写错误！']);
            }

            $trusted_domains_raw = trim($_request['trustedHttpsDomains'] ?? '');
            $trustedHttpsDomains = '';

            if ($trusted_domains_raw !== '') {
                $lines = preg_split('/\r\n|\r|\n/', $trusted_domains_raw);
                $valid_domains = [];

                foreach ($lines as $line) {
                    $domain = trim($line);
                    if ($domain === '') continue; // 忽略空行

                    if (preg_match('/^(\*\.)?([a-zA-Z0-9-]+\.)+[a-zA-Z]{2,}$/', $domain)) {
                        $valid_domains[] = $domain;
                    } else {
                        $this->ajaxReturn(['code'=>404,'msg'=>'受信域名错误，无效域名格式：' . $domain]);
                    }
                }
                $trustedHttpsDomains = $valid_domains;
            }

            $_data['seo_option'] = serialize(array(
                'project_template' => $_request['project_template'],
                'encode_style' => is_array($_request['encode_style']) ? implode(',', $_request['encode_style']) : $_request['encode_style'],
                'page_compress' => empty($_request['page_compress']) ? 'off' : $_request['page_compress'],
                'tl_url_must_verify' => empty($_request['tl_url_must_verify']) ? 'off' : $_request['tl_url_must_verify'],
                'index_jet' => empty($_request['index_jet']) ? 'off' : $_request['index_jet'],
                'replace_home_link' => empty($_request['replace_home_link']) ? 'off' : $_request['replace_home_link'],
                'trustedHttpsDomains' => $trustedHttpsDomains, // 需要验证域名格式
                'amp_switch' => empty($_request['amp_switch']) ? 'off' : $_request['amp_switch'],
                'amp_domain' => $_request['amp_domain'], // 需要验证

                'gsc_switch' => empty($_request['gsc_switch']) ? 'off' : $_request['gsc_switch'],
                'gsc_filename' => empty($_request['gsc_filename']) ? '' : $_request['gsc_filename'], // 需要验证
                'sitemapItemLimit' => $sitemapItemLimit,

                'disable_snapshots' => empty($_request['disable_snapshots']) ? 'off' : $_request['disable_snapshots'],
                'title_style' => $_request['title_style'],
                'keyword_style' => $_request['keyword_style'],
                'description_style' => $_request['description_style'],
                'spider_rule_set' => $_request['spider_rule_set'],
                'article_source' => $_request['article_source'],
                'title_source' => $_request['title_source'],
                'pic_source' => $_request['pic_source'],
                'page_language' => trim($_request['page_language']),
                'tdk_encode' => $_request['tdk_encode'],
                'tdk_encode_style' => is_array($_request['tdk_encode_style']) ? implode(',', $_request['tdk_encode_style']) : $_request['tdk_encode_style'],
            ));

            $_data['advert_option'] = serialize(array(
                'ad_js' => strip_tags($_request['ad_js'])
            ));

            $_data['cache_option'] = serialize(array(
                'cache_on' => empty($_request['cache_on']) ? 'off' : $_request['cache_on'],
                'cache_type' => empty($_request['cache_type']) ? 1 : intval($_request['cache_type']),
                'cache_expires' => empty($_request['cache_expires']) ? 0 : intval($_request['cache_expires']),
            ));


            $lines = explode("\n", $_request['ilink_rules']);
            // 普通URL的检查
            $ControllModule = new ControlModel();
            $current_type = (int)$ControllModule->field('type')->where(['mid'=>$cid])->find()['type'];
            if($current_type == 0){
                foreach ($lines as $item => $value) {
                    $line = $item + 1;
                    // 循环每一行 检查每一行是否包含 $url_rules 标签
                    $flag = 0;
                    for ($i = 0; $i < count($this->tags_static); $i++) {
                        // 计算$url_rules 数组长度 循环检查是否包含特定字符串
                        if (strstr($value, $this->tags_static[$i]) || preg_match('/\{(数字\d{1,2}|数字(\d)\-(\d{1,5})|字母\d{1,2}|字母(\d)\-(\d{1,5}))\}/', $value)) {
                            $flag++;
                        }
                    }
                    if ($flag === 0) {
                        return $this->ajaxReturn(['code' => 404, 'msg' => '当前URL规则第【' . $line . '】未通过程序检验，检查后重试~']);
                    }
                }
            }else {
                foreach ($lines as $item => $value) {
                    $line = $item + 1;
                    // 循环每一行 检查每一行是否包含 $url_rules 标签
                    $flag = 0;
                    if (strstr($value, '{tl}')) {
                        $flag++;
                    }
                    if ($flag === 0) {
                        return $this->ajaxReturn(['code' => 404, 'msg' => '当前URL规则第【' . $line . '】未通过程序检验，检查后重试~']);
                    }
                }
            }



            $_data['ilink_rules'] =  base64_encode(serialize($_request['ilink_rules']));
            $controllModule = new ControlModel();
            if(!$controllModule->token(false)->create($_data)){
                $this->ajaxReturn(['code'=>404,'msg'=>$controllModule->getError()]);
            }else{
                $controllModule->where(['mid'=>intval($cid)])->save($_data);
                // 查询绑定的域名
                $current_domain = $controllModule->field('bind_domain')->where(['mid'=>intval($cid)])->find();
                Api('sync/update',array('siteId'=>$current_domain['bind_domain']));
                $this->ajaxReturn(['code'=>200,'msg'=>'保存成功']);
            }
        }


        $controllModule = new ControlModel();
        $data = $controllModule->where(['mid' => intval($cid)])->find();
        if (!$data) {
            $this->error('未找到当前配置!', U('platform/project/port_index'));
        }
        $current_type = (int)$controllModule->field('type')->where(['mid'=>$cid])->find()['type'];
        $data['seo_option'] = unserialize($data['seo_option']);
        $data['cache_option'] = unserialize($data['cache_option']);
        $data['advert_option'] = unserialize($data['advert_option']);
        if(I('get.kwa') == '1'){
            $this->ajaxReturn([

                'keyword'=>$current_type == 1 ? $data['keyword'] : explode(',',$data['keyword']),
                'encode_style' => isset($data['seo_option']['keyword_style']) && !empty($data['seo_option']['title_style']) ? $data['seo_option']['encode_style'] : '',
                'project_template'=>explode(',',$data['seo_option']['project_template']),
                'cache_type'=>$data['seo_option']['cache_option']['cache_type'],
                'spider_rule_set' => $data['seo_option']['spider_rule_set'] ?? null,
                'title_style' => isset($data['seo_option']['keyword_style']) && !empty($data['seo_option']['title_style']) ? explode(",",$data['seo_option']['title_style']) : ['0'],
                'keyword_style'=>isset($data['seo_option']['keyword_style']) && !empty($data['seo_option']['keyword_style']) ? explode(",",$data['seo_option']['keyword_style']) : ['0'],
                'description_style' => isset($data['seo_option']['description_style']) && !empty($data['seo_option']['description_style']) ? explode(",",$data['seo_option']['description_style']) : ['0'],
                'title_source'=>isset($data['seo_option']['title_source']) && !empty($data['seo_option']['title_source']) ? $data['seo_option']['title_source'] : '',
                'article_source'=>isset($data['seo_option']['article_source']) && !empty($data['seo_option']['article_source']) ? $data['seo_option']['article_source'] : '',
                'pic_source'=> isset($data['seo_option']['pic_source']) && !empty($data['seo_option']['pic_source']) ? $data['seo_option']['pic_source'] : '',
                'tdk_encode'=> isset($data['seo_option']['tdk_encode']) && !empty($data['seo_option']['tdk_encode']) ? $data['seo_option']['tdk_encode'] : '',
                'tdk_encode_style'=> isset($data['seo_option']['tdk_encode_style']) && !empty($data['seo_option']['tdk_encode_style']) ? $data['seo_option']['tdk_encode_style'] : '',
            ]);
        }

        if(I('get.info') == 'cat'){
            IS_AJAX ? $this->ajaxReturn(json_decode(Api('cache/hosting',['clsid'=>$data['clsid']]),true)) : $this->redirect(U('Platform/Control/index'));
        }
        if(I('get.info') == 'base'){
            $return_Data = array(
                'cache_total' =>  json_decode(Api('cache/total',['siteId'=>$data['clsid']]),true),
                'storage' =>  formatFileSize(json_decode(Api('cache/storage',['siteId'=>$data['clsid']]),true)['storage']),
                'sitemap'=>  json_decode(Api('cache/sitemap',['siteId'=>$data['clsid']]),true)['total'],
                'hosting_total' => json_decode(Api('cache/hosting_total',['clsid'=>$data['clsid']]),true)['total'],
                'index_total' => json_decode(Api('cache/index_total',['clsid'=>$data['clsid']]),true)['total']
            );
            IS_AJAX ? $this->ajaxReturn($return_Data) : $this->redirect(U('Platform/Control/index'));
        }

        $this->assign('info', $data);
        $this->display();


    }
    
}