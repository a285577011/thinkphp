<?php

/**
 * 系统配文件
 * 所有系统级别的配置
 */
return array(
    /* 数据库配置 */
    'DB_TYPE' => 'mysqli', // 数据库类型
    'DB_HOST' => '10.0.0.188', // 服务器地址
    'DB_NAME' => 'icn', // 数据库名
    'DB_USER' => 'icner', // 用户名
    'DB_PWD' => 'Eovhd^#9Edd139E', // 密码
    'DB_PORT' => '3306', // 端口
    'DB_PREFIX' => 'icn_', // 数据库表前缀
    'DB_CHARSET' => 'utf8mb4',
    'DB_FILE' => array(
        'db_type' => 'mysql',
        'db_user' => 'icner',
        'db_pwd' => 'Eovhd^#9Edd139E',
        'db_host' => '10.0.0.188',
        'db_port' => '3306',
        'db_name' => 'icn_file',
        'db_prefix' => 'icn_',
        'db_charset' => 'utf8',
    ),
    //数据库配置2
    'DB_ICN' => 'mysql://icner:Eovhd^#9Edd139E@10.0.0.188:3306/icn',
    'DATA_CACHE_TYPE' => 'File',
    /* 系统数据加密设置 */
    'DATA_AUTH_KEY' => '97YHkWmIzMP2gcUuOaTCD5pRjw6qyn3LNQEAFGhs',
);
