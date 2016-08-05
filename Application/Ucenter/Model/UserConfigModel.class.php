<?php
/**
 * 用户配置
 */

namespace Ucenter\Model;


use Think\Model;

class UserConfigModel extends Model
{

    protected $_validate = array(
            array('uid', '/^[1-9]\d*$/', '用户ID错误', self::MODEL_BOTH, 'regex'),
            array('value', '/^[1-9]\d*$/', '文件ID错误', self::MODEL_BOTH, 'regex')
    );

    var $_skey_main = 'user_config_{id}';
    var $_skey_uid_name = 'user_config_{uid}_{name}';

    public function getCacheKeyMain($id) {
        return str_replace('{id}', $id, $this->_skey_main);
    }
    
    /**
     * uid+name缓存
     * @param number $uid
     * @param number $field_id
     * @author dpj
     */
    public function getCacheKeyUidName( $uid, $name){
        return str_replace(array('{uid}', '{name}'), array($uid, $name), $this->_skey_uid_name);
    }
    
    public function addData($data=array())
    {
        $data = $this->create($data);
        if(!$data){
            return false;
        }
        $res=$this->add($data);
        return $res;
    }


    /**
     * 获取单条配置数据
     * @param array $map
     * dpj 2016-02-27修改  // TODO 有空重新整理
     */
    public function findData($map=array())
    {
        if($map['id']){
            $id = $map['id'];
        }elseif(isset($map['uid']) && isset($map['name'])){
            $skey = $this->getCacheKeyUidName($map['uid'], $map['name']);
            $id = S($skey);
            if(!$id){
                $id = $this->where(array('uid' => $map['uid'], 'name' => $map['name']))->getField('id');
                S($skey, $id);
            }
        }else{
            return false;
        }
        if(!$id) return false;
        $skey = $this->getCacheKeyMain($id);
        $data = S($skey);
        if(!$data){
            $data = $this->find($id);
            S($skey, $data);
        }
        return $data;
    }

    /**
     * 
     * @param array $map
     * @param string $value
     * dpj 2016-02-27修改
     */
    public function saveValue($map=array(),$value='')
    {
        $exist = $this->findData($map);
        $data['value'] = $value;
        $data['role_id'] = 1;
        if (!$exist) {
            $data['uid'] = $map['uid'];
            $data['name'] = $map['name'];
            $res = $this->addData($data);
        } else {
            if ($exist['value'] != $value) {
                $data['id'] = $exist['id'];
                $res = $this->save($data);
                S($this->getCacheKeyMain($exist['id']), null);
            }else{
                $res = true;
            }
        }
        return $res;
    }
} 