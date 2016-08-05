<?php
/**
 * 微商圈
 */
namespace App\Controller;

use Think\Model;

class WeiquanController extends BaseController
{
    /**
     * 首页
     */
    public function home(){
        $http_request_mode = 'request';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');
        $page_size = I($http_request_mode.'.page_size', 10, 'intval'); //默认10条
        
        $data = array();
        $data['userinfo'] = query_user(array('uid', 'nickname', 'avatar', 'weiquan_cover'), $uid);
        
        if(!$uid || !$data['userinfo']){
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '用户不存在或被禁用', 'data' => (object)array() );
			exit;
        }
        
        // 新消息数量
        $data['message']['num'] = D('Weiquan/WeiquanMessage')->getCount($uid);
        
        // 最新消息头像
        if($data['message']['num']){
            // TODO 最新消息
            $last_message = D('Weiquan/WeiquanMessage')->getLastOne($uid);
            $obj_userinfo = query_user(array('avatar'), $last_message['obj_info']['uid']);
            $data['message']['avatar'] = $obj_userinfo['avatar'];
        }
        $weiquanModel = D('Weiquan/Weiquan');

        // 关注的用户
        $follow = D('Follow')->getFollowList($uid);
        $follow['ids'] ? $follow['ids'] = $uid.','.$follow['ids'] : $follow['ids'] = $uid.'';
        
        // 微商圈列表
        //查询条件
        $param = array();
        $param['where']['status'] = 1;
        // $param['where']['uid'] = $uid;
        $param['where']['uid'] = array('in', $follow['ids']);
        $param['field'] = 'id,uid';
        if ($page_no == 1) {
            $param['limit'] = 10;
        } else {
            $param['page'] = $page_no;
        }
        $param['count'] = $page_size;
        
        $res = $weiquanModel->getWeiquanList($param);
        $list = $res[0];
        if($list){
            $userModel = D('Common/User');
            $weiquanLikeModel = D('Weiquan/WeiquanLike');
            $weiquanCommentModel = D('Weiquan/WeiquanComment');
            foreach ($list as $k => &$v){
                
                $v = $weiquanModel->getDetail($v['id']);
                
                $userinfo = $userModel->query_user(array('uid','nickname', 'avatar'), $v['uid']);
                $v['nickname'] = $userinfo['nickname'];
                $v['avatar'] = $userinfo['avatar'];
                
                $is_like = $weiquanLikeModel->getLikeByWidAndUid($v['id'], $v['uid']);
                $is_like ? $v['is_like'] = true : $v['is_like'] = false;
                // 点赞列表
                $param = array();
                $param['field'] = 'uid';
                $param['where']['weiquan_id'] = $v['id'];
                $v['like_list'] = $weiquanLikeModel->getLikeListApi($param);
                // 评论列表
                $param = array();
                $param['field'] = 'id,uid,to_uid,content';
                $param['where']['status'] = 1;
                $param['where']['weiquan_id'] = $v['id'];
                $v['comment_list'] = $weiquanCommentModel->getCommentListApi($param);
                unset($v['status'], $v['is_top']);
            }
            unset($v);
            $data['list'] = $list;
        }
            
        $this->result['data'] = $data;
    }

    /**
     * 发布消息
     */
    public function send()
    {
        $http_request_mode = 'post';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $content = I($http_request_mode.'.content', '', 'op_t');
        $type = I($http_request_mode.'.type', 'feed', 'op_t');  // feed image video repost
        $attach_ids = I($http_request_mode.'.attach_ids', '', 'op_t'); // image video 类型必填
        $weiquan_id = I($http_request_mode.'.weiquan_id', 0, 'intval'); // repost 类型必填
        $extra = I($http_request_mode.'.extra', array(), 'convert_url_query');

        $types = D('Weiquan/Weiquan')->getType();
        if (!in_array($type, $types)) {
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'类型错误',  'data' => (object)array());
        	exit;
        }
        
        $return = check_action_limit('add_weibo', 'weibo', 0, is_login(), true);
        if ($return && !$return['state']) {
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>$return['info'],  'data' => (object)array());
        	exit;
        }

        $feed_data = array();
        if($type == 'image' || $type == 'video'){
            if (preg_match('/^([0-9]+[,]?)+$/', $attach_ids)) {
                $feed_data['attach_ids'] = $attach_ids;
            }else{
            	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'文件错误'.$attach_ids,  'data' => (object)array());
            	exit;
            }
        }elseif($type == 'repost'){
            if (!empty($weiquan_id)) {
                $weiquan_info = D('Weiquan/Weiquan')->getDetail($weiquan_id);
                $feed_data['source'] = '';
                $feed_data['sourceId'] = $weiquan_id;
            }else{
            	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'转发来源错误',  'data' => (object)array());
            	exit;
            }
        }

        if (!empty($extra)) $feed_data = array_merge($feed_data, $extra);

        // 执行发布，写入数据库
        $new_id = D('Weiquan/Weiquan')->addWeiquan($uid, $content, $type, $feed_data);
        if (!$new_id) {
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>D('Weiquan/Weiquan')->getError(),  'data' => (object)array());
        	exit;
        }
        
        if ($type == 'repost') {
            D('Weiquan/Weiquan')->where('id=' . $weiquan_id)->setInc('repost_count');
            //$aWeiboId != $aSourceId && D('weibo')->where('id=' . $aWeiboId)->setInc('repost_count');
            S(D('Weiquan/Weiquan')->getCacheKeyMain($weiquan_id), null);
            // 发送消息
            $user = query_user(array('nickname'), $uid);
            $to_uid = $weiquan_info['uid'];
            D('Common/Message')->sendMessage($to_uid, '转发提醒', $user['nickname'] .'转发了您的微商圈', '', array('id' => $new_id), $uid, 1);
        }
        
        
        // TODO 新消息
        $this->result['data'] = array( 'id' => $new_id );
        
    }

    /**
     * 微商圈列表
     */
    public function lists()
    {
        $http_request_mode = 'request';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');
        $page_size = I($http_request_mode.'.page_size', 10, 'intval'); //默认10条
        
        $userModel = D('Common/User');
        $info = $userModel->query_user(array('uid', 'nickname', 'avatar', 'signature', 'weiquan_cover'), $uid);
        $data['userinfo'] = $info;
        
        if(!$info){
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '用户不存在或被禁用', 'data' => (object)array() );
			exit;
        }

        $weiquanModel = D('Weiquan/Weiquan');
        
        //查询条件
        $param['field'] = 'id,uid,create_time,content,type,data';
        if ($page_no == 1) {
            $param['limit'] = 10;
        } else {
            $param['page'] = $page_no;
        }
        $param['count'] = $page_size;
        
        $param['where']['status'] = 1;        
        $param['where']['uid'] = $uid;
        
        $list = $weiquanModel->getWeiquanList($param);
        
        if($list){
            foreach ($list as &$v) {
                $v['data'] = unserialize($v['data']);
                if($v['type'] == 'image'){
                    $v['attachment'] = $weiquanModel->fetchImage($v['data']['attach_ids']);
                }elseif($v['type'] == 'video'){
                    $v['attachment'] = $weiquanModel->fetchVideo($v['data']['attach_ids']);
                }elseif($v['type'] == 'repost'){
                    $v['repost_source'] = $weiquanModel->getDetail($v['data']['sourceId']);
                }
                unset($v['data'], $v['uid']);
            }
            unset($v);
            $data['list'] = $list;
        }
    
        $this->result['data'] = $data;
    }
    
    /**
     * 删除微商圈
     */
    public function delete(){
        $http_request_mode = 'post';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $weiquan_id = I($http_request_mode.'.weiquan_id', 0, 'intval');

        $weiquanModel = D('Weiquan/Weiquan');
        
        $info = $weiquanModel->getDetail($weiquan_id);
        if(!$info || $info['uid'] != $uid || !$weiquanModel->deleteWeiquan($weiquan_id)){
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'删除失败',  'data' => (object)array());
        	exit;
        }
    }
    
    /**
     * 微商圈详情
     */
    public function detail(){
        $http_request_mode = 'request';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $weiquan_id = I($http_request_mode.'.weiquan_id', 0, 'intval');

        $weiquanModel = D('Weiquan/Weiquan');
        
        $info = $weiquanModel->getDetail($weiquan_id);
        
        // TODO 判断条件改为是否有关注
        if(!$info || $info['uid'] != $uid){
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'不存在',  'data' => (object)array());
        	exit;
        }
        $userinfo = D('Common/User')->query_user(array('uid','nickname','avatar'), $uid);
        $info['nickname'] = $userinfo['nickname'];
        $info['avatar'] = $userinfo['avatar'];

        $weiquanLikeModel = D('Weiquan/WeiquanLike');
        $weiquanCommentModel = D('Weiquan/WeiquanComment');
        $is_like = $weiquanLikeModel->getLikeByWidAndUid($info['id'], $info['uid']);
        $is_like ? $info['is_like'] = true : $info['is_like'] = false;
        // 点赞列表
        $param = array();
        $param['field'] = 'uid';
        $param['where']['weiquan_id'] = $info['id'];
        $info['like_list'] = $weiquanLikeModel->getLikeListApi($param);
        // 评论列表
        $param = array();
        $param['field'] = 'id,uid,to_uid,content';
        $param['where']['weiquan_id'] = $info['id'];
        $param['where']['status'] = 1;
        $info['comment_list'] = $weiquanCommentModel->getCommentListApi($param);
        
        $this->result['data'] = $info;
    }
    
    /**
     * 评论
     */
    public function sendComment(){
        $http_request_mode = 'post';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $content = I($http_request_mode.'.content', '', 'op_t');
        $weiquan_id = I($http_request_mode.'.weiquan_id', 0, 'intval');
        $to_comment_id = I($http_request_mode.'.comment_id', 0, 'intval'); //非必填项，代表@某条（某人）
        
        // 执行发布，写入数据库
        $comment_id = D('Weiquan/WeiquanComment')->addComment($uid, $weiquan_id, $content, $to_comment_id);
        if (!$comment_id) {
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'评论失败'.D('Weiquan/WeiquanComment')->getError(),  'data' => (object)array());
        	exit;
        }
        $this->result['data'] = array( 'id' => $comment_id );
    }
    
    /**
     * 评论列表
     */
    public function commentList(){
        $http_request_mode = 'request';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $weiquan_id = I($http_request_mode.'.weiquan_id', 0, 'intval');

        // 评论列表
        $param = array();
        $param['field'] = 'id,uid,to_uid,content';
        $param['where']['weiquan_id'] = $weiquan_id;
        $data = D('Weiquan/WeiquanComment')->getCommentListApi($param);
        $this->result['data'] = $data;
    }
    
    /**
     * 删除评论
     */
    public function delComment(){
        $http_request_mode = 'post';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $comment_id = I($http_request_mode.'.comment_id', 0, 'intval');
        
        // 执行发布，写入数据库
        if (!D('Weiquan/WeiquanComment')->deleteComment($comment_id, $uid)) {
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'删除失败',  'data' => (object)array());
        	exit;
        }
    }
    
    /**
     * 点赞/取消赞
     */
    public function like(){
        $http_request_mode = 'post';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $weiquan_id = I($http_request_mode.'.weiquan_id', 0, 'intval');
        
        // 是否赞过
        $weiquanLikeModel = D('Weiquan/WeiquanLike');
        $likeid = $weiquanLikeModel->getLikeByWidAndUid($weiquan_id, $uid);
        if($likeid){
            $id = $weiquanLikeModel->deleteLike($likeid);
        }else{
            $id = $weiquanLikeModel->addLike($uid, $weiquan_id);
        }
        if (!$id) {
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>$weiquanLikeModel->getError(),  'data' => (object)array());
        	exit;
        }
        $this->result['data'] = $likeid ? $likeid : $id;
    }
    
    /**
     * 点赞列表
     */
    public function likelist(){
        $http_request_mode = 'request';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $weiquan_id = I($http_request_mode.'.weiquan_id', 0, 'intval');

        // 点赞列表
        $param = array();
        $param['field'] = 'uid,create_time';
        $param['where']['weiquan_id'] = $weiquan_id;
        $data = D('Weiquan/WeiquanLike')->getLikeListApi($param);
        if($data){
            $avatarObject = new \Ucenter\Widget\UploadAvatarWidget();
            foreach ($data as $k => &$v){
                $v['avatar'] = $avatarObject->getAvatar($v['uid'], 64);
            }
            unset($v);
        }else{
            $data = (object)$data;
        }
        
        $this->result['data'] = $data;
    }
    
    /**
     * 新消息数
     */
    public function newMessage(){
        $http_request_mode = 'request';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $count = D('Weiquan/WeiquanMessage')->getCount($uid);
        $this->result['data'] = $count;
    }
    
    /**
     * 新消息列表
     */
    public function messageList(){
        $http_request_mode = 'request';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');
        $page_size = I($http_request_mode.'.page_size', 10, 'intval'); //默认10条
    
        $where['status'] = 0;
        $where['uid'] = $uid;
        
        if(!$uid){
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'用户不存在',  'data' => (object)array());
        	exit;
        }
        
        $weiquanMessageModel = D('Weiquan/WeiquanMessage');
        $list = $weiquanMessageModel->getList($where, 'id,uid,type,weiquan_id,obj_id,create_time', $page_no, $page_size);
    
        if($list){
            $weiquanModel = D('Weiquan/Weiquan');
            $weiquanLikeModel = D('Weiquan/WeiquanLike');
            $weiquanCommentModel = D('Weiquan/WeiquanComment');
            $userModel = D('Common/User');
            $avatarObject = new \Ucenter\Widget\UploadAvatarWidget();
            
            foreach ($list as $k => &$v){
                // 消息发布者信息(昵称,头像)
                $info = $userModel->query_user(array('uid','nickname'), $uid);
                $v['nickname'] = $info['nickname'];
                $v['avatar'] = $avatarObject->getAvatar($uid, 64);
                
                // 对象信息(点赞/评论内容)
                if($v['type'] == 1){ // 点赞
                    $obj_info = $weiquanLikeModel->getLikeById($v['obj_id']);
                    $v['create_time'] = $obj_info['create_time'];
                    $v['content'] = ''; // 点赞默认评论空
                }elseif($v['type'] == 2){ // 评论
                    $obj_info = $weiquanCommentModel->getComment($v['obj_id']);
                    $v['create_time'] = $obj_info['create_time'];
                    $v['content'] = $obj_info['content'];
                }
                $weiquaninfo = $weiquanModel->getDetail($v['weiquan_id']);
                $v['weiquan']['content'] = $weiquaninfo['content'];
                $v['weiquan']['attachment'] = $weiquaninfo['attachment'][0]['small'];
                unset($v['obj_id']);
            }
            unset($v);
            
            $weiquanMessageModel->delMessage($uid); // 更改消息状态
            S(D('Weiquan/WeiquanMessage')->getCacheKeyCount($uid), null); // 清除新消息数缓存
        }
    
        $this->result['data'] = $list;
    }
    
    /**
     * 转发
     */
    public function repost(){
        
    }
    
    /**
     * 设置封面
     */
    public function setCover(){
        $http_request_mode = 'post';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $fileid = I($http_request_mode.'.fileid', '', 'intval');

        $data = getUserConfigMap('weiquan_cover', '', $uid);
        
        $res = D('Ucenter/UserConfig')->saveValue($data, $fileid);
        
        if(!$res){
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'设置封面失败'.D('Ucenter/UserConfig')->getError(),  'data' => (object)array());
        	exit;
        }
        clean_query_user_cache($data['uid'], array('weiquan_cover')); // 删除query_user字段缓存
    }
    
}