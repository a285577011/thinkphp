<?php

/**
 * 前台公共库文件
 * 主要定义前台公共函数库
 */

/**
 * 计算取得开奖时间
 * @author zhangby
 */
function get_open_time($over_time) {
    $ot = substr($over_time, 0, 10);
    $s = 60 * (date('H', $ot) * 60 + date('i', $ot));  //截止时间 
    $rc = M('CqsscConfig')->where(array('time' => array('gt', $s)))->order(array('time' => 'asc'))->find(); //开奖时间   
    if ($rc) {
        $f_d1 = $rc['time'] > $s ? $ot : strtotime("+1 day", $ot);
        $f_d2 = $rc['time'] > $s ? $rc['time'] : $s;

        $date = date('Y-m-d', $f_d1) . ' ' . sec2time("H:i:00", $f_d2);
        return strtotime($date) . '000';
    }
    return 0;
}

/**
 * 取得我的某条爱拍参加记录
 * @param type $pid
 * @author zhangby
 */
function get_my_pai_record($pid) {
    $where = array('uid' => is_login(), 'pid' => $pid);
    $rows = D('PaiOrder')->getListByPage($where, 1, 'create_time DESC', '*', 90000)[0];
    $data = array();
    foreach ($rows as &$row) {
        $row['user'] = query_user(array('uid', 'nickname', 'avatar64'), $row['uid']);
        $codes = D('PaiCode')->getById($row['cid'])['codes'];
        $codes = array_merge(array_filter(explode(',', $codes)));
        foreach ($codes as $r) {
            $data[] = array('head' => $row['user']['avatar64'], 'code' => $r, 'time' => date_fmt('Y-m-d h:i:s', $row['create_time']));
        }
    }
    return $data;
}

/**
 * 取得商品近况
 * @author zhangby
 */
function get_near_goods($pid) {
    $map = array();
    $map['where']['pid'] = $pid;
    //$map['where']['uid_win'] = array('gt', 0);
    $map['where']['open_time'] = array('gt', 0);
    $map['order'] = "periods DESC";
    $rows = D('PaiProduct')->getIpaiList($map);
    foreach($rows as &$r){
        $r['win_user']=  query_user(array('uid','nickname','username'),$r['uid_win']);
    }
    return $rows;
}

/**
 * 生成爱拍期数数据
 */
function generate_product($id) {
    $rows = M('PaiProduct')->where(array('pid' => $id, 'status' => 2))->count();
    if ($rows)
        return;
    $d = modC('PRODUCT_LIUPAI_TIME', '3', 'IPAI');
    S(D('Ipai/PaiProductCommon')->getCacheKeyMain($id), null); //先删除缓存确保不会读取缓存数据
    $data = M('PaiProductCommon')->where(array('id' => $id))->find();
    if ($data['surplus_no'] > 0) {
        $arr = array();
        $arr['uid'] = $data['uid'];
        $arr['pid'] = $data['id'];
        $arr['need_num'] = intval($data['price_platform']);
        $arr['surplus_num'] = intval($data['price_platform']);
        $arr['periods'] = $data['release_num'] - $data['surplus_no'] + 1;
        $arr['status'] = 2;
        $arr['cate'] = $data['type_second'];
        $arr['create_time'] = time();
        $arr['liupai_time'] = strtotime("+$d month");
        $rs = M('PaiProduct')->add($arr);
        if ($rs) {
            M('PaiProduct')->where(array('id' => $rs))->save(array('issue_num' => date('Ymd') . $rs));
            S(D('Ipai/PaiProduct')->getCacheKeyMain($data['id']), null);
            M('PaiProductCommon')->where(array('id' => $data['id']))->setDec('surplus_no', 1);
        }
        if ($arr['periods'] == 1) {//如果第一次发布就把状态更新成已发布
            M('PaiProductCommon')->where(array('id' => $data['id']))->save(array('release_status' => 1));
        }
        S(D('Ipai/PaiProductCommon')->getCacheKeyMain($data['id']), null);
        unset($arr);
    }
}
