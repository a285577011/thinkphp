<?php

namespace Isay\Controller;

use Think\Controller;

class CommentController extends BaseController {

    protected $isayModel;
    protected $isayDetailModel;
    protected $isayLikeModel;
    protected $isayCommentModel;
    protected $isayCategoryModel;

    function _initialize() {
        if (isset($_POST['keywords'])) {
            $_GET['keywords'] = json_encode(trim($_POST['keywords']));
        }
        $keywords = $_GET['keywords'];

        $this->isayModel = D('Isay/Isay');
        $this->isayDetailModel = D('Isay/IsayDetail');
        $this->isayLikeModel = D('Isay/IsayLike');
        $this->isayCommentModel = D('Isay/IsayComment');
        $this->isayCategoryModel = D('Isay/IsayCategory');
    }

    /**
     * 发表评论
     */
    public function doComment() {
        $this->checkIsLogin();
        $uid = is_login();
        $aId = I('post.isay_id', 0, 'intval');
        $aContent = I('post.content', 0, 'op_t');
        $aCommentId = I('post.comment_id', 0, 'intval');

        $this->checkAuth(null, -1, L('_INFO_AUTHORITY_COMMENT_LACK_'));
        $return = check_action_limit('add_isay_comment', 'isay_comment', 0, is_login(), true);
        if ($return && !$return['state']) {
            $this->error($return['info']);
        }

        if (empty($aContent)) {
            $this->error(L('_ERROR_CONTENT_CANNOT_EMPTY_'));
        }

        //发送评论     
        $cid = $this->isayCommentModel->addComment($uid,$aId, $aContent, 'isay');//($uid, $obj_id, $content, $obj_type, $to_uid = 0) {
        $result = array();
        if ($cid) {
            //行为日志
            action_log('add_isay_comment', 'isay_comment', $result, $uid);

            $result['data'] = R('Comment/commentHtml', array('cid' => $cid, 'html' => TRUE), 'Widget');
            $result['status'] = 1;
            $result['info'] = L('_SUCCESS_COMMENT_') . L('_EXCLAMATION_');
        } else {
            $result['status'] = 0;
            $result['info'] = L('_SUCCESS_COMMENT_') . L('_EXCLAMATION_');
        }
        //返回成功结果
        $this->ajaxReturn($result);
    }

    /**
     * doDelComment  删除评论
     * 
     */
    public function doDelComment() {
        $aIsayId = I('post.isay_id', 0, 'intval');
        $aCommentId = I('post.id', 0, 'intval');
        $this->checkIsLogin();
        $comment = D('Isay/IsayComment')->getComment($aCommentId);
        $this->checkAuth(null, $comment['uid'], L('_INFO_AUTHORITY_COMMENT_DELETE_LACK_'));


        //删除评论
        $result = D('Isay/IsayComment')->deleteComment($aCommentId);
        if ($result) {
            action_log('del_isay_comment', 'isay_comment', $aCommentId, is_login());
            $return['status'] = 1;
            $return['info'] = L('_SUCCESS_DELETE_');
            $result['data'] =$aIsayId>0?R('Comment/someComment', array('isay_id' => $aIsayId, 'html' => TRUE), 'Widget'):R('Comment/someCComment', array('cid' => $id, 'html' => TRUE), 'Widget');
        } else {
            $return['status'] = 0;
            $return['info'] = L('_FAIL_DELETE_');
        }
        //返回成功信息
        $this->ajaxReturn($return);
    }

    /**
     * 发表评论回复
     */
    public function doReplyComment() {
        $this->checkIsLogin();
        $uid = is_login();
        $aContent = I('post.content', 0, 'op_t');
        $aCommentId = I('post.comment_id', 0, 'intval');
        $at = I('post.at', 0, 'intval');

        $this->checkAuth(null, -1, L('_INFO_AUTHORITY_COMMENT_LACK_'));
        $return = check_action_limit('add_isay_comment', 'isay_comment', 0, is_login(), true);
        if ($return && !$return['state']) {
            $this->error($return['info']);
        }

        if (empty($aContent)) {
            $this->error(L('_ERROR_CONTENT_CANNOT_EMPTY_'));
        }
        
       

        //发送评论     
        $cid = $this->isayCommentModel->addComment($uid, $aCommentId, $aContent, 'comment',$at);
        $result = array();
        if ($cid) {
            //行为日志
            action_log('add_isay_comment', 'isay_comment', $result, $uid);
            if ($at) {//通知回复对象                
                $this->send_at_message($at, L('_REPLY_CONTENT_') . L('_COLON_') . $aContent, 'Isay/Comment/doReplyDetail', array('cid' => $aCommentId));
            }

            $result['data'] = R('Comment/commentCHtml', array('cid' => $cid, 'html' => TRUE), 'Widget');
            $result['status'] = 1;
            $result['info'] = L('_SUCCESS_COMMENT_') . L('_EXCLAMATION_');
        } else {
            $result['status'] = 0;
            $result['info'] = L('_SUCCESS_COMMENT_') . L('_EXCLAMATION_');
        }
        //返回成功结果
        $this->ajaxReturn($result);
    }

    public function doReplyDetail() {
         $page = I('get.p', 1, 'intval');
        if (json_decode($_GET['keywords']) != '') {
            $keywords = json_decode($_GET['keywords']);
            $this->assign('search_keywords', $keywords);
            $map['title|description'] = array('like', '%' . $keywords . '%');
        } else {
            $_GET['keywords'] = null;
        }

        $aId = I('cid', 0, 'intval');
        $info = $this->isayCommentModel->getComment($aId);
        $comments = D('Isay/IsayComment')->getCommentListByObjId($aId, 1, 1);
        
         //分享内容
        $bdshare = "{";
        $bdshare.='"bdDes":"'.htmlspecialchars($info['content']).'",';
        $bdshare.='"text":"'.$info['content'].'",';
        $bdshare.='"title":"'.$info['content'].'",';
        $bdshare.='"url":"'.U('Isay/Comment/doReplyDetail@i.cn', array('cid' => $aId)).'"';    
        $bdshare .= "}";  


        $this->assign('share', $bdshare);
        $this->assign('comments', $comments);
        $this->assign('page', $page);
        $this->assign('info', $info);
        $this->setTitle('{$info.content|text} —— {:L("_MODULE_")}');
        $this->setDescription('{$info.content|text} ——{:L("_MODULE_")}');
        $this->display();
    }
  



    /**
     * 发送AT消息
     * @param type $uids
     * @param type $content
     * @param type $url
     * @param type $url_args
     */
    private function send_at_message($uids, $content, $url = '', $url_args = array()) {
        $my_username = query_user('nickname');
        $message = $content;
        $title = $my_username . L('_AT_YOU_');
        $fromUid = get_uid();
        $messageType = 1;
        if (is_array($uids)) {
            foreach ($uids as $uid) {
                D('Common/Message')->sendMessage($uid, $title, $message, $url, $url_args, $fromUid, $messageType);
            }
        } else {
            D('Common/Message')->sendMessage($uids, $title, $message, $url, $url_args, $fromUid, $messageType);           
        }
         
    }

}
