<?php

/**
 * 
 */

/**
 * 友好的时间显示
 *
 * @param int    $sTime 待显示的时间
 * @param string $type  类型. normal | mohu | full | ymd | other
 * @param string $alt   已失效
 * @return string
 */
function friendlyDate($sTime, $type = 'normal', $alt = 'false') {
    if (!$sTime)
        return '';
    //sTime=源时间，cTime=当前时间，dTime=时间差
    $cTime = time();
    $dTime = $cTime - $sTime;
    $dDay = intval(date("z", $cTime)) - intval(date("z", $sTime));
    //$dDay     =   intval($dTime/3600/24);
    $dYear = intval(date("Y", $cTime)) - intval(date("Y", $sTime));
    //normal：n秒前，n分钟前，n小时前，日期
    if ($type == 'normal') {
        if ($dTime < 60) {
            if ($dTime < 10) {
                return L('_JUST_');    //by yangjs
            } else {
                return intval(floor($dTime / 10) * 10) . L('_SECONDS_AGO_');
            }
        } elseif ($dTime < 3600) {
            return intval($dTime / 60) . L('_MINUTES_AGO_');
            //今天的数据.年份相同.日期相同.
        } elseif ($dYear == 0 && $dDay == 0) {
            //return intval($dTime/3600).L('_HOURS_AGO_');
            return L('_TODAY_') . date('H:i', $sTime);
        } elseif ($dYear == 0) {
            return date("m月d日 H:i", $sTime);
        } else {
            return date("Y-m-d H:i", $sTime);
        }
    } elseif ($type == 'mohu') {
        if ($dTime < 60) {
            return $dTime . L('_SECONDS_AGO_');
        } elseif ($dTime < 3600) {
            return intval($dTime / 60) . L('_MINUTES_AGO_');
        } elseif ($dTime >= 3600 && $dDay == 0) {
            return intval($dTime / 3600) . L('_HOURS_AGO_');
        } elseif ($dDay > 0 && $dDay <= 7) {
            return intval($dDay) . L('_DAYS_AGO_');
        } elseif ($dDay > 7 && $dDay <= 30) {
            return intval($dDay / 7) . L('_WEEK_AGO_');
        } elseif ($dDay > 30) {
            return intval($dDay / 30) . L('_A_MONTH_AGO_');
        }
        //full: Y-m-d , H:i:s
    } elseif ($type == 'full') {
        return date("Y-m-d , H:i:s", $sTime);
    } elseif ($type == 'ymd') {
        return date("Y-m-d", $sTime);
    } else {
        if ($dTime < 60) {
            return $dTime . L('_SECONDS_AGO_');
        } elseif ($dTime < 3600) {
            return intval($dTime / 60) . L('_MINUTES_AGO_');
        } elseif ($dTime >= 3600 && $dDay == 0) {
            return intval($dTime / 3600) . L('_HOURS_AGO_');
        } elseif ($dYear == 0) {
            return date("Y-m-d H:i:s", $sTime);
        } else {
            return date("Y-m-d H:i:s", $sTime);
        }
    }
}

/**
 * 获取时间戳毫秒级别
 * @return type
 * @author zhangby
 */
function msectime() {
    list($tmp1, $tmp2) = explode(' ', microtime());
    return (float) sprintf('%.0f', (floatval($tmp1) + floatval($tmp2)) * 1000);
}

/**
 * 格式化毫秒时间戳
 * @param string $format
 * @param time $date
 * @param bool $ms 是否显示毫秒
 * @return string
 * @author zhangby
 */
function date_fmt($format, $date, $ms = TRUE) {
    $d = substr($date, 0, 10);
    if (!$ms) {
        return date($format, $d);
    }

    $t = '000';
    if (strlen($date) > 10) {
        $t = substr($date, 10);
    }
    return date($format, $d) . ('.' . $t);
}

/**
 * 计算两个时间差
 * @param type $begin_time 开始时间
 * @param type $end_time 结束时间
 * @param type $all 计算类型（TRUE不区分时间先后顺序一律返回时间差，FALSE如果begin_time大于end_time则不计算）
 * @return array
 * @author zhangby
 */
function timediff($begin_time, $end_time, $all = TRUE) {
    if ($all == FALSE && $begin_time > $end_time) {
        return array("day" => 0, "hour" => 0, "min" => 0, "sec" => 0);
    }

    if ($begin_time < $end_time) {
        $starttime = $begin_time;
        $endtime = $end_time;
    } else {
        $starttime = $end_time;
        $endtime = $begin_time;
    }
    $timediff = $endtime - $starttime;
    $days = intval($timediff / 86400);
    $remain = $timediff % 86400;
    $hours = intval($remain / 3600);
    $remain = $remain % 3600;
    $mins = intval($remain / 60);
    $secs = $remain % 60;
    $res = array("day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs);
    return $res;
}

/**
 * 秒转换成时间
 * 只支持（H、h、i、s）格式化
 * @param type $format
 * @param type $time
 * @return type
 */
function sec2time($format, $time) {
    if (is_numeric($time)) {
        $d = floor($time / (24 * 3600));
        $sec = $time % (24 * 3600);
        $h = floor($sec / 3600);
        $remainSeconds = $sec % 3600;
        $m = floor($remainSeconds / 60);
        $s = intval($sec - $h * 3600 - $m * 60);

        $str = str_replace('h', $h, $format);
        $str = str_replace('H', ($h >=10 ? $h : ('0' . $h)), $str);
        $str = str_replace('i', ($m >=10 ? $m : ('0' . $m)), $str);
        $str = str_replace('s', ($s >=10 ? $s : ('0' . $s)), $str);

        return $str;
    }
    return 0;
}
