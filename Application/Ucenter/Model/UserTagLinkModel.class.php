<?php

/**
 * 用户标签
 */

namespace Ucenter\Model;

use Think\Model;

class UserTagLinkModel extends Model {

    protected $userTagModel;

    public function _initialize() {
        $this->userTagModel = new UserTagModel();
    }

    /**
     * 获取用户标签列表
     * @param int $uid
     * @return null
     * 
     */
    public function getUserTag($uid = 0) {
        !$uid && $uid = is_login() ? is_login() : session('temp_login_uid');
        $tag_ids = $this->where(array('uid' => $uid))->getField('tags');
        if ($tag_ids != '') {
            //$tag_ids=str_replace('[','',$tag_ids);
            //$tag_ids=str_replace(']','',$tag_ids);
            $tag_ids = explode(',', $tag_ids);
            $tags = $this->userTagModel->where(array('id' => array('in', $tag_ids), 'status' => 1))->field('id,title')->order('sort desc')->select();
            if (count($tags)) {
                return $tags;
            }
        }
        return null;
    }

    /**
     * 编辑用户标签链接
     * @param string $tags
     * @return bool|mixed|null
     * 
     */
    public function editData($tags = '', $uid = 0) {
        !$uid && $uid = is_login() ? is_login() : session('temp_login_uid');
        if(!$uid){
            return false;
        }
        if ($tags != '') {
            $tags = explode(',', $tags);
            sort($tags);
            /* foreach($tags as &$tag){
              $tag='['.$tag.']';
              } */
            unset($tag);
            $tags = implode(',', $tags);
            if ($this->where(array('uid' => $uid))->count()) {
                $result = $this->saveData($tags, $uid);
            } else {
                $result = $this->addData($tags, $uid);
            }
        } else {
            $result = $this->where(array('uid' => $uid))->delete();
        }
        clean_query_user_cache($uid, 'tags');
        return $result;
    }

    public function saveData($tags = '', $uid = 0) {
        !$uid && $uid = is_login() ? is_login() : session('temp_login_uid');
        $result = $this->where(array('uid' => $uid))->setField('tags', $tags);
        clean_query_user_cache($uid, 'tags');
        return $result;
    }

    public function addData($tags, $uid = 0) {
        !$uid && $uid = is_login() ? is_login() : session('temp_login_uid');
        $data['tags'] = $tags;
        $data['uid'] = $uid;
        if ($this->where(array('uid' => $uid))->count()) {
            $result = $this->saveData($tags, $uid);
        } else {
            $result=$this->add($data);
        }
        clean_query_user_cache($uid, 'tags');
        return $result;
    }

    public function getListByMap($map) {
        $list = $this->where($map)->limit(999)->select();
        !count($list) && $list = array();
        return $list;
    }

}
