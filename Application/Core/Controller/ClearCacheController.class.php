<?php

namespace Core\Controller;

use Think\Controller;

/**
 * 缓存清理
 *         
 */
class ClearCacheController extends Controller {

    var $token = "kljH987LKH6987LKJHLHlko7i7ohjlkjlkj";

    function __construct() {
        parent::__construct();
        $this->_check_server();
    }

    function _check_server() {
        $ip = $_SERVER['REMOTE_ADDR']; //请求ip
//        $host = strtolower($_SERVER['HTTP_HOST']);
//        $pos = strpos($host, "i.cn");   
        if ($ip !='127.0.0.1') {
            exit('非法请求！');
        }
      
        $time = I('get.time', time(), 'intval');
        $key = I('get.key', '', 'op_t');echo $this->get_enp_params_str();
        $str = md5(md5($this->get_enp_params_str() . $this->token) . substr($time, 2, 5));
        if ($key != $str) {
            exit('数据指纹有误！');
        }
    }

    /**
     * 取得加密请求参数
     */
    function get_enp_params_str() {
        $params = $_GET;       
        unset($params['key'], $params['time']);
        ksort($params);                
        return http_build_query($params);
    }

    /**
     * 清除pai_product行缓存
     */
    public function clearProduct() {
        $i = I('get.id', 0, 'intval');
        $key = D('Ipai/PaiProduct')->getCacheKeyMain($i);
        S($key, NULL);
        exit( '操作成功！');
    }

}
