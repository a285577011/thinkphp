<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-4-27
 * Time: 下午1:22
 * 
 */

namespace Isay\Model;

use Think\Model;
require_once('./Application/Isay/Common/function.php');
class IsayModel extends Model {

    private $_cacheKey = 'isay_{id}';
    protected $_skey_hot = 'isay_hot_{num}'; // 首页推荐n个

    public function getCacheKey($id) {
        return str_replace('{id}', $id, $this->_cacheKey);
    }
    
    /**
     * 获取首页爱说轮播缓存key
     */
    public function getCacheKeyHot($num){
        return str_replace('{num}', $num, $this->_skey_hot);
    }

    public function editData($data) {
        if (!mb_strlen($data['description'], 'utf-8')) {
            $data['description'] = msubstr(op_t($data['content']), 0, 200);
        }
        $detail['content'] = $data['content'];
        $detail['template'] = $data['template'];
        $data['reason'] = '';
        if ($data['id']) {
            $data['update_time'] = time();
            $res = $this->save($data);
            $detail['isay_id'] = $data['id'];
        } else {
            $data['create_time'] = $data['update_time'] = time();           
            $res = $this->add($data);
            action_log('add_isay', 'Isay', $res, is_login());
            $detail['isay_id'] = $res;
        }
        if ($res) {
            D('Isay/IsayDetail')->editData($detail);
        }
        return $res;
    }

    public function getListByPage($map, $page = 1, $order = 'update_time desc',$field="*", $r = 20) {
        $totalCount = $this->where($map)->count();
        $data = array();
        if ($totalCount) {
            $list = $this->where($map)->page($page, $r)->order($order)->field($field)->select();
            $ids = getSubByKey($list, 'id');          
            foreach ($ids as $v) {
                $data[$v] = $this->getData($v,$field);
            }
           
        }
        return array($data, $totalCount);
    }

    /**
     * @param unknown $map
     * @param string $order
     * @param number $limit
     * @param string $field
     */
    public function getIsayList($map, $order = 'view desc', $limit = 5, $field = '*') {
        $lists = $this->where($map)->order($order)->field($field)->limit($limit)->select();
        return $lists;
    }

    public function setDead($ids) {
        !is_array($ids) && $ids = explode(',', $ids);
        $map['id'] = array('in', $ids);
        $res = $this->where($map)->setField('dead_line', time());
        return $res;
    }

    public function getData($id,$field="*") {
        $data = S($this->getCacheKey($id));
        if (!empty($data))
            return $data;

        if ($id > 0) {
            $map['id'] = $id;
            $data = $this->where($map)->field($field)->find(); // TODO 不可传入$field 请参考getById()
            if ($data) {
                $data['detail'] = D('Isay/IsayDetail')->getData($id);
            }
            $data['can_like']=check_dolike($id,'isay',  is_login()); // TODO 此数据不可缓存
            S($this->getCacheKey($id), $data, 60 * 60);
            return $data;
        }
        return null;
    }
    
    /**
     * 一般模型里都有这个方法，统一用这个名字
     * @param number $id 主键id
     * @return Ambigous <NULL, unknown, mixed, object>
     */
    public function getById($id){
        if ($id > 0) {
            $data = S($this->getCacheKey($id));
            if (!empty($data))
                return $data;
            $data = $this->find($id);
            if ($data) {
                $data['detail'] = D('Isay/IsayDetail')->getData($id);
            }
            S($this->getCacheKey($id), $data, 60 * 60);
            return $data;
        }
        return null;
    }

    /**
     * 获取推荐位数据列表
     * @param $pos 推荐位 1-系统首页，2-推荐阅读，4-本类推荐
     * @param null $category
     * @param $limit
     * @param bool $field
     * @return mixed
     * 
     */
    public function position($pos, $category = null, $limit = 5, $field = true, $order = 'sort desc,view desc') {
        $map = $this->listMap($category, 1, $pos);
        $res = $this->field($field)->where($map)->order($order)->limit($limit)->select();
        /* 读取数据 */
        return $res;
    }

    /**
     * 设置where查询条件
     * @param  number $category 分类ID
     * @param  number $pos 推荐位
     * @param  integer $status 状态
     * @return array             查询条件
     */
    private function listMap($category, $status = 1, $pos = null) {
        /* 设置状态 */
        $map = array('status' => $status);

        /* 设置分类 */
        if (!is_null($category)) {
            $cates = D('Isay/IsayCategory')->getCategoryList(array('pid' => $category, 'status' => 1));
            $cates = array_column($cates, 'id');
            $map['category'] = array('in', array_merge(array($category), $cates));
        }
        $map['dead_line'] = array('gt', time());

        /* 设置推荐位 */
        if (is_numeric($pos)) {
            $map[] = "position & {$pos} = {$pos}";
        }

        return $map;
    }
    
    /**
     * 随机n条爱说
     * @param number $num
     * @return Ambigous <\Isay\Model\Ambigous, mixed, object>
     */
    public function getIsayHot($num = 4) {
        $skey = $this->getCacheKeyHot($num);
        $data = S($skey);
        if(!$data){
            // TODO 暂时随机取
            $param['where']['status'] = 1;
            $param['limit'] = $num;
            $param['order'] = 'rand()';
            $param['field'] = 'id,title';
            $data = $this->getList($param);
            S($skey, $data, 600); // 缓存十分钟
        }
        return $data;
    }

}
