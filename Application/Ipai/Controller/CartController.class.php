<?php

namespace Ipai\Controller;

use Think\Controller;

class CartController extends Controller {

    /**
     * 添加到购物车
     * @author zhangby
     */
    function addCart() {
        $this->_check_add_cart();
        $num = I('post.num', 0, 'intval');
        $pid = I('post.pid', 0, 'intval');
        $rs = array('status' => 0, 'message' => '');
        $r = D('PaiCart')->addCart(is_login(), array($pid => $num));
        if ($r) {
            $rs['status'] = 1;
            $rs['message'] = '添加到购物车成功!';
        } else {
            $rs['status'] = 0;
            $rs['message'] = '添加到购物车失败!';
        }
        $this->ajaxReturn($rs, 'json');
    }

    /**
     * 删除购物车物品
     * @author zhangby
     */
    function deleteGoods($id = 0) {
        if (!$id) {
            $pid = I('post.pid', 0, 'intval');
            $data = $this->_del_goods($pid);
            $this->ajaxReturn($data, 'json');
        }else{
             $data = $this->_del_goods($id);
            return json_encode($data);
        }
    }

    /**
     * 设置购物车物品数量
     * @author zhangby
     */
    function setCartGoodsNum() {
        $num = I('post.num', 0, 'intval');
        $pid = I('post.pid', 0, 'intval');
        $rs = array('status' => 0, 'message' => '');
        if (!is_login()) {
            $rs['status'] = 0;
            $rs['message'] = '请先登录';
            $this->ajaxReturn($rs, 'json');
        }
        if ($num && $pid) {
            $r = D('PaiCart')->setCartGoodsNum(is_login(), array($pid => $num));
            if ($r) {
                $rs['status'] = 1;
                $rs['message'] = '添加到购物车成功!';
            } else {
                $rs['status'] = 0;
                $rs['message'] = '添加到购物车失败!';
            }
            $this->ajaxReturn($rs, 'json');
        }
        $rs['status'] = 0;
        $rs['message'] = '没有选择商品或数量!';
        $this->ajaxReturn($rs, 'json');
    }

    /**
     * 检测添加到购物车条件
     * @author zhangby
     */
    private function _check_add_cart() {
        $num = I('post.num', 0, 'intval');
        $pid = I('post.pid', 0, 'intval');
        $rs = array('status' => 0, 'message' => '');
        if (!IS_POST) {
            $rs['status'] = 0;
            $rs['message'] = '非法请求';
            $this->ajaxReturn($rs, 'json');
        }
        if (!is_login()) {
            $rs['status'] = 0;
            $rs['message'] = '请先登录';
            $this->ajaxReturn($rs, 'json');
        }
        if ($num <= 0) {
            $rs['status'] = 0;
            $rs['message'] = '请添加商品数量';
            $this->ajaxReturn($rs, 'json');
        }
        if ($pid <= 0) {
            $rs['status'] = 0;
            $rs['message'] = '没有选择商品';
            $this->ajaxReturn($rs, 'json');
        }
    }

    private function _del_goods($id) {
        $pid = $id;
        $rs = array('status' => 0, 'message' => '');
        if (!is_login()) {
            $rs['status'] = 0;
            $rs['message'] = '请先登录';
            return $rs;
        }

        D('PaiCart')->deleteCart(is_login(), $pid);

        $rs['status'] = 1;
        $rs['message'] = '删除成功!';
        return $rs;
    }

}
