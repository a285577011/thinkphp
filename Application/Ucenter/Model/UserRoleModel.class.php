<?php
/**
 * 用户身份
 */
namespace Ucenter\Model;

use Think\Model;
use Admin\Model\RoleModel;

class UserRoleModel extends Model {
    
    /**
     * 获取用户拥有的所有身份
     * @param number $uid
     * @return multitype:
     * @author dpj
     */
    public function getRole($uid,$sts=1){
        if(!$uid){
            return false;
        }
        // TODO 缓存
        $map['status'] =$sts;
        $map['uid'] = $uid;
        $data = $this->where($map)->getField('role_id,uid');
        return $data;
    }
    
    /**
     * 检查用户是否一元爱拍认证用户
     * @param number $uid
     * @author dpj
     */
    public function isIpai($uid){
        $roles = $this->getRole($uid);
        return $roles[RoleModel::ROLE_IPAI] ? true : false;
    }
    
    /**
     * 检查用户是否一手货源认证用户
     * @param unknown $uid
     * @return boolean
     */
    public function isHuoyuan($uid){
        $roles = $this->getRole($uid);
        return $roles[RoleModel::ROLE_HUOYUAN] ? true : false;
    }
    
     /**
     * 是否微商红人
     * @param number $uid
     * @author dpj
     */
    public function isHot($uid){
        $roles = $this->getRole($uid);
        return $roles[RoleModel::ROLE_HOT] ? true : false;
    }
    
    /**
     * 
     * @param type $uid
     * @param type $role_id
     * @param type $sts
     * @return boolean
     * @author zhangby
     */
      public function getRoleByUidAndRoleAndStatus($uid,$role_id=-1,$sts=-2){
        if(!$uid){
            return false;
        }
        // TODO 缓存
        $map['status'] =$sts;
        $map['uid'] = $uid;
        $map['role_id'] = $role_id;
        $data = $this->where($map)->find();
        return $data;
    }

}
