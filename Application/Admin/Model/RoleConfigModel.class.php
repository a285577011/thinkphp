<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-10
 * Time: 下午3:27
 * 
 */

namespace Admin\Model;

use Think\Model;

class RoleConfigModel extends Model
{

    public function addData($data){
        $data=$this->create($data);
        if(!$data) return false;
        $data['update_time']=time();
        $result=$this->add($data);
        return $result;
    }

    public function saveData($map=array(),$data=array()){
        $data['update_time']=time();
        $result=$this->where($map)->save($data);
        return $result;
    }

    public function deleteData($map){
        $result=$this->where($map)->delete();
        return $result;
    }
} 