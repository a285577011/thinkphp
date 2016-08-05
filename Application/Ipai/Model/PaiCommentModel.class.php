<?php

namespace Ipai\Model;

use Think\Model;

/**
 * @author zhangby
 */
class PaiCommentModel extends Model {

    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
    );
    private $_cacheKey = 'pai_comment_{id}';
    protected $_validate = array(
    		array('title', '1,100', '标题在1-100个字符', 1,'length',1),
    		array('content', 'require', '请填写评价内容！',1),
    		array('attr_imgs', 'require', '请添加图片！',1),
    		array('evaluate', 'require', '请选择评论等级',1),
    		
    );
    public function getCacheKey($id) {
        return str_replace('{id}', $id, $this->_cacheKey);
    }

    public function getById($id) {
        if ($id > 0) {
            $data = S($this->getCacheKey($id));
            if (!empty($data))
                return $data;
            $data = $this->find($id);
            if ($data) {
                $data['imgs']=  explode(',',$data['attr_imgs'] );
                $data['imgs']= array_filter($data['imgs']);
                $data['user'] = query_user(array('uid', 'nickname','username','signature', 'level', 'catid', 'weixin','avatar128', 'avatar32', 'avatar64','space_url', 'rank_link', 'score', 'title', 'fans', 'following'), $data['uid']);
            }
            S($this->getCacheKey($id), $data, 60 * 60);
            return $data;
                        
        }
        return null;
    }

    public function getListByPage($map, $page = 1, $order = 'create_time desc', $field = "*", $r = 10) {
        $totalCount = $this->where($map)->count();
        $data = array();
        if ($totalCount) {
            $list = $this->where($map)->page($page, $r)->order($order)->field($field)->select();
            $ids = getSubByKey($list, 'id');
            foreach ($ids as $v) {
                $data[] = $this->getById($v, $field);
            }
        }
        return array($data, $totalCount);
    }
    /**
     * 添加晒单
     * @param unknown $data
     * @return \Think\mixed
     */
    public function addComent($data){
    	if(!$this->create($data)){
    		E($this->getError());
    	}
    	return $this->add($data);
     }

}
