<?php

namespace Ipai\Controller;

use Think\Controller;

class CommController extends Controller {

    public function delGoods(){
        $pid = I('get.pid', 0, 'intval');
        $data=R('Ipai/Cart/deleteGoods',array('id'=>$pid));
        echo 'var del_goods_sts='.$data;
    }
    /**
     * 购物车
     * @author zhangby
     */
    public function cart() {
        $uid = is_login();
        $rs = D('Ipai/PaiCart')->getCart($uid);
        $rows = array();
        $count = 0;
        foreach ($rs as $k => $v) {
            $count+=$v;
            $row = D('Ipai/PaiProduct')->getDetail($k);
            $r['url'] = U('/Index/details@1', array('id' => $k));
            $r['num'] = $v;
            $r['price'] = sprintf("%.2f", $v);
            $r['id'] = $k;
            $r['img'] = getThumbImageById($row['productinfo']['imgs'][0], 150, 150);
            $r['title'] = $row['productinfo']['name'];
            $rows[] = $r;
        }
       $js='var cart_list='.json_encode(array('status' => 1, 'data' => $rows, 'count' => $count));
       echo $js;
    }

    /**
     * 检查开奖结果
     */
    public function checkOpenLottery() {
        $pid = I('get.id', 0, 'intval');

        $row = D('Ipai/PaiProduct')->getById($pid);
        if (!$row) {
            $this->ajaxReturn(array('status' => 0, 'msg' => '商品不存在！'));
        }
        $this->ajaxReturn(array('status' => 1, 'msg' => $row));
    }

    public function uploadPictrue() {
        $file = A('Core/File');
        $file->uploadPicture();
    }

    //获取中国省份信息
    public function getProvince() {
        if (IS_AJAX) {
            $pid = I('pid');  //默认的省份id

            if (!empty($pid)) {
                //$map['id'] = $pid;
            }
            $map['where']['level'] = 1;
            $map['where']['upid'] = 0;
            $map['field'] = '*';
            $list = D('District')->_list($map);

            $data = "<option value =''>" . L('_PROVINCE_') . "</option>";
            foreach ($list as $k => $vo) {
                $data .= "<option ";
                if ($pid == $vo['id']) {
                    $data .= " selected ";
                }
                $data .= " value ='" . $vo['id'] . "'>" . $vo['name'] . "</option>";
            }
            $this->ajaxReturn($data);
        }
    }

    //获取城市信息
    public function getCity() {
        if (IS_AJAX) {
            $cid = I('cid');  //默认的城市id
            $pid = I('pid');  //传过来的省份id

            if (!empty($cid)) {
                //$map['id'] = $cid;
            }
            $map['where']['level'] = 2;
            $map['where']['upid'] = $pid;
            $map['field'] = '*';

            $list = D('District')->_list($map);

            $data = "<option value =''>" . L('_CITY_') . "</option>";
            foreach ($list as $k => $vo) {
                $data .= "<option ";
                if ($cid == $vo['id']) {
                    $data .= " selected ";
                }
                $data .= " value ='" . $vo['id'] . "'>" . $vo['name'] . "</option>";
            }
            $this->ajaxReturn($data);
        }
    }

    //获取区县市信息
    public function getDistrict() {
        if (IS_AJAX) {
            $did = I('did');  //默认的城市id
            $cid = I('cid');  //传过来的城市id

            if (!empty($did)) {
                //$map['id'] = $did;
            }
            $map['where']['level'] = 3;
            $map['where']['upid'] = $cid;
            $map['field'] = '*';

            $list = D('District')->_list($map);

            $data = "<option value =''>" . L('_DISTRICT_') . "</option>";
            foreach ($list as $k => $vo) {
                $data .= "<option ";
                if ($did == $vo['id']) {
                    $data .= " selected ";
                }
                $data .= " value ='" . $vo['id'] . "'>" . $vo['name'] . "</option>";
            }
            $this->ajaxReturn($data);
        }
    }

    /**
     * 验证余额
     * @author zhagnby
     */
    public function checkMoney() {
        $uid = is_login();
        $money = I('post.money', '0', 'intval');
        $money = abs($money);
        $err = array('status' => 0, 'msg' => '');
        if (!IS_POST) {
            $err['msg'] = '非法请求！';
            $this->ajaxReturn($err);
        }
        if (!$uid) {
            $err['msg'] = '请登录！';
            $this->ajaxReturn($err);
        }
        if ($money < 1) {
            $err['msg'] = '价格输入错误！';
            $this->ajaxReturn($err);
        }

        $balance = D('Ucenter/UserFund')->getUserBalance($uid);
        if ($balance <= 0 || floatval($money) > floatval($balance)) {
            $err['msg'] = '余额不足！';
            $err['money'] = $money;
            $this->ajaxReturn($err);
        }

        $err['status'] = 1;
        $this->ajaxReturn($err);
    }

}
