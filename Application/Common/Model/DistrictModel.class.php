<?php

namespace Common\Model;

use Think\Model;

/**
 * 全国城市乡镇信息模型
 */
class DistrictModel extends Model {
    /* 缓存key设置 */

    protected $_skey_main = 'district_{id}'; // 主键
    protected $_skey_pid = 'district_pid_{pid}'; // 子集
    protected $_skey_hot = 'district_hot'; // 子集

    /**
     * 获取主键缓存key
     * @param number $id
     */

    public function getCacheKeyMain($id) {
        return str_replace('{id}', $id, $this->_skey_main);
    }

    /**
     * 获取子集缓存key
     */
    public function getCacheKeyPid($pid) {
        return str_replace('{pid}', $pid, $this->_skey_pid);
    }

    /**
     * 获取热门城市缓存key
     */
    public function getCacheKeyHot() {
        return $this->_skey_hot;
    }

    public function _list($map) {
        !$map['order'] && $map['order'] = 'id ASC';
        return $this->getList($map);
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
     * 子集数据
     * @param number $pid
     */
    public function getByPid($pid = 0) {
        $skey = $this->getCacheKeyPid($pid);
        $data = S($skey);
        if (!$data) {
            $map['where']['upid'] = $pid;
            $map['field'] = 'id,name';
            $data = $this->_list($map);
            S($skey, $data);
        }
        return $data;
    }

    /**
     * 获取主键对应名称
     * @param number $id
     */
    public function getNameById($id) {
        if (!$id)
            return '';
        $data = $this->getById($id);
        return $data ? $data['name'] : '';
    }

    /**
     * 获取热门城市
     */
    public function getHotCity() {
        $skey = $this->getCacheKeyHot();
        $data = S($skey);
        if (!$data) {
            $map['where']['level'] = 2;
            $map['where']['hot'] = 1;
            $map['field'] = 'id,name';
            $data = $this->_list($map);
            S($skey, $data);
        }
        return $data;
    }

    /**
     * 获取所有省市
     */
    public function getAllProvinceCity() {
        $data = D('District')->getByPid();
        foreach ($data as $k => &$v) {
            $v['city'] = D('District')->getByPid($v['id']);
        }
        return $data;
    }

    /**
     * 
     * @return type
     * @author zhangby
     */
    public function getAllRows() {
        $skey = $this->getCacheKeyMain('all');
        $data = S($skey);
        if (!$data) {
            $data = D('District')->select();
            S($skey, $data);
        }
        return $data;
    }

}
