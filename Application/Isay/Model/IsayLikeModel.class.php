<?php

/**
 * 爱说->赞、踩
 */

namespace Isay\Model;

use Think\Model;

//require_once('./Application/Isay/Common/function.php');

class IsayLikeModel extends Model {

    protected $_validate = array(
        array('uid', 'require', '用户必须', self::MUST_VALIDATE),
        array('obj_id', 'require', '对象id必须', self::MUST_VALIDATE),
        array('obj_type', 'require', '点赞对象必须', self::MUST_VALIDATE),
    );
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
    );
    protected $_skey_like = 'isay_like_{id}'; // 点赞id缓存key
    protected $_skey_like_liked = 'isay_like_{obj_id}_{uid}_{obj_type}'; // 用户点赞特定微商圈id缓存key
    
    protected $_obj_type = array('isay' => '爱说', 'comment' => '评论');

    /**
     * 获取id缓存key
     * @param unknown $id
     */

    public function getCacheKeyMain($id) {
        return str_replace('{id}', $id, $this->_skey_like);
    }

    /**
     * 获取用户点赞某条爱说缓存key
     * @param unknown $id
     */
    public function getCacheKeyLiked($obj_id, $uid, $obj_type) {
        return str_replace(array('{obj_id}', '{uid}', '{obj_type}'), array($obj_id, $uid, $obj_type), $this->_skey_like_liked);
    }
    
    /**
     * 获取类型定义
     * @author dpj
     * @return multitype:string
     */
    public function getObjType(){
        return $this->_obj_type;
    }

    public function addLike($uid, $obj_id, $type, $obj_type) {
        //写入数据库
        $data = array('uid' => $uid, 'obj_id' => $obj_id, 'type' => $type, 'obj_type' => $obj_type);
        $data = $this->create($data);
        if (!$data)
            return false;
        $like_id = $this->add($data);

        //增加爱说点赞数量
        if ($obj_type == 'isay') {
            if ($type == 1) {
                D('Isay/Isay')->where(array('id' => $obj_id))->setInc('up');
            } elseif ($type == 2) {
                D('Isay/Isay')->where(array('id' => $obj_id))->setInc('down');
            }
            S(D('Isay/Isay')->getCacheKey($obj_id), null); // 删除爱说缓存
        }

        //增加爱说评论点赞数量
        if ($obj_type == 'comment') {
            if ($type == 1) {
                D('Isay/IsayComment')->where(array('id' => $obj_id))->setInc('up');
            } elseif ($type == 2) {
                D('Isay/IsayComment')->where(array('id' => $obj_id))->setInc('down');
            }
            S('isay_comment_' . $obj_id, null); // 删除爱说评论缓存
        }


        //返回点赞编号
        return $like_id;
    }

    public function deleteLike($like_id) {
        //获取爱说编号
        $like = $this->find($like_id);
        $obj_id = $like['obj_id'];


        //将点赞标记为已经删除
        D('Isay/IsayLike')->where(array('id' => $like_id))->delete();
        S($this->getCacheKeyMain($like_id), null); // 删除点赞缓存
        S($this->getCacheKeyLiked($like['obj_id'], $like['uid']), null);

        //更新爱说的点赞数量
        if ($like['obj_type'] == 'isay') {            
            if ($like['type'] == 1) {
                D('Isay/Isay')->where(array('id' => $obj_id))->setDec('up');
            } else if ($like['type'] == 2) {
                D('Isay/Isay')->where(array('id' => $obj_id))->setDec('down');
            }
            S(D('Isay/Isay')->getCacheKey($obj_id), null); // 删除爱说缓存
        }
        
         //更新爱说评论的点赞数量
        if ($like['obj_type'] == 'comment') {           
            if ($like['type'] == 1) {
                D('Isay/IsayComment')->where(array('id' => $obj_id))->setDec('up');
            } else if ($like['type'] == 2) {
                D('Isay/IsayComment')->where(array('id' => $obj_id))->setDec('down');
            }
           S('isay_comment_' . $obj_id, null); // 删除爱说评论缓存
        }
        
        //返回成功结果
        return true;
    }

    public function getLikeById($id) {
        $skey = $this->getCacheKeyMain($id);
        $like = S($skey);
        if (!$like) {
            $like = $this->find($id);
            S($skey, $like);
        }
        $like['can_delete'] = check_auth('Isay/Like/doDelLike', $like['uid']);
        $like['user'] = query_user(array('uid', 'nickname', 'avatar32', 'avatar64', 'avatar128', 'avatar256', 'avatar512', 'space_url', 'rank_link', 'score', 'title', 'isaycount', 'fans', 'following'), $like['uid']);
        return $like;
    }

    public function getLikeByOidAndUidAndObj($obj_id, $uid, $obj_type) {
        $skey = $this->getCacheKeyLiked($obj_id, $uid, $obj_type);
        $id = S($skey);
        if (!$id) {
            if ($id = $this->where(array('obj_id' => $obj_id, 'uid' => $uid, 'obj_type' => $obj_type))->getField('id')) {
                S($skey, $id);
            }
        }
        return $id;
    }
    
    /**
     * 根据主键获取信息
     * @author dpj
     * @param number $id
     */
    public function getById($id){
        $skey = $this->getCacheKeyMain($id);
        $like = S($skey);
        if (!$like) {
            $like = $this->find($id);
            S($skey, $like);
        }
        return $like;
    }

    /**
     * 检查是否点赞过
     */
    public function checkIsLiked($obj_id, $uid, $obj_type) {
        return $this->getLikeByOidAndUidAndObj($obj_id, $uid, $obj_type);
    }
    
    /**
     * 获取点赞状态
     * @author dpj
     * @param number $obj_id
     * @param number $uid
     * @param string $obj_type
     * @return number|Ambigous < 0:未赞/踩过 1:赞过 2:踩过>
     */
    public function getLikeStatus($obj_id, $uid, $obj_type){
        $likeid = $this->getLikeByOidAndUidAndObj($obj_id, $uid, $obj_type);
        if(!$likeid){
            return 0;
        }
        $like = $this->getById($likeid);
        return $like['type'];
    }

    public function getAllLike($obj_id, $obj_type) {

        $order = 'create_time asc';
        $like = $this->where(array('obj_id' => $obj_id, 'obj_type' => $obj_type))->order($order)->field('id')->select();
        $ids = getSubByKey($like, 'id');
        $list = array();
        foreach ($ids as $v) {
            $list[$v] = $this->getLikeById($v);
        }
        return $list;
    }

    public function getLikeList($obj_id, $obj_type, $page = 1, $rows = 36) {        
        list($list)=  $this->getLikeListRows(array('obj_id' => $obj_id, 'obj_type' => $obj_type, 'status' => 1),'create_time asc',$page,$rows);
        return $list;
    }
    
    public function getLikeListRows($map,$order='create_time DESC',$page=1,$rows=30){
        $count=$this->where($map)->count('id');
        $list=$this->where($map)->order($order)->page($page, $rows)->field('id')->select();
        $ids = getSubByKey($list, 'id');
        $data = array();
        foreach ($ids as $v) {
            $data[$v] = $this->getLikeById($v);
        }
        return array($data,$count);
    }

    // 继承Model.class.php
    public function getLikeListApi($param) {
        $list = $this->getList($param);
        $userModel = D('Common/User');
        foreach ($list as &$v) {
            $info = $userModel->query_user(array('uid', 'nickname'), $v['uid']);
            $v['uid'] = $info['uid'];
            $v['nickname'] = $info['nickname'];
        }
        unset($v);
        return $list;
    }

}
