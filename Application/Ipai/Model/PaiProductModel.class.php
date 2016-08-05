<?php

namespace Ipai\Model;

use Common\Model\ContentHandlerModel;
use Think\Model;

class PaiProductModel extends Model {

    protected $_status = array(0 => '默认',  2 => '进行中', 3 => '流拍', 4 => '已开奖',5=>'开奖中');
    protected $_validate = array(
        array('pid', 'require', '商品名称不能为空'),
        array('use_time', 'require', '可用时间不能为空', 0),
    );
    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
    );

    /* 缓存key设置 */
    protected $_skey_main = 'pai_product_{id}'; // 主键
    protected $_skey_hot = 'pai_hot_{num}'; // 推荐n个

    /**
     * 获取主键缓存key
     * @param number $id
     */

    public function getCacheKeyMain($id) {
        return str_replace('{id}', $id, $this->_skey_main);
    }

    /**
     * 获取首页爱说轮播缓存key
     */
    public function getCacheKeyHot($num) {
        return str_replace('{num}', $num, $this->_skey_hot);
    }

    /**
     * 根据主键获取信息
     * @param number $id
     */
    public function getById($id) {
        $skey = $this->getCacheKeyMain($id);
        $data = S($skey);
        if (!$data) {
            $data = $this->find($id);
            S($skey, $data);
        }
        if ($data) {
            $data['user'] = query_user(array('uid', 'key', 'catid', 'nickname', 'level', 'tags', 'weixin', 'avatar', 'avatar32', 'avatar64', 'avatar128', 'avatar256', 'avatar512', 'space_url', 'rank_link', 'score', 'title', 'fans', 'following'), $data['uid']);
        }

        return $data;
    }

    public function getOne($where) {
        $result = $this->where($where)->find();
        return $result;
    }

    public function addOrSave($where, $data) {
        $rs = FALSE;
        $id = 0;
        if ($where) {
            $id = isset($where['id']) ? $where['id'] : 0;
            $rs = $this->where($where)->save($data);
        } else {
            $rs = $this->add($data);
        }

        S($this->getCacheKeyMain($id), null);
        $this->upSurplusNum($id);
        return $rs;
    }

    //更新剩余人数
    public function upSurplusNum($id) {
        $row = $this->getById($id);
        if (!$row)
            return 0;
        $rs = $this->where(array('id' => $id))->save(array('surplus_num' => $row['need_num'] - $row['join_num']));
        S($this->getCacheKeyMain($id), null);
        $this->autoCheckStatusAndOverTime($id);
        return $rs;
    }

    /**
     * 获取爱拍完整信息
     * @param number $id 主键id
     * @param array $fields 字段
     */
    public function getDetail($id) {
        $data = $this->getById($id);
        if (!$data) {
            return null;
        }
        $data['productinfo'] = D('Ipai/PaiProductCommon')->getById($data['pid']);
        if ($data['productinfo']) {
            $contentHandler = new ContentHandlerModel();
            $data['productinfo']['content'] = $contentHandler->displayHtmlContent($data['productinfo']['content']);
        }
        return $data;
    }

    /**
     * 爱拍列表，继承基类
     * @param array $param
     */
    public function getIpaiList($param) {
        $list = $this->getList($param);
        if ($list) {
            foreach ($list as $k => &$v) {
                $info = $this->getDetail($v);
                $v = $info;
            }
        }

        return $list;
    }

    public function getByIdAndUid($id, $uid) {
        $row = $this->getDetail($id);
        if ($row && $row['uid'] == $uid) {
            return $row;
        }
        return NULL;
    }

    public function getListByPage($map, $page = 1, $order = 'create_time desc', $field = "*", $r = 20) {
        $totalCount = $this->where($map)->count();
        $data = array();
        if ($totalCount) {
            $list = $this->where($map)->page($page, $r)->order($order)->field($field)->select();                     
            $ids = getSubByKey($list, 'id');
            foreach ($ids as $v) {
                $data[$v] = $this->getDetail($v);
            }
        }
        return array($data, $totalCount);
    }

    /**
     * 首页n条爱拍
     * @param number $num
     */
    public function getIpaiHot($num = 8) {
        $skey = $this->getCacheKeyHot($num);
        $data = S($skey);
        if (!$data) {
            // TODO 暂时随机取 
            $param['where']['status'] = 2;
            $param['limit'] = $num;
            $param['order'] = 'rand()';
            $data = $this->getList($param);
            S($skey, $data, 600); // 缓存十分钟
        }
        foreach ($data as $k => &$v) {
            $info = $this->getDetail($v);
            $v = $info;
        }
        return $data;
    }

    /**
     * 根据主键更新人数
     * @param number $id
     */
    public function countNum($id, $num) {
        $this->where(array('id' => $id))->setInc("join_num", $num);
        S($this->getCacheKeyMain($id), null);
        $this->upSurplusNum($id);
    }

    /**
     * 取得爱拍（没有缓存）
     * @param type $id
     * @return type
     */
    public function getByIdNoCache($id) {
        $data = $this->find($id);
        $data['productinfo'] = D('PaiProductCommon')->getById($data['pid']);
        if ($data && $data['productinfo']) {
            $data['productinfo']['imgs'] = explode(',', $data['productinfo']['attr_imgs']);
            $contentHandler = new ContentHandlerModel();
            $data['productinfo']['content'] = $contentHandler->displayHtmlContent($data['productinfo']['content']);
        }
        return $data;
    }

    /**
     * 如果库存数量小于等于0则自动更新STATUS和Over_time字段为开奖进行中
     */
    public function autoCheckStatusAndOverTime($pid) {
        $id = intval($pid);
        $time = msectime();
        $sql = "UPDATE __PREFIX__pai_product SET status=5,over_time=$time WHERE id=$id AND  surplus_num<=0 AND status=2 AND over_time<=0";
        $rs = $this->execute($sql);
        if ($rs) {
            S($this->getCacheKeyMain($id), null);
            $this->upOpenTime($id,$time);
        }
        return $rs;
    }

    /**
     * 更新开奖时间
     * @param type $over_time
     * @return type
     */
    public function upOpenTime($id,$over_time) {      
        $open_time = get_open_time($over_time);
        $rs = $this->where(array('id' => $id, 'open_time' => array('elt', 0)))->save(array('open_time' => $open_time)); //echo $this->getLastSql();exit;
        S($this->getCacheKeyMain($id), null);
        return $rs;
    }

    /**
     * 增加爱拍浏览量
     * @param type $id
     */
    public function addViewCount($id) {
        $this->where(array('id' => $id))->setInc("view_count", 1);
    }

    /**
     * 链表查询
     * @param type $where
     * @param type $order
     * @param type $page
     * @param type $r
     * @author zhangby
     */
    public function getListLinkProductCommonByPage($where, $order = 'create_time desc', $page = 1, $r = 10) {
        $offset = $page <= 1 ? 0 : ($page * $r - $r - 1);
        $field = "SELECT p.*,c.*,c.create_time as pc_create_time,c.uid as pc_uid,c.id as pc_id";
        $sql = " FROM __PREFIX__pai_product AS p LEFT JOIN __PREFIX__pai_product_common AS c ON p.pid=c.id WHERE $where ORDER BY $order ";
        $limit = " LIMIT $offset,$r";

        $totalCount = $this->query("SELECT COUNT(p.id) AS `count` $sql")[0]['count'];
        $rows = $this->query($field . $sql . $limit);
        return array($rows, $totalCount);
    }

    public function getStatus() {
        return $this->_status;
    }
    /**
     * 改变晒单状态
     * @param unknown $id
     * @param unknown $share
     * @authorhuangyy
     */
    public function changeShareById($id,$share){
    	S($this->getCacheKeyMain($id), null);
    	return $this->where(array('id'=>$id))->save(array('share'=>$share));
    }

}
