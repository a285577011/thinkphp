<?php

namespace Weiquan\Widget;

use Think\Controller;

class CommentWidget extends Controller {
    /* 显示指定分类的同级分类或子分类列表 */

    public function detail($comment_id) {
        $comment = D('Weiquan/WeiquanComment')->getComment($comment_id);
        $this->assign('comment', $comment);
        $this->display(T('Weiquan@Widget/comment/comment'));
    }

    public function comment_html($comment_id) {
        $comment = D('Weiquan/WeiquanComment')->getComment($comment_id);
        $this->assign('comment', $comment);
        return $this->fetch(T('Weiquan@Widget/comment/comment'));
    }

    public function someComment($weibo_id) {

        $comments = D('Weiquan/WeiquanComment')->getCommentList($weibo_id, 1);

        $weobo = D('Weiquan/Weiquan')->getWeiquanDetail($weibo_id);

        $this->assign('weiboCommentTotalCount', $weobo['comment_count']);
        $this->assign('comments', $comments);
        $this->assign('weiboId', $weibo_id);
        $this->assign('page', 1);
        $this->display(T('Weiquan@Widget/comment/somecomment'));
    }
    
      public function someCommentIndex($weibo_id) {

        $comments = D('Weiquan/WeiquanComment')->getCommentList($weibo_id, 1);
        $weobo = D('Weiquan/Weiquan')->getWeiquanDetail($weibo_id);
        $this->assign('weiboCommentTotalCount', $weobo['comment_count']);
        $this->assign('comments', $comments);
        $this->assign('weiboId', $weibo_id);
        $this->assign('page', 1);
        $this->display(T('Weiquan@Widget/comment/somecomment_index'));
    }

    public function someCommentHtml($weibo_id) {

        $comments = D('Weiquan/WeiquanComment')->getCommentList($weibo_id, 1);
        $weobo = D('Weiquan/Weiquan')->getWeiquanDetail($weibo_id);
        $this->assign('weiboCommentTotalCount', $weobo['comment_count']);
        $this->assign('comments', $comments);
        $this->assign('weiboId', $weibo_id);
        $this->assign('page', 1);
        return $this->fetch(T('Weiquan@Widget/comment/somecomment'));
    }

}
