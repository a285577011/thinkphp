<?php

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: huajie <banhuajie@163.com>
// +----------------------------------------------------------------------

namespace Ipai\Model;

use Think\Model;

class PaiCategoryModel extends Model {

    var $_cacheKey = 'paiCategory_{id}';

    public function getCacheKey($id) {
        return str_replace('{id}', $id, $this->_cacheKey);
    }

    public function getRow($id) {
        $cache = S($this->getCacheKey($id));
        if (!$cache) {
            $cache = $this->where(array('id' => $id))->find();
            S($this->getCacheKey($id), $cache);
        }
        return $cache;
    }

    public function getAllRows($where=array()) {
        $rows = $this->where($where)->field('id')->select();
        $ids = getSubByKey($rows, 'id');
        $data = array();
        foreach ($ids as $v) {
            $data[$v] = $this->getRow($v);
        }
        return $data;
    }

}
