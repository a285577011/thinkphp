<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Common\Model;

use Think\Model;


/**
 * 文档基础模型
 */
class MemberLevelModel extends Model
{
    /* 用户模型自动完成 */
    protected $_auto = array(
        array('create_time', NOW_TIME),
        array('update_time', NOW_TIME),
    );

    protected $_validate = array(

    );

    protected $insertField = 'nickname,sex,weixin,catid'; //新增数据时允许操作的字段
    protected $updateField = 'nickname,sex,weixin,catid,last_login_ip,login,update_time,last_login_role,show_role,status,tox_money,score,pos_province,pos_city,pos_district,pos_community'; //编辑数据时允许操作的字段

    /*缓存key设置*/
    protected $_skey = 'member_level'; // 全表缓存key

    /**
     * 获取主键缓存key
     * @param number $id
     */
    public function getCacheKey(){
        return $this->_skey;
    }
    
    /**
     * 获取全表数据
     */
    public function getLevel(){
        $skey = $this->getCacheKey();
        $data = S($skey);
        if(!$data){
            $data = $this->order('level')->getField('score,id');
            S($skey, $data);
        }
        return $data;
    }
}
