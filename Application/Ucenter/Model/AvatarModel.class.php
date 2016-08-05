<?php
namespace Ucenter\Model;
use Think\Model;

class AvatarModel extends Model
{
    protected $_validate = array(
        array('uid', '/^[1-9]\d*$/', '用户ID错误', self::MODEL_INSERT, 'regex'),
        array('path', 'require', '缺少图片对象')
    );

    var $_skey_uid = 'avatar_{uid}';
    
    public function getCacheKeyUid($uid) {
        return str_replace('{uid}', $uid, $this->_skey_uid);
    }
    
    /**
     * 
     * @param unknown $where
     */
    public function getByUid($uid) {
        $skey = $this->getCacheKeyUid($uid);
        $data = S($skey);
        if(!$data){
            $data = $this->where(array('uid' => $uid))->find();
            S($skey, $data);
        }
    	return $data;
    }
    
    /**
     * 保存头像
     * @param array $data
     */
    public function saveAvatar($data){
        $data['driver'] = modC('PICTURE_UPLOAD_DRIVER','local','config');
        $data['status'] = 1;
        $data['is_temp'] = 0;
        $data['create_time'] = time();
        $data = $this->create($data);
        if(!$data){
            return false;
        }
        $info = $this->getByUid($data['uid']);
        if($info){
            $res = $this->where(array('uid'=>$data['uid']))->save($data);
        }else{
            $res = $this->add($data);
        }
        S($this->getCacheKeyUid($data['uid']), null);
        clean_query_user_cache($data['uid'], array('avatar', 'avatar256', 'avatar128', 'avatar64', 'avatar32', 'avatar512'));
        return $res;
    }
    /**
     * 检查头像
     */
    public function checkAvatar($uid,$imgId){
        return $this->where(array('uid'=>$uid,'path'=>$imgId,'status'=>1))->find();
    }
}