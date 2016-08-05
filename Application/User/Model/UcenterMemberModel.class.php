<?php

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace User\Model;

use Think\Model;
use Home\Model\MemberModel;

require_once(APP_PATH . '/User/Conf/config.php');
require_once(APP_PATH . '/User/Common/common.php');

/**
 * 会员模型
 */
class UcenterMemberModel extends Model {

    /**
     * 数据表前缀
     * @var string
     */
    protected $tablePrefix = UC_TABLE_PREFIX;

    /**
     * 数据库连接
     * @var string
     */
    protected $connection = UC_DB_DSN;

    /* 用户模型自动验证 */
    protected $_validate = array(
        /* 验证用户名 */
        array('username', 'checkUsernameLength', -1, self::EXISTS_VALIDATE, 'callback'), //用户名长度不合法
        array('username', 'checkDenyMember', -2, self::EXISTS_VALIDATE, 'callback'), //用户名禁止注册
        array('username', 'checkUsername', -20, self::EXISTS_VALIDATE, 'callback'),
        array('username', '', -3, self::EXISTS_VALIDATE, 'unique'), //用户名被占用

        /* 验证密码 */
        array('password', '6,30', -4, self::EXISTS_VALIDATE, 'length'), //密码长度不合法

        /* 验证手机号码 */
        array('mobile', '/^(13[0-9]|15[012356789]|17[0-9]|18[0-9]|14[57])[0-9]{8}$/', -9, self::EXISTS_VALIDATE), //手机格式不正确 TODO:
        array('mobile', 'checkDenyMobile', -10, self::EXISTS_VALIDATE, 'callback'), //手机禁止注册
        array('mobile', '', -11, self::EXISTS_VALIDATE, 'unique'), //手机号被占用
    );

    /* 用户模型自动完成 */
    protected $_auto = array(
        array('password', 'think_ucenter_md5', self::MODEL_BOTH, 'function', UC_AUTH_KEY),
        array('reg_time', NOW_TIME, self::MODEL_INSERT),
        array('reg_ip', 'get_client_ip', self::MODEL_INSERT, 'function', 1),
        array('update_time', NOW_TIME),
        array('status', '1', self::MODEL_INSERT),
            //array('status', 'getStatus', self::MODEL_BOTH, 'callback'),
    );
    protected $_skey_main = 'ucenter_member_{id}'; // uid(主键)获取用户名缓存前缀
    protected $_skey_mobile = 'ucenter_member_mobile_{mobile}'; // 手机号获取用户名缓存前缀
    protected $_skey_username = 'ucenter_member_username_{username}'; // 手机号获取用户名缓存前缀

    /**
     * 根据uid获取缓存key
     * @param number $uid
     * @return mixed
     */

    public function getCacheKeyMain($id) {
        return str_replace('{id}', $id, $this->_skey_main);
    }

    /**
     * 根据手机号获取缓存key
     * @param unknown $mobile
     * @return mixed
     */
    public function getCacheKeyMobile($mobile) {
        return str_replace('{mobile}', $mobile, $this->_skey_mobile);
    }

    /**
     * 根据手机号获取缓存key
     * @param unknown $mobile
     * @return mixed
     */
    public function getCacheKeyUsername($username) {
        return str_replace('{username}', $username, $this->_skey_username);
    }

    /**
     * 检测用户名是不是被禁止注册(保留用户名)
     * @param  string $username 用户名
     * @return boolean          ture - 未禁用，false - 禁止注册
     */
    protected function checkDenyMember($username) {
        $denyName = M("Config")->where(array('name' => 'USER_NAME_BAOLIU'))->getField('value');
        if ($denyName != '') {
            $denyName = explode(',', $denyName);
            foreach ($denyName as $val) {
                if (!is_bool(strpos($username, $val))) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * 检测邮箱是不是被禁止注册
     * @param  string $email 邮箱
     * @return boolean       ture - 未禁用，false - 禁止注册
     */
    protected function checkDenyEmail($email) {
        return true; //TODO: 暂不限制，下一个版本完善
    }

    protected function checkUsername($username) {
        //如果用户名中有空格，不允许注册
        if (strpos($username, ' ') !== false) {
            return false;
        }
        // TODO 6-20位字符，必须同时包含字母数字
        preg_match("/^[a-zA-Z0-9_]{6,20}$/", $username, $result);

        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * 检查微信名称
     * @param string $weixin
     * @author dpj
     */
    protected function checkWeixin($weixin) {
        //如果用户名中有空格，不允许注册
        if (strpos($weixin, ' ') !== false) {
            return false;
        }
        // TODO 6-20位字符数字，必须字母开头
        preg_match("/^[a-zA-Z0-9_]{6,20}$/", $weixin, $result);

        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * 验证用户名长度
     * @param $username
     * @return bool
     * 
     */
    protected function checkUsernameLength($username) {
        if (!$username) {
            return false;
        }
        $length = mb_strlen($username, 'utf-8'); // 当前数据长度
        if ($length < modC('USERNAME_MIN_LENGTH', 2, 'USERCONFIG') || $length > modC('USERNAME_MAX_LENGTH', 32, 'USERCONFIG')) {
            return false;
        }
        return true;
    }

    /**
     * 检测手机是不是被禁止注册
     * @param  string $mobile 手机
     * @return boolean        ture - 未禁用，false - 禁止注册
     */
    protected function checkDenyMobile($mobile) {
        return true; //TODO: 暂不限制，下一个版本完善
    }

    /**
     * 根据配置指定用户状态
     * @return integer 用户状态
     */
    protected function getStatus() {
        return true; //TODO: 暂不限制，下一个版本完善
    }

    /**
     * 注册一个新用户
     * @param  string $username 用户名
     * @param  string $nickname 昵称
     * @param  string $password 用户密码
     * @param  string $weixin 微信
     * @param  string $mobile 用户手机号码
     * @return integer          注册成功-用户信息，注册失败-错误编号
     */
    public function register($username, $nickname, $password, $mobile, $weixin, $type = 1, $sex = 1, $catid = 0) {
        $data = array(
            'username' => $username,
            'password' => $password,
            'nickname' => $nickname,
            'mobile' => $mobile,
            'type' => $type,
            'sex' => $sex,
            'catid' => $catid,
            'weixin' => $weixin,
        );

        //验证手机
        //if (empty($data['mobile'])) unset($data['mobile']);
        //if (empty($data['username'])) unset($data['username']);
        //if (empty($data['email'])) unset($data['email']);

        /* 添加用户 */
        $usercenter_member = $this->create($data);
        if ($usercenter_member) {
            $result = D('Common/Member')->registerMember($data);
            if ($result > 0) {
                $usercenter_member['id'] = $result;
                $uid = $this->add($usercenter_member);
                if ($uid === false) {
                    //如果注册失败，则回去Memeber表删除掉错误的记录
                    D('Common/Member')->where(array('uid' => $result))->delete();
                }
                action_log('reg', 'ucenter_member', 1, 1);
                return $uid ? $uid : 0; //0-未知错误，大于0-注册成功
            } else {
                return $result;
            }
        } else {
            return $this->getError(); //错误详情见自动验证注释
        }
    }

    /**
     * 多种方式注册
     * @param array $data
     * @param number $type 注册方式 1手机 2微信
     */
    public function registerMutil($data, $type = 1) {
        switch ($type) {
            case 1:
                return $this->_registerMobile($data);
                break;
            case 2:
                return $this->_registerWeixin($data);
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * 手机注册
     * @param array $data
     * @author dpj
     */
    private function _registerMobile($data) {
        // 验证规则设置
        /**
          mobile	√	string	手机号
          password	√	string	密码
          repassword	√	string	重复密码
          usernaem	√	string	账号(i.cn二级域名)
          weixin	√	string	微信号
          nickename	√	string	微信名称(昵称)
          sex	√	int	性别 1男 2女
          type	√	int	1商品微商 2非商品微商
          keyword	√	string	关键词
         */
        /* $rules = array(
          array('username', 'checkUsernameLength', '用户名长度不合法', self::EXISTS_VALIDATE,'callback'), //用户名长度不合法
          array('username', 'checkDenyMember', '用户名禁止注册', self::EXISTS_VALIDATE, 'callback'), //用户名禁止注册
          array('username', 'checkUsername', '用户名不符合规则', self::EXISTS_VALIDATE, 'callback'),
          array('username', '', '用户名被占用', self::EXISTS_VALIDATE, 'unique'), //用户名被占用TODO 改为callback
          array('password', '6,30', '密码长度不合法', self::EXISTS_VALIDATE, 'length'), //密码长度不合法
          array('repassword', 'password', '密码不一致', 0, 'confirm'), // 验证确认密码是否和密码一致
          array('mobile', '/^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/', -9, self::EXISTS_VALIDATE), //手机格式不正确 TODO:
          array('mobile', 'checkDenyMobile', '手机禁止注册', self::EXISTS_VALIDATE, 'callback'), //手机禁止注册
          array('mobile', '', '手机号被占用', self::EXISTS_VALIDATE, 'unique'), //手机号被占用 //TODO 改为callback
          ); */

        $regdata = $this->create($data);
        if (!$regdata) {
            $this->error = $this->getErrorMessage($this->error);
            return false;
        }

        // 主关键词
        $userCategoryModel = D('Ucenter/UserCategory');
        $cat['type'] = $data['type'];
        $cat['title'] = $data['keyword'];

        $data = D('Common/Member')->create($data);
        if (!$data) {
            $this->error = D('Common/Member')->getError();
            return false;
        }

        // TODO 事务 -- start

        $catid = $userCategoryModel->addCategory($cat);
        if (!$catid) {
            $this->error = $userCategoryModel->getError();
            return false;
        }

        // 注册UcenterMember
        $data['catid'] = $catid;
        if (!$uid = $this->add($regdata)) {
            return false;
        }

        // 注册Member
        $data['uid'] = $uid;
        if (!D('Common/Member')->registerMember($data)) {
            $this->error = D('Common/Member')->getError();
            return false;
        }
        // TODO 事务 -- end

        return $uid;
    }

    /**
     * 微信注册
     * @param array $data
     * @author dpj
     */
    private function _registerWeixin($data) {
        // 验证规则设置
        /**
          uid	√	string	用户ID
          openid	√	string	微信平台ID
          acount	√	string	账号(i.cn二级域名)
          weixin	√	string	微信号
          type	√	int	1商品微商 2非商品微商
          keyword	√	string	关键词
         */
        // 检测微信是否同步过
        $map = array('type_uid' => $data['openid'], 'type' => 'weixin');
        $uid = D('sync_login')->where($map)->getField('uid');
        if (!$uid) {
            return false;
        }
        $userinfo = query_user(array('nickname'), $uid);
        if (!$userinfo) {
            D('sync_login')->where($map)->delete();
            // 删除同步记录
            return false;
        }
        $data['nickname'] = $userinfo['nickname'];

        $rules = array(
            /* 验证用户名 */
            array('username', 'checkUsernameLength', '用户名长度不合法', self::EXISTS_VALIDATE, 'callback'), //用户名长度不合法
            array('username', 'checkDenyMember', '用户名禁止注册', self::EXISTS_VALIDATE, 'callback'), //用户名禁止注册
            array('username', 'checkUsername', '用户名不符合规则', self::EXISTS_VALIDATE, 'callback'),
            array('username', '', '用户名被占用', self::EXISTS_VALIDATE, 'unique'), //用户名被占用TODO 改为callback
        );

        $data['password'] = rand(100000, 999999); // 随机密码
        $regdata = $this->validate($rules)->create($data);
        if (!$regdata) {
            return false;
        }

        // 主关键词
        $userCategoryModel = D('Ucenter/UserCategory');
        $cat['type'] = $data['type'];
        $cat['title'] = $data['keyword'];

        $data = D('Common/Member')->create($data);
        if (!$data) {
            $this->error = D('Common/Member')->getError();
            return false;
        }

        // TODO 事务 -- start

        $catid = $userCategoryModel->addCategory($cat);
        if (!$catid) {
            $this->error = $userCategoryModel->getError();
            return false;
        }

        // 注册UcenterMember
        if (!$this->where(array('id' => $uid))->save($regdata)) {
            return false;
        }

        // 注册Member
        $data['uid'] = $uid;
        $data['catid'] = $catid;
        if (!D('Common/Member')->save($data)) {
            return false;
        }
        // TODO 事务 -- end

        return $uid;
    }

    /**
     * 绑定手机号
     * @param int $uid
     * @param array $data
     * @return boolean|Ambigous <mixed, boolean, unknown, string, NULL, multitype:Ambigous <string, unknown> unknown , object>
     */
    public function bindMobile($uid, $data) {

        // 检测微信是否同步过
        $map = array('uid' => $uid, 'type' => 'weixin');
        $uid = D('sync_login')->where($map)->getField('uid');
        if (!$uid) {
            $this->error = '微信未注册';
            return false;
        }

        $userinfo = query_user(array('mobile'), $uid);
        if ($userinfo['mobile']) {
            $this->error = '已绑定过手机号';
            return false;
        }

        // 验证规则设置
        $rules = array(
            array('mobile', '/^(13[0-9]|15[012356789]|17[0-9]|18[0-9]|14[57])[0-9]{8}$/', '手机格式不正确', self::MUST_VALIDATE), //手机格式不正确
            array('mobile', 'checkDenyMobile', '手机禁止注册', self::MUST_VALIDATE, 'callback'), //手机禁止注册
            array('mobile', '', '手机号被占用', self::MUST_VALIDATE, 'unique'), //手机号被占用
            array('password', '6,20', '密码长度不正确', self::MUST_VALIDATE, 'length'), //密码长度不合法
            array('repassword', 'password', '确认密码不正确', 0, 'confirm'), // 验证确认密码是否和密码一致
        );

        $data = $this->validate($rules)->create($data);

        if (!$data) {
            return false;
        }

        $update['mobile'] = $data['mobile'];
        $update['password'] = $data['password'];
        $update['update_time'] = $data['update_time'];
        $res = $this->where(array('id' => $uid))->save($update);

        clean_query_user_cache($uid, 'mobile');

        return true;
    }

    /**
     * 用户登录认证
     * @param  string  $username 用户名
     * @param  string  $password 用户密码
     * @param  integer $type 用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
     * @return integer           登录成功-用户ID，登录失败-错误编号
     */
    public function login($username, $password, $type = 1) {
        $map = array();
        switch ($type) {
            case 1:
                $map['username'] = $username;
                break;
            case 2:
                $map['email'] = $username;
                break;
            case 3:
                $map['mobile'] = $username;
                break;
            case 4:
                $map['id'] = $username;
                break;
            default:
                return 0; //参数错误
        }
        /* 获取用户数据 */
        $user = $this->where($map)->find();

        $return = check_action_limit('input_password', 'ucenter_member', $user['id'], $user['id']);
        if ($return && !$return['state']) {
            return $return['info'];
        }


        if (UC_SYNC && $user['id'] != 1) {
            return $this->ucLogin($username, $password);
        }

        if (is_array($user) && $user['status']) {
            /* 验证用户密码 */
            if (think_ucenter_md5($password, UC_AUTH_KEY) === $user['password']) {
                $this->updateLogin($user['id']); //更新用户登录信息
                return $user['id']; //登录成功，返回用户ID
            } else {
                action_log('input_password', 'ucenter_member', $user['id'], $user['id']);
                return -2; //密码错误
            }
        } else {
            return -1; //用户不存在或被禁用
        }
    }

    public function ucLogin($username, $password) {
        include_once './api/uc_client/client.php';
        //Ucenter 内数据
        $uc_user = uc_user_login($username, $password, 0);
        //关联表内数据
        $uc_user_ref = get_ucenter_user_ref('', $uc_user['0'], '');
        //登录
        if ($uc_user_ref['uid'] && $uc_user_ref['uc_uid'] && $uc_user[0] > 0) {
            return $uc_user_ref['uid'];
        }
        //本地帐号信息
        $tox_user = $this->getLocal($username, $password);
        // 关联表无、UC有、本地无的
        if ($uc_user[0] > 0 && !$tox_user['id']) {
            $uid = $this->register($uc_user[1], $uc_user[1], $uc_user[2], $uc_user[3], '', 1);
            if ($uid <= 0) {
                return A('Ucenter/Member')->showRegError($uid);
            }

            $this->initRoleUser(1, $uid); //初始化角色用户

            $result = add_ucenter_user_ref($uid, $uc_user[0], $uc_user[1], $uc_user[3]);
            if (!$result) {
                return L('_USER_DOES_NOT_EXIST_OR_PASSWORD_ERROR_');
            }
            return $uid;
        }
        // 关联表无、UC有、本地有的
        if ($uc_user[0] > 0 && $tox_user['id'] > 0) {
            $result = add_ucenter_user_ref($tox_user['id'], $uc_user[0], $uc_user[1], $uc_user[3]);
            if (!$result) {
                return L('_USER_DOES_NOT_EXIST_OR_PASSWORD_ERROR_');
            }
            return $tox_user['id'];
        }
        // 关联表无、UC无、本地有
        if ($uc_user[0] < 0 && $tox_user['id'] > 0) {
            $email = $tox_user['email'] ? $tox_user['email'] : $this->rand_email();
            //写入UC
            $uc_uid = uc_user_register($tox_user['username'], $password, $email, '', '', get_client_ip());
            if ($uc_uid <= 0) {
                return L('_UC_ACCOUNT_REGISTRATION_FAILED_PLEASE_CONTACT_THE_ADMINISTRATOR_');
            }
            //写入关联表
            if (M('ucenter_user_link')->where(array('uid' => $tox_user['id']))->find()) {
                $result = update_ucenter_user_ref($tox_user['id'], $uc_uid, $tox_user['username'], $email);
            } else {
                $result = add_ucenter_user_ref($tox_user['id'], $uc_uid, $tox_user['username'], $email);
            }
            if (!$result) {
                return L('_USER_DOES_NOT_EXIST_OR_PASSWORD_ERROR_');
            }
            return $tox_user['id'];
        }

        //关联表无、UC无、本地无的
        return L('_USERS_DO_NOT_EXIST_');
    }

    /**
     * 初始化角色用户信息
     * @param $role_id
     * @param $uid
     * @return bool
     * 
     */
    public function initRoleUser($role_id = 0, $uid) {
        $memberModel = D('Member');
        $role = D('Role')->where(array('id' => $role_id))->find();
        // TODO setp配置
        $user_role = array('uid' => $uid, 'role_id' => $role_id, 'step' => "finish");
        if ($role['audit']) { //该角色需要审核
            $user_role['status'] = 2; //未审核
        } else {
            $user_role['status'] = 1;
        }
        $result = D('UserRole')->add($user_role);
        if (!$role['audit']) {
            //该角色不需要审核
            $memberModel->initUserRoleInfo($role_id, $uid);
        }
        $memberModel->initDefaultShowRole($role_id, $uid);

        return $result;
    }

    public function getLocal($username, $password) {
        $aUsername = $username;
        check_username($aUsername, $email, $mobile, $type);

        $map = array();
        switch ($type) {
            case 1:
                $map['username'] = $username;
                break;
            case 2:
                $map['email'] = $username;
                break;
            case 3:
                $map['mobile'] = $username;
                break;
            case 4:
                $map['id'] = $username;
                break;
            default:
                return 0; //参数错误
        }

        /* 获取用户数据 */
        $user = $this->where($map)->find();

        if (is_array($user) && $user['status']) {
            /* 验证用户密码 */
            if (think_ucenter_md5($password, UC_AUTH_KEY) === $user['password']) {
                return $user; //登录成功，返回用户ID
            } else {
                return false; //密码错误
            }
        } else {
            return false; //用户不存在或被禁用
        }
    }

    /**
     * 用户密码找回认证
     * @param  string  $username 用户名
     * @param  string  $password 用户密码
     * @param  integer $type 用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
     * @return integer           登录成功-用户ID，登录失败-错误编号
     */
    public function lomi($username, $email) {
        $map = array();
        $map['username'] = $username;
        $map['email'] = $email;
        /* 获取用户数据 */
        $user = $this->where($map)->find();
        if (is_array($user)) {
            /* 验证用户 */
            //if($user['last_login_time']){
            //return $user['last_login_time']; //成功，返回用户最后登录时间
            return $user; //成功，返回用户最后登录时间
            //}else{
            //return $user['reg_time']; //返回用户注册时间
            //return -1; //成功，返回用户最后登录时间
            //}
        } else {
            return -2; //用户和邮箱不符
        }
    }

    /**
     * 用户密码找回认证2
     * @param  string  $username 用户名
     * @param  string  $password 用户密码
     * @param  integer $type 用户名类型 （1-用户名，2-邮箱，3-手机，4-UID）
     * @return integer           登录成功-用户ID，登录失败-错误编号
     */
    public function reset($uid) {
        $map = array();
        $map['id'] = $uid;
        /* 获取用户数据 */
        $user = $this->where($map)->find();
        if (is_array($user)) {
            return $user; //成功，返回用户数据
        } else {
            return -2; //用户和邮箱不符
        }
    }

    /**
     * 根据IP获取用户最后注册时间
     * @param  string  $uid 用户ID或用户名
     * @param  boolean $is_username 是否使用用户名查询
     * @return array                用户信息
     */
    public function infos($regip) {
        $map['reg_ip'] = $regip;
        $user = $this->where($map)->max('reg_time');
        if ($user) {
            return $user;
        } else {
            return -1; //用户不存在或被禁用
        }
    }

    /**
     * 获取用户信息
     * @param  string  $uid 用户ID或用户名
     * @param  boolean $is_username 是否使用用户名查询
     * @return array                用户信息
     */
    public function info($uid, $is_username = false) {
        $map = array();
        if ($is_username) { //通过用户名获取
            $map['username'] = $uid;
        } else {
            $map['id'] = $uid;
        }
        $user = $this->where($map)->field('id,username,mobile,status')->find();
        if (is_array($user) && $user['status'] = 1) {
            return array($user['id'], $user['username'], $user['mobile']);
        } else {
            return -1; //用户不存在或被禁用
        }
    }

    /**
     * 根据手机号获取用户信息
     * @param string $mobile
     */
    public function getUidByMobile($mobile) {
        if (!$mobile) {
            return false;
        }
        $skey = $this->getCacheKeyMobile($mobile);
        $id = $this->where(array('mobile' => $mobile))->getField('id');
        if (!$id) {
            return false;
        }
        S($skey, $id);
        return $id;
    }

    /**
     * 根据账号获取用户信息
     * @param string $mobile
     */
    public function getUidByUsername($username) {
        if (!$username) {
            return false;
        }
        $skey = $this->getCacheKeyUsername($username);
        $id = $this->where(array('username' => $username))->getField('id');
        if (!$id) {
            return false;
        }
        S($skey, $id);
        return $id;
    }

    /**
     * 根据手机号获取用户信息
     * @param number $id
     */
    public function getInfoById($id) {
        $skey = $this->getCacheKeyMain($id); //find方法设置option读取缓存
        return $this->where(array('id' => $id))->find(array('cache' => array('key' => $skey)));
    }

    /**
     * 检测用户信息
     * @param  string  $field 用户名
     * @param  integer $type 用户名类型 1-用户名，2-用户邮箱，3-用户电话
     * @return integer         错误编号
     */
    public function checkField($field, $type = 1) {
        $data = array();
        switch ($type) {
            case 1:
                $data['username'] = $field;
                break;
            case 2:
                $data['email'] = $field;
                break;
            case 3:
                $data['mobile'] = $field;
                break;
            default:
                return 0; //参数错误
        }

        return $this->create($data) ? 1 : $this->getError();
    }

    /**
     * 更新用户登录信息
     * @param  integer $uid 用户ID
     */
    public function updateLogin($uid) {
        $data = array(
            'id' => $uid,
            'last_login_time' => NOW_TIME,
            'last_login_ip' => get_client_ip(1),
        );
        $this->save($data);
    }

    /**
     * 更新用户信息
     * @param int    $uid 用户id
     * @param string $password 密码，用来验证
     * @param array  $data 修改的字段数组
     * @return true 修改成功，false 修改失败
     * 
     */
    public function updateUserFields($uid, $password, $data) {
        if (empty($uid) || empty($password) || empty($data)) {
            $this->error = L('_PARAM_ERROR_25_');
            return false;
        }

        //更新前检查用户密码
        if (!$this->verifyUser($uid, $password)) {
            $this->error = L('_VERIFY_ERROR_PW_WRONG_');
            return false;
        }

        //更新用户信息
        $data = $this->create($data, 2); //指定此处为更新数据
        if ($data) {
            return $this->where(array('id' => $uid))->save($data);
        }
        return false;
    }

    /**
     * 重置用户密码
     * @param int    $uid 用户id
     * @param string $password 密码，用来验证
     * @param array  $data 修改的字段数组
     * @return true 修改成功，false 修改失败
     * 
     */
    public function updateUserFieldss($uid, $data) {
        if (empty($uid) || empty($data)) {
            $this->error = L('_PARAM_ERROR_25_');
            return false;
        }
        //更新用户信息
        $data = $this->create($data, 2);
        if ($data) {
            return $this->where(array('id' => $uid))->save($data);
        }
        return false;
    }

    /**
     * 验证用户密码
     * @param int    $uid 用户id
     * @param string $password_in 密码
     * @return true 验证成功，false 验证失败
     * 
     */
    public function verifyUser($uid, $password_in) {
        $password = $this->getFieldById($uid, 'password');
        if (think_ucenter_md5($password_in, UC_AUTH_KEY) === $password) {
            return true;
        }
        return false;
    }

    /*     * 修改密码
     * @param $old_password
     * @param $new_password
     * @return bool
     * 
     */

    public function changePassword($old_password, $new_password) {
        //检查旧密码是否正确
        if (!$this->verifyUser(get_uid(), $old_password)) {
            $this->error = -41;
            return false;
        }
        //更新用户信息
        $model = $this;
        $data = array('password' => $new_password);
        $data = $model->create($data);
        if (!$data) {
            $this->error = $model->getError();
            return false;
        }
        $model->where(array('id' => get_uid()))->save($data);
        //返回成功信息
        clean_query_user_cache(get_uid(), 'password'); //删除缓存
        D('user_token')->where('uid=' . get_uid())->delete();
        return true;
    }

    public function getErrorMessage($error_code = null) {

        $error = $error_code == null ? $this->error : $error_code;
        switch ($error) {
            case -1:
                $error = L('_USER_NAME_MUST_BE_IN_LENGTH_') . modC('USERNAME_MIN_LENGTH', 2, 'USERCONFIG') . '-' . modC('USERNAME_MAX_LENGTH', 32, 'USERCONFIG') . L('_BETWEEN_CHARACTERS_WITH_EXCLAMATION_');
                break;
            case -2:
                $error = L('_USER_NAME_IS_FORBIDDEN_TO_REGISTER_WITH_EXCLAMATION_');
                break;
            case -3:
                $error = L('_USER_NAME_IS_OCCUPIED_WITH_EXCLAMATION_');
                break;
            case -4:
                $error = L('_PW_LENGTH_6_30_');
                break;
            case -41:
                $error = L('_USERS_OLD_PASSWORD_IS_INCORRECT_');
                break;
            case -5:
                $error = L('_MAILBOX_FORMAT_IS_NOT_CORRECT_WITH_EXCLAMATION_');
                break;
            case -6:
                $error = L('_EMAIL_LENGTH_4_32_');
                break;
            case -7:
                $error = L('_MAILBOX_IS_PROHIBITED_TO_REGISTER_WITH_EXCLAMATION_');
                break;
            case -8:
                $error = L('_MAILBOX_IS_OCCUPIED_WITH_EXCLAMATION_');
                break;
            case -9:
                $error = L('_MOBILE_PHONE_FORMAT_IS_NOT_CORRECT_WITH_EXCLAMATION_');
                break;
            case -10:
                $error = L('_MOBILE_PHONES_ARE_PROHIBITED_FROM_REGISTERING_WITH_EXCLAMATION_');
                break;
            case -11:
                $error = L('_PHONE_NUMBER_IS_OCCUPIED_WITH_EXCLAMATION_');
                break;
            case -12:
                $error = L('_UN_LIMIT_SOME_');
                break;
            case -13:
                $error = '微信号必须';
                break;
            case -31:
                $error = L('_THE_NICKNAME_IS_PROHIBITED_');
                break;
            case -33:
                $error = L('_NICKNAME_LENGTH_MUST_BE_IN_') . modC('NICKNAME_MIN_LENGTH', 2, 'USERCONFIG') . '-' . modC('NICKNAME_MAX_LENGTH', 32, 'USERCONFIG') . L('_BETWEEN_CHARACTERS_WITH_EXCLAMATION_');
                break;
            case -32:
                $error = L('_THE_NICKNAME_IS_NOT_LEGAL_');
                break;
            case -30:
                $error = L('_THE_NICKNAME_HAS_BEEN_OCCUPIED_');
                break;

            default:
                $error = L('_UNKNOWN_ERROR_');
        }
        return $error;
    }

    /**
     * addSyncData
     * @return mixed
     * 
     */
    public function addSyncData() {
        //$data['email'] = $this->rand_email();
        $data['password'] = create_rand(10);
        $data['type'] = 2;  // 视作用邮箱注册
        $data = $this->create($data);
        $uid = $this->add($data);
        return $uid;
    }

    protected function rand_email() {
        $email = create_rand(10) . '@i.cn';
        if ($this->where(array('email' => $email))->select()) {
            $this->rand_email();
        } else {
            return $email;
        }
    }

    /**
     * 设置用户密码 用微信登录的
     * @param unknown $password
     */
    public function setPassword($password) {
        $data = array('password' => $password);
        $data = $this->create($data);
        return $this->where(array('id' => get_uid()))->save($data);
    }

    /**
     * 
     * @param type $uid
     * @param type $mobile
     * @return boolean
     */
    public function simpBindMobile($uid, $mobile) {
        $userinfo = query_user(array('mobile'), $uid);
        if ($userinfo['mobile']) {
            $this->error = '已绑定过手机号';
            return false;
        }

        // 验证规则设置
        $rules = array(
            array('mobile', '/^(13[0-9]|15[012356789]|17[678]|18[0-9]|14[57])[0-9]{8}$/', '手机格式不正确', self::MUST_VALIDATE), //手机格式不正确
            array('mobile', 'checkDenyMobile', '手机禁止注册', self::MUST_VALIDATE, 'callback'), //手机禁止注册
            array('mobile', '', '手机号被占用', self::MUST_VALIDATE, 'unique'), //手机号被占用           
        );

        $update = array();
        $update['mobile'] = $mobile;
        $update['update_time'] = time();

        $data = $this->validate($rules)->create($update);
        if (!$data) {
            return false;
        }

        $res = $this->where(array('id' => $uid))->save($update);
        clean_query_user_cache($uid, 'mobile');
        if ($res) {
            return true;
        }

        return FALSE;
    }

    /**
     * 针对第一次设置密码
     * @param type $uid
     * @param type $pwd
     * @return boolean
     */
    public function firstSetPwd($uid, $pwd) {      
        $userinfo = query_user(array('password'), $uid);
        if ($userinfo['password']) {
            $this->error = '已设过密码！';
            return false;
        }
     
        $update['password'] =think_ucenter_md5($pwd, UC_AUTH_KEY);
        $update['update_time'] = time();
        $res = $this->where(array('id' => $uid))->save($update);
        if ($res) {
            return true;
        }
        clean_query_user_cache($uid, 'password');

        return true;
    }

}
