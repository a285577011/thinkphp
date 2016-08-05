<?php
return array(
        // 模块名
        'name' => 'Forum',
        // 别名
        'alias' => '论坛',
        // 版本号
        'version' => '2.4.0',
        // 是否商业模块,1是，0，否
        'is_com' => 0,
        // 是否显示在导航栏内？ 1是，0否
        'show_nav' => 1,
        // 模块描述
        'summary' => '论坛模块，轻便强大的论坛模块',
        // 开发者
        'developer' => 'i.cn',
        // 开发者网站
        'website' => 'http://i.cn',
        // 前台入口，可用U函数
        'entry' => 'Forum/index/index',
        
        'admin_entry' => 'Admin/Forum/post',
        
        'icon' => 'comments',
        
        'can_uninstall' => 1
);