<?php

namespace Common\Model;

use Think\Model;

/**
 * 快递信息模型
 */
class ExpressCorpModel extends Model {
    /* 缓存key设置 */

    protected $_skey_main = 'express_corp_{id}'; // 主键

    /**
     * 获取主键缓存key
     * @param number $id
     */

    public function getCacheKeyMain($id) {
        return str_replace('{id}', $id, $this->_skey_main);
    }

    /**
     * 主键数据
     * @param unknown $id
     */
    public function getById($id) {
        $skey = $this->getCacheKeyMain($id);
        $data = S($skey);
        if (!$data) {
            $data = $this->find($id);
            S($skey, $data);
        }
        return $data;
    }

    /**
     * 
     * @return type
     * @author zhangby
     */
    public function getAllRows($where = NULL) {
        $skey = $this->getCacheKeyMain('all');
        $data = S($skey);
        if (!$data) {
            if ($where) {
                $data = $this->where($where)->select();
            } else {
                $data = $this->select();
            }
            S($skey, $data);
        }
        return $data;
    }

}
