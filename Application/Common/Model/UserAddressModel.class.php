<?php

namespace Common\Model;

use Think\Model;
use Think\Hook;

/**
 * @author zhangby
 */
class UserAddressModel extends Model {

    protected $_validate = array(
        //array('uid', '/^[1-9]\d*$/', '用户ID错误', self::EXISTS_VALIDATE, 'regex'),  
        array('region', '0,1000', '所在地区不能为空', self::EXISTS_VALIDATE, 'length'),
        array('realname', '1,50', '收货人姓名不能为空', self::EXISTS_VALIDATE, 'length'),
        array('realname', '0,20', '收货人姓名太长', self::EXISTS_VALIDATE, 'length'),
        array('address', '1,200', '详细地址不能为空', self::EXISTS_VALIDATE, 'length'),
        array('mobile', '1,15', '手机号码不能为空', self::EXISTS_VALIDATE, 'length'),
    );
    var $_skey_main = 'user_address_{id}';

    public function getCacheKeyMain($id) {
        return str_replace('{id}', $id, $this->_skey_main);
    }

    public function addOrSave($data = array()) {
        $data = $this->create($data);
        if (!$data) {
            return false;
        }
        if (isset($data['id'])) {
            $res = $this->save($data);
            S($this->getCacheKeyMain($data['id']), null);
        } else {
            $data['create_time'] = time();
            $res = $this->add($data);
        }

        return $res;
    }
    
    public function upAddress($where,$data){
        $map=$where;
        unset($map['id']);
        $rows=  $this->getList(array('where'=>$map,'field'=>'id'));
        foreach ($rows as $v){
           S($this->getCacheKeyMain($v['id']), null); 
           $this->where(array('id'=>$v['id']))->save(array('is_default'=>0));
        }
        $rs= $this->where($where)->save($data);
        return $rs;
    }

    public function deleteById($id) {
        S($this->getCacheKeyMain($id), null);
        return $this->delete($id);
    }

    public function deleteByIdAndUid($id, $uid) {
        S($this->getCacheKeyMain($id), null);
        return $this->where(array('id' => $id, 'uid' => $uid))->delete();
    }

    public function getById($id) {
        $skey = $this->getCacheKeyMain($id);
        $row = S($skey);
        if (!$row) {
            $row = $this->find($id);
            $area = explode(',', $row['region']);
            $row['arr_region'] = array_filter($area);
            $row['arr_region'] =array_values($row['arr_region']);                      
            $row['mobile_enp'] = substr($row['mobile'], 0, 3) . '*****' . substr($row['mobile'], -3);
            S($skey, $row);
        }
        $row['user_info'] = get_user_info($row['uid']);
        return $row;
    }

    public function getListByPage($where, $page = 1, $r = 10, $order = 'create_time DESC') {
        $count = $this->where($where)->count('id');
        $rows = $this->where($where)->order($order)->page($page, $r)->field('id')->select();
        $ids = getSubByKey($rows, 'id');
        $list = array();
        foreach ($ids as $v) {
            $list[$v] = $this->getById($v);
        }
        return array($list, $count);
    }

}
