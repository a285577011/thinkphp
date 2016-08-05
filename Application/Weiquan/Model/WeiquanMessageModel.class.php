<?php
/**
 * 微商圈消息
 * 用户点赞、评论会触发新消息
 * @author dpj
 * @version 1.0
 */

namespace Weiquan\Model;

use Think\Model;

require_once('./Application/Weiquan/Common/function.php');

class WeiquanMessageModel extends Model {

    protected $_skey_message = 'weiquan_message_{id}'; // 主键缓存key
    protected $_skey_message_count = 'weiquan_message_count_{uid}'; // 新消息数缓存key
    
    protected $_validate = array(
        array('uid', 'require', '用户必须', self::MUST_VALIDATE),
        array('weiquan_id', 'require', '微商圈id必须', self::MUST_VALIDATE),
        array('obj_id', 'require', '消息对象id必须', self::MUST_VALIDATE),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('update_time', NOW_TIME, self::MODEL_BOTH),
    );
    
    protected $_type = array( 1 => '点赞', 2 => '评论');
    
    /**
     * 获取主键缓存key
     * @param number $uid
     */
    public function getCacheKeyMain($id){
        return str_replace('{id}', $id, $this->_skey_message);
    }
    
    /**
     * 获取新消息数缓存key
     * @param number $uid
     */
    public function getCacheKeyCount($uid){
        return str_replace('{uid}', $uid, $this->_skey_message_count);
    }
    
    /**
     * 添加消息记录
     * @param int $uid 被评论或者赞的用户id
     * @param int $type 消息类型(赞，评论)
     * @param int $obj_id 消息对象id
     * @return boolean|Ambigous <mixed, boolean, unknown, string>
     */
    public function addMessage($uid, $weiquan_id, $type, $obj_id) {
        //写入数据库
        $data = array('uid' => $uid, 'weiquan_id' => $weiquan_id, 'type' => $type, 'obj_id' => $obj_id);
        $data = $this->create($data);
        if (!$data)
            return false;
        $id = $this->add($data);
	    
	    $uinfo = query_user(array('avatar'), $uid);
	    $num = S($this->getCacheKeyCount($uid));
	    $num += 1;
	    
	    // 消息推送，官方接口alias必须是字符串
	    D('Common/JPush')->pushMessAlias((string)$uid, '新消息', '新消息', array('type'=>1, 'num' => $num, 'avatar' => $uinfo['avatar']));

        S($this->getCacheKeyMain($uid), $num); // 删除消息数缓存
        S($this->getCacheKeyCount($uid), null); // 更新消息数缓存
        //返回点赞编号
        return $id;
    }

    /**
     * 批量添加消息记录
     */
    public function addAllMessage($uid, $weiquan_id) {
        //写入数据库
        $data = array('uid' => $uid, 'weiquan_id' => $weiquan_id);
        $data = $this->create($data);
        if (!$data)
            return false;
        $id = $this->add($data);

        S($this->getCacheKeyMain($uid), null); // 删除消息数缓存
        S($this->getCacheKeyCount($uid), null); // 删除消息数缓存
        //返回点赞编号
        return $id;
    }

    /**
     * 删除消息记录(消息设为已读)
     * @param int $uid
     */
    public function delMessage($uid) {
        if(!$uid){
            return false;
        }

        //将点赞标记为已读
        $data['status'] = 1;
        $data['update_time'] = time();
        $this->where(array('uid' => $uid))->save($data);

        S($this->getCacheKeyMain($uid), null); // 删除消息数缓存
        S($this->getCacheKeyCount($uid), null); // 删除消息数缓存
        //返回成功结果
        return true;
    }
    
    /**
     * 获取新消息数
     * @param int $uid
     */
    public function getCount($uid){
        $skey = $this->getCacheKeyCount($uid);
        $count = S($skey);
        if($count === false){ // 0的判断
            $map['uid'] = $uid;
            $map['status'] = 0;
            $count = $this->where($map)->count();
            S($skey, $count);
        }
        return $count;
    }
    
    /**
     * 获取最新一条消息
     * @param number $uid
     */
    public function getLastOne($uid){
        // TODO 缓存
        $map['uid'] = $uid;
        $map['status'] = 0;
        $message_info = $this->where($map)->order('id DESC')->find();
        if($message_info){
            $obj_info = array();
            if($message_info['type'] == 1){
                $message_info['obj_info'] = D('Weiquan/WeiquanLike')->getLikeById($message_info['obj_id']);
            }else{
                $message_info['obj_info'] = D('Weiquan/WeiquanComment')->getComment($message_info['obj_id']);
            }
        }
        return $message_info;
    }

    /**
     * 根据主键获取消息内容
     * @param int $id
     */
    public function getById($id) {
        $skey = $this->getCacheKeyMain($id);
        $info = S($skey);
        if (!$info) {
            $info = $this->find($id);
            S($skey, $info);
        }
        return $info;
    }

    // 继承Model.class.php
    public function getList($where, $field='*', $page = 1, $pagesize = 10, $order='create_time asc') {
        return $list = $this->where($where)->order($order)->page($page, $pagesize)->field($field)->select();
    }

}
