<?php

/**
 * Created by i.cn.
 * User: zhangby
 * Date: 16.1.28
 * 
 */

namespace Common\Model;

use Think\Model;

class FundsLogModel extends Model {

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT)
    );

    public function addLog($uid, $obj_id, $type, $status, $funds, $message,$module='',$mod_event='') {
        $data = array();
        $data['uid'] = $uid;
        $data['obj_id'] = $obj_id;
        $data['type'] = $type;
        $data['status'] = $status;
        $data['funds'] = $funds;
        $data['message'] = $message;
        $data['module'] = $module;
        $data['mod_event'] = $mod_event;
        $data['message'] = $message;
        $data['create_time'] = time();
        return $this->add($data);
    }

}
