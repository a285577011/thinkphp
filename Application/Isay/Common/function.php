<?php
/**
 * 检查是否点赞
 */
function check_dolike($obj_id,$obj_type,$uid){
    return D('Isay/IsayLike')->getLikeByOidAndUidAndObj($obj_id,$uid,$obj_type);
}

////获取用户信息
//function get_user_info($uid = null) {
//    $user_info = query_user(array('avatar64', 'avatar128', 'nickname','email', 'uid', 'space_url', 'score', 'title', 'fans', 'pos_province', 'pos_city', 'pos_district', 'pos_community', 'following', 'weiquancount', 'rank_link', 'signature', 'sex'), $uid);
//    //获取用户封面id
//    $map = getUserConfigMap('user_cover', '', $uid);
//    $map['role_id'] = 0;
//    $model = D('Ucenter/UserConfig');
//    $cover = $model->findData($map);
//    $user_info['cover_id'] = $cover['value'];
//    $user_info['cover_path'] = getThumbImageById($cover['value'], 1140, 230);
//    $user_info['tags'] = D('Ucenter/UserTagLink')->getUserTag($uid);
//
//    if ($user_info['pos_province'] != 0) {
//        $user_info['pos_province'] = D('district')->where(array('id' => $user_info['pos_province']))->getField('name');
//        $user_info['pos_city'] = D('district')->where(array('id' => $user_info['pos_city']))->getField('name');
//        $user_info['pos_district'] = D('district')->where(array('id' => $user_info['pos_district']))->getField('name');
//        $user_info['pos_community'] = D('district')->where(array('id' => $user_info['pos_community']))->getField('name');
//    }
//    return $user_info;
//}


