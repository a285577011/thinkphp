<?php
/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-8
 * Time: PM4:14
 */
namespace Forum\Model;
use Think\Model;
use Think\Log;

class ForumBookmarkModel extends Model
{

    protected $_auto = array(
            array(
                    'create_time',
                    NOW_TIME,
                    self::MODEL_INSERT
            )
    );

    public function exists ($uid, $post_id)
    {
        $result = $this->where(
                array(
                        'uid' => $uid,
                        'post_id' => $post_id
                ))->find();
        return $result ? true : false;
    }

    public function addBookmark ($uid, $post_id)
    {
        // 如果存在，就不添加了
        if ($this->exists($uid, $post_id)) {
            return 0;
        }
        // 如果不存在，就添加到数据库
        $data = array(
                'uid' => $uid,
                'post_id' => $post_id
        );
        $data = $this->create($data);
        if (! $data)
            return false;
        
        D('ForumPost')->where(
                array(
                        'id' => $post_id
                ))->setInc('collect_count', 1);
        
        return $this->add($data);
    }

    public function removeBookmark ($uid, $post_id)
    {
        D('ForumPost')->where(
                array(
                        'id' => $post_id
                ))->setDec('collect_count', 1);
        return $this->where(
                array(
                        'uid' => $uid,
                        'post_id' => $post_id
                ))->delete();
    }
}
