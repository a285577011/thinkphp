<?php
namespace App\Controller;

class PublicController extends BaseController
{

    public function sendsms ()
    {
        
        $this->result = array('code'=>0, 'msg'=>'失败',  'data' => (object)array());
        exit;
    }
    
    /**
     * 地区列表
     */
    public function district(){
        $http_request_mode = 'request';
        $pid = I($http_request_mode.'.pid', 0, 'intval');
        
        $data = D('District')->getByPid($pid);
        $this->result['data'] = $data;
    }
    
    /**
     * 省市
     */
    public function city(){
        $data = D('District')->getByPid();
        foreach ($data as $k => &$v){
            $v['city'] = D('District')->getByPid($v['id']);
        }
        $this->result['data'] = $data;
    }
    
}