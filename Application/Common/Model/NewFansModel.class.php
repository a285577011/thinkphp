<?php
/**
 * 新增粉丝
 */
namespace Common\Model;

use Think\Model;

class NewFansModel extends Model {

    /*缓存key设置*/
    protected $_skey_count = 'new_fans_count_{uid}'; // 新粉丝数
    protected $_skey_last = 'new_fans_last_{uid}'; // 最新粉丝
    
    /**
     * 获取最新粉丝数缓存key
     * @param number $id
     */
    public function getCacheKeyCount($uid){
        return str_replace('{uid}', $uid, $this->_skey_count);
    }
    
    /**
     * 获取最新粉丝缓存key
     * @param number $id
     */
    public function getCacheKeyNewest($uid){
        return str_replace('{uid}', $uid, $this->_skey_last);
    }
	
	/**
	 * 添加新粉丝记录
	 * @param number $uid_follow 谁关注
	 * @param number $follow_uid 关注谁
	 */
	public function addNewFans($uid_follow, $follow_uid){
	    $data = array();
	    $data['uid_follow'] = $uid_follow;
	    $data['follow_uid'] = $follow_uid;
	    $data['create_time'] = time();
	    
	    $uinfo = query_user(array('avatar'), $uid_follow);
	    $num = S($this->getNewFansCount($follow_uid));
	    $num += 1;
	    
	    // 消息推送，官方接口alias必须是字符串 // TODO 队列
	    D('Common/JPush')->pushMessAlias((string)$follow_uid, '新的关注', '新的关注', array('type'=>2, 'num' => $num, 'avatar' => $uinfo['avatar']));
	    
	    S($this->getCacheKeyCount($follow_uid), $num);  // 更新新粉丝数缓存
	    S($this->getCacheKeyNewest($follow_uid), null);  // 删除最新粉丝缓存
	    return $this->add($data);
	}
	
	/**
	 * 删除新粉丝记录
	 * @param number $uid_follow 谁关注
	 * @param number $follow_uid 关注谁
	 */
	public function delNewFans($uid_follow, $follow_uid){
	    $map = array();
	    $uid_follow && $map['uid_follow'] = $uid_follow;
	    $map['follow_uid'] = $follow_uid;
	    S($this->getCacheKeyCount($follow_uid), null);  // 删除粉丝总数缓存
	    S($this->getCacheKeyNewest($follow_uid), null);  // 删除最新粉丝缓存
	    return $this->where($map)->delete();
	}
	
	/**
	 * 清空新粉丝记录
	 * @param number $uid
	 */
	public function clearNewFans($uid){
	    return $this->delNewFans(0, $uid);
	}
	
	/**
	 * 获取新增粉丝总数
	 * @param number $uid 被关注者uid
	 */
	public function getNewFansCount($uid){
	    $skey = $this->getCacheKeyCount($uid);
	    $count = S($skey);
	    if($count === false){
	        $count = $this->where(array ( 'follow_uid' => $uid ))->count();
	        S($skey, $count);
	    }
	    return $count;
	}
	
	/**
	 * 获取最新粉丝
	 * @param number $uid
	 */
	public function getLastNewFans($uid){
	    $skey = $this->getCacheKeyNewest($uid);
	    $uid_follow = S($skey);
	    if($uid_follow === false){
	        $uid_follow = $this->where(array ( 'follow_uid' => $uid ))->order('create_time DESC')->getField('uid_follow');
	        S($skey, $uid);
	    }
	    return $uid_follow;
	}
	
	/**
	 * 获取新增粉丝列表
	 * @param number $uid 被关注者uid
	 */
	public function getNewFansList($uid, $param = array()){
		$param['where']['follow_uid'] = $uid;
		$param['field'] = 'uid_follow';
		
		// 不分页了
		//!isset($param['page']) && $param['page'] = 1;
		//!isset($param['limit']) && $param['limit'] = 10;
		
	    $data = $this->getList( $param );
	    foreach($data as $k => $v){
	        $list[] = $v['uid_follow'];
	    }
	    
	    return $list;
	}
}