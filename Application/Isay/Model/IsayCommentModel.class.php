<?php

/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-10
 * Time: PM9:01
 */

namespace Isay\Model;

use Think\Model;

require_once('./Application/Isay/Common/function.php');

class IsayCommentModel extends Model {

    protected $_validate = array(
        array('content', '1,99999', '内容不能为空', self::EXISTS_VALIDATE, 'length'),
        array('content', '0,500', '内容太长', self::EXISTS_VALIDATE, 'length'),
        array('uid', '/^[1-9]\d*$/', '用户ID错误', self::MODEL_INSERT, 'regex'),
        array('obj_id', '/^[1-9]\d*$/', '对象ID错误', self::MODEL_INSERT, 'regex'),
        array('obj_type', array('isay', 'comment'), '对象类型不正确', self::MODEL_INSERT, 'in'),
    );
    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );
    
    /*缓存key*/
    private $_skey_main = 'isay_comment_{id}';
    private $_skey_message = 'isay_comment_message_{uid}'; // 新消息状态缓存
    
    /*评论对象类型*/
    protected $_obj_type = array('isay' => '爱说', 'comment' => '评论');

    /**
     * 主键缓存
     * @param number $id
     * @return mixed
     */
    public function getCacheKey($id) {
        return str_replace('{id}', $id, $this->_skey_main);
    }
    
    /**
     * 新消息状态缓存
     * @param number $uid
     * @return mixed
     */
    public function getCacheKeyMessage($uid){
        return str_replace('{uid}', $uid, $this->_skey_message);
    }
    
    /**
     * 获取类型定义
     * @author dpj
     * @return multitype:string
     */
    public function getObjType(){
        return $this->_obj_type;
    }

    /**
     * 发布评论
     * @param int $uid 用户id
     * @param int $obj_id 评论对象id(爱说的id或者评论的id)
     * @param string $content 评论内容
     * @param string $obj_type 评论对象类型
     * @return boolean|Ambigous <mixed, boolean, unknown, string>
     */
    public function addComment($uid, $obj_id, $content, $obj_type, $to_uid = 0) {
        $data = array('uid' => $uid, 'content' => $content, 'obj_id' => $obj_id, 'obj_type' => $obj_type, 'to_uid' => $to_uid);
        $data = $this->create($data);
        if (!$data)
            return false;

        // 对象信息
        if ( $obj_type == 'comment' ){
            $obj_info = $this->getComment($obj_id);
            if(!$obj_info || $obj_info['obj_type'] != 'isay'){
                $this->error = '对象信息不存在';
                return false;
            }
        }else{
            $obj_info = D('Isay/Isay')->getById($obj_id);
            if(!$obj_info){
                $this->error = '对象信息不存在';
                return false;
            }
        }

        //写入数据库
        $comment_id = $this->add($data);

        if ($obj_type == 'isay') {
            //增加爱说评论数量
            D('Isay/Isay')->where(array('id' => $obj_id))->setInc('comment');
            S(D('Isay/Isay')->getCacheKey($obj_id), null);
        } else {//增加回复评论数量
            $this->where(array('id' => $obj_id,'obj_type'=>'isay'))->setInc('comment');
            S($this->getCacheKey($obj_id), null);            
            if($to_uid) S($this->getCacheKeyMessage($to_uid), 1); // 设置新消息状态
        }

        //返回评论编号
        return $comment_id;
    }

    public function deleteComment($comment_id) {
        //获取爱说编号
        $comment = D('Isay/IsayComment')->find($comment_id);
        if ($comment['status'] == -1) {
            return false;
        }

        $map = array();
        $map['id'] = $comment_id;       

        //将评论标记为已经删除
        D('Isay/IsayComment')->where($map)->setField('status', -1);

        //减少爱说的评论数量
        $obj_id = $comment['obj_id'];
        D('Isay/Isay')->where(array('id' => $obj_id))->setDec('comment');
        S('isay_' . $obj_id, null);

        //减少爱说的评论回复数量
        if ($comment['obj_type']) {
            $obj_type = $comment['obj_type'];
            $this->where(array('id' => $obj_type))->setDec('comment');
            S($this->getCacheKey($obj_type), null);
        }
        
        //删除评论的评论
        list($childs)= $this->getCommentListByObjId($comment_id);      
        foreach ($childs as $v) {
            $this->deleteComment($v['id']);
        }

        //返回成功结果
        return true;
    }

    /**
     * 评论详情
     * @param int $id
     * @param number $uid
     * @return boolean|Ambigous <mixed, object>
     */
    public function getComment($id, $uid=0) {
        $comment = S($this->getCacheKey($id));
        if (!$comment) {
            $comment = $this->find($id);
            if(!$comment){
                return false;
            }
            $comment['content'] = $this->parseComment($comment['content']);
            S($this->getCacheKey($id), $comment);
        }
        !$uid && $uid = is_login();
        $comment['content'] = parse_at_users($comment['content'], true);
        $comment['can_like'] = check_dolike($id, 'comment', $uid);
        $comment['can_delete'] = check_auth('Isay/Comment/doDelComment', $comment['uid']);
        //$comment['user'] = query_user(array('uid', 'nickname', 'avatar32', 'avatar64', 'avatar128', 'avatar256', 'avatar512', 'space_url', 'rank_link', 'score', 'title', 'isaycount', 'fans', 'following'), $comment['uid']);
        return $comment;
    }
    
    /**
     * 通过主键获取信息
     * @author dpj
     * @param number $id
     */
    public function getById($id){
        $data = S($this->getCacheKey($id));
        if (!$data) {
            $data = $this->find($id);
            if(!$data){
                return false;
            }
            $data['content'] = $this->parseComment($data['content']);
            S($this->getCacheKey($id), $data);
        }
        return $data;
    }

    public function parseComment($content) {
        $content = shorten_white_space($content);
        $content = op_t($content, false);
        $content = parse_url_link($content);

        $content = parse_expression($content);

//        $content = parseIsayContent($content);
        return $content;
    }

    public function getCommentList($obj_id = FALSE, $status = -1, $page = 1, $rows = 10) {       
        $param = array();
        if ($obj_id !== FALSE) {
            $param['obj_id'] = $obj_id;
        }
        if ($status >= 0) {
            $param['status'] = $status;
        }
        if ($param) {
            $this->where($param);
        }

        $comment = $this->getCommentRows($param, $page, $rows); // $this->order($order)->page($page, $rows)->field('id')->select();


        $ids = getSubByKey($comment, 'id');
        $list = array();
        foreach ($ids as $v) {
            $list[$v] = $this->getComment($v);
        }
        return $list;
    }

    
    public function getCommentListByObjId($obj_id,$obj_type,$status = -1, $page = 1, $rows = 10) {      
        $order = modC('ISAY_COMMENT_ORDER', 0, 'ISAY') == 1 ? 'create_time asc' : 'create_time desc';
        $param = array();
        $param['obj_id'] = $obj_id;
        $param['obj_type']=$obj_type;
        if ($status >= 0) {
            $param['status'] = $status;
        }
        $count = $this->where($param)->count('id');
        $comment = $this->where($param)->order($order)->page($page, $rows)->field('id')->select();        

        $list=array();
        $ids = getSubByKey($comment, 'id');        
        foreach ($ids as $v) {
            $list[$v] = $this->getComment($v);
        }
        
        return array($list,$count);
    }

    public function getAllCommentByIsayId($obj_id) {

        $order = modC('COMMENT_ORDER', 0, 'ISAY') == 1 ? 'create_time asc' : 'create_time desc';
        $comment = $this->where(array('obj_id' => $obj_id, 'status' => 1,'obj_type'=>'isay'))->order($order)->field('id')->select();
        $ids = getSubByKey($comment, 'id');
        $list = array();
        foreach ($ids as $v) {
            $list[$v] = $this->getComment($v);
        }
        return $list;
    }

    public function getCommentListByIsayId($obj_id,$obj_type, $page = 1) {
        $order = modC('ISAY_COMMENT_ORDER', 0, 'ISAY') == 1 ? 'create_time asc' : 'create_time desc';
        $comment = $this->where(array('obj_id' => $obj_id, 'status' => 1,'obj_type'=>$obj_type))->order($order)->page($page, 10)->field('id')->select();

        $ids = getSubByKey($comment, 'id');
        $list = array();
        foreach ($ids as $v) {
            $list[$v] = $this->getComment($v);
        }
        return $list;
    }

    public function getCommentRows($map, $page = 1, $rows = 10) {
        $order = modC('ISAY_COMMENT_ORDER', 0, 'ISAY') == 1 ? 'create_time asc' : 'create_time desc';
        $comment = $this->where($map)->order($order)->page($page, $rows)->field('id')->select();

        $ids = getSubByKey($comment, 'id');
        $list = array();
        foreach ($ids as $v) {
            $list[$v] = $this->getComment($v);
        }
        return $list;
    }

    public function getCommentRowsCount($map) {
        $comment = $this->where($map)->count("id");
        return $comment;
    }
    
    /**
     * 类型检查
     * @param string $obj_type
     * @return boolean
     * 
     * @author dpj
     */
    public function checkType($obj_type){
        $types = $this->getObjType();
        if(!$types[$obj_type]){
            return false;
        }
        return true;
    }

}
