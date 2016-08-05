<?php

namespace Ipai\Model;

use Think\Model;

class PaiCartModel extends Model {

    // 购物车缓存key
    protected $_skey_cart = 'cart_{id}';

    // 获取缓存键值
    public function getCacheKeyCart($uid) {
        return str_replace('{id}', $uid, $this->_skey_cart);
    }
    
    /**
     * 设置物品数量
     * @param type $uid
     * @param type $data
     * @return type
     * @author zhangby
     */
    public function setCartGoodsNum($uid,$data){
        $result = $this->getCart($uid);
        if ($result) {
            foreach ($data as $k => $v) {
                if ($result[$k]) {
                   $result[$k]=$v;                   
                }               
            }
        }     
        //empty($result) && $result = $data;
        is_array($result) && $data += $result;
        S($this->getCacheKeyCart($uid), $data);
        return $result; 
    }
    
    

    //新增缓存
    public function addCart($uid, $data) {
        $result = $this->getCart($uid);
        if ($result) {
            foreach ($data as $k => &$v) {
                if ($result[$k]) {
                    $v += $result[$k];
                    unset($result[$k]);
                }
                unset($v);
            }
        }
        is_array($result) && $data += $result;
        S($this->getCacheKeyCart($uid), $data);
        return $data;
    }
    

    //删除缓存
    public function deleteCart($uid, $id) {
        $result = $this->getCart($uid);
        foreach ($result as $k => $v) {
            if ($k == $id) {
                unset($result[$k]);               
            }
        }
        S($this->getCacheKeyCart($uid), $result);
    }
    
    //清空缓存
    public function clearCart($uid) {       
        S($this->getCacheKeyCart($uid),NULL);
    }

    // 获取缓存数据
    public function getCart($uid) {
        return S($this->getCacheKeyCart($uid));
    }

}
