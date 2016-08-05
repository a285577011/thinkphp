<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-6-5
 * Time: 上午10:38
 * 
 */

/**
 * 获取要排除的uids(版主、自己)
 *
 * @param int $lzl_reply_id            
 * @param int $reply_id            
 * @param int $post_id            
 * @param int $forum_id            
 * @param int $with_self
 *            是否包含记录的uid
 * @return array|int|mixed
 *
 */
function get_expect_ids($lzl_reply_id = 0, $reply_id = 0, $post_id = 0, $forum_id = 0, $with_self = 1)
{
    $uid = 0;
    if (! $forum_id) {
        if (! $post_id) {
            if (! $reply_id) {
                $lzl_reply = D('ForumLzlReply')->find($lzl_reply_id);
                $uid = $lzl_reply['uid'];
                $post_id = $lzl_reply['post_id'];
            } else {
                $reply = D('ForumPostReply')->find(intval($reply_id));
                $uid = $reply['uid'];
                $post_id = $reply['post_id'];
            }
        }
        $post = D('ForumPost')->where(array(
            'id' => $post_id,
            'status' => 1
        ))->find();
        $forum_id = $post['forum_id'];
        if (! $uid) {
            $uid = $post['uid'];
        }
    }
    $forum = D('Forum')->find($forum_id);
    if (mb_strlen($forum['admin'], 'utf-8')) {
        $expect_ids = str_replace('[', '', $forum['admin']);
        $expect_ids = str_replace(']', '', $expect_ids);
        $expect_ids = explode(',', $expect_ids);
        if ($uid && $with_self) {
            if (! in_array($uid, $expect_ids)) {
                $expect_ids = array_merge($expect_ids, array(
                    $uid
                ));
            }
        }
    } else {
        if ($with_self && $uid) {
            $expect_ids = $uid;
        } else {
            $expect_ids = - 1;
        }
    }
    return $expect_ids;
}

/**
 * 论坛板块是否允许发帖
 *
 * @param
 *            $forum_id
 * @return bool
 *
 */
function forumAllowCurrentUserGroup($forum_id)
{
    $forum_id = intval($forum_id);
    // 如果是超级管理员，直接允许
    if (is_login() == 1) {
        return true;
    }
    
    // 如果帖子不属于任何板块，则允许发帖
    if (intval($forum_id) == 0) {
        return true;
    }
    
    // 读取论坛的基本信息
    $forum = D('Forum')->where(array(
        'id' => $forum_id
    ))->find();
    $userGroups = explode(',', $forum['allow_user_group']);
    
    // 读取用户所在的用户组
    $list = M('AuthGroupAccess')->where(array(
        'uid' => is_login()
    ))->select();
    foreach ($list as &$e) {
        $e = $e['group_id'];
    }
    
    // 判断用户组是否有权限
    $list = array_intersect($list, $userGroups);
    return $list ? true : false;
}

/**
 *
 * @param type $totalCount
 *            数据总条目
 * @param type $countPerPage
 *            分页数据条目
 * @param type $nowpage
 *            当前页
 * @param array $u_param
 *            格式(array('mod'=>'Home\Index\Index','param'=>array('c'=>1,'b'=>2))),参数1“mod”代表U格式化路径，参数2'param'代表U格式化参数
 * @param type $show_goto
 *            是否显示跳转
 * @param type $show_page_count
 *            显示分页页码数
 * @return string
 */
function getPageViewF($totalCount, $countPerPage, $u_param, $show_goto = FALSE, $show_page_count = 10, $show_total_page = true)
{
    $nowpage = I('get.p', '1', 'intval');
    // 计算总页数
    $pageCount = ceil($totalCount / $countPerPage);
    
    // 如果只有1页，就没必要翻页了
    if ($pageCount <= 1) {
        return '';
    }
    
    $url = is_array($u_param) && isset($u_param['mod']) ? $u_param['mod'] : $u_param;
    $param = is_array($u_param) && isset($u_param['param']) ? $u_param['param'] : I('get.');
    $pages = '';
    // 数字分页
    if ($pageCount < $show_page_count) {
        for ($i = 1; $i <= $pageCount; $i ++) {
            $param['p'] = $i;
            if ($i == $nowpage) {
                $pages .= "<a class='page active' >" . $i . "</a>\n";
            } else {
                $pages .= "<a class='page' href='" . U($url, $param) . "'>" . $i . "</a>\n";
            }
        }
    } elseif ($nowpage < $show_page_count && $pageCount >= $show_page_count) {
        for ($i = 1; $i < $show_page_count; $i ++) {
            $param['p'] = $i;
            if ($i == $nowpage) {
                $pages .= "<a class='page active' >" . $i . "</a>\n";
            } else {
                $pages .= "<a class='page' href='" . U($url, $param) . "'>" . $i . "</a>\n";
            }
        }
        $param['p'] = $pageCount;
        $pages .= "<span class=\"three_point\">▪▪▪</span>\n";
        $pages .= "<a   class='page' href='" . U($url, $param) . "'>" . $pageCount . "</a>\n";
    } elseif ($nowpage >= $show_page_count && ($pageCount - $nowpage) < $show_page_count) {
        $star = $pageCount - $show_page_count;
        for ($i = $star; $i <= $pageCount; $i ++) {
            if ($i < 1)
                continue;
            $param['p'] = $i;
            if ($i == $nowpage) {
                $pages .= "<a class='page active'>" . $i . "</a>\n";
            } else {
                $pages .= "<a class='page' href='" . U($url, $param) . "'>" . $i . "</a>\n";
            }
        }
    } elseif ($nowpage >= $show_page_count) {
        $count = ($pageCount - $nowpage) >= $show_page_count ? $show_page_count : ($pageCount - $nowpage);
        for ($i = $nowpage; $i <= ($nowpage + $count); $i ++) {
            $param['p'] = $i;
            if ($i == $nowpage) {
                $pages .= "<a class='page active' >" . $i . "</a>\n";
            } else {
                $pages .= "<a class='page' href='" . U($url, $param) . "'>" . $i . "</a>\n";
            }
        }
        if ($count >= $show_page_count) {
            $param['p'] = $pageCount;
            $pages .= "<span class=\"three_point\">▪▪▪</span>\n";
            $pages .= "<a class='page' href='" . U($url, $param) . "'>" . $pageCount . "</a>\n";
        }
    }
    
    // 上一页
    if ($nowpage == 1) {
        $pre = "<a class='page_pre disabled' ></a>\n";
    } else {
        $param['p'] = $nowpage - 1;
        $pre = "<a class='page_pre'  href = '" . U($url, $param) . "'></a>\n";
    }
    
    // 下一页
    if ($nowpage == $pageCount) {
        $next = "<a class='page_next disabled' ></a>\n";
    } else {
        $param['p'] = $nowpage + 1;
        $next = "<a class='page_next'  href = '" . U($url, $param) . "'></a>\n";
    }
    
    if ($show_total_page) {
        $total_page = '共' . $pageCount . '页';
    }
    
    // 跳转到
    if ($show_goto) {
        $param['p'] = 1;
        $goto = "<span class=\"page_goto\">到 <input type=\"text\" class=\"page_txt_num\" onkeyup=\"if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}\" onafterpaste=\"if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}\">页\n";
        $goto .= "<button class=\"page_btn_go\" onclick=\"goToUrl('.page_txt_num',this)\" data-url=\"" . U($url, $param) . "\">go</button>\n";
    }
    
    $script = <<<js
<script>
function goToUrl(jqclass,obj){
    var cls=$(obj).parent().find(jqclass);
    var num=parseInt(cls.val());
    if(isNaN(num))return;
    var url = $(obj).attr('data-url').replace(/p\/(\d)+\.?/g, "p/" + num + ".");
  
     window.location.href=url;
}
</script>
js;
    
    return $pre . $pages . $next . $total_page . $goto . $script;
}

/**
 * 根据帖子ID获取板块名字跟ID
 */
function getForumNameById($forumId)
{
    return M('Forum')->where(array(
        'id' => $forumId
    ))
        ->field('id,title')
        ->find();
}

function checkIsPointLike($postId, $uId)
{
    return M('ForumPointlike')->where(array(
        'post_id' => $postId,
        'uid' => $uId
    ))->count();
}

function getTagNameById($id)
{
    return M('ForumTag')->where(array(
        'id' => $id
    ))->getField('title');
}

function getPageHtmlF($f_name, $totalpage, $data, $nowpage)
{
    if ($totalpage > 1 && $totalpage != null) {
        $str = '';
        foreach ($data as $k => $v) {
            $str = $str . '"' . $v . '"' . ',';
        }
        $pages = '';
        for ($i = 1; $i <= $totalpage; $i ++) {
            if ($i == $nowpage) {
                $pages = $pages . "<a href=\"javascript:\"  class='page active' onclick='" . $f_name . "(" . $str . $i . ")'>" . $i . "</a>";
            } else {
                $pages = $pages . "<a href=\"javascript:\"  class='page' onclick='" . $f_name . "(" . $str . $i . ")'>" . $i . "</a>";
            }
        }
        if ($nowpage == 1) {
            $a = $nowpage;
            $pre = "<a href=\"javascript:\" class='page_pre'  onclick = '" . $f_name . "( " . $str . $a . ")'></a>";
        } else {
            $a = $nowpage - 1;
            $pre = "<a href=\"javascript:\" class='page_pre'  onclick = '" . $f_name . "( " . $str . $a . ")'></a>";
        }
        /*
         * $pre = "<a class='a page_pre' onclick = '" .
         * $f_name . "( " . $str . $a . ")'>" . L('_LAST_PAGE_') . "</a>";
         */
        
        if ($nowpage == $totalpage) {
            $b = $totalpage;
            $next = "<a href=\"javascript:\" class='a page_next'  onclick = '" . $f_name . "( " . $str . $b . ")'></a>";
        } else {
            $b = $nowpage + 1;
            $next = "<a href=\"javascript:\" class='a page_next'  onclick = '" . $f_name . "( " . $str . $b . ")'></a>";
        }
        
        return $pre . $pages . $next;
    }
}

/**
 * 检测验证码
 * 
 * @param integer $id
 *            验证码ID
 * @return boolean 检测结果
 *        
 */
function check_verify($code, $id = 1)
{
    $verify = new \Think\Verify();
    return $verify->check($code, $id);
}

/**
 * 格式化过去时间
 *
 * @param int $time            
 * @return string
 * @author huangyy
 */
function newTimeToDHIS($time, $allFlag = FALSE)
{
    $time = time() - $time;
    if ($time <= 0) {
        return '-';
    }
    $timeStr = '';
    $nY = intval($time / 60 / 60 / 24 / 365);
    $nD = ($time / 60 / 60 / 24) % 365;
    $nH = ($time / 60 / 60) % 24;
    $nI = ($time / 60) % 60;
    $nS = ($time % 60);
    $nD = $nY * 365 + $nD;
    if ($allFlag) {
        $leftTime = $nY ? $nY . '年' : '';
        $leftTime .= $nD ? $nD . '天' : '';
        $leftTime .= $nH ? $nH . '时' : '';
        $leftTime .= $nI ? $nI . '分' : '';
        $leftTime .= $nS ? $nS . '秒' : '';
        
        return $leftTime;
    }
    if ($nD >= 7) {
        $timeStr .= $nD . '天';
    } elseif ($nD >= 1) {
        $timeStr .= $nD . '天';
        $timeStr .= $nH ? $nH . '时' : '';
    } elseif ($nH >= 1) {
        $timeStr .= $nH . '时';
        $timeStr .= $nI ? $nI . '分' : '';
    } elseif ($nI >= 1) {
        $timeStr .= $nI . '分';
        $timeStr .= $nS ? $nS . '秒' : '';
    } else {
        $timeStr .= $nS . '妙';
    }
    return $timeStr;
}

function getForumIdByPost($postId)
{
    return M('ForumPost')->where(array(
        'id' => $postId
    ))->getField('forum_id');
}

function getFirstChar($s0)
{
    $firstchar_ord = ord(strtoupper($s0{0}));
    if (($firstchar_ord >= 65 and $firstchar_ord <= 91) or ($firstchar_ord >= 48 and $firstchar_ord <= 57))
        return strtoupper($s0{0});
    $s = iconv("UTF-8", "gb2312", $s0);
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if ($asc >= - 20319 and $asc <= - 20284)
        return "A";
    if ($asc >= - 20283 and $asc <= - 19776)
        return "B";
    if ($asc >= - 19775 and $asc <= - 19219)
        return "C";
    if ($asc >= - 19218 and $asc <= - 18711)
        return "D";
    if ($asc >= - 18710 and $asc <= - 18527)
        return "E";
    if ($asc >= - 18526 and $asc <= - 18240)
        return "F";
    if ($asc >= - 18239 and $asc <= - 17923)
        return "G";
    if ($asc >= - 17922 and $asc <= - 17418)
        return "H";
    if ($asc >= - 17417 and $asc <= - 16475)
        return "J";
    if ($asc >= - 16474 and $asc <= - 16213)
        return "K";
    if ($asc >= - 16212 and $asc <= - 15641)
        return "L";
    if ($asc >= - 15640 and $asc <= - 15166)
        return "M";
    if ($asc >= - 15165 and $asc <= - 14923)
        return "N";
    if ($asc >= - 14922 and $asc <= - 14915)
        return "O";
    if ($asc >= - 14914 and $asc <= - 14631)
        return "P";
    if ($asc >= - 14630 and $asc <= - 14150)
        return "Q";
    if ($asc >= - 14149 and $asc <= - 14091)
        return "R";
    if ($asc >= - 14090 and $asc <= - 13319)
        return "S";
    if ($asc >= - 13318 and $asc <= - 12839)
        return "T";
    if ($asc >= - 12838 and $asc <= - 12557)
        return "W";
    if ($asc >= - 12556 and $asc <= - 11848)
        return "X";
    if ($asc >= - 11847 and $asc <= - 11056)
        return "Y";
    if ($asc >= - 11055 and $asc <= - 10247)
        return "Z";
    return null;
}