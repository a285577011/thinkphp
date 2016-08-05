<?php

/**
 * Created by PhpStorm.
 * User: caipeichao
 * Date: 14-3-10
 * Time: PM7:40
 */

/**
 * 
 * @param type $totalCount 数据总条目
 * @param type $countPerPage 分页数据条目
 * @param type $nowpage 当前页
 * @param array $u_param 格式(array('mod'=>'Home\Index\Index','param'=>array('c'=>1,'b'=>2))),参数1“mod”代表U格式化路径，参数2'param'代表U格式化参数
 * @param type $show_goto 是否显示跳转
 * @param type $show_page_count 显示分页页码数
 * @return string
 */
function getPageView($totalCount, $countPerPage, $u_param, $show_goto = FALSE, $show_page_count = 10, $show_total_page = true) {
    $nowpage = I('get.p', '1', 'intval');
    //计算总页数
    $pageCount = ceil($totalCount / $countPerPage);

    //如果只有1页，就没必要翻页了
    if ($pageCount <= 1) {
        return '';
    }

    $url = is_array($u_param) && isset($u_param['mod']) ? $u_param['mod'] : $u_param;
    $param = is_array($u_param) && isset($u_param['param']) ? $u_param['param'] : array('p' => 1);
    $pages = '';
    //数字分页
    if ($pageCount < $show_page_count) {
        for ($i = 1; $i <= $pageCount; $i++) {
            $param['p'] = $i;
            if ($i == $nowpage) {
                $pages.="<a class='page active' >" . $i . "</a>\n";
            } else {
                $pages .= "<a class='page' href='" . U($url, $param) . "'>" . $i . "</a>\n";
            }
        }
    } elseif ($nowpage < $show_page_count && $pageCount >= $show_page_count) {
        for ($i = 1; $i < $show_page_count; $i++) {
            $param['p'] = $i;
            if ($i == $nowpage) {
                $pages.="<a class='page active' >" . $i . "</a>\n";
            } else {
                $pages .="<a class='page' href='" . U($url, $param) . "'>" . $i . "</a>\n";
            }
        }
        $param['p'] = $pageCount;
        $pages.="<span class=\"three_point\">▪▪▪</span>\n";
        $pages.="<a   class='page' href='" . U($url, $param) . "'>" . $pageCount . "</a>\n";
    } elseif ($nowpage >= $show_page_count && ($pageCount - $nowpage) < $show_page_count) {
        $star=$pageCount-$show_page_count;
        for ($i = $star; $i <= $pageCount; $i++) {
            if($i<1)continue;
            $param['p'] = $i;
            if ($i == $nowpage) {
                $pages.="<a class='page active'>" . $i . "</a>\n";
            } else {
                $pages .="<a class='page' href='" . U($url, $param) . "'>" . $i . "</a>\n";
            }
        }
    } elseif ($nowpage >= $show_page_count) {
        $count = ($pageCount - $nowpage) >= $show_page_count ? $show_page_count : ($pageCount - $nowpage);
        for ($i = $nowpage; $i <= ($nowpage + $count); $i++) {
            $param['p'] = $i;
            if ($i == $nowpage) {
                $pages.="<a class='page active' >" . $i . "</a>\n";
            } else {
                $pages .="<a class='page' href='" . U($url, $param) . "'>" . $i . "</a>\n";
            }
        }
        if ($count >= $show_page_count) {
            $param['p'] = $pageCount;
            $pages.="<span class=\"three_point\">▪▪▪</span>\n";
            $pages.="<a class='page' href='" . U($url, $param) . "'>" . $pageCount . "</a>\n";
        }
    }

    //上一页
    if ($nowpage == 1) {
        $pre = "<a class='page_pre disabled' ></a>\n";
    } else {
        $param['p'] = $nowpage - 1;
        $pre = "<a class='page_pre'  href = '" . U($url, $param) . "'></a>\n";
    }

    //下一页
    if ($nowpage == $pageCount) {
        $next = "<a class='page_next disabled' ></a>\n";
    } else {
        $param['p'] = $nowpage + 1;
        $next = "<a class='page_next'  href = '" . U($url, $param) . "'></a>\n";
    }
    
    if($show_total_page){
        $total_page = '共'.$pageCount.'页';
    }
    
    //跳转到
    if ($show_goto) {
        $param['p'] = 1;
        $goto = "<span class=\"page_goto\">到 <input type=\"text\" class=\"page_txt_num\" onkeyup=\"if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}\" onafterpaste=\"if(this.value.length==1){this.value=this.value.replace(/[^1-9]/g,'')}else{this.value=this.value.replace(/\D/g,'')}\">页\n";
        $goto.="<button class=\"page_btn_go\" onclick=\"goToUrl('.page_txt_num',this)\" data-url=\"".U($url, $param)."\">确定</button>\n";
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

function getPagination($totalCount, $countPerPage = 10, $rollPage = 0) {
    //计算总页数
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

function getPageHtml($f_name, $totalpage, $data, $nowpage) {
    if ($totalpage > 1 && $totalpage != null) {
        $str = '';
        foreach ($data as $k => $v) {
            $str = $str . '"' . $v . '"' . ',';
        }
        $pages = '';
        for ($i = 1; $i <= $totalpage; $i++) {
            if ($i == $nowpage) {
                $pages = $pages . "<li class=\"active\"><a href=\"javascript:\"  class='page active' onclick='" . $f_name . "(" . $str . $i . ")'>" . $i . "</a></li>";
            } else {
                $pages = $pages . "<li><a href=\"javascript:\"  class='page' onclick='" . $f_name . "(" . $str . $i . ")'>" . $i . "</a></li>";
            }
        }
        if ($nowpage == 1) {
            $a = $nowpage;
            $pre = "<li class=\"disabled\"><a href=\"javascript:\" class='page_pre'  onclick = '" . $f_name . "( " . $str . $a . ")'>" . "&laquo;" . "</a></li>";
        } else {
            $a = $nowpage - 1;
            $pre = "<li><a href=\"javascript:\" class='page_pre'  onclick = '" . $f_name . "( " . $str . $a . ")'>" . "&laquo;" . "</a></li>";
        }
        /*    $pre = "<li class=\"disabled\"><a class='a page_pre'  onclick = '" . $f_name . "( " . $str . $a . ")'>" . L('_LAST_PAGE_') . "</a></li>"; */

        if ($nowpage == $totalpage) {
            $b = $totalpage;
            $next = "<li class=\"disabled\"><a href=\"javascript:\" class='a page_next'  onclick = '" . $f_name . "( " . $str . $b . ")'>" . "&raquo;" . "</a></li>";
        } else {
            $b = $nowpage + 1;
            $next = "<li><a href=\"javascript:\" class='a page_next'  onclick = '" . $f_name . "( " . $str . $b . ")'>" . "&raquo;" . "</a></li>";
        }

        return $pre . $pages . $next;
    }
}

function getPage($data, $limit, $page) {
    $offset = ($page - 1) * $limit;
    return array_slice($data, $offset, $limit);
}

function addUrlParam($url, $params) {
    $app = MODULE_NAME;
    $controller = CONTROLLER_NAME;
    $action = ACTION_NAME;
    $get = array_merge($_GET, $params);
    return U("$app/$controller/$action", $get);
}

function getCurrentUrl() {
    return $_SERVER['REQUEST_URI'];
}
