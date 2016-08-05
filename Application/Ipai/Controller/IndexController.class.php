<?php

namespace Ipai\Controller;

use Think\Controller;

class IndexController extends Controller {

    //全国商品
    public function index() {
        $data = array();

        $param = $this->_getQueryParam();
        $where = 'c.release_status=1 AND c.type_first=1 AND c.audit_status=1 AND p.status=2 ';
        if ($param['cat'] > 0) {
            $where.=' AND c.type_second=' . $param['cat'];
        }
        $order = $this->_getOrderParam();
        list($data['rows'], $data['count']) = D('PaiProductCommon')->getListLinkProduct($where, 'p.' . $order, $param['page'], 24);
        foreach ($data['rows'] as &$v) {
            $v['ratio'] = ($v['join_num'] / $v['need_num']) * 100;
            $v['ratio'] = $v['ratio'] > 100 ? 100 : $v['ratio'];
            $v['user'] = get_user_info($v['uid']);
            $v['user']['follow'] = get_follow($v['uid']);
            $v['imgs'] = explode(',', $v['attr_imgs']);
        }
      
        list($data['shaidan'], $data['shai_count']) = D('PaiComment')->getListByPage('1=1', 1, 'create_time desc', '*', 20);
               
       
        // 轮播广告
        $data['advs'] = D('Advs')->getAdv(9);
        $data['page'] = $param['page'];
        $data['param'] = $param;
        $data['show_search'] = true;
        $this->assign($data);
        $this->display();
    }

    /**
     * 本地服务
     * @author  zhangby
     */
    public function service() {
        $data = array();

        $param = $this->_getQueryParam();
        $where = 'c.release_status=1 AND c.type_first=0 AND c.audit_status=1 AND p.status=2 ';
        if ($param['cat'] > 0) {
            $where.=' AND c.type_second=' . $param['cat'];
        }
        $order = $this->_getOrderParam();
        list($data['rows'], $data['count']) = D('PaiProductCommon')->getListLinkProduct($where, 'p.' . $order, $param['page'], 24);
        foreach ($data['rows'] as &$v) {
            $v['ratio'] = ($v['join_num'] / $v['need_num']) * 100;
            $v['ratio'] = $v['ratio'] > 100 ? 100 : $v['ratio'];
            $v['user'] = get_user_info($v['uid']);
            $v['imgs'] = explode(',', $v['attr_imgs']);
        }

        // 轮播广告
        $data['advs'] = D('Advs')->getAdv(9);
        $data['page'] = $param['page'];
        $data['param'] = $param;
        $data['show_search'] = true;
        $this->assign($data);
        $this->display();
    }

    //购买商品
    public function buy() {
        if (IS_POST) {
            $BuyListModel = D('BuyMenu');
            $data = $BuyListModel->create();

            $ip = get_client_ip();
            $ipInfos = GetIpLookup($ip);
            $city = $ipInfos['city'];

            $data['ip'] = ip2long($ip);
            //$data['uid']  = is_login() ;
            $data['city'] = '测试';
            if ($data) {
                if ($BuyListModel->add($data)) {
                    $this->success('发布成功');
                } else {
                    $this->error('发布失败');
                }
            } else {
                $this->error($BuyListModel->getError());
            }
        }
    }

    //商品详情页
    public function details() {
        $id = I('get.id', '', 'intval');
        $data = array();
        $data['row'] = D('Ipai/PaiProduct')->getDetail($id);
        if ($data['row'] && $data['row']['status'] > 1) {
            $data['row']['ratio'] = ($data['row']['join_num'] / $data['row']['need_num']) * 100;
            $data['row']['ratio'] = $data['row']['ratio'] > 100 ? 100 : $data['row']['ratio'];
            $data['my_record'] = get_my_pai_record($id);
            $data['near'] = get_near_goods($data['row']['pid']);
            $data['is_follow'] = get_follow($data['row']['uid']);
            $data['related'] = D('Common/Member')->similarList($data['row']['user']['catid']);
        } else {
            $this->error('您访问的页面不存在！');
        }


        //增加点击数
        D('Ipai/PaiProduct')->addViewCount($id);

        //如果结束则自动开始下期
        if ($data['row']['status'] == 4 || $data['row']['status'] == 5) {
            $this->_autoAddNextRecord($data['row']['pid']);
            return $this->over($data);
        }

        $this->setTitle(getShort($data['row']['productinfo']['name'], 20) . ' —— 一元爱拍');
        $this->setDescription($data['row']['productinfo']['name'] . ' ——一元爱拍');
        $this->assign($data);


        $this->display();
    }

    /**
     * 商品开奖中/开奖结束
     */
    public function over($data) {
        //取得我的参与次数
        list($data['row']['my_code'], $data['row']['my_num']) = $this->_getOrderCodes(is_login(), $data['row']['id']);
        //近一期
        list($data['near_run'], $count) = D('Ipai/PaiProduct')->getListByPage(array('pid' => $data['row']['pid'], 'status' => 2), 'create_time asc', 1);
        if ($data['near_run']) {
            $data['near_run'] = array_pop($data['near_run']);
            $data['near_run']['ratio'] = ($data['near_run']['join_num'] / $data['near_run']['need_num']) * 100;
            $data['near_run']['ratio'] = $data['near_run']['ratio'] > 100 ? 100 : $data['near_run']['ratio'];
        }


        //开奖结果专属
        if ($data['row']['status'] == 4) {
            $data['row']['win_user'] = query_user(array('nickname', 'uid', 'avatar64'), $data['row']['uid_win']);
            //取得中奖者时间 
            $order = D('Ipai/PaiOrder')->getById($data['row']['order_id']);
            if ($order) {
                $data['row']['win_user']['code'] = D('Iapi/PaiCode')->getById($order['cid']);
                $data['row']['win_user']['code']['create_time'] = $order['create_time'];
            }
            //取得中奖者CODES
            list($data['row']['win_user']['codes'], $data['row']['win_user']['num']) = $this->_getOrderCodes($data['row']['uid_win'], $data['row']['id']);

            //取得全站倒数50条参记录
            $params = array();
            $params['page'] = 1;
            $params['field'] = '*';
            $params['limit'] = 50;
            $params['order'] = 'create_time DESC';
            $params['where'] = array('create_time' => array('lt', $data['row']['over_time']));
            $data['row']['record'] = D('Ipai/PaiOrder')->getList($params);

            //时间之和，取余
            $data['row']['sumtime'] = 0;
            foreach ($data['row']['record'] as &$v) {
                $v['inttime'] = str_replace('.', '', date_fmt('his', $v['create_time']));
                $data['row']['sumtime']+=floatval($v['inttime']);
                $v['product'] = D('Ipai/PaiProduct')->getDetail($v['pid']);
                $v['user_info'] = query_user(array('nickname', 'uid'), $v['uid']);
            }
            $data['row']['modnum'] = fmod(floatval($data['row']['sumtime']) + floatval($data['row']['winning_num']), floatval($data['row']['need_num']));
        }

        //倒计时
        if ($data['row']['over_time'] > 0) {
            $data['row']['countdown'] = $data['row']['over_time'] + 600000;
            $data['row']['timediff'] = timediff(substr($data['row']['countdown'], 0, 10), time(), FALSE);
        }



        $this->assign($data);
        $this->setTitle(getShort($data['row']['productinfo']['name'], 20) . ' —— 一元爱拍');
        $this->setDescription($data['row']['productinfo']['name'] . ' ——一元爱拍');
        $this->display('over');
    }

    /**
     * 取得参与者记录前台AJAX调用
     * @author zhangby
     */
    public function orderRecord($id = '') {
        $pid = empty($id) ? I('get.pid', 0, 'intval') : $id;
        $page = I('get.p', 1, 'intval');
        $data = array();

        $where = array();
        $where['pid'] = $pid;
        list($rows, $count) = D('PaiOrder')->getListByPage($where, $page);
        foreach ($rows as &$v) {
            $v['ip'] = long2ip($v['ip']);
            $data['rows'][date('Y-m-d', substr($v['create_time'], 0, 10))][] = $v;
        }
        $data['count'] = $count;
        $data['pid'] = $pid;

        $this->assign($data);
        $html = $this->fetch(T('_order_record'));
        $html = str_replace('onclick="goToUrl', 'onclick="goTo', $html);

        $this->show($html);
    }

    /**
     * 取得中奖者晒单评论供前台AJAX调用
     * @author zhangby
     */
    public function orderComment($id = '') {
        $pcid = empty($id) ? I('get.pid', 0, 'intval') : $id;
        $page = I('get.p', 1, 'intval');
        $data = array();
        $where = array();
        $where['pcid'] = $pcid;
        $where['status'] = 1;
        list($data['rows'], $data['count']) = D('PaiComment')->getListByPage($where, $page);


        $data['pid'] = $pcid;
        $this->assign($data);
        $html = $this->fetch(T('_order_comment'));
        $html = str_replace('onclick="goToUrl', 'onclick="goTo', $html);
        $this->show($html);
    }
    
    /**
     * 检查发布条件
     * @author zhangby
     */
    private function _check_send_condition(){
        if (!is_login()) {
            $this->error(L('_PLEASE_LOGIN_'));
        }
        $user=  query_user(array('uid','username','mobile','ipai','password'),  is_login()); 
        if($user && !$user['mobile']){
            $this->error('请绑定手机号！');
        }
        
        if($user && !$user['mobile']){
            $this->error('请设置密码！');
        }        
       
        if($user && !$user['ipai']){
            $this->error('请认证一元爱拍！',U('Ucenter/Ipai/authRealName@'));
        }       
       
        
    }

    /**
     * 发布一元爱拍
     */
    public function send() {        
        $this->_check_send_condition();

        $data = array();
        $pid = I('get.pid', 0, "intval");
        $re = I('get.re', 0, "intval");
        $type = I('get.type_first', 1, "intval");
        $data['tag_param']['type_first'] = $type;


        $param = array();
        if ($type == 0) {
            $param['pid'] = 100;
        } else {
            $param['pid'] = 1;
        }
        $data['cat'] = D('Ipai/PaiCategory')->getAllRows($param);
        $data['fee'] = modC('PRODUCT_COMMISSION', 0, 'IPAI');

        if ($pid) {//编辑
            $data['tag_param']['pid'] = $pid;
            $data['row'] = D('Ipai/PaiProductCommon')->getById($pid);
        } elseif ($re) {//在发一期
            $data['tag_param']['re'] = $re;
            $data['row'] = D('Ipai/PaiProductCommon')->getById($re);
            unset($data['row']['id']);
        }

        //如果不是本人发布则不给予修改
        if ($data['row'] && $data['row']['uid'] != is_login()) {
            $this->error('无权操作！');
        }
        if ($data['row'] && $data['row']['audit_status'] != 2 && isset($data['row']['id'])) {
            $this->error('无权操作！');
        }

        if ($data['row']) {
            $data['tag_param']['type_first'] = $data['row']['type_first'];
        }

        if (IS_POST) {
            //提交表单
            $this->_AddGoods($data['row']);
        }

        $this->setTitle('发布一元爱拍');
        $this->assign($data);

        $this->display();
    }

    /**
     * 预览
     * @return type
     */
    public function preview() {
        $arr = array();
        $id = I('get.id', 0, 'intval');
        if ($id) {
            $arr = D('Ipai/PaiProductCommon')->getById($id);
        } elseif (IS_POST) {
            $arr['uid'] = is_login();
            $arr['name'] = I("post.name", '', 'op_t');
            $arr['type_second'] = I("post.type_second", '', 'intval');
            $arr['release_num'] = I("post.release_num", '', 'intval');
            $arr['price'] = I("post.price", '', 'intval');
            $arr['price_platform'] = I("post.price_platform", '', 'intval');
            $arr['type_first'] = I("post.type_first", 0, 'intval');
            $arr['pos_province'] = I("post.pos_province", '', 'intval');
            $arr['pos_city'] = I("post.pos_city", '', 'intval');
            $arr['pos_district'] = I("post.pos_district", '', 'intval');
            $arr['contact'] = I("post.contact", '', 'op_t');
            $arr['added_type'] = I("post.added_type", '', 'intval');
            $arr['added_timing'] = I("post.added_timing", '', 'text');
            $arr['added_timing'] = strtotime($arr['added_timing']);
            $arr['content'] = I("post.content", '', 'filter_content');
            $arr['attr_imgs'] = I("post.attr_imgs", '', 'op_t');
            if ($arr['type_first'] == 1) {
                $arr['reservation_msg'] = I("post.reservation_msg", '', 'op_t');
                $arr['use_rules'] = I("post.use_rules", '', 'op_t');
                $arr['server_address'] = I("post.server_address", '', 'op_t');
                $arr['use_time'] = I("post.use_time", '', 'text');
                $arr['end_time'] = I("post.end_time", '', 'text');
                $arr['end_time'] = strtotime($arr['end_time']);
                $arr['begin_time'] = I("post.start_time", '', 'intval');
                $arr['begin_time'] = strtotime($arr['begin_time']);
            }

            session('_one_pai_preview', $arr);
            return;
        }

        if (!$id) {
            $arr = session('_one_pai_preview');
            if (isset($arr['attr_imgs'])) {
                $arr['imgs'] = $this->_getAttrPicture($arr['attr_imgs']);
            }
        }
        $data = $arr;
        $data['user'] = get_user_info($data['uid']);
        $data['is_follow'] = get_follow($data['uid']);
        $this->assign('data', $data);
        $this->display();
    }

    /**
     * 晒单列表
     * @author zhangby
     */
    public function commentList() {       
        $page=I('get.p',0,'intval');

        $data = array();
        list($data['rows'], $data['count']) = D('PaiComment')->getListByPage('1=1', $page, 'create_time DESC', '*',36);
        foreach ($data['rows'] as &$v) {
            $v['product'] = D('PaiProduct')->getDetail($v['pid']);
        }
        $this->assign($data);
        $this->display("comment_list");
    }

    /**
     * 赛单详情
     * @author zhangby
     */
    public function commentView() {
        $cid = I('get.cid', 0, '');
        $data = array();
        $data['row'] = D('PaiComment')->getById($cid);
        if (!$data['row']) {
            $this->error('您查看的页面不存在！');
        }
        $data['row']['product'] = D('PaiProduct')->getDetail($data['row']['pid']);
        list($data['row']['user']['codes'], $data['row']['user']['num']) = $this->_getOrderCodes(is_login(), $data['row']['pid']);
        //print_r($data);exit;

        $this->assign($data);
        $this->setTitle($data['row']['title'] . ' —— 一元爱拍');
        $this->setDescription($data['row']['content'] . ' ——一元爱拍');
        $this->display("comment_view");
    }

//    private function _get_ajax_comment_items() {
//        $page = I('get.page', 1, 'intval');
//        list($data['rows'], $data['count']) = D('PaiComment')->getListByPage('1=1', $page, 'create_time DESC', '*', 16);
//        foreach ($data['rows'] as &$v) {
//            $v['product'] = D('PaiProduct')->getDetail($v['pid']);
//        }
//        $data['token']=  md5($page);
//        $data['page'] = $page;
//        $data['html'] = $this->fetch(T('comment_list_items'));
//        $this->ajaxReturn($data);
//    }

    /**
     * 取得图片附件
     * @param type $ids
     * @return type
     * @author zhangby
     */
    private function _getAttrPicture($ids) {
        $arr = explode(',', $ids);
        $imgs = array_filter($arr);
        return $imgs;
    }

    /**
     * 取得用户CODES
     * @param type $uid
     * @param type $pid
     */
    private function _getOrderCodes($uid, $pid) {
        $where = array();
        $where['uid'] = $uid;
        $where['pid'] = $pid;
        list($order, $total) = D('Ipai/PaiOrder')->getListByPage($where, 1, 'create_time DESC', '*', 100000);

        $codes = '';
        $num = 0;
        foreach ($order as $v) {
            $code = D('Ipai/PaiCode')->getById($v['cid']);
            $codes.=empty($codes) ? $code['codes'] : (',' . $code['codes']);
            $num+=$v['num'];
        }
        $codes = explode(',', $codes);
        $codes = array_merge(array_filter($codes));
        return array($codes, $num);
    }

    /**
     * 变更余额和冻结资金
     * @param type $pid
     * @param type $money
     */
    private function _changeBalanceAndFrozen($pid, $money) {
        $rs = D('Ucenter/UserFund')->upBalance(is_login(), 1, $money);
        if ($rs) {
            $row = array();
            $row['uid'] = is_login();
            $row['obj_id'] = $pid;
            $row['type'] = 1;
            $row['status'] = 0;
            $row['funds'] = $money;
            $row['message'] = '发布商品/服务冻结保证金';
            $row['module'] = 'Ipai';
            $row['mod_event'] = 'send';
            $row['create_time'] = time();
            $r = D('Ucenter/UserFund')->upFrozen(is_login(), 0, $money);
            if ($r) {
                D('Common/FundsLog')->add($row);
                return FALSE;
            }
        }
        return FALSE;
    }

    /**
     * 验证爱拍发布提交数据
     * @param type $data
     */
    private function checkIpaiPostParam($data, $row) {
        if ($row && $row['uid'] != is_login()) {
            $this->error('无权操作！');
        }
        if ($row && $row['audit_status'] != 2 && isset($row['id'])) {
            $this->error('无权操作！');
        }

        if ($data['type_second'] < 1) {
            $this->error('请选择发布类型。');
        }
        if (strlen(trim($data['name'])) < 2) {
            $this->error('标题字数不能少于两个字。');
        }
        if ($data['release_num'] < 1) {
            $this->error('请输入发布数量。');
        }
        if ($data['price'] < 1) {
            $this->error('请输入原价。');
        }
//        if ($data['price_platform'] < 1) {
//            $this->error('请输入平台价。');
//        }
        if (strlen(trim($data['contact'])) < 2) {
            $this->error('联系方式字数不能少于两个字。');
        }
        if (strlen(trim($data['attr_imgs'])) < 1) {
            $this->error('请上传图片。');
        }
        if (strlen(trim($data['content'])) < 1) {
            $this->error('请填写描述。');
        }
        if ($data['added_type'] == 0) {
            $this->error('请选择定时上架。');
        }
        if ($data['added_type'] == 2) {
            if (strlen(trim($data['added_timing'])) < 1) {
                $this->error('请设置定时上架时间。');
            }
        }

        //商品
        if ($data['type_first'] == 1) {
            if (!$data['pos_province'] || !$data['pos_city'] || !$data['pos_district']) {
                $this->error('请选择发货区域。');
            }
        }
        //非商品
        if ($data['type_first'] == 0) {
            if (strlen(trim($data['begin_time'])) < 1 || strlen(trim($data['end_time'])) < 1) {
                $this->error('请设置有效期。');
            }
            if (strlen(trim($data['use_time'])) < 2) {
                $this->error('可用时间字数不能少于两个字。');
            }
            if (strlen(trim($data['reservation_msg'])) < 2) {
                $this->error('预约提示字数不能少于两个字。');
            }
            if (strlen(trim($data['use_rules'])) < 2) {
                $this->error('使用规则字数不能少于两个字。');
            }
            if (strlen(trim($data['server_address'])) < 2) {
                $this->error('服务位置字数不能少于两个字。');
            }
        }
    }

    /**
     * 处理发布商品提交到数据库
     */
    private function _AddGoods($row) {
        $md5token = I('post.md5token', '', 'op_t');
        $sess_md5token = session('sess_ipai_edit');
        if ($md5token && $sess_md5token && $md5token == $sess_md5token) {
            $this->error('请不要重复提交。');
        }
        session('sess_ipai_edit', $md5token);
        $fee = modC('PRODUCT_COMMISSION', 0, 'IPAI');
        $arr = array();
        $arr['security_deposit'] = modC('SECURITY_DEPOSIT', '30', 'IPAI');
        $arr['uid'] = is_login();
        $arr['audit_status'] = 0;
        $arr['create_time'] = time();
        $arr['id'] = I("post.id", 0, 'intval');
        $arr['name'] = I("post.name", '', 'op_t');
        $arr['type_second'] = I("post.type_second", 0, 'intval');
        $arr['release_num'] = I("post.release_num", 0, 'intval');
        $arr['surplus_no'] = $arr['release_num'];
        $arr['price'] = I("post.price", 0, 'intval');
        $arr['price_platform'] = round(floatval($arr['price']) * ($fee / 100) + floatval($arr['price']));
        $arr['type_first'] = I("post.type_first", 1, 'intval');
        $arr['pos_province'] = I("post.pos_province", 0, 'intval');
        $arr['pos_city'] = I("post.pos_city", 0, 'intval');
        $arr['pos_district'] = I("post.pos_district", 0, 'intval');
        $arr['contact'] = I("post.contact", '', 'html');
        $arr['added_type'] = I("post.added_type", 0, 'intval');
        $arr['content'] = I("post.content", '', 'filter_content');
        $arr['attr_imgs'] = I("post.attr_imgs", '', 'op_t');
        if ($arr['type_first'] == 0) {
            $arr['reservation_msg'] = I("post.reservation_msg", '', 'op_t');
            $arr['use_rules'] = I("post.use_rules", '', 'op_t');
            $arr['server_address'] = I("post.server_address", '', 'op_t');
            $arr['use_time'] = I("post.use_time", '', 'text');
            $arr['end_time'] = I("post.end_time", '', 'text');
            $arr['end_time'] = strtotime($arr['end_time']);
            $arr['begin_time'] = I("post.start_time", '', 'text');
            $arr['begin_time'] = strtotime($arr['begin_time']);
        }
        $arr['added_timing'] = time();
        if ($arr['added_type'] == 2) {
            $arr['added_timing'] = I("post.added_timing", '', 'text');
            $arr['added_timing'] = strtotime($arr['added_timing']);
        }



        //验证提交参数
        $this->checkIpaiPostParam($arr, $row);
        if (!D('PaiProductCommon')->create($arr)) {
            $this->error(D('PaiProductCommon')->getError());
        }

        //这里判断保证金
        $balance = D('Ucenter/UserFund')->getUserBalance(is_login());
        $security_deposit = floatval($arr['price']) * ($arr['security_deposit'] / 100);
        $security_deposit = round($security_deposit)*$arr['release_num'];
        if ($security_deposit > floatval($balance)) {
            $this->error('余额不足！');
        }

        $rs = null;
        $id = 0;
        if (!$arr['id']) {
            unset($arr['id']);
            $rs = $id = D('PaiProductCommon')->add($arr);
        } else {
            $id = $arr['id'];
            $rs = D('PaiProductCommon')->upDate($arr);
        }


        if ($rs) {
            $r = $this->_changeBalanceAndFrozen($id, $security_deposit);
            $this->success(L('_IPAI_SUCCESS_'), U('Ucenter/Ipai/myIpai@'));
        } else {
            $this->success('发布失败！');
        }
    }

    /**
     * 取得请求参数
     * @return type
     * @author zhangby
     */
    private function _getQueryParam() {
        $param = array();
        $param['cat'] = I('get.cg', 0, 'intval');
        $param['order'] = I('get.o', 1, 'intval');
        $param['page'] = I('get.p', 1, 'intval');
        return $param;
    }

    /**
     * 取得排序参数
     * @return type
     * @author zhangby
     */
    private function _getOrderParam() {
        $param = array();
        $param['order'] = I('get.o', 1, 'intval');

        $order = 'view_count DESC';
        switch ($param['order']) {
            case 1://人气
                $order = 'view_count DESC';
                break;
            case 2://剩余人数
                $order = 'surplus_num ASC';
                break;
            case 3://最新商品
                $order = 'create_time ASC';
                break;
            case 4://总需人数
                $order = 'need_num DESC';
                break;
            default :
                $order = 'create_time DESC';
                break;
        }
        return $order;
    }

    /**
     * 如果本条爱拍结束则自动检查下一条爱拍是否生成，如没有则自动生成
     * @author zhangby
     */
    private function _autoAddNextRecord($pid) {
        $row = D('Ipai/PaiProductCommon')->getById($pid);
        if (!$row || $row['surplus_no'] <= 0)
            return;
        $rows = D('Ipai/PaiProduct')->where(array('pid' => $pid, 'status' => 2, 'uid' => $row['uid']))->count();
        if ($rows > 0)
            return;
        generate_product($pid);
    }

}
