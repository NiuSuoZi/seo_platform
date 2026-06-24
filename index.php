<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
date_default_timezone_set('PRC');
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
define('DOC_PATH',dirname(__FILE__) . DIRECTORY_SEPARATOR);
// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',true);

define('BUILD_DIR_SECURE', false); // 不自动生成Home控制器 

// 定义应用目录
define('APP_PATH', DOC_PATH . 'application/');

// 定义缓存目录
define('RUNTIME_PATH',dirname(__FILE__) . DIRECTORY_SEPARATOR . 'runtime' . DIRECTORY_SEPARATOR);

define('OSS_URL','');

// 定义模板文件默认目录
define("TMPL_PATH","./template/");

// 引入ThinkPHP入口文件
require DOC_PATH . 'framework/start.php';


