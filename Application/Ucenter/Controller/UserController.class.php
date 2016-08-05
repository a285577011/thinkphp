<?php

namespace Ucenter\Controller;

use Think\Controller;
use User\Api\UserApi;

require_once APP_PATH . 'User/Conf/config.php';

/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 * @author zhangby
 */
class UserController extends Controller {

    /**
     * 检查登录
     * @author zhangby
     */
    public function isLogin() {
        $data = array('status' => 1, 'msg' => '已登录');
        if (!is_login()) {
            $data = array('status' => 0, 'msg' => '请先登录');
        }
        $this->ajaxReturn($data, 'JSONP');
    }

    /**
     * 检测绑定手机
     * @author zhangby
     */
    public function isBindMobile() {
        $data = array('status' => 0, 'msg' => '请绑定手机号');
        $row = query_user(array('uid', 'username', 'mobile'), is_login());
        if ($row['mobile']) {
            $data = array('status' => 1, 'msg' => '已绑定手机号');
        }
        $this->ajaxReturn($data, 'JSONP');
    }

    /**
     * 检测是否设置密码
     * @author zhangby
     */
    public function isSetPass() {
        $data = array('status' => 0, 'msg' => '还未设置密码');
        $row = query_user(array('password'), is_login());
        if ($row['password']) {
            $data = array('status' => 1, 'msg' => '已设置过密码！');
        }
        $this->ajaxReturn($data, 'JSONP');
    }

    /**
     * 实名认证
     * @author zhangby
     */
    public function isRealName() {
        $data = array('status' => 0, 'msg' => '还未实名认证');
        $row =  query_user(array('ipai'),  is_login());
        if ($row['ipai']) {
            $data = array('status' => 1, 'msg' => '已通过实名认证！');
        }
        $this->ajaxReturn($data, 'JSONP');
    }

    /**
     * 第一次设置密码
     * @author zhangby
     */
    public function doFirstSetPass() {
        $pwd = I('get.pwd', '', 'op_t');
        $spwd = I('get.spwd', '', 'op_t');
        if (!trim($pwd) || strlen(trim($pwd)) < 6) {
            $data = array('status' => 0, 'msg' => '密码必须大于6位');
            $this->ajaxReturn($data, 'JSONP');
        }
        if ($pwd != $spwd) {
            $data = array('status' => 0, 'msg' => '两次输入的密码不一致');
            $this->ajaxReturn($data, 'JSONP');
        }

        $rs = D('User/UcenterMember')->firstSetPwd(is_login(), $pwd);
        if ($rs) {
            $data = array('status' => 1, 'msg' => '设置成功！');
            $this->ajaxReturn($data, 'JSONP');
        }

        $data = array('status' => 0, 'msg' => D('User/UcenterMember')->getError());
        $this->ajaxReturn($data, 'JSONP');
    }

    /**
     * 发送手机验证码
     * @author zhangby
     */
    public function sendMobileVerify() {
        $mobile = I('get.mobile', 0, 'op_t');
        $mobile = trim($mobile);
        if (!check_mobile($mobile)) {
            $data = array('status' => 0, 'msg' => '手机格式不正确');
            $this->ajaxReturn($data, 'JSONP');
        }

        $code = D('Common/Verify')->addVerify($mobile, 'mobile');
        if ($code) {
            $data = array('status' => 1, 'msg' => $code['code']);
            $sms = sendSMS($mobile, $code['verify'], $code['code']);
            $this->ajaxReturn($data, 'JSONP');
        }

        $data = array('status' => 0, 'msg' => '发送失败！');
        $this->ajaxReturn($data, 'JSONP');
    }

    /**
     * 绑定手机号码
     * @author zhangby
     */
    public function doBindMobile() {
        if (!is_login()) {
            $data = array('status' => 0, 'msg' => '请先登录');
            $this->ajaxReturn($data, 'JSONP');
        }

        $mobile = I('get.mobile', 0, 'op_t');
        $code = I('get.code', 0, 'intval');
        $this->_check_mobile_verify($mobile, $code);


        $rs = D('User/UcenterMember')->simpBindMobile(is_login(), $mobile);
        if ($rs) {
            //D('Common/Verify')->delVerify($mobile);
            $data = array('status' => 1, 'msg' => '绑定成功！');
            $this->ajaxReturn($data, 'JSONP');
        }

        $data = array('status' => 0, 'msg' => D('User/UcenterMember')->getError());
        $this->ajaxReturn($data, 'JSONP');
    }

    private function _check_mobile_verify($mobile, $code) {
        $data = array();
        if (!check_mobile($mobile)) {
            $data = array('status' => 0, 'msg' => '手机格式不正确');
            $this->ajaxReturn($data, 'JSONP');
        }

        $ver = D('Common/Verify')->getVerify($mobile);
        if ($ver != $code) {
            $data = array('status' => 0, 'msg' => '验证码不正确');
            $this->ajaxReturn($data, 'JSONP');
        }
    }

}
