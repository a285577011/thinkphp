<?php

namespace Addons\InsertWvideo;

use Common\Controller\Addon;

class InsertWvideoAddon extends Addon
{

    public $error = '';
    public $info = array(
        'name' => 'InsertWvideo',
        'title' => '插入微商圈视频',
        'description' => '微商圈插入视频',
        'status' => 1,
        'author' => 'i.cn',
        'version' => '1.0'
    );

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    //实现的InsertImage钩子方法
    public function weiquanType($param)
    {
        $this->display('insertWvideo');
    }



    public function fetchWvideo($weibo)
    {
        $weibo_data = unserialize($weibo['data']);
        $this->assign('weibo',$weibo);
        $this->assign('weibo_data',$weibo_data);
        return $this->fetch('display');
    }

    public function parseExtra($extra = array()){
        $extra['title'] = text($extra['title']);
        if(preg_match("/.swf/i", $extra['video_url'], $check)){
            $this->error = '仅支持优酷、酷6、新浪、土豆网、搜狐、音悦台、腾讯、爱奇艺等视频网址发布';
            return false;
        }

        if (!preg_match("/(youku.com|ku6.com|sohu.com|sina.com.cn|qq.com|tudou.com|yinyuetai.com|iqiyi.com)/i",  $extra['video_url'], $hosts)) {
            $this->error = '仅支持优酷、酷6、新浪、土豆网、搜狐、音悦台、腾讯、爱奇艺等视频网址发布';
            return false;
        }
        $info = D('ContentHandler')->getVideoInfo($extra['video_url']);
        $extra['video_url'] = $info['flash_url'];
        $extra['title'] =text($info['title']);
        $extra['img_url'] =text($info['img_url']);
        return $extra;
    }


}