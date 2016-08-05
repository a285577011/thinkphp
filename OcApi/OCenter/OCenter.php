<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-3-3
 * Time: 下午3:23
 * 
 */


error_reporting(0);
define('OC_ROOT', substr(__FILE__, 0, -11));


class OCApi
{
    /**
     * ocPost  执行方法
     * @param $model
     * @param $action
     * @param array $args
     * @return mixed
     * 
     */
    function ocPost($model, $action, $args = array())
    {
        global $oc_model;
        if (empty($oc_model[$model])) {
            include_once OC_ROOT . "Model/$model.php";
            $oc_model[$model] = new $model();
        }
        $action = 'do' . ucfirst($action);
        return $oc_model[$model]->$action($args);
    }

    /**
     * ocUserLogin  登录
     * @param $username
     * @param $password
     * @return mixed
     * 
     */
    function ocUserLogin($username, $password)
    {

        $return = $this->ocPost('User', 'login', array('username' => $username, 'password' => $password));
        return $return;
    }

    /**
     * ocGetUserInfo 获取用户信息
     * @param string $where
     * @return mixed|string
     * 
     */
    function ocGetUserInfo($where = '')
    {
        $return = '无法查找';
        if ($where) {
            $return = $this->ocPost('User', 'getUserInfo', $where);
        }
        return $return;
    }

    /**
     * ocSynLogin  同步登录
     * @param $uid
     * @return mixed
     * 
     */
    function ocSynLogin($uid)
    {
        $return = $this->ocPost('User', 'synLogin', $uid);
        return $return;
    }

    /**
     * ocSynLogout 同步登出
     * @return mixed
     * 
     */
    function ocSynLogout()
    {
        $return = $this->ocPost('User', 'synLogout', null);
        return $return;
    }

}