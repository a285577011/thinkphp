<?php
/**
 * Created by i.cn.
 * User: zhangby
 * Date: 2015/12/25
 */

namespace Isay\Controller;


use Think\Controller;

class BaseController extends  Controller{   
    public function _initialize(){
                  
    }
    
      /**
     * checkIsLogin  判断是否登录
     * 
     */
      protected function checkIsLogin() {
        if (!is_login()) {
            $this->error(L('_ERROR_PLEASE_FIRST_LOGIN_'));
        }
    }
}