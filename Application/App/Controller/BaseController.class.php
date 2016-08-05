<?php
namespace App\Controller;
use Think\Controller;
use Think\Log;

class BaseController extends Controller
{
    
    public $result;
    protected $_secret = 'ICNAPI';
    
    const SUCCESS_CODE = '1';
    const ERROR_CODE = '0';
    
    public function _initialize(){
        header("Content-Type:text/html; charset=utf-8");
        $signature = $this->_sign($_REQUEST);
        $sign = trim($_REQUEST['sign']);
        //var_dump($signature.'--'.$sign);
        Log::write(json_encode($_REQUEST));
        
        /* if($sign != $signature){ // 签名不正确
            $this->result = array('code'=>self::ERROR_CODE, 'msg'=>'签名不正确', 'data'=>array('md5'=>$signature));
            exit;
        } */
        $this->result = array('code' => self::SUCCESS_CODE, 'msg' => "成功", 'data'=>(object)array());
    }
    
    /**
     * @desc 对指定的数据进行签名请求
     * @param string $param，区分大小写
     * @return string 
     */
    protected function _sign($param){
        //$param['ma'] = $param['_URL_'][0].'/'.$param['_URL_'][1];
        unset($param['sign']);
        ksort($param);
        return md5(implode($param).$this->_secret);
    }
    
    public function __destruct(){
        //$this->ajaxReturn($this->result, 'json');exit;
        exit(json_encode($this->result));
    }
    
}