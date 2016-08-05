<?php
/**
 * 所属项目 OnePlus.
 * 开发者: 想天
 * 创建日期: 3/12/14
 * 创建时间: 12:49 PM
 * 版权所有 想天工作室(www.ourstu.com)
 */

namespace Weiquan\Controller;


use Think\Controller;
use PHPImageWorkshop\Core\ImageWorkshopLayer;
use PHPImageWorkshop\ImageWorkshop;
class PublicController extends Controller
{


    public function card()
    {
    	$logic=new \Ucenter\Logic\UcenterLogic();
        $aUID = I('get.uid', 0, 'intval');
        $user = $logic->getProfile($aUID);
        $follow=D('Common/Follow')->isFollow(is_login(),$aUID);
        $this->assign('follow',$follow);
        $this->assign('uid', $aUID);
        $this->assign('user', $user);
        $not_self = get_uid() != $aUID;
        $this->assign('not_self',$not_self);
        $this->display(OS_THEME_PATH.modC('NOW_THEME', 'default', 'Theme').'/Ucenter/View/public/card.html');
    }



    /**
     * 用户修改封面
     * 
     */
    public function changeCover()
    {
    	$logic=new \Ucenter\Logic\UcenterLogic();
    	if (!is_login()) {
    		$this->error(L('_ERROR_NEED_LOGIN_').L('_EXCLAMATION_'));
    	}
    	if (IS_POST) {
    		$result=$logic->changeCover();
    		$this->ajaxReturn($result);
    	} else {
    		//获取用户封面id
    		$my_cover=$logic->getCoverInfo();
    		$this->assign('my_cover', $my_cover);
    		$this->display(OS_THEME_PATH.modC('NOW_THEME', 'default', 'Theme').'/Ucenter/View/public/_change_cover.html');
    	}
    	//R('Ucenter/Public/changeCover');
    }
    public function unfollow()
    {
    	R('Core/Public/unfollow');
    }
    public function follow()
    {
    	R('Core/Public/follow');
    }
    public function uploadPicture(){
    	R('Core/File/uploadPicture');
    }
    
}