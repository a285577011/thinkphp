<?php

namespace Ucenter\Model;

use Think\Model;

class UserStepModel extends Model {
    /* 缓存key设置 */

    protected $_skey_main = 'user_step_{uid}'; // 主键

    /**
     * 获取主键缓存key
     * @param number $id
     */

    public function getCacheKeyMain($uid) {
        return str_replace('{uid}', $uid, $this->_skey_main);
    }

//    public function insertData($data) {        
//        $cache = $this->getByTitle($data['title']);
//        if ($cache) {
//            return $cache['id'];
//        }
//        if(empty($data['title']))return;
//        $result = $this->add($data);
//        return $result;
//    }
//
//    public function addData() {
//        $category = $this->create();
//        $result = $this->add($category);
//        return $result;
//    }

    public function saveData($data) {
        $count = $this->where(array('uid' => $data['uid']))->count();
        S($this->getCacheKeyMain($data['uid']), null);
        if ($count) {
            return $this->save($data);
        }
        return $this->add($data);
    }

    /**
     * 根据标签名获取信息
     * @param $id
     * @param bool $field
     * @return mixed
     * 
     */
    public function getByUid($uid) {
        $skey = $this->getCacheKeyMain($uid);
        $data = S($skey);
        if (!$data) {
            $data = $this->where(array('uid' => $uid))->find();
            S($skey, $data);
        }
        return $data;
    }

    public function getStepPercentage($uid) {
        $row = $this->getByUid($uid);
        if (!$row)
            return 0;
        $full = 0;
        $unfull = 0;
        foreach ($row as $v) {
            if ($v) {
                $full++;
            } else {
                $unfull++;
            }
        }
        return intval(($full / ($full + $unfull))*100);
    }

}
