<?php
/**
 * 微商圈评论/回复
 */

namespace Weiquan\Model;

use Think\Model;

require_once('./Application/Weiquan/Common/function.php');

class WeiquanCommentModel extends Model
{
    protected $_validate = array(
        array('uid', 'require', '用户必须', self::EXISTS_VALIDATE),
        array('weiquan_id', 'require', '微商圈id必须', self::EXISTS_VALIDATE),
        array('content', '1,99999', '内容不能为空', self::EXISTS_VALIDATE, 'length'),
        array('content', '0,500', '内容太长', self::EXISTS_VALIDATE, 'length'),
    );

    protected $_auto = array(
        array('create_time', NOW_TIME, self::MODEL_INSERT),
        array('status', '1', self::MODEL_INSERT),
    );

    protected $_skey_comment = 'weiquan_comment_{id}'; // 主键缓存key
    
    /**
     * 获取id缓存key
     * @param unknown $id
     */
    public function getCacheKeyMain($id){
        return str_replace('{id}', $id, $this->_skey_comment);
    }

    public function addComment($uid, $weiquan_id, $content, $to_comment_id = 0)
    {
        $weiquan_info = D('Weiquan/Weiquan')->getDetail($weiquan_id);
        if (!$weiquan_info)
            return false;
        
        //写入数据库
        $data['uid'] = $uid;
        $data['content'] = $content;
        $data['weiquan_id'] = $weiquan_id;
        if($to_comment_id){
            $to_comment_info = $this->getById($to_comment_id);
            $to_comment_info && $data['to_comment_id'] = $to_comment_id;
            $to_comment_info && $data['to_uid'] = $to_comment_info['uid'];
        }
        $data = $this->create($data);
        if (!$data) return false;
        
        $comment_id = $this->add($data);
        if (!$comment_id) return false;

        //增加微博评论数量
        D('Weiquan/Weiquan')->where(array('id' => $weiquan_id))->setInc('comment_count');
        
        // 不是评论自己，增加新消息记录
        if($uid != $weiquan_info['uid']){
            D('Weiquan/WeiquanMessage')->addMessage($weiquan_info['uid'], $weiquan_id, 2, $comment_id);
        }

        S(D('Weiquan/Weiquan')->getCacheKeyMain($weiquan_id), null);
        //返回评论编号
        return $comment_id;
    }

    public function deleteComment($comment_id, $uid = 0)
    {
        !$uid && $uid = is_login();
        //获取微博编号
        $comment = D('Weiquan/WeiquanComment')->find($comment_id);
        if ($comment['status'] == -1 || $comment['uid'] != $uid) {
            return false;
        }
        $weiquan_id = $comment['weiquan_id'];

        //将评论标记为已经删除
        D('Weiquan/WeiquanComment')->where(array('id' => $comment_id))->setField('status', -1);

        //减少微博的评论数量
        D('Weiquan/Weiquan')->where(array('id' => $weiquan_id))->setDec('comment_count');
        S(D('Weiquan/Weiquan')->getCacheKeyMain($weiquan_id), null);
        //返回成功结果
        return true;
    }
    
    /**
     * 根据id获取
     * @param number $id
     * @author dpj
     */
    public function getById($id){
        $skey = $this->getCacheKeyMain($id);
        $data = S($skey);
        if (!$data) {
            $data = $this->find($id);
            $data && $data['content'] = $this->parseComment($data['content']);
            S($skey, $data);
        }
        return $data;
    }

    public function getComment($id)
    {
        $comment = $this->getById($id);
        if(!$comment){
            return false;
        }
        $comment['content']= parse_at_users( $comment['content'], true);;
        $comment['can_delete'] = check_auth('Weiquan/Index/doDelComment', $comment['uid']);
        $comment['user'] = query_user(array('uid', 'nickname', 'avatar32', 'avatar64', 'avatar128', 'avatar256', 'avatar512', 'space_url', 'rank_link', 'score', 'title', 'weiquancount', 'fans', 'following'), $comment['uid']);
        return $comment;

    }

    public function parseComment($content)
    {
        $content = shorten_white_space($content);
        $content = op_t($content, false);
        $content = parse_url_link($content);

        $content = parse_expression($content);

//        $content = parseWeiboContent($content);
        return $content;
    }


    public function getAllComment($weiquan_id)
    {

        $order = modC('COMMENT_ORDER', 0, 'WEIQUAN') == 1 ? 'create_time asc' : 'create_time desc';
        $comment = $this->where(array('weiquan_id' => $weiquan_id, 'status' => 1))->order($order)->field('id')->select();
        $ids = getSubByKey($comment, 'id');
        $list = array();
        foreach ($ids as $v) {
            $list[$v] = $this->getComment($v);
        }
        return $list;

    }

    /**
     * 微圈列表
     * @param unknown $weiquan_id
     * @return multitype:Ambigous <mixed, object>
     */
    public function getCommentList($weiquan_id, $page = -1)
    {

        $order = modC('COMMENT_ORDER', 0, 'WEIQUAN') == 1 ? 'create_time asc' : 'create_time desc';
        $this->where(array('weiquan_id' => $weiquan_id, 'status' => 1));
        $this->order($order);        
        if($page>0){
            $this->page($page, 10);
        }
        $this->field('id');        
        $comment = $this->select();
        $ids = getSubByKey($comment, 'id');
        $list = array();
        foreach ($ids as $v) {
            $list[$v] = $this->getComment($v);
        }
        return $list;

    }
    
    /**
     * 微圈列表(API)
     * @param unknown $param
     */
    public function getCommentListApi($param) {
        $list = $this->getList($param);
        if($list){
            $userModel = D('Common/User');
            foreach ($list as &$v) {
                $info = $userModel->query_user(array('uid','nickname'),$v['uid']);
                $v['nickname'] = $info['nickname'];
                $v['content'] = $v['content'];
                if($v['to_uid']){
                    $info = $userModel->query_user(array('uid','nickname'),$v['to_uid']);
                    $v['to_nickname'] = $info['nickname'];
                }else{
                    $v['to_nickname'] = '';
                }
            }
            unset($v);
        }else{
            $list = null;
        }
        return $list;
    }
	
    public function getCommentListIds($weiquan_id, $page = -1)
    {

        $order = modC('COMMENT_ORDER', 0, 'WEIQUAN') == 1 ? 'create_time asc' : 'create_time desc';
        $this->where(array('weiquan_id' => $weiquan_id, 'status' => 1));
        $this->order($order);        
        if($page>0){
            $this->page($page, 10);
        }
        $this->field('id');        
        $comment = $this->select();
        $ids = getSubByKey($comment, 'id');      
        return $ids;

    }
}