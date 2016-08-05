<?php
/**
 * 所属项目 OnePlus.
 * 开发者: 想天
 * 创建日期: 3/12/14
 * 创建时间: 12:49 PM
 * 版权所有 想天工作室(www.ourstu.com)
 */

namespace Ucenter\Controller;


use Think\Controller;
use PHPImageWorkshop\Core\ImageWorkshopLayer;
use PHPImageWorkshop\ImageWorkshop;
use Ucenter\Logic\UcenterLogic;
class PublicController extends Controller
{
	protected $logic;
    public function _initialize(){
    	$this->logic=new UcenterLogic();
    }
    public function card()
    {
        $aUID = I('get.uid', 0, 'intval');
        $user = $this->logic->getProfile($aUID);
        $follow=D('Common/Follow')->isFollow(is_login(),$aUID);
        $not_self = get_uid() != $aUID;
        $this->assign('follow',$follow);
        $this->assign('uid', $aUID);
        $this->assign('user', $user);
        $not_self = get_uid() != $aUID;
        $this->assign('not_self',$not_self);
        $this->display();
    }

    public function setAlias()
    {
        $aUid = I('post.uid', 0, 'intval');
        $aAlias =trim(I('post.alias', '', 'text'));
        if($aAlias==''){
            $this->error(L('_ERROR_REMARK_CANNOT_EMPTY_').L('_PERIOD_'));
        }
        if (is_login()) {
            $followModel = D('Common/Follow');
            $follow['who_follow'] = get_uid();
            $follow['follow_who'] = $aUid;
            $follow = $followModel->where($follow)->find();
            if (!$follow) {
                $this->error(L('_ERROR_REMARK_CANNOT_'));
            }
            $follow['alias'] = $aAlias;
            $result=$followModel->save($follow);
            if($result===false){
                $this->error(L('_ERROR_DB_WRITE_FAIL_').L('_PERIOD_'));
            }else{
                S('nickname_' . get_uid() . '_' . $aUid, null);
                $this->success(L('_SUCCESS_SETTINGS_').L('_PERIOD_'));
            }

        } else {
            $this->error(L('_FOLLOW_AFTER_LOGIN_').L('_PERIOD_'));
        }

    }

    /**检测消息
     * 返回新聊天状态和系统的消息
     * 
     */
    public function getInformation()
    {
        $message = D('Common/Message');
        //取到所有没有提示过的信息
        $haventToastMessages = $message->getHaventToastMessage(is_login());

        $message->setAllToasted(is_login()); //消息中心推送

        $new_talks = D('TalkPush')->getAllPush(); //聊天推送
        D('TalkPush')->where(array('uid' => get_uid(), 'status' => 0))->setField('status', 1); //读取到推送之后，自动删除此推送来防止反复推送。

        $new_talk_messages = D('TalkMessagePush')->getAllPush(); //聊天消息推送
        D('TalkMessagePush')->where(array('uid' => get_uid(), 'status' => 0))->setField('status', 1); //读取到推送之后，自动删除此推送来防止反复推送。

        foreach ($new_talk_messages as &$message) {
            $message = D('TalkMessage')->find($message['source_id']);
            $message['user'] = query_user(array('avatar64', 'uid', 'username'), $message['uid']);
            $message['ctime'] = date('m-d h:i', $message['create_time']);
            $message['avatar64'] = $message['user']['avatar64'];
        }
        exit(json_encode(array('messages' => $haventToastMessages, 'new_talk_messages' => $new_talk_messages, 'new_talks' => $new_talks)));
    }

    /**设置全部的系统消息为已读
     * 
     */
    public function setAllMessageReaded()
    {
        D('Message')->setAllReaded(is_login());
    }

    /**设置某条系统消息为已读
     * @param $message_id
     * 
     */
    public function readMessage($message_id)
    {
        exit(json_encode(array('status' => D('Common/Message')->readMessage($message_id))));

    }

    /**
     * 用户修改封面
     * 
     */
    public function changeCover()
    {
        if (!is_login()) {
           $this->error(L('_ERROR_NEED_LOGIN_').L('_EXCLAMATION_'));
        }
        if (IS_POST) {
            $result=$this->logic->changeCover(); 
            $this->ajaxReturn($result);
        } else {
            //获取用户封面id
        	$my_cover=$this->logic->getCoverInfo();
            $this->assign('my_cover', $my_cover);
            $this->display('_change_cover');
        }
    }

    public function changeAvatar(){
        if (!is_login()) {
            $this->error(L('_ERROR_NEED_LOGIN_').L('_EXCLAMATION_'));
        }
        if (IS_POST) {
        	if(!array_filter(I('post.'))){
        		$this->ajaxReturn(array('status'=>0,'info'=>'请上传图片'));
        	}
            if($id=intval(I('post.imgId'))){
                
            }else{
                list($id,$path)=$this->cropPicture();
            }
            if(D('Ucenter/Avatar')->saveAvatar(array('uid'=>is_login(),'path'=>$id))){
                $this->ajaxReturn(array('status'=>1,'path'=>query_user(array('avatar'),is_login())['avatar'],'info'=>'保存成功'));
            }
            $this->ajaxReturn(array('status'=>0,'path'=>query_user(array('avatar'),is_login())['avatar'],'info'=>'保存失败，请重试'));
        }
    }
    
    public function cropPicture()
    {
        $driver = modC('PICTURE_UPLOAD_DRIVER','local','config');
        $x = I('post.x');
        $y = I('post.y');
        $width = I('post.w');
        $height = I('post.h');
        $path = I('post.img');
        if (strtolower($driver) == 'local') {
            //解析crop参数
            //本地环境
            $image = ImageWorkshop::initFromPath($path);
            //生成将单位换算成为像素
            $x = $x * $image->getWidth();
            $y = $y * $image->getHeight();
            $width = $width * $image->getWidth();
            $height = $height * $image->getHeight();
            //如果宽度和高度近似相等，则令宽和高一样
            if (abs($height - $width) < $height * 0.01) {
                $height = min($height, $width);
                $width = $height;
            }
            //调用组件裁剪头像
            $image = ImageWorkshop::initFromPath($path);
            $image->crop(ImageWorkshopLayer::UNIT_PIXEL, $width, $height, $x, $y);
            $image->save(dirname($path), basename($path));
            //返回新文件的路径
            return  cut_str('/Uploads/Avatar',$path,'l');
        }else{
            $name = get_addon_class($driver);
            $class = new $name();
            $new_img = $class->crop($path, $x . ',' . $y . ',' . $width . ',' . $height);
            return array( $new_img['id'], $new_img['url'] );
        }


    }
    public function changeQrcode()
    {
            if (!is_login()) {
                $this->error(L('_ERROR_NEED_LOGIN_').L('_EXCLAMATION_'));
            }
            if (IS_POST) {
            	if(!array_filter(I('post.'))){
            		$this->ajaxReturn(array('status'=>0,'info'=>'请上传图片'));
            	}
                list($id,$path)=$this->cropPicture();
                $data = getUserConfigMap('qrcode');
                $data['role_id'] = 0;
                $model = D('Ucenter/UserConfig');
                $already_data = $model->findData($data);
                if (!$already_data) {
                    $data['value'] = $id;
                    $res = $model->addData($data);
                } else {
                    if ($already_data['value'] == $id) {
                        $result['info'] = L('_ALTER_NOT_').L('_EXCLAMATION_');
                        $result['status']=0;
                        $this->ajaxReturn($result);
                    } else {
                        $res = $model->saveValue($data, $id);
                    }
                }
                if($res){
                    $this->ajaxReturn(array('status'=>1,'path'=>$path,'info'=>'保存成功'));
                }
                $this->ajaxReturn(array('status'=>0,'info'=>'保存失败'));
            }
    
    
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