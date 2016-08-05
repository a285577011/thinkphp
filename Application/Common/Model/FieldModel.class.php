<?php

/**
 * Created by i.cn.
 * User: zhangby
 * Date: 16.1.28
 * 
 */

namespace Common\Model;

use Think\Model;

class FieldModel extends Model {

    protected $_validate = array(
        array('uid', '/^[1-9]\d*$/', '用户ID错误', self::MODEL_INSERT, 'regex'),
        array('field_id', '/^[1-9]\d*$/', '字段ID错误', self::MODEL_INSERT, 'regex')
    );

    var $_skey_main = 'field_{id}';
    var $_skey_uid_filedid = 'field_{uid}_{field_id}';
    
    public function getCacheKeyMain($id) {
        return str_replace('{id}', $id, $this->_skey_main);
    }
    
    /**
     * uid+fieldid缓存
     * @param number $uid
     * @param number $field_id
     * @author dpj
     */
    public function getCacheKeyUidFiledID( $uid, $field_id){
        return str_replace(array('{uid}', '{field_id}'), array($uid, $field_id), $this->_skey_uid_filedid);
    }

    public function getRowByMap($map) {
        $cache = S($this->getCacheKeyMain(md5($map)));
        if (!$cache) {
            $cache = $this->where($map)->find();
            if (!$cache) {
                return null;
            }
            S($this->getCacheKeyMain($map), $cache);
        }
        return $cache;
    }

    public function getRow($id) {
        $cache = S($this->getCacheKeyMain($id));
        if (!$cache) {
            $cache = $this->where(array('id' => $id))->find();
            if (!$cache) {
                return null;
            }
            S($this->getCacheKeyMain($id), $cache);
        }
        return $cache;
    }

    public function getFieldByUid($uid) {
        $totalCount = $this->where(array('uid' => $uid))->count();
        $data = array();
        if ($totalCount) {
            $list = $this->where(array('uid' => $uid))->field('id')->select();
            $ids = getSubByKey($list, 'id');
            foreach ($ids as $v) {
                $data[$v] = $this->getRow($v);
            }
        }
        return $data;
    }
    
    /**
     * 根据uid和字段id获取field表主键
     * @param number $uid
     * @param number $field_id
     */
    public function getKeyByUidFieldId($uid, $field_id){
        $skey =$this->getCacheKeyUidFiledID($uid, $field_id);
        $data = S($skey);
        if (!$data) {
            $map['uid'] = $uid;
            $map['field_id'] = $field_id;
            $data = $this->where($map)->getField('id');
            if (!$data) {
                return null;
            }
            S($skey, $data);
        }
        return $data;
    }

    /**
     * 更新扩展字段信息
     * @param array $data 修改的内容
     * @param array $map 筛选条件
     * @author zhangby
     */
    public function saveData( $data, $map) {
        $id = isset($map['id']) ? $map['id'] : NULL;
        
        $rs =$this->where($map)->save($data);
        if ($rs && $id) {
            S($this->getCacheKeyMain($id), NULL);
        }
        return $rs;
    }
    
    /**
     * 保存单条扩展字段信息
     * @param number $uid
     * @param number $field_id
     * @param string $field_data
     * @author dpj
     */
    public function saveField($uid, $field_id, $field_data, $visiable = 1){
        $visiable && $visiable = 1;
        $data['uid'] = $uid;
        $data['field_id'] = $field_id;
        $data['field_data'] = $field_data;
        $data['visiable'] = $visiable;
        
        $data = $this->create($data);
        if(!$data){
            return  false;
        }
        
        $setting = D('FieldSetting')->getFieldSetting($field_id);
        $setting['value'] = $field_data;
        // 字段验证
        $info = $this->_checkInput($setting);
        if(!$info['succ']){
            return false;
        }
        $id = $this->getKeyByUidFieldId($uid, $field_id);
        if($id){
            $rs = $this->where(array('id'=>$id))->save($data);
            $rs !== false && $rs = true;
        }else{
            $rs = $this->add($data);
        }
        if ($rs) {
            S($this->getCacheKeyMain($id), NULL);
        }
        return $rs;
    }


    /**
     * input类型验证
     * @param $data
     * @return mixed
     */
     private function _checkInput($data) {
        $info['succ'] = 1;
        if ($data['form_type'] == "textarea") {
            $validation = $this->_getValidation($data['validation']);
            if (($validation['min'] != 0 && mb_strlen($data['value'], "utf-8") < $validation['min']) || ($validation['max'] != 0 && mb_strlen($data['value'], "utf-8") > $validation['max'])) {
                if ($validation['max'] == 0) {
                    $validation['max'] = '';
                }
                $info['succ'] = 0;
                $info['msg'] = $data['field_name'] . L('_INFO_LENGTH_1_') . $validation['min'] . "-" . $validation['max'] . L('_INFO_LENGTH_2_');
            }
        } else {
            switch ($data['child_form_type']) {
                case 'string':
                    $validation = $this->_getValidation($data['validation']);
                    if (($validation['min'] != 0 && mb_strlen($data['value'], "utf-8") < $validation['min']) || ($validation['max'] != 0 && mb_strlen($data['value'], "utf-8") > $validation['max'])) {
                        if ($validation['max'] == 0) {
                            $validation['max'] = '';
                        }
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . L('_INFO_LENGTH_1_') . $validation['min'] . "-" . $validation['max'] . L('_INFO_LENGTH_2_');
                    }
                    break;
                case 'number':
                    if (preg_match("/^\d*$/", $data['value'])) {
                        $validation = $this->_getValidation($data['validation']);
                        if (($validation['min'] != 0 && mb_strlen($data['value'], "utf-8") < $validation['min']) || ($validation['max'] != 0 && mb_strlen($data['value'], "utf-8") > $validation['max'])) {
                            if ($validation['max'] == 0) {
                                $validation['max'] = '';
                            }
                            $info['succ'] = 0;
                            $info['msg'] = $data['field_name'] . L('_INFO_LENGTH_1_') . $validation['min'] . "-" . $validation['max'] . L('_INFO_LENGTH_2_') . L('_COMMA_') . L('_INFO_AND_DIGITAL_');
                        }
                    } else {
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . L('_INFO_DIGITAL_');
                    }
                    break;
                case 'email':
                    if (!preg_match("/^([0-9A-Za-z\\-_\\.]+)@([0-9a-z]+\\.[a-z]{2,3}(\\.[a-z]{2})?)$/i", $data['value'])) {
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . L('_INFO_FORMAT_EMAIL_');
                    }
                    break;
                case 'phone':
                    if (!preg_match("/^\d{11}$/", $data['value'])) {
                        $info['succ'] = 0;
                        $info['msg'] = $data['field_name'] . L('_INFO_FORMAT_PHONE_');
                    }
                    break;
            }
        }
        return $info;
    }
    


    /**
     * 处理$validation
     * @param $validation
     * @return mixed
     *
     */
    function _getValidation($validation) {
        $data['min'] = $data['max'] = 0;
        if ($validation != '') {
            $items = explode('&', $validation);
            foreach ($items as $val) {
                $item = explode('=', $val);
                if ($item[0] == 'min' && is_numeric($item[1]) && $item[1] > 0) {
                    $data['min'] = $item[1];
                }
                if ($item[0] == 'max' && is_numeric($item[1]) && $item[1] > 0) {
                    $data['max'] = $item[1];
                }
            }
        }
        return $data;
    }

    /**
     * 根据uid获取用户扩展信息
     * @param number $uid 用户ID
     * @param number $other_uid 查看的用户ID
     * @author dpj
     */
    public function getExpandFieldInfo($uid, $other_uid = 0){
        // 扩展信息分组
        $group = D('FieldGroup')->getFieldGroup();
        
        $fieldSettingModel = D('FieldSetting');
        $data = array();
        foreach ($group as $k => $v){
            if(!$v['status'] || !$v['visiable']){
                continue;
            }
            $fields = array();
            
            // 分组字段
            $field_setting = $fieldSettingModel->getFieldList(array('profile_group_id' => $v['id']));
            
            // 构造用户扩展资料信息
            foreach ($field_setting as $fk => $fv){
                if(!$v['status'] || !$v['visiable']){
                    continue;
                }
                $id = $this->getKeyByUidFieldId($uid, $fv['id']);
                $row_info = $this->getRow($id);
                // TODO 隐私字段排除
                if( ($row_info && !$row_info['visiable']) && ($other_uid && $other_uid != $uid)){ // 不可见或不是用户自己
                    continue;
                }
                
                $field['field_id'] = $fv['id'];
                $field['field_name'] = $fv['field_name'];
                $field['field_value'] = isset($row_info['field_data']) ? $row_info['field_data'] : '';
                $field['visiable'] = isset($row_info['visiable']) ? $row_info['visiable'] : 1;
                
                $fields[] = $field;
            }
            $v['field'] = $fields;
            $data[] = $v;
        }
        return $data;
    }

}
