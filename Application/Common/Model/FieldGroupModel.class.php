<?php

/**
 * 
 * 用户扩展资料分组模型
 * Created by i.cn.
 * Date: 2016-02-25
 * author: dpj
 * 
 */

namespace Common\Model;

use Think\Model;

class FieldGroupModel extends Model {
    
    private $_skey = 'field_group';
    
    public function getCacheKey(){
        return $this->_skey;
    }

    /**
     * 获取用户扩展资料分组信息，全表缓存
     * @param number $id
     */
    public function getFieldGroup($id = 0) {
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
