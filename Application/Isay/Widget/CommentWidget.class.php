<?php

namespace Isay\Widget;

use Think\Controller;

class CommentWidget extends Controller {

    public function someComment($isay_id, $html = FALSE, $rows = 0, $is_index = TRUE,$page=1) {      
        $map = array('obj_id' => $isay_id, 'status' => 1,'obj_type'=>'isay');
        $comments = D('Isay/IsayComment')->getCommentRows($map, $page, ($rows > 0 ? $rows : 10));
        $totalCount = D('Isay/IsayComment')->getCommentRowsCount($map);
        //$isay = D('Isay/Isay')->getData($isay_id);
        $user = query_user(array('uid', 'nickname', 'avatar32', 'avatar64', 'avatar128', 'avatar256', 'avatar512', 'space_url', 'rank_link', 'score', 'title', 'isaycount', 'fans', 'following'), is_login());

        $this->assign('user_info', $user);
        $this->assign('totalCount', $totalCount);
        $this->assign('comments', $comments);
        $this->assign('isayId', $isay_id);
        $this->assign('is_index', $is_index);

        if ($html) {
            return $this->fetch(T('Widget/comment/somecomment'));
        } else {
            $this->display(T('Widget/comment/somecomment'));
        }
    }

    public function commentHtml($cid, $html = FALSE) {
        $comment = D('Isay/IsayComment')->getComment($cid);
        //$comment['can_like']=check_dolike($cid,'comment',  is_login());
        $this->assign('comment', $comment);
        if ($html) {
            return $this->fetch(T('Widget/comment/comment'));
        } else {
            $this->display(T('Widget/comment/comment'));
        }
    }

    //评论的评论
    public function someCComment($id, $html = FALSE, $rows = 0,$page=1) {       
        list($comments, $totalCount) = D('Isay/IsayComment')->getCommentListByObjId($id,'comment', 1, $page, ($rows > 0 ? $rows : 10));
        $data = D('Isay/IsayComment')->getComment($id);
        $user = query_user(array('uid', 'nickname', 'avatar32', 'avatar64', 'avatar128', 'avatar256', 'avatar512', 'space_url', 'rank_link', 'score', 'title', 'isaycount', 'fans', 'following'), is_login());

        $this->assign('user_info', $user);
        $this->assign('data', $data);
        $this->assign('comments', $comments);
        $this->assign('commentId', $id);
        $this->assign('totalCount', $totalCount);


        if ($html) {
            return $this->fetch(T('Widget/comment/some_c_comment'));
        } else {
            $this->display(T('Widget/comment/some_c_comment'));
        }
    }

    //评论的评论
    public function commentCHtml($cid, $html = FALSE) {
        $comment = D('Isay/IsayComment')->getComment($cid);
        $this->assign('comment', $comment);
        if ($html) {
            return $this->fetch(T('Widget/comment/c_comment'));
        } else {
            $this->display(T('Widget/comment/c_comment'));
        }
    }

}
