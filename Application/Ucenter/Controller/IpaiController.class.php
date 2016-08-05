<?php

/**
 * 
 */

namespace Ucenter\Controller;

use Think\Controller;

/**
 * @author zhangby
 */
class IpaiController extends Controller {

    var $my_user = NULL;

    public function _initialize() {
        $uid = isset($_GET['uid']) ? op_t($_GET['uid']) : is_login();
        //调用API获取基本信息
        $this->userInfo($uid);
        $this->my_user = $this->userInfo(is_login());
    }

    /**
     * 查看发起的一元爱拍
     * @author zhangby
     */
    public function index() {
        $data = array();
        $uid = I('get.uid', is_login(), 'intval');
        $page = I('get.p', 1, 'intval');
        $product = D('Ipai/PaiProduct');

        if ($uid == is_login() && is_login()) {
            $this->redirect('Ucenter/Ipai/myIpai');
        }

        $where = array();
        $where['uid'] = $uid;
        list($data['rows'], $data['count']) = $product->getListByPage($where, $page, 'create_time DESC', '*', 10);


        $PaiProductCommon = D('Ipai/PaiProductCommon');
        $data['type'] = $PaiProductCommon->getType();
        $data['status'] = $product->getStatus();
        $data['auditStatus'] = $PaiProductCommon->getAuditStatus();


        $data['uid'] = $uid;
        //四处一词 seo     
        $str = '发起的一元爱拍-一元爱拍-';
        $this->setTitle($str . L('_INDEX_TITLE_'));
        $this->setKeywords($str . L('_PAGE_PERSON_'));
        $this->setDescription($str . L('_DE_PERSON_') . L('_PAGE_'));
        //四处一词 seo end

        $this->assign($data);
        $this->display();
    }

    /**
     * 参与一元爱拍
     * @author zhangby
     */
    public function participate() {
        $data = array();
        $data['show_rows'] = 10;

        $uid = I('get.uid', is_login(), 'intval');
        $page = I('get.p', 1, 'intval');

        $order = D('Ipai/PaiOrder');
        $where = array();
        $where['uid'] = $uid;
        list($data['rows'], $data['count']) = $order->getListByPage($where, $page, 'create_time DESC', '*', 10);
        foreach ($data['rows'] as &$v) {
            //取得产品信息
            $v['product'] = D('Ipai/PaiProduct')->getDetail($v['pid']);
            //取得CODES
            $codes = D('Ipai/PaiCode')->getById($v['cid']);
            if ($codes) {
                $v['codes'] = explode(',', $codes['codes']);
                $v['codes'] = array_slice(array_filter($v['codes']), 0, 11);
                $v['codes'] = implode('; ', $v['codes']);
            } else {
                $v['codes'] = '';
            }
        }

        $data['status'] = D('Ipai/PaiProduct')->getStatus();
        $data['uid'] = $uid;
        //print_r($data);exit;
        //四处一词 seo     
        $str = '参与的一元爱拍-一元爱拍-';
        $this->setTitle($str . L('_INDEX_TITLE_'));
        $this->setKeywords($str . L('_PAGE_PERSON_'));
        $this->setDescription($str . L('_DE_PERSON_') . L('_PAGE_'));
        //四处一词 seo end

        $this->assign($data);
        $this->display();
    }

    /**
     * 中奖记录
     */
    public function winning() {
        $data = array();

        $uid = I('get.uid', is_login(), 'intval');
        $page = I('get.p', 1, 'intval');

        $where = array();
        $where['uid_win'] = $uid ? $uid : -1;
        list($data['rows'], $data['count']) = D('Ipai/PaiProduct')->getListByPage($where, $page, 'open_time DESC', '*', 10);

        foreach ($data['rows'] as &$v) {
            $order = D('Ipai/PaiOrder')->getById($v['order_id']);
            $v['buy_time'] = $order ? $order['create_time'] : 0;
        }

        $data['uid'] = $uid;
        //四处一词 seo     
        $str = '中奖记录一元爱拍-';
        $this->setTitle($str . L('_INDEX_TITLE_'));
        $this->setKeywords($str . L('_PAGE_PERSON_'));
        $this->setDescription($str . L('_DE_PERSON_') . L('_PAGE_'));
        //四处一词 seo end

        $this->assign($data);
        $this->display();
    }

    /**
     * 我的发起记录
     * @author zhangby
     */
    public function myIpai() {
        $this->check_login();
        $data = array();
        $uid = is_login();
        $page = I('p', 1, 'intval');
        $status = I('status', -1, 'intval');
        $audit = I('audit', -1, 'intval');
        $search = I('search', '', 'op_t');
        $productC = D('Ipai/PaiProductCommon');


        $where = array();
        $where['uid'] = $uid;
        if ($status != -1) {
            $where['release_status'] = $status;
        }
        if ($audit != -1) {
            $where['audit_status'] = $audit;
        }
        if ($search) {
            $where['name'] = array('like', '%' . $search . '%');
        }
        list($data['rows'], $data['totalCount']) = $productC->getListByPage($where, $page, 'create_time DESC', '*', 10); //print_r($data);exit;
        foreach ($data['rows'] as &$v) {
            $v['imgs'] = explode(',', $v['attr_imgs']);
        }

        $data['type'] = $productC->getType();
        $data['releaseStatus'] = $productC->getReleaseStatus();
        $data['auditStatus'] = $productC->getAuditStatus();


        if ($search) {
            $data['search'] = $search;
        }
        $data['sts'] = $status;
        $data['audit'] = $audit;
        $data['uid'] = $uid;
        //四处一词 seo     
        $str = '发起记录-一元爱拍-';
        $this->setTitle($str . L('_INDEX_TITLE_'));
        $this->setKeywords($str . L('_PAGE_PERSON_'));
        $this->setDescription($str . L('_DE_PERSON_') . L('_PAGE_'));
        //四处一词 seo end

        $this->assign($data);
        $this->display('my_ipai');
    }

    /**
     * 我的参与记录
     * @author zhangby
     */
    public function myParticipate() {
        $this->check_login();
        $data = array();
        $uid = is_login();
        $page = I('get.p', 1, 'intval');
        $status = I('status', -1, 'intval');
       
        $where = 'o.uid=' . $uid;
        switch ($status) {
            case 3:
                $where .= " AND p.status=3";
                break;
            case 4:
                $where .= " AND p.status=4";
                break;
            case 6:          
                $where .= " AND (p.status=2 OR p.status=5)";
                break;
        }



        list($data['rows'], $data['count']) = D('Ipai/PaiOrder')->getOrderLinkProductList($where, $page, 10);
        foreach ($data['rows'] as &$v) {
            //取得产品信息
            $v['product'] = D('Ipai/PaiProduct')->getDetail($v['pid']);
            //取得CODES
            $codes = D('Ipai/PaiCode')->getById($v['cid']);
            if ($codes) {
                $code = explode(',', $codes['codes']);
                $code = array_filter($code);
                $v['codes'] = implode('; ', $code);
                $v['codes_count'] = count($code);
                $v['codes_short'] = implode('; ', array_slice(array_filter($code), 0, 11));
            } else {
                $v['codes'] = $v['codes_short'] = '';
            }
        }

        $data['status'] = D('Ipai/PaiProduct')->getStatus();
        $data['uid'] = $uid;
        $data['sts'] = $status;
        //print_r($data);exit;
        //四处一词 seo     
        $str = '参与的一元爱拍-一元爱拍-';
        $this->setTitle($str . L('_INDEX_TITLE_'));
        $this->setKeywords($str . L('_PAGE_PERSON_'));
        $this->setDescription($str . L('_DE_PERSON_') . L('_PAGE_'));
        //四处一词 seo end

        $this->assign($data);
        $this->display("my_participate");
    }

    /**
     * 我的中奖记录
     * @author zhangby
     */
    public function myWinning() {
        $this->check_login();
        $data = array();
        $uid = is_login();
        $page = I('get.p', 1, 'intval');

        $where = array();
        $where['uid_win'] = $uid ? $uid : -1;
        list($data['rows'], $data['count']) = D('Ipai/PaiProduct')->getListByPage($where, $page, 'open_time DESC', '*', 10);
        foreach ($data['rows'] as &$v) {
            $order = D('Ipai/PaiOrder')->getById($v['order_id']);
            $v['buy_time'] = $order ? $order['create_time'] : 0;
        }
        $data['uid'] = $uid;
        //四处一词 seo     
        $str = '中奖记录一元爱拍-';
        $this->setTitle($str . L('_INDEX_TITLE_'));
        $this->setKeywords($str . L('_PAGE_PERSON_'));
        $this->setDescription($str . L('_DE_PERSON_') . L('_PAGE_'));
        //四处一词 seo end

        $this->assign($data);
        $this->display('my_winning');
    }

    /**
     * 爱拍其数列表
     * @author zhangby
     */
    public function ipaiList() {
        $this->check_login();
        $data = array();
        $uid = is_login();
        $page = I('get.p', 1, 'intval');
        $status = I('get.status', -1, 'intval');
        $pid = I('get.pid', 0, 'intval');
        $search = I('get.search', '', 'op_t');


        $where = array('pid' => $pid, 'uid' => $uid);
        if ($status == 2) {
            $where['_string'] = '(status=2 or status=5)';
        } elseif ($status != -1) {
            $where['status'] = $status;
        }

        $data['row'] = D('Ipai/PaiProductCommon')->getById($pid);
        if ($data['row']['surplus_no'] < 1) {
            unset($data['row']);
        }

        list($data['rows'], $data['count']) = D('Ipai/PaiProduct')->getListByPage($where, $page, 'create_time DESC', '*', 8);
        foreach ($data['rows'] as &$v) {
            $v['ratio'] = ($v['join_num'] / $v['need_num']) * 100;
            $v['ratio'] = $v['ratio'] > 100 ? 100 : $v['ratio'];
            if ($v['uid_win']) {
                $v['win_user'] = get_user_info($v['uid_win']);
                $v['win_user']['sum_buy_num'] = D('Ipai/PaiOrder')->getSumBuyNum(array('uid' => $v['uid_win'], 'pid' => $v['id']));
            }
        }

        $data['uid'] = $uid;
        $data['status'] = $status;
        $data['pid'] = $pid;

        //四处一词 seo     
        $str = '发起记录一元爱拍-';
        $this->setTitle($str . L('_INDEX_TITLE_'));
        $this->setKeywords($str . L('_PAGE_PERSON_'));
        $this->setDescription($str . L('_DE_PERSON_') . L('_PAGE_'));
        //四处一词 seo end

        $this->assign($data);
        $this->display('ipai_list');
    }

    /**
     * 设置发货地址
     * @author zhangby
     */
    public function sendAddress() {
        $data = array();
        if (!IS_POST) {
            return $this->error('请求错误!');
        }
        $out = array('status' => 0, 'msg' => '');
        $uid = is_login();
        $pid = I('POST.pid', 0, 'intval');
        $aid = I('POST.aid', 0, 'intval');
        $goods = D('Ipai/PaiProduct')->getById($pid);
        $adds = D('Common/UserAddress')->getById($aid);

        if (!$goods || $goods['uid_win'] != $uid) {
            $out['status'] = 0;
            $out['msg'] = '设置错误,您没有中奖。';
            $this->ajaxReturn($out);
        }
        if (!$adds || $adds['uid'] != $uid) {
            $out['status'] = 0;
            $out['msg'] = '设置错误,地址不存在。';
            $this->ajaxReturn($out);
        }
        $order = D('Ipai/PaiOrder')->getById($goods['order_id']);
        if (!$order) {
            $out['status'] = 0;
            $out['msg'] = '设置错误,订单不存在。';
            $this->ajaxReturn($out);
        }

        $address = D('Common/District')->getAllRows();
        $address = field2array_key($address, 'id', 'name');
        $data['address'] = '';
        foreach ($adds['arr_region'] as $v) {
            $data['address'].=$address[$v] . ' ';
        }
        $data['address'].=$adds['address'];
        $data['contact'] = $adds['realname'];
        $data['phone'] = $adds['mobile'];
        $data['post'] = $adds['postcode'];
        $data['sure_time'] = time();

        $rs = D('Ipai/PaiOrder')->upData(array('id' => $goods['order_id']), $data);
        if ($rs) {
            D('Ipai/PaiProduct')->addOrSave(array('id' => $pid), array('send_status' => 2));
            session('icn_order_winning_pid', null);
            $out['status'] = 1;
            $out['msg'] = '设置收货地址成功！。';
            $this->ajaxReturn($out);
        }
        $out['status'] = 0;
        $out['msg'] = '设置收货地址失败！' . D('Ipai/PaiProduct')->getError();
        $this->ajaxReturn($out);
    }

    /**
     * 我的订单信息
     * @author zhangby
     */
    public function myOrder() {
        $data = array();
        $uid = is_login();
        $pid = I('get.pid', 0, 'intval');

        $data['row'] = D('Ipai/PaiProduct')->getDetail($pid);
        if (!$data['row'] || $data['row']['uid_win'] != $uid) {
            $this->error('您没有中奖！');
        }


        $data['order'] = D('Ipai/paiOrder')->getById($data['row']['order_id']);
        $area = D('Common/District')->getAllRows();
        $data['area'] = field2array_key($area, 'id', 'name');

        $data['express'] = D('Common/ExpressCorp')->getAllRows();
        $data['express'] = field2array_key($data['express'], 'id', 'name');

        $this->assign($data);
        $this->display('my_order');
    }

    /**
     * 确认收货
     * @author zhangby
     */
    public function suerInGoods() {
        if (!IS_POST) {
            $this->error('非法请求。');
        }
        if (!is_login()) {
            $this->ajaxReturn(array('status' => 0, 'msg' => '请先登录！'));
        }
        $uid = is_login();
        $oid = I('post.oid', 0, 'intval');
        $pwd = I('post.pwd', '', 'text');

        if (!$this->_check_password($uid, $pwd)) {
            $this->ajaxReturn(array('status' => 0, 'msg' => '登录密码无效！'));
        }

        $data['order'] = D('Ipai/PaiOrder')->getById($oid);
        if (!$data['order'] || $data['order']['uid'] != $uid) {
            $this->ajaxReturn(array('status' => 0, 'msg' => '该笔订单不存在或无效！'));
        }

        $day = modC('GET_BACK_MONEY', 0, 'IPAI');
        $rs = D('Ipai/PaiProduct')->addOrSave(array('id' => $data['order']['pid'], 'send_status' => 3, 'uid_win' => $uid), array('send_status' => 4, 'back_time' => strtotime("+$day days")));
        if ($rs) {
            D('Ipai/PaiOrder')->upData(array('id' => $oid, 'uid' => $uid), array('over_time' => time()));
            $this->ajaxReturn(array('status' => 1, 'msg' => '收货成功！'));
        }
        $this->ajaxReturn(array('status' => 0, 'msg' => '收货失败！请检查是否已经收过货。'));
    }

    /**
     * 商家验证服务券
     * @author zhangby
     */
    public function verifyServerCode() {
        $this->check_login();
        $data = array();
        //四处一词 seo     
        $str = '验证服务券-一元爱拍-';
        $this->setTitle($str . L('_INDEX_TITLE_'));
        $this->setKeywords($str . L('_PAGE_PERSON_'));
        $this->setDescription($str . L('_DE_PERSON_') . L('_PAGE_'));

        $this->assign($data);
        $this->display('verify_server_code');
    }

    /**
     * 前端检查验证服务券是否使用
     */
    public function ajaxCheckServerCode() {

        $data = array();
        $data['code'] = I('post.code', 0, 'op_t');
        $code = str_replace(" ", '', $data['code']);
        $code = floatval($code);
        if (!IS_POST) {
            $this->error('非法请求。');
        }

        if (!is_login()) {
            $this->ajaxReturn(array('status' => 1, 'content' => '请先登录！'));
        }


        $params = array();
        $params['where'] = array('server_code' => $code, 'uid' => is_login());
        $params['limit'] = 1;
        $data['row'] = D('Ipai/PaiProduct')->getIpaiList($params)[0];
        if ($data['row']) {
            $data['win_user'] = get_user_info($data['uid_win']);
        }
        //print_r($data);exit;

        $data['codeno'] = $code;
        $data['codelen'] = strlen($code);
        $this->assign($data);
        $html = $this->fetch(T('check_server_code'));
        $out = array('status' => 1, 'content' => $html);
        $this->ajaxReturn($out);
    }

    /**
     * 使用服务券
     * @author zhangby
     */
    public function useServerCode() {
        if (!IS_POST) {
            $this->error('非法请求。');
        }
        $pid = I('post.pid', 0, 'intval');
        $code = I('post.code', '', 'op_t');
        $row = D('Ipai/PaiProduct')->getByIdAndUid($pid, is_login());

        $out = array('status' => 0, 'msg' => '');
        if (!check_is_ipai(is_login())) {
            $out['status'] = 0;
            $out['msg'] = 'Sorry!您不是爱拍商家,无权操作。';
            $this->ajaxReturn($out);
        }

        if (!$row || trim($row['server_code']) != $code) {
            $out['status'] = 0;
            $out['msg'] = '数据不存在！';
            $this->ajaxReturn($out);
        }

        $rs = D('Ipai/PaiProduct')->addOrSave(array('id' => $pid, 'server_code' => $code, 'code_status' => 0), array('code_status' => 1, 'send_status' => 4, 'code_time' => time()));
        if ($rs) {

//            //获得交易款爱币
//            $score2 = floatval($row['productinfo']['price']);
//            $r = D('Common/Member')->upScore2($row['uid'], 0, $score2);
//            if ($r) {
//                D('Common/FundsLog')->addLog($row['uid'], $pid, 0, 1, $score2, '使用服务券验证消费获得商品交易款（爱币）', 'Ipai', 'code');
//            }
//            //清除字段缓存               
//            clean_query_user_cache($row['uid'], array('balance', 'frozen', 'score2'));


            $out['status'] = 1;
            $out['msg'] = '消费成功！';
            $this->ajaxReturn($out);
        }

        $out['status'] = 0;
        $out['msg'] = '消费失败！';
        $this->ajaxReturn($out);
    }

    /**
     * 发货
     * @author zhagnby
     */
    public function sendGoods() {
        if (IS_POST) {
            $this->_set_order_send_goods();
            return;
        }

        $this->check_login();
        $data = array();
        $uid = is_login();
        $pid = I('get.pid', 0, 'intval');

        $data = $this->_check_send_goods($pid);
        $data['is_follow'] = get_follow($data['row']['uid_win']);
        $data['row']['win_user'] = get_user_info($data['row']['uid_win']);

        $data['order'] = D('Ipai/PaiOrder')->getById($data['row']['order_id']);
        if (!$data['order']) {
            $this->error('该笔订单不存在或无效！');
        }
        $data['express'] = D('Common/ExpressCorp')->getAllRows(array('enabled' => 1));


        $this->assign($data);
        $this->display('send_goods');
    }

    /**
     * 查看服务券
     * @author zhangby
     */
    public function viewServerCode() {
        $this->check_login();
        $data = array();
        $uid = is_login();
        $pid = I('get.pid', 0, 'intval');
        $data['row'] = D('Ipai/PaiProduct')->getDetail($pid);
        if (!$data['row'] || $data['row']['uid_win'] != $uid) {
            $this->error('数据不存在！');
        }
        $data['is_follow'] = get_follow($data['row']['uid']);
        $data['row']['user_info'] = get_user_info($data['row']['uid']);
        $data['order'] = D('Ipai/PaiOrder')->getById($data['row']['order_id']);
        if (!$data['order']) {
            $this->error('该笔订单不存在或无效！');
        }


        $this->assign($data);
        $this->display('view_server_code');
    }

    /**
     * 爱拍实名认证
     * @author zhangby
     */
    public function authRealName() {
        if (IS_POST) {
            $this->_save_auth_realname();
            return;
        }
        $this->check_login();
        $data = array();
        $uid = is_login();
        $e = I('get.edit', 0, 'intval');

        $data['edit'] = $e ? 1 : 0;
        $data['row'] = D('User/UserAuthRealname')->getByUid($uid);
        $this->assign($data);
        $this->display('auth_real_name');
    }

    /**
     * 保存一元爱拍实名认证申请
     * @author zhangby
     */
    private function _save_auth_realname() {
        if (!is_login()) {
            $this->ajaxReturn(array('status' => 0, 'msg' => '请先登录！'));
        }
        $row = D('User/UserAuthRealname')->getByUid(is_login());
        if ($row && $row['status'] == 1) {
            $this->ajaxReturn(array('status' => 0, 'msg' => '请不要重复认证！'));
        }

        $param = array();
        $param['uid'] = is_login();
        $param['status'] = 0;
        $param['reason'] = '';
        $param['id'] = I('post.id', 0, 'intval');
        $param['realname'] = I('post.realname', '', 'op_t');
        $param['id_card'] = I('post.id_card', '', 'op_t');
        $param['id_pic'] = I('post.id_pic', '', 'op_t');
        $param['create_time'] = time();
        $param['update_time'] = time();
        if (!$param['id']) {
            unset($param['id']);
        } else {
            $row = D('User/UserAuthRealname')->getByid($param['id']);
            $record = $row['old_record'];
            unset($row['old_record'], $row['imgs']);

            if (!$record) {
                $param['old_record'] = json_encode(array($row));
            } else {
                $record = json_decode($record, TRUE);
                $record[] = $row;
                $param['old_record'] = json_encode($record);
            }
        }
        $rs = D('User/UserAuthRealname')->addOrSave($param);
        if ($rs) {
            $this->ajaxReturn(array('status' => 1, 'msg' => '数据提交成功！'));
        }
        $this->ajaxReturn(array('status' => 0, 'msg' => D('User/UserAuthRealname')->getError()));
    }

    /**
     * 验证用户密码
     * @param type $uid
     * @param type $pwd
     * @author zhangby
     */
    private function _check_password($uid, $pwd) {
        $user = D('User/UcenterMember')->where(array('id' => $uid))->find();
        if (think_ucenter_md5($pwd, UC_AUTH_KEY) == $user['password']) {
            return true;
        }
        return false;
    }

    /**
     * 检查发货条件
     * @param type $pid
     * @param type $isAjax
     * @author zhangby
     */
    private function _check_send_goods($pid, $isAjax = FALSE) {
        if (!is_login()) {
            if ($isAjax) {
                $this->ajaxReturn(array('status' => 0, 'msg' => '请先登录！'));
            } else {
                $this->check_login();
            }
        }

        $uid = is_login();
        $data['row'] = D('Ipai/PaiProduct')->getDetail($pid);
        if (!$data['row'] || $data['row']['uid_win'] != $uid) {
            if ($isAjax) {
                $this->ajaxReturn(array('status' => 0, 'msg' => '数据不存在！'));
            } else {
                $this->error('数据不存在！');
            }
        }

        if ($data['row']['uid'] != $uid || $data['row']['productinfo']['type_first'] == 0) {
            if ($isAjax) {
                $this->ajaxReturn(array('status' => 0, 'msg' => '非法操作！'));
            } else {
                $this->error('非法操作！');
            }
        }
        if (!($data['row']['send_status'] == 2 || $data['row']['send_status'] == 3)) {
            if ($isAjax) {
                $this->ajaxReturn(array('status' => 0, 'msg' => '不能执行此步骤！'));
            } else {
                $this->error('不能执行此步骤');
            }
        }
        return $data;
    }

    /**
     * 设置订单发货信息
     */
    private function _set_order_send_goods() {
        $pid = I('post.pid', 0, 'intval');
        $out = array('status' => 0, 'msg' => '');
        $param = array();
        $param['exp_id'] = I('post.express', 0, 'intval');
        $param['exp_code'] = I('post.exp_code', '', 'text');
        $param['exp_remarks'] = I('post.exp_remarks', '', 'text');
        $param['send_time'] = time();

        $data = $this->_check_send_goods($pid, TRUE);

        if (!$param['exp_id']) {
            $this->ajaxReturn(array('status' => 0, 'msg' => '请选择快递公司！'));
        }

        if (!trim($param['exp_code']) || strlen(trim($param['exp_code'])) < 6) {
            $this->ajaxReturn(array('status' => 0, 'msg' => '请输入正确的快递单号！'));
        }

        $data['order'] = D('Ipai/PaiOrder')->getById($data['row']['order_id']);
        if (!$data['order']) {
            $out['status'] = 0;
            $out['msg'] = '该笔订单不存在或无效！';
            $this->ajaxReturn($out);
        }

        $rs = D('Ipai/PaiOrder')->upData(array('id' => $data['row']['order_id']), $param);
        if ($rs) {
            D('Ipai/PaiProduct')->addOrSave(array('id' => $pid, 'send_status' => 2), array('send_status' => 3));
            $out['status'] = 1;
            $out['msg'] = '发货完成！';
            $this->ajaxReturn($out);
        }

        $out['status'] = 0;
        $out['msg'] = '发货失败！';
        $this->ajaxReturn($out);
    }

    private function userInfo($uid = null) {
        $user_info = query_user(array('avatar128', 'nickname', 'uid', 'space_url', 'score', 'title', 'fans', 'following', 'weibocount', 'rank_link', 'signature', 'ipai'), $uid);
        //获取用户封面id
        $map = getUserConfigMap('user_cover', '', $uid);
        $map['role_id'] = 0;
        $model = D('Ucenter/UserConfig');
        $cover = $model->findData($map);
        $user_info['cover_id'] = $cover['value'];
        $user_info['cover_path'] = getThumbImageById($cover['value'], 1140, 230);

        $user_info['tags'] = D('Ucenter/UserTagLink')->getUserTag($uid);
        $this->assign('user_info', $user_info);
        return $user_info;
    }

    private function check_login() {
        if (!is_login()) {
            $this->error(L('_ERROR_NEED_LOGIN_'));
        }
    }

    private function check_ipai($uid) {
        if (!check_is_ipai($uid)) {
            $this->error(L('您不是爱拍商家。'));
        }
    }

    public function showOrder() {
        $productId = intval(I('get.productId'));
        $userId = intval(I('get.uid'));
        if (!$userId || !$productId) {
            $this->error('参数错误!');
        }
        $this->check_login();
        if (!$data = D('Ipai/PaiProduct')->getOne(array('send_status' => 4, 'share' => 0, 'uid' => $userId, 'id' => $productId, 'uid_win' => is_login()))) {
            $this->error('无法晒单!');
        }
        if (IS_POST) {
            $content = array();
            $content['title'] = op_t(I('post.title'));
            $content['content'] = op_t(I('post.content'));
            $content['evaluate'] = op_t(I('post.evaluate'));
            $content['attr_imgs'] = op_t(I('post.attach_ids'));
            $content['uid'] = is_login();
            $content['pid'] = $data['id'];
            $content['pcid'] = $data['pid'];
            $content['status'] = 0;
            $content['create_time'] = time();
            $paiCommentModel = D('Ipai/PaiComment');
            $paiCommentModel->startTrans();
            if ($paiCommentModel->addComent($content) && D('Ipai/PaiProduct')->changeShareById($data['id'], 1)) {
                $paiCommentModel->commit();
                $this->success('晒单成功！', U('ipai/mywinning'));
            }
            $paiCommentModel->rollback();
            $this->error('晒单失败,请重新操作！');
        } else {
            //$orderSno=I('get.orderSno');
            // $this->assign('orderSno',$orderSno);
            $this->display('show_order');
        }
    }

}
