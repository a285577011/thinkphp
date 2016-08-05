<?php

namespace Isay\Widget;

use Think\Controller;

class LikeWidget extends Controller {

    public function someLike($obj_id, $obj_type = 0, $type =0, $page = 1,$rs=5) {
        $map = array();
        $map['obj_id'] = $obj_id;
        $map['obj_type'] = $obj_type;
        $map['type'] = $type;
        list($rows, $totalCount) = D('Isay/IsayLike')->getLikeListRows($map, 'create_time DESC', $page, $rs);

        $this->assign('rows', $rows);
        $this->assign('totalCount', $totalCount);
        $this->assign('page', $page);
        $this->assign('obj_id', $obj_id);
        $this->assign('obj_type', $obj_type);
        $html=$this->fetch(T('Widget/Like/somelike'));

        return str_replace('onclick="goToUrl', 'onclick="goTo', $html);
    }

}
