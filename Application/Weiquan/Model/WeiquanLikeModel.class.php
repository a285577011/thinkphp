<?php
/**
 * 微商圈->赞
 */

namespace Weiquan\Model;

use Think\Model;

require_once('./Application/Weiquan/Common/function.php');

class WeiquanLikeModel extends Model {

    protected $_validate = array(
        array('uid', 'require', '用户必须', self::MUST_VALIDATE),
        array('weiquan_id', 'require', '微商圈id必须', self::MUST_VALIDATE),
    );
    
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );

    protected $_skey_like = 'weiquan_like_{id}'; // 点赞id缓存key
    protected $_skey_like_liked = 'weiquan_like_{weiquan_id}_{uid}'; // 用户点赞特定微商圈id缓存key
    
    /**
     * 获取id缓存key
     * @param unknown $id
     */
    public function getCacheKeyMain($id){
        return str_replace('{id}', $id, $this->_skey_like);
    }
    
    /**
     * 获取用户点赞某条微商圈缓存key
     * @param unknown $id
     */
    public function getCacheKeyLiked($weiquan_id, $uid){
        return str_replace(array('{weiquan_id}','{uid}'), array($weiquan_id, $uid), $this->_skey_like_liked);
    }

    public function addLike($uid, $weiquan_id) {
        $weiquan_info = D('Weiquan/Weiquan')->getDetail($weiquan_id);
        if (!$weiquan_info)
            return false;
        
        //写入数据库
        $data = array('uid' => $uid, 'weiquan_id' => $weiquan_id);
        $data = $this->create($data);
        if (!$data)
            return false;
        $like_id = $this->add($data);
        if (!$like_id) return false;

        //增加微博点赞数量
        D('Weiquan/Weiquan')->where(array('id' => $weiquan_id))->setInc('like_count');

        // 增加新消息记录
        D('Weiquan/WeiquanMessage')->addMessage($weiquan_info['uid'], $weiquan_id, 1, $like_id);
        
        S(D('Weiquan/Weiquan')->getCacheKeyMain($weiquan_id), null); // 删除微商圈缓存
        //返回点赞编号
        return $like_id;
    }

    public function deleteLike($like_id) {
        //获取微博编号
        $like = $this->find($like_id);
        $weiquan_id = $like['weiquan_id'];

        //将点赞标记为已经删除
        D('Weiquan/WeiquanLike')->where(array('id' => $like_id))->delete();
        S($this->getCacheKeyMain($like_id), null); // 删除点赞缓存
        S($this->getCacheKeyLiked($like['weiquan_id'], $like['uid']), null);

        //减少微博的点赞数量
        D('Weiquan/Weiquan')->where(array('id' => $weiquan_id))->setDec('like_count');
        S(D('Weiquan/Weiquan')->getCacheKeyMain($weiquan_id), null); // 删除微商圈缓存
        //返回成功结果
        return true;
    }

    public function deleteLikeByWidAndUid($weiquan_id, $uid) {        
        $id = $this->getLikeByWidAndUid($weiquan_id, $uid);
        S($this->getCacheKeyLiked($weiquan_id, $uid), null);
       
        return $id ? $this->deleteLike($id) : false;
    }

    public function getLikeById($id) {
        $skey = $this->getCacheKeyMain($id);
        $like = S($skey);
        if (!$like) {
            $like = $this->find($id);
            S($skey, $like);
        }
        $like['can_delete'] = check_auth('Weiquan/Index/doDelLike', $like['uid']);
        $like['user'] = query_user(array('uid', 'nickname', 'avatar32', 'avatar64', 'avatar128', 'avatar256', 'avatar512', 'space_url', 'rank_link', 'score', 'title', 'weiquancount', 'fans', 'following','username'), $like['uid']);
        return $like;
    }

    public function getLikeByWidAndUid($weiquan_id, $uid) {
        $skey = $this->getCacheKeyLiked($weiquan_id, $uid);
        $id = S($skey);
        if (!$id) {
            if($id = $this->where(array('weiquan_id' => $weiquan_id, 'uid' => $uid))->getField('id')){
                S($skey, $id);
            }
        }
        return $id ;
    }
    
    /**
     * 检查是否点赞过
     */
    public function checkIsLiked($weiquan_id, $uid){
        return $this->getLikeByWidAndUid($weiquan_id, $uid);
    }

    public function getAllLike($weiquan_id) {

        $order = 'create_time asc';
        $like = $this->where(array('weiquan_id' => $weiquan_id))->order($order)->field('id')->select();
        $ids = getSubByKey($like, 'id');
        $list = array();
        foreach ($ids as $v) {
            $list[$v] = $this->getLikeById($v);
        }
        return $list;
    }

    public function getLikeList($weiquan_id, $page = 1,$count=36) {
        $order = 'create_time asc';
        $like = $this->where(array('weiquan_id' => $weiquan_id, 'status' => 1))->order($order)->page($page, $count)->field('id')->select();
        $ids = getSubByKey($like, 'id');
        $list = array();
        foreach ($ids as $v) {
            $list[$v] = $this->getLikeById($v);
        }
        return $list;
    }

    // 继承Model.class.php
    public function getLikeListApi($param) {
        $list = $this->getList($param);
        if($list){
            $userModel = D('Common/User');
            foreach ($list as &$v) {
                $info = $userModel->query_user(array('uid','nickname'),$v['uid']);
                $v['uid'] = $info['uid'];
                $v['nickname'] = $info['nickname'];
            }
            unset($v);
        }else{
            $list = null;
        }
        return $list;
    }

}
