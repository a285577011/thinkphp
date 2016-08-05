<?php

namespace Ipai\Model;

use Think\Model;

class PaiOrderModel extends Model {

    private $_cacheKey = 'pai_order_{id}';

    public function getCacheKey($id) {
        return str_replace('{id}', $id, $this->_cacheKey);
    }

    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
    );

    public function getById($id, $field = "*") {
        if ($id > 0) {
            $data = S($this->getCacheKey($id));
            if (!empty($data))
                return $data;
            $data = $this->field($field)->find($id);
            if ($data) {
                $data['user'] = query_user(array('uid', 'nickname', 'level', 'tags', 'weixin', 'avatar32', 'avatar64', 'avatar128', 'avatar256', 'avatar512', 'space_url', 'rank_link', 'score', 'title', 'fans', 'following'), $data['uid']);
                $codes = D('Iapi/PaiCode')->getById($data['cid']);
                $data['codes'] = $codes ? $codes['codes'] : null;
            }
            S($this->getCacheKey($id), $data, 60 * 60);
            return $data;
        }
        return null;
    }

    public function getListByUidAndPid($uid, $pid) {
        $totalCount = $this->where(array('uid' => $uid, 'pid' => $pid))->count();
        $data = array();
        if ($totalCount) {
            $list = $this->where(array('uid' => $uid, 'pid' => $pid))->order('create_time DESC')->field('id')->select();
            $ids = getSubByKey($list, 'id');
            foreach ($ids as $v) {
                $data[$v] = $this->getById($v);
            }
        }
        return $data;
    }

    public function getListByPage($map, $page = 1, $order = 'create_time desc', $field = "*", $r = 15) {
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
     * 根据条件取得num之和
     * @param type $where
     * @return type
     */
    public function getSumBuyNum($where) {
        return $this->where($where)->sum('num');
    }

    /**
     * 
     * @param type $where
     * @param type $data
     * @author zhangby
     */
    public function upData($where, $data) {
        $rs = $this->where($where)->save($data);
        if (isset($where['id'])) {
            S($this->getCacheKey($where['id']), NULL);
        }
        return $rs;
    }

    public function getOrderLinkCodeList($where, $limit = '', $offset = 0) {
        $str_limit = '';
        if ($limit) {
            $str_limit.=$offset ? " LIMIT $offset,$limit " : " LIMIT $limit ";
        }

        $sql = "SELECT o.id,o.uid,o.sno,o.pid,o.cid,c.codes FROM __PREFIX__pai_order AS o LEFT JOIN __PREFIX__pai_code AS c ON o.cid=c.id WHERE $where $str_limit";
        $rows = $this->query($sql);
        return $rows;
    }

    /**
     * 链表查询
     * @param type $where
     * @param type $limit
     * @param type $offset
     * @return type
     */
    public function getOrderLinkProductList($where,$page=1, $r = 10) {
        $offset = $page <= 1 ? 0 : ($page * $r - $r - 1);        
        $str_limit=" LIMIT $offset,$r " ; 
        
        $totalCount = $this->query("SELECT COUNT(o.id) AS `count` FROM __PREFIX__pai_order AS o LEFT JOIN __PREFIX__pai_product AS p ON o.pid=p.id WHERE $where")[0]['count'];
        $sql = "SELECT o.id,o.uid,o.sno,o.pid,o.cid,o.create_time,o.num FROM __PREFIX__pai_order AS o LEFT JOIN __PREFIX__pai_product AS p ON o.pid=p.id WHERE $where";
        $rows = $this->query($sql . " ORDER BY o.create_time DESC $str_limit");
        return array($rows,$totalCount);
    }

}
