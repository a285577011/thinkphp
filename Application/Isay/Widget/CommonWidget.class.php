<?php

namespace Isay\Widget;

use Think\Controller;

class CommonWidget extends Controller {

    public function likeAndShare($isay, $html = FALSE) {
        if (!is_array($isay)) {
            $isay = D('Isay/Isay')->getData($isay);
        }
        $bdshare = "{";
        $bdshare.='"bdDes":"'.htmlspecialchars($isay['description']).'",';
        $bdshare.='"text":"'.$isay['title'].'",';
        $bdshare.='"title":"'.$isay['title'].'",';
        $bdshare.='"url":"'.U('Isay/index/detail@i.cn', array('id' => $isay['id'])).'"';       
        if (!$isay['cover']) {
            $bdshare.=',"pic":"http://i.cn/'.getThumbImageById($isay['cover'], 720, 309).'"';
        }
         $bdshare .= "}";       
        
        $this->assign('share', $bdshare);
        $this->assign('data', $isay);
        if ($html) {
            return $this->fetch(T('Widget/like_and_share'));
        } else {
            $this->display(T('Widget/like_and_share'));
        }
    }
    
    public function bdShare($data=FALSE,$html = FALSE){
        $this->assign('share', $data);
        if ($html) {
            return $this->fetch(T('Widget/bdshare'));
        } else {
            $this->display(T('Widget/bdshare'));
        } 
    }

}
