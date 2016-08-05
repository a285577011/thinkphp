<?php

namespace Weiquan\Widget;

use Think\Controller;

/**
 * 
 */
class LikeWidget extends Controller {
    var $_theme;
    public function __construct() {
        parent::__construct();
          $this->_theme=modC('NOW_THEME','default','Theme');
        
    }
    public function like_html($like_id) {
        $like = D('Weiquan/WeiquanLike')->getLike($like_id);
        $this->assign('like', $like);
        return $this->fetch(T('Weiquan@Widget/like/like'));
    }

    public function likeUser($weiquan_id, $return = FALSE) {
        $like = D('Weiquan/WeiquanLike')->getLikeList($weiquan_id, 1);
        $this->assign('like', $like);
        $this->assign('weiquanId', $weiquan_id);
        if ($return) {
            return $this->fetch(T('Weiquan@Widget/like/likeuser'));
        } else {
            $this->display(T('Weiquan@Widget/like/likeuser'));
        }
    }
    
    public function likeUserDetail($weiquan_id, $return = FALSE) {
        $like = D('Weiquan/WeiquanLike')->getLikeList($weiquan_id, 1);
        $this->assign('like', $like);
        $this->assign('weiquan_id', $weiquan_id);
        if ($return) {
            return $this->fetch(T('Weiquan@Widget/like/likeuser'));
        } else {
            $this->display(T('Weiquan@Widget/like/likeuser'));
        }
    }

    public function someLike($weibo_id,$count=20, $return = FALSE) {
        $like = D('Weiquan/WeiquanLike')->getLikeList($weibo_id, 1,$count);
        $weiquan = D('Weiquan/Weiquan')->getWeiquanDetail($weibo_id);

        $this->assign('weiquanLikeTotalCount', $weiquan['like_count']);
        $this->assign('like', $like);
        $this->assign('weiquanId', $weibo_id);
        $this->assign('page', 1);
         if ($return) {
            return $this->fetch(T('Weiquan@Widget/like/somelike'));
        } else {
            $this->display(T('Weiquan@Widget/like/somelike'));
        }
    }

}
