<?php
/**
 * 验证
 */

namespace App\Controller;

require_once(APP_PATH . '/User/Conf/config.php');

class VerifyController extends BaseController{

    /**
     * sendVerify 发送验证码
     */
    public function sendSms()
    {
		$http_request_mode = 'request';
        $mobile = I($http_request_mode.'.mobile', '', 'op_t');
        $type = I($http_request_mode.'.type', '', 'op_t');
        
        if(!check_mobile($mobile)){
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'手机格式不正确',  'data' => (object)array());
        	exit;
        }
        
        // TODO session会失效，待检查
        $time = time();
        $resend_time =  modC('SMS_RESEND','60','USERCONFIG'); // 验证码发送间隔时间，默认60秒
        
        //if($time <= session('verify_time')+$resend_time ){
        $last_time = S('verify_time_'.$mobile);
        if($time <= $last_time + $resend_time ){
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'请'.($resend_time-($time-$last_time)).'秒后再发送',  'data' => (object)array());
        	exit;
        }
        
        switch ($type){
            case 'register':
                if(D('User/UcenterMember')->getUidByMobile($mobile)){
                	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'手机号已被注册',  'data' => (object)array());
                	exit;
                }
                break;
            case 'findpassword':
                if(!D('User/UcenterMember')->getUidByMobile($mobile)){
                	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'手机号未注册',  'data' => (object)array());
                	exit;
                }
                break;
            case 'bind':
                if(D('User/UcenterMember')->getUidByMobile($mobile)){
                	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'手机号已被注册',  'data' => (object)array());
                	exit;
                }
                break;
            default:
            	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'非法请求',  'data' => (object)array());
            	exit;
                break;
        }

        $verify = D('Verify')->addVerify($mobile, 'mobile');
        if (!$verify) {
        	$this->result = array('code'=>self::ERROR_CODE, 'msg'=>'发送失败',  'data' => (object)array());
        	exit;
        }

        $res =  A('Ucenter/Member')->doSendVerify($mobile, $verify, 'mobile');
        //$res = sendSMS($mobile, $verify);
        if ($res === true) {
            //session('verify_time',$time);
            S('verify_time_'.$mobile, $time); // 时间控制改用缓存，session问题有空再查
        	$this->result = array('code'=>self::SUCCESS_CODE, 'msg'=>'发送成功', 'data'=>array('code' => $verify['code']));
        	exit;
        } else {
            $this->result = array('code'=>self::ERROR_CODE, 'msg'=>$res,  'data' => (object)array());
            exit;
        }

    }
    
    /**
     * 验证短信
     */
    public function verifySms(){
		$http_request_mode = 'request';
        $mobile = I($http_request_mode.'.mobile', '', 'op_t');
        $code = I($http_request_mode.'.code', '', 'op_t');

        if (D('Verify')->checkVerify($mobile, 'mobile', $code, 0)) {
        	$this->result = array('code'=>self::SUCCESS_CODE, 'msg'=>'验证成功',  'data' => (object)array());
        	exit;
        } else {
            $this->result = array('code'=>self::ERROR_CODE, 'msg'=>'验证失败',  'data' => (object)array());
            exit;
        }
    }

}