<?php

namespace Ucenter\Controller;

use Think\Controller;

//vendor('formvalidator.formvalidator');
class AddressController extends BaseController {

    var $jsonp = '';

    public function _initialize() {
        parent::_initialize();
        if (!is_login()) {
            $this->error(L('_ERROR_FIRST_LOGIN_'));
        }
        $this->setTitle(L('_DATA_EDIT_'));
        $this->_assignSelf();
        $this->jsonp = is_jsonp() ? 'JSONP' : '';
    }

    /*     * 关联自己的信息
     * 
     */

    private function _assignSelf() {
        $self = query_user(array('avatar128', 'nickname', 'space_url', 'space_link', 'score', 'title', 'pos_province', 'pos_city', 'pos_district'));
        $this->assign('user', $self);
    }

    /**
     * 收货地址管理
     * @author ZHANGBY
     */
    public function items() {
        $pid = I('get.pid', 0, 'intval');
        if (IS_POST) {
            $this->_save_address();
            return;
        }
        $data = array();
        $uid = is_login();
        list($data['rows'], $data['count']) = D('Common/UserAddress')->getListByPage(array('uid' => $uid), 1, 10);
        $district = D('Common/District')->getAllRows();
        $data['goods'] = D('Ipai/PaiProduct')->getById($pid);

        $data['district'] = field2array_key($district, 'id');
        $data['user'] = query_user(array('uid', 'mobile', 'level', 'score', 'username', 'nickname', 'weixin', 'signature', 'catid', 'email', 'mobile', 'avatar128', 'rank_link', 'sex', 'pos_province', 'pos_city', 'pos_district', 'pos_community'), $uid);
        $this->assign($data);
        $this->display('address_list');
    }

    /**
     * 删除收货地址
     * @author zhangby
     */
    public function del() {
        $out = array('status' => 0, 'msg' => '');
        $uid = is_login();
        $id = I('id', 0, 'intval');

        $row = D('Common/UserAddress')->getById($id);
        if ($row && $row['is_default'] == 1) {
            $out['status'] = 0;
            $out['msg'] = '不能删除默认地址。';
            $this->ajaxReturn($out, $this->jsonp);
            return;
        }

        $rs = D('Common/UserAddress')->deleteByIdAndUid($id, $uid);
        if (!$rs) {
            $out['status'] = 0;
            $out['msg'] = D('Common/UserAddress')->getError();
            $this->ajaxReturn($out, $this->jsonp);
            return;
        }

        $out['status'] = 1;
        $out['msg'] = '删除成功！';
        $this->ajaxReturn($out, $this->jsonp);
    }

    /**
     * 设置默认地址
     */
    public function setDefault($_id = 0) {
        $where = array();
        $data = array();
        $where['uid'] = is_login();
        $where['id'] = I('id', 0, 'intval');
        $data['is_default'] = 1;
        if ($_id) {
            $where['id'] = $_id;
            return D('Common/UserAddress')->upAddress($where, $data);
        }
        $rs = D('Common/UserAddress')->upAddress($where, $data);
        if (!$rs) {
            $out['status'] = 0;
            $out['msg'] = D('Common/UserAddress')->getError();
            $this->ajaxReturn($out, $this->jsonp);
        }
        $out['status'] = 1;
        $out['msg'] = '操作成功！';

        $this->ajaxReturn($out, $this->jsonp);
    }

    /**
     * 地址编辑，主要JSOPN调用
     * @author zhangby
     */
    public function edit() {
        $id = I('id', 0, 'intval');
        $data['row'] = D('Common/UserAddress')->getById($id);
        if (!$data['row'] || $data['row']['uid'] != is_login()) {
            $data['row'] = NULL;
        }

        if ($data['row']) {
            $data['row']['pid'] = isset($data['row']['arr_region'][0]) ? $data['row']['arr_region'][0] : 0;
            $data['row']['cid'] = isset($data['row']['arr_region'][1]) ? $data['row']['arr_region'][1] : 0;
            $data['row']['did'] = isset($data['row']['arr_region'][2]) ? $data['row']['arr_region'][2] : 0;
        }
        

        $this->assign($data);
        $html = $this->fetch(T('edit'));

        $this->ajaxReturn(array('status' => 1, 'data' => $html), $this->jsonp);
    }

    public function save() {
        $this->_save_address();
    }

    /**
     * 选择地址，主要JSOPN调用
     * @author zhangby
     */
    public function selectList() {
        $data = array();
        list($data['rows'], $data['count']) = D('Common/UserAddress')->getListByPage(array('uid' => is_login()), 1, 10);
        $district = D('Common/District')->getAllRows();
        $data['district'] = field2array_key($district, 'id');

        $this->assign($data);
        $html = $this->fetch(T('select_list'));
        $this->ajaxReturn(array('status' => 1, 'data' => $html), $this->jsonp);
    }

    /**
     * 保存地址
     * @author zhangby
     */
    private function _save_address() {
        $param = array();
        $param['uid'] = is_login();
        $param['id'] = I('id', 0, 'intval');
        $param['address'] = I('address', '', 'op_t');
        $param['postcode'] = I('postcode', 0, 'intval');
        $param['realname'] = I('realname', '', 'op_t');
        $param['mobile'] = I('mobile', '', 'op_t');
        $param['is_defaule'] = I('is_defaule', 0, 'intval');
        $province = I('province', 0, 'intval');
        $city = I('city', 0, 'intval');
        $area = I('area', 0, 'intval');
        $param['region'] = ',' . $province . ',' . $city . ',' . $area . ',';

        if (!$province || !$city || !$area) {
            $out['status'] = 0;
            $out['msg'] = '请选择所在地区。';
            $this->ajaxReturn($out, $this->jsonp);
        }

        list($rows, $count) = D('Common/UserAddress')->getListByPage(array('uid' => $param['uid']), 1, 1);
        $out = array('status' => 0, 'msg' => '');
        if (!$param['id'] && $count > 10) {
            $out['status'] = 0;
            $out['msg'] = '收货地址达到最多数，请先删除一些旧地址，在进行添加。';
            $this->ajaxReturn($out, $this->jsonp);
        }
        if ($count < 1) {
            $param['is_defaule'] = 1;
        }
        if (!$param['id']) {
            unset($param['id']);
        }
        $rs = D('Common/UserAddress')->addOrSave($param); //echo $rs;echo D('Common/UserAddress')->getLastSql();
        if (!$rs) {
            $out['status'] = 0;
            $err = D('Common/UserAddress')->getError();
            $out['msg'] = $err ? $err : '未进行更改。';
            $this->ajaxReturn($out, $this->jsonp);
        }

        if ($param['is_defaule']) {
            $uid = isset($param['id']) ? $param['id'] : $rs;
            $this->setDefault($uid);
        }
        $out['status'] = 1;
        $out['msg'] = '操作成功！';
        $this->ajaxReturn($out, $this->jsonp);
    }

}
