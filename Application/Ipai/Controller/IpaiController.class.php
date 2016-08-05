<?php

/**
 * Created by i.cn.
 * User: zhangby
 * Date: 16-1-26
 * Time: 上午9:21
 * 
 */

namespace Admin\Controller;

use Admin\Builder\AdminConfigBuilder;
use Admin\Builder\AdminListBuilder;
use Admin\Builder\AdminTreeListBuilder;
use Common\Model\ContentHandlerModel;
use Ucenter\Model\SystemAvatarModel;

vendor('formvalidator.formvalidator');

class IpaiController extends AdminController {

    var $paiCategory;
    var $paiProductCommon;
    var $paiProduct;
    var $userAuthRealname;

    function _initialize() {
        parent::_initialize();
        $this->paiCategory = D('Ipai/PaiCategory');
        $this->paiProduct = D('Ipai/PaiProduct');
        $this->paiProductCommon = D('Ipai/PaiProductCommon');
        $this->userAuthRealname = D('User/UserAuthRealname');
    }

    /**
     * 基础配置
     */
    public function config() {
        $builder = new AdminConfigBuilder();
        if (IS_POST) {
            $validator = new \FormValidator();
            $validator->addValidation("SECURITY_DEPOSIT", "req", '请输入保证金比率');
            $validator->addValidation("SECURITY_DEPOSIT", "regexp=/^[0-9]{1,3}$/", L('_FAIL_RORMAT_') . L('_INPUT_GT_OR_EQ_ZERO_NUM_') . ' ' . L('_MAX_THREE_DIGIT_'));
            $validator->addValidation("PRODUCT_COMMISSION", "req", '请输入商品佣金');
            $validator->addValidation("PRODUCT_COMMISSION", "regexp=/^[0-9]{1,3}$/", L('_FAIL_RORMAT_') . L('_INPUT_GT_OR_EQ_ZERO_NUM_') . ' ' . L('_MAX_THREE_DIGIT_'));
            $validator->addValidation("PRODUCT_LIUPAI_TIME", "regexp=/^[1-9]{1,3}$/", L('_FAIL_RORMAT_') . L('_INPUT_GT_OR_EQ_ZERO_NUM_') . ' ' . L('_MAX_THREE_DIGIT_'));
            $validator->addValidation("PRODUCT_LIUPAI_TIME", "req", '请输入流拍时间');
            $validator->addValidation("PRODUCT_RULE", "req", '请输入爱拍规则');
            if (!$validator->ValidateForm()) {
                $error = '';
                $error_hash = $validator->GetErrors();
                foreach ($error_hash as $inpname => $inp_err) {
                    $error.= '<p>' . $inp_err . '</p>';
                }
                $this->error(L('_FAIL_') . '<br/>' . $error);
            }
            $builder->handleConfig();
            return;
        }

        $data = $builder->handleConfig();
        $builder->title(L('_IPAI_BASIC_CONF_'))->data($data);
        $builder->keyInteger('SECURITY_DEPOSIT', L('_IPAI_SECURITY_DEPOSIT_') . '%', L('_IPAI_SECURITY_DEPOSIT_DESC_'))->keyDefault('SECURITY_DEPOSIT', '0')
                ->keyInteger('PRODUCT_COMMISSION', L('_IPAI_PRODUCT_COMMISSION_') . '%', L('_IPAI_PRODUCT_COMMISSION_DESC_'))->keyDefault('PRODUCT_COMMISSION', '0')
                ->keyInteger('PRODUCT_LIUPAI_TIME', '流拍时间', '【单位：月份】商品发布后增加的月份时间，发布商品后，自动增加月份，非负整数')->keyDefault('PRODUCT_LIUPAI_TIME', '3')
                ->keyTextArea('PRODUCT_RULE', '爱拍规则')
                ->group(L('_IPAI_BASIC_CONF_'), 'SECURITY_DEPOSIT,PRODUCT_COMMISSION,PRODUCT_RULE,PRODUCT_LIUPAI_TIME')
                ->buttonSubmit()->buttonBack()
                ->display();
    }

    /**
     * 财务配置
     */
    public function financeConfig() {
        $builder = new AdminConfigBuilder();
        if (IS_POST) {
            $validator = new \FormValidator();
            $validator->addValidation("RECHARGE_CASH_MIN", "regexp=/^\\d*?\\.{0,1}\\d{1,3}$/", L('_FAIL_RORMAT_') . L('_INPUT_GT_OR_EQ_ZERO_NUM_') . L('_RESERVED_THREE_DECIMAL_PLACES_'));
            $validator->addValidation("GET_CASH_MIN", "regexp=/^\\d*?\\.{0,1}\\d{1,3}$/", L('_FAIL_RORMAT_') . L('_INPUT_GT_OR_EQ_ZERO_NUM_') . L('_RESERVED_THREE_DECIMAL_PLACES_'));
            $validator->addValidation("GET_CASH_MAX", "regexp=/^\\d*?\\.{0,1}\\d{1,3}$/", L('_FAIL_RORMAT_') . L('_INPUT_GT_OR_EQ_ZERO_NUM_') . L('_RESERVED_THREE_DECIMAL_PLACES_'));
            $validator->addValidation("GET_CASH_FEE", "regexp=/^[0-9]{1,3}$/", L('_FAIL_RORMAT_') . L('_INPUT_GT_OR_EQ_ZERO_NUM_') . ' ' . L('_MAX_THREE_DIGIT_'));
            $validator->addValidation("GET_BACK_MONEY", "regexp=/^[0-9]{1,2}$/", L('_FAIL_RORMAT_') . L('_INPUT_GT_OR_EQ_ZERO_NUM_') . ' ' . L('_MAX_THREE_DIGIT_'));
            if (!$validator->ValidateForm()) {
                $error = '';
                $error_hash = $validator->GetErrors();
                foreach ($error_hash as $inpname => $inp_err) {
                    $error.= '<p>' . L('_IPAI_' . $inpname . '_') . ' ：' . $inp_err . '</p>';
                }
                $this->error(L('_FAIL_') . '<br/>' . $error);
            }
            $builder->handleConfig();
            return;
        }

        $data = $builder->handleConfig();
        $builder->title(L('_IPAI_FINANCE_CONF_'))->data($data);
        $builder->keyInteger('RECHARGE_CASH_MIN', L('_IPAI_RECHARGE_CASH_MIN_'))->keyDefault('RECHARGE_CASH_MIN', '0')
                ->keyInteger('GET_CASH_MIN', L('_IPAI_GET_CASH_MIN_'))->keyDefault('GET_CASH_MIN', '0')
                ->keyInteger('GET_CASH_MAX', L('_IPAI_GET_CASH_MAX_'))->keyDefault('GET_CASH_MAX', '0')
                ->keyInteger('GET_CASH_FEE', L('_IPAI_GET_CASH_FEE_') . '%')->keyDefault('GET_CASH_FEE', '0')
                ->keyInteger('GET_BACK_MONEY', '交易完成退回保证金期限' . ' (单位：天)')->keyDefault('GET_BACK_MONEY', '3')
                ->group(L('_IPAI_FINANCE_CONF_'), 'RECHARGE_CASH_MIN,GET_CASH_MIN,GET_CASH_MAX,GET_CASH_FEE,GET_BACK_MONEY')
                ->buttonSubmit()->buttonBack()
                ->display();
    }

    /**
     * 产品列表
     * @param type $p
     * @param type $r
     */
    public function productList($p = 1, $r = 20) {
        GF('Common/function', 'Ipai');
        $data = array();
        $data['audit'] = array(0 => '审核中', 1 => '通过', 2 => '拒绝');

        list($list, $totalCount) = $this->paiProductCommon->getListByPage('1=1', $p, 'create_time desc', '*', $r);
        $category = $this->paiCategory->getList(array('field' => '*'));
        $category = array_combine(array_column($category, 'id'), $category);
        foreach ($list as &$val) {
            $val['category'] = $category[$val['type_second']]['name'];
            $val['user'] = query_user(array('uid', 'username'), $val['uid']);
        }
        unset($val);

        $optCat = array();
        foreach ($category as $val) {
            $optCat[$val['id']] = $val['name'];
        }

        $data['opt_cat'] = $optCat;
        $data['list'] = $list;

        $data['pagination'] = getPagination($totalCount, $r);
        $this->assign($data);
        $this->display("product_list");
    }

    /**
     * 审核中=0，通过=1，拒绝=2
     * @param $ids
     * @param $status
     * 
     */
    public function setAuditStatus($ids, $status) {
        !is_array($ids) && $ids = explode(',', $ids);
        $id = array_unique((array) $ids);
        foreach ($id as $v) {
            $row = D('Ipai/PaiProductCommon')->getById($v);
            if ($row['audit_status'] == 2 || $row['audit_status'] == 1) {
                continue;
            }
            $data = array();
            $data['audit_status'] = $status;
            $data['reason'] = I('post.reason', '', 'op_t');
            $rs = D('Ipai/PaiProductCommon')->where(array('id' => $v))->save($data);
            if ($rs && $status == 2) {//审核未通过退回保证金
                $this->backFrozen($v);
            }
            //清除缓存
            S(D('Ipai/PaiProductCommon')->getCacheKeyMain($v), null);
            //生成爱拍数据
            GF('Common/function', 'Ipai');
            if ($row['added_type'] == 1 && $row['release_status'] == 0 && $status == 1 && $row['surplus_no'] > 0) {
                generate_product($v);
            }
        }

        $this->success('设置成功！', $_SERVER['HTTP_REFERER']);
    }

    /**
     * 爱拍实名认证
     */
    public function realNameList($page = 1, $r = 20) {
        $data = array();
        list($data['list'], $data['totalCount']) = $this->userAuthRealname->getListByPage('1=1', $page, 'create_time desc', '*', $r);
        foreach ($data['list'] as &$v) {
            $v['user'] = query_user(array('uid', 'username'), $v['uid']);
            $v['admin'] = query_user(array('uid', 'username'), $v['adminid']);
        }
        
       

        $data['pagination'] = getPagination($data['totalCount'], $r);
        $this->assign($data);
        $this->display("realname_list");

//        $builder = new AdminListBuilder();
//        $builder->title(L('爱拍产品列表'))->data($list)
//                ->setSelectPostUrl(U('Admin/Ipai/productList'))
//                ->buttonSetStatus(U('Ipai/setUserAuditRealnameStatus'), 1, '审核通过')->buttonSetStatus(U('Ipai/setUserAuditRealnameStatus'), 2, '审核拒绝')
//                ->keyId()->keyUid()->keyText('realname', '姓名')->keyText('id_card', '身份证号')->keyUid('adminid', '审核人')->keyMap('status', '审核', array(0 => '审核中', 1 => '通过', 2 => '拒绝'))
//                ->keyText('reason', '拒绝原因')->keyCreateTime()->keyCreateTime('update_time', '更新时间');
//        $builder->pagination($totalCount, $r)
//                ->display();
    }

    public function setUserAuditRealnameStatus($ids, $status) {
        !is_array($ids) && $ids = explode(',', $ids);
        $id = array_unique((array) $ids);
        foreach ($id as $v) {
            $row = $this->userAuthRealname->getById($v);
            $data = array();
            $data['status'] = $status;
            $data['reason'] = I('post.reason', '', 'op_t');
            $rs = $this->userAuthRealname->where(array('id' => $v))->save($data);
            if ($rs) {
                $rl = D('Ucenter/UserRole')->where(array('uid' => $row['uid'], 'role_id' => 2))->find();
                if ($rl) {//存在角色进行更新
                    D('Ucenter/UserRole')->where(array('id' => $rl['id']))->save(array('status' => $status == 1 ? 1 : -1));
                } elseif ($status == 1) {//不存在新增角色
                    D('Ucenter/UserRole')->add(array('uid' => $row['uid'], 'role_id' => 2, 'status' => 1));
                }
            }
            //清除缓存
            S($this->userAuthRealname->getCacheKeyMain($v), null);
        }

        $this->success('设置成功！', $_SERVER['HTTP_REFERER']);
    }

    //===========================私有函数===================================

    /**
     * 退回冻结资金
     */
    private function backFrozen($pid) {
        $r = D('Ipai/PaiProductCommon')->getById($pid);
        if ($r) {
            $security_deposit = floatval($r['price']) * ($r['security_deposit'] / 100);
            $security_deposit = round($security_deposit);
            $rs = D('Ucenter/UserFund')->upAddBalanceAndCutFrozen($r['uid'], $security_deposit); //echo  D('Ucenter/UserFund')->getLastSql();exit;
            if ($rs) {
                $row = array();
                $row['uid'] = $r['uid'];
                $row['obj_id'] = $pid;
                $row['type'] = 1;
                $row['status'] = 1;
                $row['funds'] = $security_deposit;
                $row['message'] = '商品/服务审核未通过退回保证金';
                $row['module'] = 'Ipai';
                $row['mod_event'] = 'audit';
                $row['create_time'] = time();
                D('Common/FundsLog')->add($row);
            }
            return FALSE;
        }
        return FALSE;
    }

    public function showorder($page = 1) {
        $status = intval(I('get.status'));
        $evaluate = intval(I('get.evaluate'));
        $map = array();
        $status >= 0 && $map['status'] = $status;
        $evaluate >= 0 && $map['evaluate'] = $evaluate;
        list($list, $totalCount) = D('Ipai/PaiComment')->getListByPage($map, $page, 'create_time desc', '*', $r = 10);
        $builder = new AdminListBuilder();
        $builder->title(L('晒单列表'))->data($list)->select('审核状态', 'status', 'select', '', '', '', array(array('id' => -1, 'value' => '全部'), array('id' => 0, 'value' => '审核中'), array('id' => 1, 'value' => '通过'), array('id' => 2, 'value' => '拒绝')))->select('评价等级', 'evaluate', 'select', '', '', '', array(array('id' => -1, 'value' => '全部'), array('id' => 0, 'value' => '默认'), array('id' => 1, 'value' => '好评'), array('id' => 2, 'value' => '中评'), array('id' => 3, 'value' => '差评')))
                ->setSelectPostUrl(U('Ipai/showorder'))
                ->buttonSetStatus(U('Ipai/setIpaiCommentStatus'), 1, '审核通过')->buttonSetStatus(U('Ipai/setIpaiCommentStatus'), 2, '审核拒绝')
                ->keyId()->keyUid()->keyText('pid', '产品开奖表ID')->keyText('pcid', '产品基本表ID')->keyText('title', '标题')->keyText('content', '内容')->keyImages('imgs', '晒单图片图片')->keyMap('status', '审核状态', array(0 => '审核中', 1 => '通过', 2 => '拒绝'))->keyMap('evaluate', '评价', array(0 => '默认', 1 => '好评', 2 => '中评', 3 => '差评'))
                ->keyText('reason', '拒绝原因')->keyCreateTime();
        $builder->pagination($totalCount, $r)
                ->display();
    }

    public function setIpaiCommentStatus($ids, $status) {
        !is_array($ids) && $ids = explode(',', $ids);
        $id = array_unique((array) $ids);
        $msg = '';
        foreach ($id as $v) {
            $rs = D('Ipai/PaiComment')->where(array('id' => $v))->save(array('status' => $status));
            if (!is_int($rs)) {
                $msg.='id:' . $v . '审核失败';
            }
            //清除缓存
            S(D('Ipai/PaiComment')->getCacheKey($v), null);
        }
        $msg = $msg? : '设置成功！';
        $this->success($msg, $_SERVER['HTTP_REFERER']);
    }

}
