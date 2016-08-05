<?php

namespace Cli\Controller;

use Think\Controller;
use Think\Model;

/**
 * 爱拍本地运行脚本
 * @author zhangby
 */
class IpaiController extends Controller {

    /**
     * 处理开奖
     * @return type
     */
    public function kaiJiang() {
        $rows = M('PaiProduct')->where(array('over_time' => array('gt', 0), 'surplus_num' => 0, 'status' => 5))->select();
        if (!$rows) {
            return;
        }
        foreach ($rows as $v) {
            print_r($v);
            $ot = substr($v['open_time'], 0, 10);
            $dt = date('Ymd', $ot);
            $time = 60 * (date('H', $ot) * 60 + date('i', $ot)); //echo $d2;
            $cr = $this->_get_chongqing_shishi_cai($dt, $time);
            if ($cr) {
                $this->_up_open_win_data($v, $cr);
            }
        }
    }

    /**
     * 更新流拍数据
     * @return type
     */
    public function liuPai() {
        $params = array();
        $params['where'] = array('liupai_time' => array('elt', time()), 'status' => 2);
        $params['field'] = "*";
        $rows = M('PaiProduct')->getList($params);
        if (!$rows) {
            return;
        }

        foreach ($rows as $v) {
            print_r($v);
            $this->_process_liupai($v);
        }
    }

    /**
     * 定时发布
     */
    public function dingShiFaBu() {
        GF('Common/function', 'Ipai');
        $where = array();
        $where['release_status'] = 0;
        $where['audit_status'] = 1;
        $where['added_type'] = 2;
        $where['added_timing'] = array('elt', time());
        $rows = M('PaiProductCommon')->where($where)->select();
        if ($rows) {
            foreach ($rows as $r) {
                print_r($r);
                generate_product($r['id']);
            }
        }
    }
    
    /**
     * 退回商家保证金、交易款
     * @return type
     */
     public function backMoney() {
        $params = array();
        $params['where'] = array('back_time' => array('elt', time()), 'is_back' => 0,'send_status'=>4,'status'=>4);
        $params['field'] = "*";
        $rows = M('PaiProduct')->getList($params);
        if (!$rows) {
            return;
        }

        foreach ($rows as $v) {
            print_r($v);
            $this->_back_shop_back_money($v);
        }
    }
    

    //=============================私有函数================================================
    /**
     * 处理流拍信息
     * @param type $data
     */
    private function _process_liupai($data) {
        S(D('Ipai/PaiProductCommon')->getCacheKeyMain($data['pid']), NULL);
        $detail = M('PaiProductCommon')->where(array('id' => $data['pid']))->find();
        if (!$detail) {
            return FALSE;
        }

        $model = new Model();
        $model->startTrans();
        $flag = TRUE;


        $rs = $model->table('__PAI_PRODUCT__')->where(array('id' => $data['id']))->save(array('status' => 3)); 
        if (!$rs) {
            $flag = FALSE;
        } else {
            //清除爱拍缓存
            S(D('Ipai/PaiProduct')->getCacheKeyMain($data['id']), NULL);
            //处理用户
            $param = array();
            $param['where'] = array('pid' => $data['id'], 'status' => 0);
            $param['field'] = '*';
            $orders = M('PaiOrder')->getList($param);
            if ($orders) {//退回用户订单爱币
                foreach ($orders as $o) {
                    $rs = $model->table('__PAI_ORDER__')->where(array('id' => $o['id']))->save(array('status' => 1));
                    if (!$rs) {
                        $flag = FALSE;
                        break;
                    }
                    //清除订单缓存
                    S(D('Ipai/PaiOrder')->getCacheKey($o['id']), NULL);
                    //退回爱币
                    $rs_m = $model->table('__MEMBER__')->where(array('uid' => $o['uid']))->setInc('score2', $o['num']);
                    if (!$rs_m) {
                        $flag = FALSE;
                        break;
                    }
                    //清除用户字段缓存
                    clean_query_user_cache($o['uid'], 'score2');
                    //记入日志
                    $log = array();
                    $log['type'] = 0;
                    $log['status'] = 1;
                    $log['uid'] = $o['uid'];
                    $log['obj_id'] = $o['id'];
                    $log['create_time'] = time();
                    $log['funds'] = floatval($o['num']);
                    $log['message'] = '商品流拍退回爱币';
                    $log['module'] = 'script';
                    $log['mod_event'] = 'liupai';
                    M('FundsLog')->add($log);
                }
            }
          

            //处理商家
            $rs_c = $model->table('__PAI_PRODUCT_COMMON__')->where(array('id' => $data['pid']))->save(array('surplus_no' => 0)); 
            //这里不判断受影响行数，如果是最后一期则surplus_no为0则不会返回影响行数
//            if (!$rs_c) {
//                $flag = FALSE;
//            } else {
                //清除产品基表缓存
                S(D('Ipai/PaiProductCommon')->getCacheKeyMain($data['pid']), NULL);
                //退回流拍和未发布期数保证金  
                $security_deposit = floatval($detail['price']) * ($detail['security_deposit'] / 100);
                //$balance = $security_deposit / $detail['release_num'];//平均每期保证金
                $money = round($security_deposit)*($detail['surplus_no'] + 1) ;//退回保证金=平均每期保证金*剩余期数
                $uid = $detail['uid'];
                $sql = "UPDATE __PREFIX__user_fund SET `balance`=`balance`+$money,`frozen`=`frozen`-$money WHERE `uid`=$uid";
                $rs_f = $model->execute($sql);
                if (!$rs_f) {
                    $flag = FALSE;
                } else {
                    //清除用户字段缓存
                    clean_query_user_cache($o['uid'], array('balance', 'frozen'));
                    //记入日志
                    $log = array();
                    $log['type'] = 1;
                    $log['status'] = 1;
                    $log['uid'] = $uid;
                    $log['obj_id'] = $data['id'];
                    $log['create_time'] = time();
                    $log['funds'] = floatval($money);
                    $log['message'] = '商品流拍退回保证金';
                    $log['module'] = 'script';
                    $log['mod_event'] = 'liupai';
                    M('FundsLog')->add($log);
                }
//            }
        }

        //事务提交
        if ($flag) {
            $model->commit();
            return TRUE;
        } else {
            $model->rollback();
        }

        return FALSE;
    }

    /**
     * 处理开奖数据
     */
    private function _up_open_win_data($data, $cr) {
        $params = array();
        $params['page'] = 1;
        $params['field'] = '*';
        $params['limit'] = 50;
        $params['order'] = 'create_time DESC';
        $params['where'] = array('create_time' => array('lt', $data['over_time']));
        $record = M('PaiOrder')->getList($params);
        $time_sum = 0;
        foreach ($record as $r) {
            $t = str_replace('.', '', date_fmt('his', $r['create_time']));
            $time_sum+=floatval($t);
        }
        $win_code = fmod(floatval($time_sum) + floatval($cr['result']), floatval($data['need_num'])) + 10000001;

        $where = 'o.pid=' . $data['id'] . " AND c.codes LIKE '%,$win_code,%' ";
        $win_order = D('Ipai/PaiOrder')->getOrderLinkCodeList($where, 1);
        if ($win_order) {
            $win_order = $win_order[0];
            //更新中奖数据
            $arr = array();
            $arr['status'] = 4;
            $arr['send_status'] = 1;
            $arr['rno'] = $win_code;
            $arr['uid_win'] = $win_order['uid'];
            $arr['order_id'] = $win_order['id'];
            $arr['winning_num'] = $cr['result'];

            $day = modC('GET_BACK_MONEY', 0, 'IPAI');
            $arr['back_time'] = strtotime("+$day days");

            //非商品生产服务券号
            $proCom = M('PaiProductCommon')->where(array('id' => $data['pid']))->find();
            if ($proCom && $proCom['type_first'] == 0) {
                $arr['send_status'] = 4;
                $arr['server_code'] = (time() + (date('Ymd') . '00')) . rand(10, 99);
            }

            M('PaiProduct')->where(array('id' => $data['id']))->save($arr);
            S(D('Ipai/PaiProduct')->getCacheKeyMain($data['id']), NULL);
        }
    }

    /**
     * 取得重庆时时彩
     */
    private function _get_chongqing_shishi_cai($d, $ot) {
        $row = M('CqsscConfig')->where(array('time' => array('gt', $ot)))->order(array('time' => 'asc'))->find();
        if ($row) {
            $cp = M('CqsscResult')->where(array('period' => $row['period'], 'date' => $d))->find();
            return $cp;
        }
        return FALSE;
    }

    /**
     * 退回商家保证金
     */
    private function _back_shop_back_money($data) {
        //清除产品基表缓存
        S(D('Ipai/PaiProductCommon')->getCacheKeyMain($data['pid']), NULL);
        $detail = M('PaiProductCommon')->where(array('id' => $data['pid']))->find();
        if (!$detail) {
            return FALSE;
        }

        $model = new Model();
        $model->startTrans();
        $flag = TRUE;

        $rs_p = $model->table('__PAI_PRODUCT__')->where(array('id' => $data['id'], 'is_back' => 0))->save(array('is_back' => 1));
        if (!$rs_p) {
            $flag = FALSE;
        } else {
             S(D('Ipai/PaiProduct')->getCacheKeyMain($data['id']), NULL);
            //退回保证金
            $balance = floatval($detail['price']) * ($detail['security_deposit'] / 100);
            //$balance = $security_deposit / $detail['release_num'];//平均每期保证金               
            $money = round($balance);//当期保证金
            $price=  floatval($detail['price'])+floatval($money);//总退回金额=当期保证金+交易额
            $uid = $detail['uid'];
            $sql = "UPDATE __PREFIX__user_fund SET `balance`=`balance`+$price,`frozen`=`frozen`-$money WHERE `uid`=$uid";
            $rs_f = $model->execute($sql);
            if (!$rs_f) {
                $flag = FALSE;
            } else {
                //清除用户字段缓存
                clean_query_user_cache($uid, array('balance', 'frozen'));
                //记入日志
                $log = array();
                $log['type'] = 1;
                $log['status'] = 1;
                $log['uid'] = $data['uid'];
                $log['obj_id'] = $data['id'];
                $log['create_time'] = time();
                $log['funds'] =$price;
                $log['message'] = '交易完成获得交易款（￥'.$detail['price'].'）及退回保证金(￥'.$money.')';
                $log['module'] = 'script';
                $log['mod_event'] = 'backmoney';
                M('FundsLog')->add($log);
            }
        }

        //事务提交
        if ($flag) {
            $model->commit();
            return TRUE;
        } else {
            $model->rollback();
        }
        return FALSE;
    }
    
    /**
     * 时时彩开奖结果采集，每天0点-2点，10点-24点，每5分钟一次，3分开始
     * 3,13,23,33,43,53,8,18,28,48,38,58 0,1,10,11,12,13,14,15,16,17,18,19,20,21,22,23 * * * /usr/local/php/bin/php /web/i.cn/wwwroot/cli.php ipai/sscResult
     * @author dpj 20160405
     */
    public function sscResult(){
        $date = date('Ymd');
        $time = 60 * (date('H') * 60 + date('i'));

        $row = M('CqsscConfig')->where(array('time' => array('gt', $time)))->order(array('time' => 'asc'))->find();
        if ($row) {
            $cp = M('CqsscResult')->where(array('period' => $row['period'], 'date' => $date))->find();
        }
        
        // 开奖记录不存在，采集
        if(!$cp){
            $period = substr($date, 2).$row['period'];
            $res = $this->_collect_ssc_result($period);
            if(!$res){
                sleep(2);
                $res = $this->_collect_ssc_result($period);
            }
            
            if($res){
                $data['date'] = $date;
                $data['period'] = $row['period'];
                $data['result'] = $res;
                $data['create_time'] = time();
                M('CqsscResult')->add($data);
            }
        }
    }
    
    /**
     * 采集时时彩开奖结果
     */
    protected function _collect_ssc_result($period){
        $url = 'http://cp.360.cn/i/lotapi.html?datatype=json&do=qkjcode&lotID=255401&issue='.$period; //160405029;
        var_dump($url);
        $res = @file_get_contents($url); // [{"code":"8,4,6,1,9","sw":"\u5c0f\u5355","gw":"\u5927\u5355","issue":"160405029","h2Dx":"1:1","h2Jo":"2:0","h2Style":"","h3Dx":"2:1","h3Jo":"2:1","h3Style":"\u7ec4\u516d"}]
        
        $json = json_decode($res, true);
        if(!$json[0]['code']){
            return false;
        }
        return str_replace(',', '', $json[0]['code']);
    }

}
