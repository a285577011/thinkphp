<?php

/**
 * 调试开关
 * 项目正式部署后请设置为false
 */
define('APP_DEBUG', true);
define('BIND_MODULE','Rpc');
define('RUNTIME_PATH', dirname(__FILE__) . '/Runtime/');
define('APP_PATH', dirname(__FILE__) . '/Application/');

require dirname(__FILE__) . '/ThinkPHP/ThinkPHP.php';
