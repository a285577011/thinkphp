<?php

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------
namespace Core\Controller;
use Think\Controller;

/**
 * Class PublicController 公共控制器
 * 
 * @package Core\Controller
 *         
 */
class PublicController extends Controller
{
	
	/**
     * 设置关注取消(成功时字段is_follow：0未关注，1已关注，2被关注，3相互关注)
     * @author zhangby
     */
    public function setFollow() {
        $aUid = I('post.uid', 0, 'intval');
        $aSts = I('post.sts', 0, 'intval');

        if (!is_login()) {
            $out['status'] = 0;
            $out['info'] = "请先登录！";
            $this->ajaxReturn($out);
        }

        if ($aSts == 1 && D('Common/Follow')->unfollow(is_login(), $aUid)) {
            $out['is_follow'] = D('Common/Follow')->getFollowSts($aUid, is_login());           
            $out['status'] = 1;
            $out['info'] ="取消关注成功！" ;
        } elseif ($aSts == 0 && D('Common/Follow')->addFollow(is_login(), $aUid)) {
            $out['is_follow'] = D('Common/Follow')->getFollowSts($aUid, is_login());        
            $out['status'] = 1;
            $out['info'] ="关注成功！";
        } else {
            $out['status'] = 0;
            $out['info'] = "操作失败！";
        }
        $this->ajaxReturn($out);
    }

    /*
     * 关注某人
     * @param int $uid
     *
     */
    public function follow ()
    {
        $aUid = I('post.uid', 0, 'intval');
        $out = array( 'btn_text' => '' );
        session('user_auth');
        if (! is_login()) {
            $out['status'] = 0;
            $out['info'] = L("_PLEASE_") . " " . L("_LOG_IN_");
            $this->ajaxReturn($out);
        }
        if (D('Common/Follow')->addFollow(is_login(), $aUid)) {
            $fol = D('Common/Follow')->isFollow($aUid, is_login());
            $out['btn_text'] = $fol ? L("_MUTUALLY_FOLLOWED_") : L("_FOLLOWED_");
            $out['status'] = $fol ? 2 : 1;
            $out['info'] = L("_FOLLOWERS_") . " " . L('_SUCCESS_');
        } else {
            $out['status'] = 0;
            $out['info'] = L("_FOLLOWERS_") . " " . L("_FAIL_");
        }
        $this->ajaxReturn($out);
    }

    /*
     * 取消对某人的关注
     * @param int $uid
     *
     */
    public function unfollow ()
    {
        $aUid = I('post.uid', 0, 'intval');
        $out = array( 'btn_text' => '' );
        if (! is_login()) {
            $out['status'] = 0;
            $out['info'] = L("_PLEASE_") . " " . L("_LOG_IN_");
            $this->ajaxReturn($out);
        }
        
        if (D('Common/Follow')->unfollow(is_login(),$aUid)) {
            $out['status'] = 1;
            $out['info'] = L("_CANCEL_") . " " . L("_FOLLOWERS_") . " " . L("_SUCCESS_");
            $out['btn_text'] = L('_FOLLOWERS_');
        } else {
            $out['status'] = 0;
            $out['info'] = L("_CANCEL_") . " " . L("_FOLLOWERS_") . " " . L("_FAIL_");
        }
        $this->ajaxReturn($out);
    }


    /**
     * atWhoJson
     */
    public function atWhoJson ()
    {
        exit(json_encode($this->getAtWhoUsersCached()));
    }

    private function getAtWhoUsersCached ()
    {
        $cacheKey = 'weibo_at_who_users';
        $atusers = S($cacheKey);
        if (empty($atusers[get_uid()])) {
            $atusers[get_uid()] = $this->getAtWhoUsers();
            S($cacheKey, $atusers, 600);
        }
        return $atusers[get_uid()];
    }

    /**
     * getAtWhoUsers 获取@列表
     * 
     * @return array
     *
     */
    private function getAtWhoUsers ()
    {
            // 获取能AT的人，UID列表
        $uid = get_uid();
        $follows = D('Follow')->where(array( 'who_follow' => $uid, 'follow_who' => $uid, '_logic' => 'or' ))->select();
        $uids = array();
        foreach ($follows as &$e) {
            $uids[] = $e['who_follow'];
            $uids[] = $e['follow_who'];
        }
        unset($e);
        $uids = array_unique($uids);
        
        // 加入拼音检索
        $users = array();
        foreach ($uids as $uid) {
            $user = query_user( array( 'nickname', 'id', 'avatar32' ), $uid);
            $user['search_key'] = $user['nickname'] . D('PinYin')->Pinyin($user['nickname']);
            $users[] = $user;
        }
        
        // 返回at用户列表
        return $users;
    }

    public function getVideo ()
    {
        $aLink = I('post.link');
        $this->ajaxReturn( array( 'data' => D('ContentHandler')->getVideoInfo($aLink) ));
    }
    
    /**
     * 消息推送测试
     */
    public function pushMess(){
        echo D('Common/JPush')->pushMess('all', '消息标题', '消息内容', array('type'=>'newMessage', 'num' => 5));
    }
}
