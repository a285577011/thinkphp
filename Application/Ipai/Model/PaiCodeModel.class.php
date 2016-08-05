<?php

namespace Ipai\Model;

use Think\Model;

class PaiCodeModel extends Model {

    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
    );
    private $_cacheKey = 'pai_code_{id}';

    public function getCacheKey($id) {
        return str_replace('{id}', $id, $this->_cacheKey);
    }

    public function getById($id) {
        if ($id > 0) {
            $data = S($this->getCacheKey($id));
            if (!empty($data))
                return $data;
            $data = $this->find($id);
            S($this->getCacheKey($id), $data, 60 * 60);
            return $data;
        }
        return null;
    }

    public function getOne($where) {
        $codes = $this->where($where)->find();
        return $codes;
    }

    public function getList($where) {
        $codes = $this->where($where)->select();
        return $codes;
    }

}
