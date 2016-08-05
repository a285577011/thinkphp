<?php
/**
 * Created by i.cn.
 * User: zhangby
 * Date: 2015/12/25
 */

namespace Weiquan\Controller;


use Think\Controller;

class BaseController extends  Controller{
    var $theme;
    var $rUid=0;
    public function _initialize(){
        
        $username = I('username','','op_t');
        if($username){
            $this->rUid = D('User/UcenterMember')->getUidByUsername($username);
            if (!$this->rUid) {
                E('用户不存在', 815);exit; // 跳转404
            }
            //$_GET['uid'] = $this->rUid;
        }else{ // TODO 关闭
            $uid = intval($_REQUEST['uid']) ? intval($_REQUEST['uid']) : is_login();
            if (!$uid) {
                $this->error(L('_ERROR_NEED_LOGIN_'));
            }
        }
        //$this->assign('uid', $this->rUid);
        $this->mid = is_login();
        
        $this->theme=modC('NOW_THEME','default','Theme');
        $sub_menu =
            array(
                'left' =>
                    array(
                        array('tab' => 'index', 'title' => L('_MY_') . L('_MODULE_'), 'href' =>  U('index/index')),
                        array('tab' => 'hot', 'title' => L('_HOT_').L('_MODULE_'), 'href' => U('index/index',array('type'=>'hot'))),
                        array('tab' => 'topic', 'title' =>L('_HOT_TOPIC_'), 'href' => U('topic/topic')),
                    ),
                'right'=>array(
                    array('type'=>'search', 'input_title' => L('_INPUT_KEYWORDS_'),'input_name'=>'keywords','from_method'=>'post', 'action' =>U('Weiquan/index/search')),
                )
            );
        
        $this->assign('sub_menu', $sub_menu);
        $this->assign('current', 'index');
        
        
    }
}