<?php

/**
 * 调试开关
 * 项目正式部署后请设置为false
 */
define('APP_DEBUG', true);

// 从URL获取SESSION编号
ini_set("session.use_cookies", 0);
ini_set("session.use_trans_sid", 1);
if ($_REQUEST['session_id']) {
    session_id($_REQUEST['session_id']);
    session_start();
}

define('DOMAIN','i.cn');
// 调用Application/Api应用
define('BIND_MODULE', 'App');
//define('APP_MODE', 'api');
define('APP_PATH', './Application/');

define('RUNTIME_PATH', './Runtime/');
require './ThinkPHP/ThinkPHP.php';