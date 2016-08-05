<?php

namespace Ipai\Controller;

use Think\Controller;
use Think\Model;

class OrderController extends Controller {

    var $my_user = null;

    public function __construct() {
        parent::__construct();
        $this->my_user = query_user(array('uid', 'nickname', 'weixin', 'avatar', 'score2', 'balance', 'ipai'), is_login());
        $this->assign('my_user', $this->my_user);
    }

    public function addNum() {
        $this->_check_login();
        $id = I('post.id');
        $uid = is_login();

        $PaiCartModel = D('PaiCart');
        $PaiCartModel->addCart($uid, array($id => 1));
    }

    public function deleteNum() {
        $this->_check_login();
        $id = I('post.id');
        $uid = is_login();

        $PaiCartModel = D('PaiCart');
        $PaiCartModel->deleteCart($uid, $id);
    }

    public function join() {
        $this->_check_login();
        $uid = is_login();

        $PaiCartModel = D('PaiCart');
        $result = $PaiCartModel->getCart($uid);

        $row = array();
        foreach ($result as $k => $v) {
            //$this->_check_product_is_buy($k, $v);
            $PaiProductModel = D('PaiProduct');
            $r = $PaiProductModel->getDetail($k);
            $r['buy_num'] = $v;
            $row[] = $r;
        }
        $data = array();
        $data['list'] = $row;
        $data['related'] = D('Common/Member')->similarList(isset($row[0]) ? $row[0]['user']['catid'] : 1, 24);

        // print_r($data);exit;


        $this->assign($data);
        $this->display();
    }

    /**
     * 提交生成订单
     */
    public function getnumber() {
        $this->_check_login();
        $info = array();

        $ids = I('post.ids', 0, 'op_t');
        $token_form = I('post.token_form', '');
        $s_token = session('token_form');
        if ($token_form && $token_form == $s_token) {
            $this->error('请不要重复提交！');
        }

        $datas = D('PaiCart')->getCart(is_login());
        $list = array();
        foreach ($ids as $id) {
            if (isset($datas[$id]) && $datas[$id]) {
                $list[$id] = $datas[$id];
            }
        }

        if (!$list) {
            $this->error('没有选择商品！');
        }

        //这里判断各种条件
        $this->_check_commit_order($list);

        $model = new Model();
        $model->startTrans();
        $flag = TRUE;
        $uid = is_login();
        $pids = array();
        foreach ($list as $k => $v) {
            $this->_check_product_is_buy($k, $v);
            //分配开奖码
            $PaiProductModel = D('PaiProduct');
            $info[$k] = $PaiProductModel->getDetail($k);
            for ($x = 0; $x < $v; $x++) {
                $info[$k]['code'] .= empty($info[$k]['code']) ? (',' . (10000001 + $info[$k]['join_num'] + $x) . ',') : ( (10000001 + $info[$k]['join_num'] + $x) . ',');
            }
            $pids[] = $info[$k]['pid'];

            //保存CODE数据
            $code = array();
            $code['codes'] = $info[$k]['code'];
            $PaiCodeModel = D('PaiCode');
            $cid = $PaiCodeModel->add($code);
            if (!$cid) {
                $flag = FALSE;
                break;
            }

            //添加订单
            $order = array();
            $order['uid'] = $uid;
            $order['cid'] = $cid;
            $order['pid'] = $info[$k]['id'];
            $order['ip'] = get_client_ip(1);
            $order['sno'] = msectime() . rand(100, 999);
            $order['create_time'] = msectime();
            $order['num'] = $v;
            $oid = D('PaiOrder')->add($order);
            if (!$oid) {
                $flag = FALSE;
                break;
            }
            $info[$k]['orderid'] = $oid;
            $info[$k]['create_time'] = $order['create_time'];

            //统计变更
            $PaiProductModel->countNum($k, $v);

            $info[$k]['codes'] = explode(',', $code['codes']);
            $info[$k]['codes'] = array_filter($info[$k]['codes']);
            if (count($info[$k]['codes']) > 9) {
                $info[$k]['head'] = array_slice($info[$k]['codes'], 0, 9);
            }
            $info[$k]['num'] = $v;

            //扣除爱币余额
            $this->_deduct_ib_money($order['num'], $oid);
        }

        if ($token_form && $token_form != $s_token) {
            session('token_form', $token_form);
        }

        if ($flag) {
            $model->commit();
            //删除购物车缓存
            foreach ($list as $k => $v) {
                D('PaiCart')->deleteCart($uid, $k);
            }
        } else {
            $model->rollback();
        }

        foreach ($pids as $id) {
            generate_product($id); //自动生成下一条爱拍
        }

        $data = array();
        $data['info'] = $info;
        $data['related'] = D('Common/Member')->similarList(1, 24);
        $data['status'] = D('PaiProduct')->getStatus();


        //页面渲染
        $this->assign($data);
        $this->display();
    }

    /**
     * 取得我的某条订单开奖码
     */
    public function itsallnumber() {
        if (!is_login()) {
            $this->ajaxReturn(array('status' => 0, 'msg' => '请先登录！'));
        }
        $pid = I('get.oid', 0, 'intval');
        $order = D('Ipai/PaiOrder')->getById($pid);
        if (!$order) {
            $this->ajaxReturn(array('status' => 0, 'msg' => '暂无数据'));
        }
        $code = D('Ipai/PaiCode')->getById($order['cid']);
        $codes = explode(',', $code['codes']);
        $codes = array_filter($codes);

        $this->assign('codes', $codes);
        $this->assign('join_tal_num', $order['num']);
        $html = $this->fetch(T('itsallnumber'));
        $this->ajaxReturn(array('status' => 1, 'msg' => $html));
    }

    public function agreement() {
        $this->display();
    }

    /**
     * 选择付款类型
     */
    public function pay() {
        $this->_check_login();
        $ids = I('post.ids', 0, 'op_t');
        $datas = D('PaiCart')->getCart(is_login());
        $list = array();
        if (!$ids) {
            $this->error('请先选择商品！');
        }

        $talnum = 0;
        foreach ($ids as $id) {
            if (isset($datas[$id]) && $datas[$id]) {
                $talnum += $datas[$id];
                $row = array('num' => $datas[$id], 'goods' => D('PaiProduct')->getDetail($id));
                if ($row['goods']) {
                    $this->_check_product_is_buy($id, $datas[$id]);
                }
                $list[] = $row;
            }
        }

        if (!$list) {
            $this->error('请先购买商品！');
        }
        
        $district = D('Common/District')->getAllRows();       
        $district = field2array_key($district, 'id');
        list($address, $add_count) = D('Common/UserAddress')->getListByPage(array('uid' => is_login()), 1, 10);

        $token_form = md5(json_encode($datas));
        $this->assign('address', $address);
        $this->assign('district', $district);
        $this->assign('token_form', $token_form);
        $this->assign('talnum', $talnum);
        $this->assign('list', $list);
        $this->display();
    }

    //===================private fuunction==========================

    private function _check_login() {
        $err = array('status' => 0, 'message' => '请登录！');
        if (is_login()) {
            return TRUE;
        }

        if (IS_AJAX) {
            $this->ajaxReturn($err);
        } else {
            $this->error('请登录！');
        }
    }

    //取得购物车商品总数量
    private function _get_cart_goods_count() {
        $datas = D('PaiCart')->getCart(is_login());
        $talnum = 0;
        foreach ($datas as $v) {
            $talnum += $v;
        }
        return $talnum;
    }

    /**
     * 扣除爱拍币和余额
     * @author zhangby
     */
    private function _deduct_ib_money($total, $oid) {
        $ibpay = I('post.ibpay', 0, 'intval');
        $money = I('post.money', 0, 'intval');
        if (!empty($money)) {
            //统一转换成爱币
            $mn = $total - $this->my_user['score2'];
            $mn = $mn > 0 ? $mn : $total;
            $r = D('Ucenter/UserFund')->upBalance(is_login(), 1, $mn);
            if ($r) {
                D('Common/FundsLog')->addLog(is_login(), 0, 1, 0, $mn, '爱拍消费余额转换爱币');
                $t = D('Common/Member')->upScore2(is_login(), 0, $mn);
                $t && D('Common/FundsLog')->addLog(is_login(), 0, 0, 1, $mn, '余额充值到爱币');
            }
        }

        $r = D('Common/Member')->upScore2(is_login(), 1, $total);
        $r && D('Common/FundsLog')->addLog(is_login(), $oid, 0, 0, $total, '参加爱拍消费');
        D('Common/User')->clean_query_user_cache(is_login(), array('score2', 'balance'));
    }

    /**
     * 检测提交订单相关条件
     * @author zhangby
     */
    private function _check_commit_order($datas) {
        $ibpay = I('post.ibpay', 0, 'intval');
        $money = I('post.money', 0, 'intval');

        $total = 0;
        foreach ($datas as $k => $v) {
            $row = D('PaiProduct')->getByIdNoCache($k);
            if ($row['surplus_num'] < $v) {
                $this->error('您拍的太多了,[' . $row['productinfo']['name'] . ']数量不足，请尝试减少数量。');
            }
            $total+=$v;
        }



        if (!empty($ibpay) && !empty($money)) { 
            $ib_sum = $this->my_user['score2'] + $this->my_user['balance'];
            if ($ib_sum < $total) {
                $this->error('您的爱币和余额不足，请选择其它方式支付。');
            }
        } elseif (!empty($ibpay)) {
            if ($this->my_user['score2'] < $total) {
                $this->error('您的爱币不足，请选择其它方式支付。');
            }
        } elseif (!empty($money)) {
            if ($this->my_user['balance'] < $total) {
                $this->error('您的余额不足，请选择其它方式支付。');
            }
        } else {
            $this->error('请选择支付方式。');
        }
    }

    /**
     * 检查所购买商品是否可买
     * @param type $id
     */
    private function _check_product_is_buy($id, $buy_num) {
        $params = array();
        $params['field'] = '*';
        $params['limit'] = 1;
        $params['where'] = array('id' => $id);
        $row = D('Ipai/PaiProduct')->getList($params);
        if ($row) {
            $row = $row[0];
        }

        $row['product'] = D('Ipai/PaiProductCommon')->getById($row['pid']);
        if ($row['status'] != 2) {
            $this->error('抱歉！目前【' . $row['product']['name'] . '】商品不可购买。');
        }

        if ($row['surplus_num'] < 1 || $buy_num > $row['surplus_num']) {
            $this->error('【' . $row['product']['name'] . '】剩余人次不足，请尝试减少数目。');
        }
    }

}
