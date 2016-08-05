<?php

namespace Isay\Controller;

use Think\Controller;

class LikeController extends BaseController {

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
    
    public function getLikeList(){ 
        $page=I('get.p',1,'intval');
        $aObjId=I('get.obj_id',0,'intval');
        $aType=I('get.type',0,'intval');
        $aToOjb=I('get.obj_type','','htmlspecialchars');
        $html=R('Isay/Like/someLike',array('obj_id'=>$aObjId,'obj_type'=>$aToOjb,'type'=>$aType,'page'=>$page),'Widget');

        $this->show($html);        
      
    }

    /**
     * 点赞
     */
    public function doLike() {
        $this->checkIsLogin();
        $aIsayId = I('post.isay_id', 0, 'intval');
        $aType = I('post.type', 1, 'intval');
        $aType = $aType == 1 ? 1 : 2;
        $like = D('Isay/IsayLike')->getLikeByOidAndUidAndObj($aIsayId, is_login(),'isay');
        if ($like) {
            $result['status'] = 0;
            $result['info'] = L('_ERROR_REPEAT_POINT_LIKE_');
        } else {
            //发送点赞
            $result['data'] = D('Isay/IsayLike')->addLike(is_login(), $aIsayId,$aType,'isay');
            if ($result['data']) {               
                $aIsay = D('Isay/Isay')->getData($aIsayId);                    
               
                $result['status'] = 1;
                $result['info'] = L('_SUCCESS_LIKE_');
                $result['html'] = R('Isay/Common/likeAndShare', array('isay' => $aIsay, 'return' => TRUE), 'Widget');
                $result['count'] = $aIsay['like_up'] < 1 ? '' : $aIsay['like_up'];
                if ($aType == 2) {
                    $result['count'] = $aIsay['like_down'] < 1 ? '' : $aIsay['like_down'];
                }
            } else {
                $result['status'] = 0;
                $result['info'] = L('_ERROR_LIKE_');
            }
        }
        //返回成功结果
        $this->ajaxReturn($result);
    }
    
        
     /**
     * 评论点赞
     */
    public function doCommentLike() {
        $this->checkIsLogin();
        $aCid = I('post.cid', 0, 'intval');
        $aType = I('post.type', 1, 'intval');
        $aType = $aType == 1 ? 1 : 2;
        $like = D('Isay/IsayLike')->getLikeByOidAndUidAndObj($aCid, is_login(),'comment');
        if ($like) {
            $result['status'] = 0;
            $result['info'] = L('_ERROR_REPEAT_POINT_LIKE_');
        } else {
            //发送点赞
            $result['data'] = D('Isay/IsayLike')->addLike(is_login(), $aCid,$aType,'comment');
            if ($result['data']) {
                $aJump = U('Isay/Comment/doReplyDetail', array('cid' => $aCid));
                $aComm = D('Isay/IsayComment')->getComment($aCid);
                $user = query_user(array('uid', 'nickname', 'avatar64', 'space_url', 'rank_link', 'title'), is_login());
                D('Common/Message')->sendMessage($aComm['uid'], $user['nickname'] . L('_LIKE_YOU_'), $user['nickname'] . L('_LIKE_YOU_ADD_'), $aJump, array('id' => $aCid));
               
                $result['status'] = 1;
                $result['info'] = L('_SUCCESS_LIKE_');
                $result['html'] = //R('Isay/Like/likeUser', array('isay_id' => $aCid, 'return' => TRUE), 'Widget');
                $result['count'] = $aComm['like_up_count'] < 1 ? '' : $aComm['like_up_count'];
                if ($aType == 2) {
                    $result['count'] = $aComm['like_down_count'] < 1 ? '' : $aComm['like_down_count'];
                }
            } else {
                $result['status'] = 0;
                $result['info'] = L('_ERROR_LIKE_');
            }
        }
        //返回成功结果
        $this->ajaxReturn($result);
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
