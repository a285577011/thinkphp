<?php

/**
 * Created by i.cn.
 * User: zhangby
 * Date: 16.1.28
 * 
 */

namespace Common\Model;

use Think\Model;

class UserFieldSettingModel extends Model {

     var $_cacheKey = 'userFieldSetting_{uid}';

    public function getCacheKey($uid) {
        return str_replace('{uid}', $uid, $this->_cacheKey);
    }
    public function getRow($uid) {
        $cahce = S($this->getCacheKey($uid));
        if (!$cahce) {
            $cahce = D('UserFieldSetting')->where('uid='.$uid)->find(); 
            S($this->getCacheKey($uid), $cahce);
        }
        return $cahce;
    }
    
    public function saveData($data) {
        $count=D('UserFieldSetting')->where('uid='.$data['uid'])->count();
         S($this->getCacheKey($data['uid']), NULL);
        if($count){
          return D('UserFieldSetting')->save($data); 
        }        
        return D('UserFieldSetting')->add($data); 
    }

}
