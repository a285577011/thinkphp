<?php

/**
 * Created by i.cn.
 * User: zhangby
 * Date: 16.1.28
 * 
 */

namespace Common\Model;

use Think\Model;

class FieldSettingModel extends Model {
    
    private $_skey = 'field_setting';
    
    public function getCacheKey(){
        return $this->_skey;
    }

    public function getFieldList($map) {
        $skey = 'fieldSettingList_' . serialize($map);
        $cahce = S($skey);
        if (!$cahce) {
            $cahce = D('FieldSetting')->where($map)->select();
            S($skey, $cahce);
        }
        return $cahce;
    }

    /**
     * 获取用户扩展资料分组信息，全表缓存
     * @param number $id
     * @author dpj
     */
    public function getFieldSetting($id = 0) {
        $skey = $this->getCacheKey();
        $data = S($skey);
        if (!$data) {
            $data = $this->order('sort ASC')->select(array('index' => id));
            S($skey, $data);
        }
        return $id ? $data[$id] : $data;
    }
    
    /**
     * 删除缓存
     * @return Ambigous <mixed, object>
     */
    public function delCache(){
        return S($this->getCacheKey(), null);
    }
    
    //后置操作
    public function _after_save(){
        $this->delCache();
    }
    public function _after_add(){
        $this->delCache();
    }

}
