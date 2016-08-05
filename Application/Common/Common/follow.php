<?php

/**
 * 获取用户关注
 * @param type $follow_who
 * @param type $before
 * @param type $after
 */
 function get_follow($follow_who = 0, $before, $after)    {
        $follow_who = intval($follow_who);
        $who_follow = is_login();
        $is_following = D('Follow')->isFollow($who_follow, $follow_who);
        $data=array();
        $data['after']=$after;
        $data['before']=$before;
        $data['is_following']=$is_following ? 1 : 0;
        $data['is_self']=($who_follow == $follow_who);
        $data['follow_who']=$follow_who;//关注谁
        $data['mutually_following']=D('Follow')->isFollow($follow_who,$who_follow);//判断是否互相关注
      
        return $data;
        
    }
