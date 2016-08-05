<?php

/**
 * 创建日期: 3/25/14
 * 创建时间: 9:27 AM
 */

/**
 * @param $content
 * @return mixed
 */
function match_users($content)
{
    $user_pattern = "/\@([^\#|\s]+)\s/"; //匹配用户
    preg_match_all($user_pattern, $content, $user_math);
    return $user_math;
}