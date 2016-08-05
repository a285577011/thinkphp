<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-8
 * Time: PM4:14
 */
namespace Forum\Model;
use Think\Model;

class ForumPointlikeModel extends Model
{

    protected $_auto = array(
            array(
                    'create_time',
                    NOW_TIME,
                    self::MODEL_INSERT
            )
    );

    public function exists ($uid, $post_id,$type)
    {
        $result = $this->where(
                array(
                        'uid' => $uid,
                        'flag_id' => $post_id,
                		'type'=>$type
                ))->find();
        return $result ? true : false;
    }

    public function addPointLike ($uid, $post_id,$type=1)
    {
        // 如果存在，就不添加了
        if ($this->exists($uid, $post_id,$type)) {
            return 0;
        }
        // 如果不存在，就添加到数据库
        $data = array(
                'uid' => $uid,
                'flag_id' => $post_id,
        		'type'=>$type
        );
        $data = $this->create($data);
        if (! $data)
            return false;
        switch ($type){
            case 1:
                D('ForumPost')->where(
                array(
                'id' => $post_id
                ))->setInc('point_like_count', 1);
                break;
            case 2:
                D('ForumPostReply')->where(
                array(
                'id' => $post_id
                ))->setInc('point_like_count', 1);
                break;
        }
        
        return $this->add($data);
    }

    public function removePointLike ($uid, $post_id,$type=1)
    {
        switch ($type){
            case 1:
                D('ForumPost')->where(
                        array(
                                'id' => $post_id
                        ))->setDec('point_like_count', 1);
                        break;
            case 2:
                D('ForumPostReply')->where(
                        array(
                                'id' => $post_id
                        ))->setDec('point_like_count', 1);
                    break;
        }
        return $this->where(
                array(
                        'uid' => $uid,
                        'flag_id' => $post_id,
                        'type'=>$type
                ))->delete();
    }
}
