<?php
namespace Ipai\Model;
use Think\Model;

class BuyMenuModel extends Model
{
    protected $_validate = array(
        array('num', 'number', '只允许填数字'),
        array('num', 'require', '商品次数不能为空'),
    );
    
    protected $_auto = array (
        array('create_time','microtime_float',1,'callback'), 
    );
    
    function microtime_float()
    {
       $str = microtime(true);
       if (strlen($str) == 15) {
           $str = substr($str, 0, -1) ;
       }
       list($t1, $t2) = explode(".", $str);
       return $t1.$t2;
    }
    
    //获取购买次数
    public function getBuyNum($id) {
        $info = $this->where(array('id'=>$id))->find();
        $num = $info['num'];
        return $num;
    }    
}