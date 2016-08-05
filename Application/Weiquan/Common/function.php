<?php

/**
 * send_weibo  发布微博
 * @param $content
 * @param $type
 * @param string $feed_data
 * @param string $from
 * @return bool
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_weiquan($content, $type, $feed_data = '', $from = '',$aAttachIds=false) {

    $uid = is_login();

    D('Weiquan/Topic')->addTopic($content);
    $weibo_id = D('Weiquan')->addWeiquan($uid, $content, $type, $feed_data, $from,$aAttachIds);
    if (!$weibo_id) {
        return false;
    }
    action_log('add_weiquan', 'weiquan', $weibo_id, $uid);
    $uids = get_at_uids($content);
    send_at_message($uids, $weibo_id, $content);
    clean_query_user_cache(is_login(), array('weiquancount'));
    M('UserStatistic')->where(array('uid'=>$uid))->setInc('trends');//增加统计表
    M('Member')->where(array('uid'=>$uid))->save(array('update_time'=>time()));//更新增量
    return $weibo_id;
}

/**
 * send_comment  发布评论
 * @param $weibo_id
 * @param $content
 * @param int $comment_id
 * @return bool
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_comment($weibo_id, $content, $comment_id = 0) {
    $uid = is_login();
    $result = D('WeiquanComment')->addComment($uid, $weibo_id, $content, $comment_id);
    if (!$result) {
        return false;
    }
    //行为日志
    action_log('add_weiquan_comment', 'weiquan_comment', $result, $uid);
    //通知微博作者
    $weibo = D('Weiquan')->getWeiquanDetail($weibo_id);
    send_comment_message($weibo['uid'], $weibo_id, L('_COMMENT_CONTENT_') . L('_COLON_') . "$content");
    //通知回复的人
    if ($comment_id) {
        $comment = D('WeiquanComment')->getComment($comment_id);
        if ($comment['uid'] != $weibo['uid']) {
            send_comment_message($comment['uid'], $weibo_id, L('_REPLY_CONTENT_') . L('_COLON_') . "$content");
        }
    }

    $uids = get_at_uids($content);
    $uids = array_subtract($uids, array($weibo['uid'], $comment['uid']));
    send_at_message($uids, $weibo_id, $content);
    M('UserStatistic')->where(array('uid'=>$weibo['uid']))->setInc('comment');//增加统计表
    M('Member')->where(array('uid'=>$weibo['uid']))->save(array('update_time'=>time()));//更新增量
    return $result;
}

/**
 * send_comment_message 发送评论消息
 * @param $uid
 * @param $weibo_id
 * @param $message
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_comment_message($uid, $weibo_id, $message) {
    $title = L('_COMMENT_MESSAGE_');
    $from_uid = is_login();
    $type = 1;
    D('Common/Message')->sendMessage($uid, $title, $message, 'Weiquan/Index/weiquanDetail', array('id' => $weibo_id), $from_uid, $type);
}

/**
 * send_at_message  发送@消息
 * @param $uids
 * @param $weibo_id
 * @param $content
 * @author:xjw129xjt(肖骏涛) xjt@ourstu.com
 */
function send_at_message($uids, $weibo_id, $content) {
    $my_username = query_user('nickname');
    foreach ($uids as $uid) {
        $message = L('_CONTENT_') . $content;
        $title = $my_username . '@了您';
        $fromUid = get_uid();
        $messageType = 1;
        D('Common/Message')->sendMessage($uid, $title, $message, 'Weiquan/Index/weiquanDetail', array('id' => $weibo_id), $fromUid, $messageType);
    }
}

function parse_topic($content) {
    //找出话题
    $topic = get_topic($content);
    //将##替换成链接
    foreach ($topic as $e) {
        if($e=='视频分享')
        {
           $content = str_replace("#$e#", "", $content);
        }
        $tik = D('Weiquan/Topic')->where(array('name' => $e))->find();

        //没有这个话题的时候创建这个话题
        if ($tik) {
            //D('Weibo/Topic')->add(array('name'=> $e));
            $space_url = U('Weiquan/Topic/index', array('topk' => urlencode($e)));
            if (modC('HIGH_LIGHT_TOPIC', 1, 'Weiquan')) {
                $content = str_replace("#$e#", " <a class='label label-badge label-info'  href=\"$space_url\" target=\"_blank\">#$e# </a> ", $content);
            } else {
                $content = str_replace("#$e#", " <a href=\"$space_url\" target=\"_blank\">#$e# </a> ", $content);
            }
        }
    }

    //返回替换的文本
    return $content;
}

function get_topic($content) {
    //正则表达式匹配
    $topic_pattern = "/#([^\\#|.]+)#/";
    preg_match_all($topic_pattern, $content, $users);

    //返回话题列表
    return array_unique($users[1]);
}

function use_topic() {
    $topic = modC('USE_TOPIC');

    if (empty($topic)) {
        return;
    }
    $topics = explode(',', $topic);
    $html = '';
    foreach ($topics as $k => $v) {
        $v = '#' . $v . '#';
        $html .= '<a href="javascript:" class="label label-badge label-info" data-role="chose_topic">' . $v . '</a> ';
    }
    unset($k, $v);

    return $html;
}
function weiquanPageLink($totalCount, $countPerPage = 10, $rollPage = 0){

    $pageCount = ceil($totalCount / $countPerPage);
    
    //如果只有1页，就没必要翻页了
    if ($pageCount <= 1) {
        return '';
    }
    $Page = new \Think\Page($totalCount, $countPerPage); // 实例化分页类 传入总记录数和每页显示的记录数
    if ($rollPage) {
        $Page->setRollPage($rollPage);
    }
    return $Page->show();
}



