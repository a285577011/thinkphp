<?php

/**
 * Created by i.cn.
 * User: zhangby
 * Date: 16.1.28
 * 
 */

namespace Common\Model;

use Think\Model;

class UserFieldIconModel extends Model {


    public function getRowList() {
        $cache = S('userFieldIcon');
        if (!$cache) {
            $cache = $this->select();
            if (!$cache) {
                return null;
            }
            S('userFieldIcon', $cache);
        }
        return $cache;
    }

}
