<?php
/**
 * 
 * ==============================================
 * Copy right 2015-2016 http://i.cn
 * ----------------------------------------------
 * i.cn说
 * ==============================================
 * @author: dpj
 * @date: 2016年2月1日
 * @version: v1.0.0
 * @desc: 
 */
 
namespace App\Controller;

class IsayController extends BaseController{
    /**
     * 爱说列表
     */
    public function lists(){
        $http_request_mode = 'request';
        $uid = I($http_request_mode.'.uid', 0, 'intval'); // 登录者uid
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');
        $page_size = I($http_request_mode.'.page_size', 10, 'intval'); //默认10条
        $page_size > 20 && $page_size = 20;

        $isayModel = D('Isay/Isay');
        $isayLikeModel = D('Isay/IsayLike');
        
        //查询条件
        $param['where']['status'] = 1;
        $param['field'] = 'id,title,description,comment,up,down';
        $param['order'] = 'create_time DESC';
        if ($page_no == 1) {
            $param['limit'] = $page_size;
        } else {
            $param['page'] = $page_no;
        }

        $new_message = S(D('Isay/IsayComment')->getCacheKeyMessage($uid)); // 新消息状态
        
        $list = $isayModel->getList($param);
        foreach($list as $k => &$v){
            $v['liked'] = $isayLikeModel->getLikeStatus($v['obj_id'], $v['uid'], $v['obj_type']); // 0:未赞/踩过 1:赞过 2:踩过
        }
        unset($v);
    
        $this->result['data'] = array('message'=>$new_message , 'list' => $list);
    }
    
    /**
     * 爱说详情
     */
    public function detail(){
        $http_request_mode = 'request';
        $uid = I($http_request_mode.'.uid', 0, 'intval'); // 登录者uid
        $isay_id = I($http_request_mode.'.id', 0, 'intval');
        
        $isay = D('Isay')->getById($isay_id);
        
        if(!$isay){
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'不存在',  'data' => '');
        	exit;
        }

        $isayCommentModel = D('Isay/IsayComment');
        $isayLikeModel = D('Isay/IsayLike');
        
        $info['id'] = $isay_id;
        $info['description'] = $isay['description'];
        $info['up'] = $isay['up'];
        $info['down'] = $isay['down'];
        $info['liked'] = $isayLikeModel->getLikeStatus($isay_id, $uid, 'isay'); // 0:未赞/踩过 1:赞过 2:踩过

        // 评论列表
        $param = array();
        $param['field'] = 'id,uid,content,create_time,comment,up,down';
        $param['where']['obj_id'] = $isay_id;
        $param['limit'] = 10;
        $param['order'] = 'create_time DESC';
        $comment_list = $isayCommentModel->getList($param);
        foreach ($comment_list as $k => &$v){
            $v['liked'] = $isayLikeModel->getLikeStatus($isay_id, $uid, 'comment'); // 0:未赞/踩过 1:赞过 2:踩过
            
            $userinfo = query_user(array('nickname', 'avatar64'), $v['uid']);
            $v['nickname'] = $userinfo['nickname'];
            $v['avatar'] = $userinfo['avatar64'];
        }
        unset($v);
        $info['comment_list'] = $comment_list;
        
        $this->result['data'] = $info;
    }
    
    /**
     * 点赞
     */
    public function like(){
        $http_request_mode = 'post';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $obj_id = I($http_request_mode.'.obj_id', 0, 'intval');
        $type = I($http_request_mode.'.obj_type', '', 'op_t');
        
        // 是否赞过
        $isayLikeModel = D('Isay/IsayLike');
        $likeid = $isayLikeModel->getLikeByOidAndUidAndObj($obj_id, $uid, $type);
        if($likeid){
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'您已经顶过',  'data' => (object)array());
        	exit;
        }else{
            $id = $isayLikeModel->addLike($uid, $obj_id, 1, $type);  // 1表示赞
        }
        if (!$id) {
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>$isayLikeModel->getError(),  'data' => (object)array());
        	exit;
        }
        $this->result['data'] = $likeid ? $likeid : $id;
    }
    
    /**
     * 踩
     */
    public function dislike(){
        $http_request_mode = 'post';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $obj_id = I($http_request_mode.'.obj_id', 0, 'intval');
        $type = I($http_request_mode.'.obj_type', '', 'op_t');
        
        // 是否赞过
        $isayLikeModel = D('Isay/IsayLike');
        $likeid = $isayLikeModel->getLikeByOidAndUidAndObj($obj_id, $uid, $type);
        if($likeid){
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'您已经踩过',  'data' => (object)array());
        	exit;
        }else{
            $id = $isayLikeModel->addLike($uid, $obj_id, 2, $type);  // 2表示踩
        }
        if (!$id) {
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>$isayLikeModel->getError(),  'data' => (object)array());
        	exit;
        }
        $this->result['data'] = $likeid ? $likeid : $id;
    }
    
    /**
     * 评论
     */
    public function comment(){
        $http_request_mode = 'post';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $content = I($http_request_mode.'.content', '', 'op_t');
        $obj_id = I($http_request_mode.'.obj_id', 0, 'intval');
        $obj_type = I($http_request_mode.'.obj_type', '', 'op_t');
        $to_uid = I($http_request_mode.'.to_uid', 0, 'intval');
        
        // 执行发布，写入数据库
        $comment_id = D('Isay/IsayComment')->addComment($uid, $obj_id, $content, $obj_type, $to_uid);
        if (!$comment_id) {
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'评论失败'.D('Isay/IsayComment')->getError(),  'data' => (object)array());
        	exit;
        }
        $this->result['data'] = array( 'id' => $comment_id );
        
    }
    
    /**
     * 评论详情
     */
    public function commentDetail(){
        $http_request_mode = 'request';
        $uid = I($http_request_mode.'.uid', 0, 'intval'); // 登录者ID
        $id = I($http_request_mode.'.comment_id', 0, 'intval'); // 评论ID
        
        $isayCommentModel = D('Isay/IsayComment');
        $isayLikeModel = D('Isay/IsayLike');
        // 评论信息
        $info = $isayCommentModel->getComment($id, $uid);
        if(!$info || $info['obj_type'] != 'isay'){
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'评论不存在',  'data' => (object)array());
        	exit;
        }
        $info['liked'] = $info['can_like'] ? true : false;
        
        // 用户信息
        $userinfo = query_user(array('nickname','avatar64'), $info['uid']);
        $info['nickname'] = $userinfo['nickname'];
        $info['avatar'] = $userinfo['avatar64'];
        
        // 爱说信息
        $isayinfo = D('Isay/Isay')->getById($info['obj_id']);
        $info['isay'] = $isayinfo['description'];
        
        unset($info['obj_id'], $info['status'], $info['obj_type'], $info['update_time'], $info['to_comment_id'], $info['can_like'], $info['can_delete']);
        
        // 赞列表
        $param = array();
        $param['field'] = 'uid,create_time';
        $param['where']['type'] = 1;
        $param['where']['obj_id'] = $id;
        $param['where']['obj_type'] = 'comment';
        $param['order'] = 'create_time DESC';
        $param['limit'] = 6;
        $data = $isayLikeModel->getList($param);
        foreach ($data as $k => &$v){
            $userinfo = query_user(array('nickname','avatar64'), $v['uid']);
            $v['nickname'] = $userinfo['nickname'];
            $v['avatar'] = $userinfo['avatar64'];
        }
        unset($v);
        $info['like_list'] = $data;
        
        // 评论列表
        $param = array();
        $param['field'] = 'id,uid,obj_id,content,create_time,up,comment,to_uid';
        $param['where']['status'] = 1;
        $param['where']['obj_id'] = $id;
        $param['limit'] = 10;
        //$param['where']['obj_id'] = $obj_id;
        $data = $isayCommentModel->getList($param);
        foreach ($data as $k => &$v){
            $userinfo = query_user(array('nickname', 'avatar64'), $v['uid']);
            $v['nickname'] = $userinfo['nickname'];
            $v['avatar'] = $userinfo['avatar64'];

            // AT
            if($v['to_uid']){
                $userinfo = query_user(array('nickname'), $v['to_uid']);
                $v['to_nickname'] = $userinfo['nickname'];
            }

            $liked = $isayLikeModel->checkIsLiked($v['id'], $uid, 'comment');
            $v['liked'] = $liked ? 1 : 0;
        }
        unset($v);
        $info['comment_list'] = $data;
        
        $this->result['data'] = $info;
    }
    
    
    /**
     * 评论列表
     */
    public function commentList(){
        $http_request_mode = 'request';
        $uid = I($http_request_mode.'.uid', 0, 'intval'); // 不作为筛选条件，仅用于判断赞过、评论过
        $obj_id = I($http_request_mode.'.obj_id', 0, 'intval');
        $type = I($http_request_mode.'.obj_type', '', 'op_t');
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');
        $page_size = I($http_request_mode.'.page_size', 10, 'intval'); //默认10条
        $page_size > 25 && $page_size = 25;

        // 评论列表
        $param = array();
        $param['field'] = 'id,uid,obj_id,content,create_time,up,comment,to_uid';
        $param['where']['status'] = 1;
        $param['where']['obj_id'] = $obj_id;
        $param['where']['obj_type'] = $type;
        $param['order'] = 'create_time DESC';
        if ($page_no == 1) {
            $param['limit'] = $page_size;
        } else {
            $param['page'] = $page_no;
        }
        //$param['count'] = $page_size;
        //$param['where']['obj_id'] = $obj_id;
        $data = D('Isay/IsayComment')->getList($param);

        $isayLikeModel = D('Isay/IsayLike');
        foreach ($data as $k => &$v){
            $userinfo = query_user(array('nickname', 'avatar'), $v['uid']);
            $v['nickname'] = $userinfo['nickname'];
            //$v['avatar'] = $userinfo['avatar64'];

            // AT
            if($v['to_uid']){
                $userinfo = query_user(array('nickname'), $v['to_uid']);
                $v['to_nickname'] = $userinfo['nickname'];
            }

            $liked = $isayLikeModel->checkIsLiked($v['id'], $uid, 'comment');
            $v['liked'] = $liked ? 1 : 0;
        }
        unset($v);
        
        $this->result['data'] = $data;
    }
    
    
    /**
     * 分享
     */
    public function share(){
        
    }
    
    /**
     * 点赞列表
     */
    public function likeList(){
        $http_request_mode = 'request';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $obj_id = I($http_request_mode.'.obj_id', 0, 'intval');
        $obj_type = I($http_request_mode.'.obj_type', '', 'op_t');

        // 点赞列表
        $isayLikeModel = D('Isay/IsayLike');
        $param = array();
        $param['field'] = 'uid,create_time';
        $param['where']['type'] = 1;
        $param['where']['obj_id'] = $obj_id;
        $param['where']['obj_type'] = $obj_type;
        $data = $isayLikeModel->getList($param);
        foreach ($data as $k => &$v){
            $userinfo = query_user(array('nickname','avatar64'), $v['uid']);
            $v['nickname'] = $userinfo['nickname'];
            $v['avatar'] = $userinfo['avatar64'];
            
            $liked = $isayLikeModel->checkIsLiked($obj_id, $uid, $obj_type);
            $v['liked'] = $liked ? 1 : 0;
        }
        unset($v);
        
        $this->result['data'] = $data;
    }
    
    /**
     * 踩列表
     */
    public function dislikeList(){
        $http_request_mode = 'request';
        $uid = I($http_request_mode.'.uid', 0, 'intval');
        $obj_id = I($http_request_mode.'.obj_id', 0, 'intval');
        $obj_type = I($http_request_mode.'.obj_type', '', 'op_t');

        // 点赞列表
        $isayLikeModel = D('Isay/IsayLike');
        $param = array();
        $param['field'] = 'uid,create_time';
        $param['where']['type'] = 2;
        $param['where']['obj_id'] = $obj_id;
        $param['where']['obj_type'] = $obj_type;
        $data = $isayLikeModel->getList($param);
        foreach ($data as $k => &$v){
            $userinfo = query_user(array('nickname','avatar64'), $v['uid']);
            $v['nickname'] = $userinfo['nickname'];
            $v['avatar'] = $userinfo['avatar64'];
            
            $liked = $isayLikeModel->checkIsLiked($obj_id, $uid, $obj_type);
            $v['liked'] = $liked ? 1 : 0;
        }
        unset($v);
        
        $this->result['data'] = $data;
    }
    
    /**
     * 消息列表
     */
    public function message(){
        $http_request_mode = 'request';
        $uid = I($http_request_mode.'.uid', 0, 'intval'); // 登录者id,我
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');
        $page_size = I($http_request_mode.'.page_size', 10, 'intval'); //默认10条
        $page_size > 25 && $page_size = 25;
        
        $isayCommentModel = D('Isay/IsayComment');
        $isayModel = D('Isay/Isay');
        
        $new = S($isayCommentModel->getCacheKeyMessage($uid));
        // 如果新消息状态是1
        if($new){
            S($isayCommentModel->getCacheKeyMessage($uid), null); // 重置新消息状态
        }
        
        // 消息列表
        $param['where']['to_uid'] = $uid;
        $param['where']['status'] = 1;
        $param['order'] = 'id DESC';
        $param['field'] = 'uid,content,obj_id,to_uid,create_time';
        if ($page_no == 1) {
            $param['limit'] = $page_size;
        } else {
            $param['page'] = $page_no;
        }
        $param['count'] = $page_size;
        
        $list = $isayCommentModel->getList($param);
        
        foreach ($list as $k => &$v){
            // 评论者微信名称
            $user = query_user(array('nickname'), $v['uid']);
            $v['nickname'] = $user['nickname'];
            
            //被评论者微信名称,我
            $user = query_user(array('nickname'), $v['to_uid']);
            $v['to_nickname'] = $user['nickname'];
            
            $isay_comment = $isayCommentModel->getById($v['obj_id']); // 我对爱说的评论
            $v['to_content'] = $isay_comment['content'];
            
            $isay = $isayModel->getById($isay_comment['obj_id']); // 我评论的爱说信息
            $v['isay_id'] = $isay['id'];
            $v['isay_content'] = $isay['description'];
        }
        unset($v);
        $this->result['data'] = $list;
        
    }
}