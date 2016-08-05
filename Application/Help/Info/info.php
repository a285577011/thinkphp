<?php
/**
 * 帮助中心
 */


return array(
    //模块名
    'name' => 'Help',
    //别名
    'alias' => '帮助中心',
    //版本号
    'version' => '1.0.0',
    //是否商业模块,1是，0，否
    'is_com' => 0,
    //是否显示在导航栏内？  1是，0否
    'show_nav' => 1,
    //模块描述
    'summary' => '帮助中心',
    //开发者
    'developer' => 'i.cn',
    //开发者网站
    'website' => 'http://i.cn',
    //前台入口，可用U函数
    'entry' => 'Help/Index/index',

    'admin_entry' => 'Admin/Help/index',

    'icon' => 'file-text',

    'can_uninstall' => 1

);