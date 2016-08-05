<?php

namespace User\Model;

use Think\Model;

/**
 * @author zhangby
 */
class UserAuthRealnameModel extends Model {
    /* 缓存key设置 */

    protected $_skey_main = 'user_auth_realname_{id}'; // 主键
    protected $_validate = array(
        array('realname', 'checkCnName', '姓名只能输入中文且不能少于2个字', self::MODEL_INSERT, 'callback'),
        array('id_card', 'require', '身份证号码不能为空', self::MUST_VALIDATE),
        array('id_card', '15,18', '身份证号码长度不合法，必须介于15~18位', self::MUST_VALIDATE, 'length'),
        array('id_pic', 'checkIdImg', '请上传手持身份证照和身份证正面照', self::MUST_VALIDATE, 'callback'),
    );
    protected $_auto = array(
        array('create_time', 'time', 1, 'function'),
    );

    /**
     * 获取主键缓存key
     * @param number $id
     */
    public function getCacheKeyMain($id) {
        return str_replace('{id}', $id, $this->_skey_main);
    }

    /**
     * 新增与更新（此方法用于强制验证必要字段的需求）
     * @author zhangby
     */
    public function addOrSave($data) {
        $data = $this->create($data); //指定此处为更新数据
        if (!$data) {
            return FALSE;
        }
        
        if (isset($data['id'])) {
            S($this->getCacheKeyMain($data['id']), null);
            return $this->save($data);
        }
        return $this->add($data);
    }

    /**
     * 此方法用于普通更新
     * @param type $data
     * @param type $where
     * @return type
     * @author zhangby
     */
    public function saveData($data, $where) {
        $row = $this->where($where)->select();
        foreach ($row as $v) {
            S($this->getCacheKeyMain($v['id']), null);
        }
        return $this->save($data);
    }

    /**
     * 根据标签名获取信息
     * @param $id
     * @param bool $field
     * @return mixed
     * @author zhangby
     * 
     */
    public function getByUid($uid) {
        $row = $this->where(array('uid' => $uid))->field('id')->find();
        if ($row) {
            return $this->getById($row['id']);
        }
        return NULL;
    }

    public function getById($id) {
        $skey = $this->getCacheKeyMain($id);
        $data = S($skey);
        if (!$data) {
            $data = $this->where(array('id' => $id))->find();
            if ($data) {
                $data['imgs'] = explode(',', $data['id_pic']);
            }
            S($skey, $data);
        }
        return $data;
    }
    
    /**
     * 
     * @param type $map
     * @param type $page
     * @param type $order
     * @param type $field
     * @param type $r
     * @return type
     * @author zhangby
     */
     public function getListByPage($map, $page = 1, $order = 'create_time desc', $field = "*", $r = 20) {
        $totalCount = $this->where($map)->count();
        $data = array();
        if ($totalCount) {
            $list = $this->where($map)->page($page, $r)->order($order)->field($field)->select();
            $ids = getSubByKey($list, 'id');
            foreach ($ids as $v) {
                $data[$v] = $this->getById($v);
            }
        }
        return array($data, $totalCount);
    }

    //=========================验证函数=================================
    /**
     * 验证是否上传图片
     * @param type $id_pic
     * @return type
     * @author zhangby
     */
    protected function checkIdImg($id_pic) {
        $arr = explode(',', $id_pic);
        $arr = array_filter($arr);
        return count($arr) < 2 ? FALSE : TRUE;
    }

    protected function checkCnName($name) {
        $name = str_replace(array(' ', '　'), array(''), $name);  
        if (!preg_match('/(^[\x80-\xff\s\']+?)$/', $name,$match)) {    
            return false;
        }                 
        $length = mb_strlen($name, 'utf-8'); // 当前数据长度        
        if ($length < 2 || $length > 15) {
            return false;
        }

        return true;
    }

}
