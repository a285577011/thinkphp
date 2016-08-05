<?php

namespace Ipai\Model;

use Think\Model;

class PaiProductCommonModel extends Model {

    protected $_skey = 'product_common_';
    protected $_type = array(1 => '商品', 0 => '非商品');
    protected $_auditStatus = array(0 => '审核中', 1 => '通过', 2 => '拒绝');
    protected $_releaseStatus = array(0 => '未发布', 1 => '已发布');
    protected $_validate = array(
        /* 验证商品名 */
        array('content', '1,99999', '内容不能为空', self::EXISTS_VALIDATE, 'length'),
        array('name', '3,100', '商品名称长度不合法', self::EXISTS_VALIDATE, 'length'),
        array('release_num', 'number', '发布只允许填数字', 1),
        array('price', 'checkMoney', '原价只允许填数字', 1, 'callback'),
        array('price_platform', 'checkMoney', '平台价只允许填数字', 0, 'callback'),
        array('type_second', 'number', '发布类型不能为空', 1),
        array('attr_img', 'require', '请上传图片', 0),
        array('reservation_msg', 'require', '预约提示不能为空', 0),
        array('contact', 'require', '联系方式不能为空', 0),
        array('use_rules', 'require', '使用规则不能为空', 0),
        array('server_address', 'require', '服务位置不能为空'),
        array('added_type', 'require', '定时上架不能为空', 0),
        array('added_timing', 'require', '上架定时不能为空', 0),
//        array('pos_province', 'require', '上架定时不能为空', 0),
//        array('pos_city', 'require', '上架定时不能为空', 0),
//        array('pos_district', 'require', '上架定时不能为空', 0), 
    );
    protected $_auto = array(
        array('create_time', 'time', self::MODEL_INSERT, 'function'),
        array('audit_status', 0),
        array('release_status', 0),
    );

    /* 缓存key设置 */
    protected $_skey_main = 'pai_product_common_{id}'; // 主键

    /**
     * 获取主键缓存key
     * @param number $id
     */

    public function getCacheKeyMain($id) {
        return str_replace('{id}', $id, $this->_skey_main);
    }

    public function getOne($where) {
        list($rows, $count) = $this->getList($where);
        if (count($rows) > 0) {
            return $rows[0];
        }
        return null;
    }

    public function upDate($data, $where = NULL) {
        if (isset($data['id'])) {
            if (!$where) {
                $where = array();
            }
            $where['id'] = $data['id'];
            unset($data['id']);
        }
        $rs = $this->where($where)->save($data);
        if ($rs && isset($where['id'])) {
            S($this->getCacheKeyMain($where['id']), NULL);
        }
        return $rs;
    }

    public function getList($where, $order = 'create_time desc', $field = "*") {
        $totalCount = $this->where($where)->count();
        $data = array();
        if ($totalCount) {
            $list = $this->where($where)->order($order)->field($field)->select();
            $ids = getSubByKey($list, 'id');
            foreach ($ids as $v) {
                $data[$v] = $this->getById($v);
            }
        }
        return array($data, $totalCount);
    }

    public function getListByPage($map, $page = 1, $order = 'create_time desc', $field = "*", $r = 20) {
        $totalCount = $this->where($map)->count();
        $data = array();
        if ($totalCount) {
            $list = $this->where($map)->page($page, $r)->order($order)->field($field)->select();
            $ids = getSubByKey($list, 'id');
            foreach ($ids as $v) {
                $data[$v] = $this->getById($v, $field);
            }
        }
        return array($data, $totalCount);
    }

    public function getAuditStatus() {
        return $this->_auditStatus;
    }

    public function getType() {
        return $this->_type;
    }

    public function getReleaseStatus() {
        return $this->_releaseStatus;
    }

    /**
     * 链表到product查询
     * @param type $where
     * @param type $order
     * @param type $page
     * @param type $r
     * @return type
     */
    public function getListLinkProduct($where, $order, $page, $r = 20) {
        $offset = $page <= 1 ? 0 : ($page * $r - $r - 1);
        $field = "SELECT p.*,c.*,c.create_time as pc_create_time,c.uid as pc_uid,c.id as pc_id,p.id as id";
        $sql = " FROM  __PREFIX__pai_product AS p LEFT JOIN  __PREFIX__pai_product_common AS c ON p.pid=c.id WHERE $where ORDER BY $order ";
        $limit = " LIMIT $offset,$r";

        $totalCount = $this->query("SELECT COUNT(p.id) AS `count` $sql")[0]['count'];
        $rows = $this->query($field . $sql . $limit);
        return array($rows, $totalCount);
    }

    /**
     * 根据主键获取信息
     * @param number $id
     */
    public function getById($id, $field = "*") {
        $skey = $this->getCacheKeyMain($id);
        $data = S($skey);
        if (!$data) {
            $data = $this->field($field)->find($id);
            if ($data) {
                $district = D('Common/District')->getAllRows();
                $district = field2array_key($district, 'id', 'name');
                $data['pos_province_name'] = $district[$data['pos_province']];
                $data['pos_city_name'] = $district[$data['pos_city']];
                $data['pos_district_name'] = $district[$data['pos_district']];
                $data['imgs'] = explode(',', $data['attr_imgs']);
                $data['imgs'] = array_filter($data['imgs']);
            }
            S($skey, $data);
        }
        return $data;
    }

    //==============================验证=============================
    /**
     * 验证金额
     * @param type $money
     * @return boolean
     */
    protected function checkMoney($money) {
        $n = floatval($money);
        if ($n < 0) {
            return FALSE;
        }
        return TRUE;
    }

}
