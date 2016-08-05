<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Weiquan\Widget;

use Think\Controller;

class WeiquanDetailWidget extends Controller
{

    /* 显示指定分类的同级分类或子分类列表 */
    public function detail($weiquan_id,$can_hide=0,$indexpage=false,$show_user=0)
    {      
        $weibo = D('Weiquan/Weiquan')->getWeiquanDetail($weiquan_id);
       // print_r($weibo['user']['space_url']);
        //置顶微博隐藏显示
        $this->assign('can_hide',$can_hide);
        $top_hide=0;
        if($can_hide){
            $hide_ids=cookie('Weiquan_index_top_hide_ids');
            $hide_ids=explode(',',$hide_ids);
            $top_hide=in_array($weiquan_id,$hide_ids);
        }       
        $is_like= D('Weiquan/WeiquanLike')->getLikeByWidAndUid($weiquan_id,is_login());
        //是否微商圈首页
        $this->assign('indexpage',$indexpage);
        //显示是否点赞        
        $this->assign('is_like',$is_like);
        $this->assign('top_hide',$top_hide);
        $this->assign('weibo', $weibo);
        $this->assign('show_user', $show_user);
        $this->display(T('Weiquan@Widget/detail'));
    }

    public function weiquan_html($weiquan_id)
    {
        $weibo = D('Weiquan/Weiquan')->getWeiquanDetail($weiquan_id);
        $this->assign('weibo', $weibo);
        return $this->fetch(T('Weiquan@Widget/detail'));
    }
    public function weiquanContentHtml($weiquan_id)
    {
        $weibo = D('Weiquan/Weiquan')->getWeiquanDetail($weiquan_id);
        $this->assign('show_user',true);
        $this->assign('weibo', $weibo);
        return $this->fetch(T('Weiquan@Widget/detail'));
    }
}
