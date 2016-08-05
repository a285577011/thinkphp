<?php
namespace Ucenter\Logic;

class UcenterLogic extends \Think\Model
{
   public function getUcCard(){
   	
   }
   /**获取个人资料，用以支持小名片
    *
    */
   public function getProfile($uid)
   {
   	$uid = intval($_REQUEST['uid']);
   	$userProfile = query_user(array('username','catid','uid', 'nickname', 'avatar64','avatar', 'space_url', 'following', 'fans', 'weiquancount', 'signature', 'rank_link','sex'), $uid);
   	$follow['follow_who'] = $userProfile['uid'];
   	$follow['who_follow'] = is_login();
   	$userProfile['followed'] = D('Follow')->where($follow)->count();
   	$userProfile['following_url'] = U('Index/following', array('uid' => $uid));
   	$userProfile['fans_url'] = U('Index/fans', array('uid' => $uid));
   	$userProfile['weibo_url'] = U('Index/appList', array('uid' => $uid, 'type' => "weibo"));
   	$html = '';
   	if (count($userProfile['rank_link'])) {
   		foreach ($userProfile['rank_link'] as $val) {
   			if ($val['is_show']) {
   				if (empty($val['label_content'])) {
   					$html = $html . '<img class="img-responsive" src="' . $val['logo_url'] . '" title="' . $val['title'] . '" alt="' . $val['title'] . '" style="width: 18px;height: 18px;vertical-align: middle;margin-left: 3px;display: inline;"/>';
   				} else {
   					$html = $html . '<span class="label label-badge rank-label" title="' . $val['title'] . '" style="background:' . $val['label_bg'] . ' !important;color:' . $val['label_color'] . ' !important;vertical-align: middle;margin-left: 3px;">' . $val['label_content'] . '</span>';
   				}
   			}
   		}
   		unset($val);
   	}
   	$userProfile['rank_link'] = $html;
   	//获取用户封面path
   	$map = getUserConfigMap('user_cover', '', $uid);
   	$map['role_id'] = 0;
   	$model = D('Ucenter/UserConfig');
   	$cover = $model->findData($map);
   	if ($cover) {
   		$userProfile['cover_path'] = getThumbImageById($cover['value'], 344, 100);
   	} else {
   		$userProfile['cover_path'] = __ROOT__ . '/Public/images/qtip_bg.png';
   	}
   	//个人标签
   	/* $userProfile['tags'] = '';
   	 $userTagLinkModel = D('Ucenter/UserTagLink');
   	 $myTags = $userTagLinkModel->getUserTag($uid);
   	 if (count($myTags)) {
   	 $userProfile['tags'] = L('_PERSONAL_TAB_').L('_COLON_').'<span>';
   	 $first = 1;
   	 foreach ($myTags as $val) {
   	 if ($first) {
   	 $userProfile['tags'] .= '<a style="color: #848484;"  href="' . U('people/index/index', array('tag' => $val['id'])) . '">' . $val['title'] . '</a>';
   	 $first = 0;
   	 } else {
   	 $userProfile['tags'] .= '、<a style="color: #848484;"  href="' . U('people/index/index', array('tag' => $val['id'])) . '">' . $val['title'] . '</a>';
   	 }
   	 }
   	 $userProfile['tags'] .= '</span>';
   	 }
   	 */
   	$userProfile['followFlag']=R('Ucenter/index/getFollowFlag',array(is_login(),$uid));
   	return $userProfile;
   }
   public function changeCover(){
   	$aCoverId = I('post.cover_id', 0, 'intval');
   	$result['status'] = 0;
   	if ($aCoverId <= 0) {
   		$result['info'] = L('_ERROR_ILLEGAL_OPERATE_').L('_EXCLAMATION_');
   		return $result;
   		//$this->ajaxReturn($result);
   	}
   	
   	$data = getUserConfigMap('user_cover');
   	$data['role_id'] = 0;
   	$model = D('Ucenter/UserConfig');
   	$already_data = $model->findData($data);
   	if (!$already_data) {
   		$data['value'] = $aCoverId;
   		$res = $model->addData($data);
   	} else {
   		if ($already_data['value'] == $aCoverId) {
   			$result['info'] = L('_ALTER_NOT_').L('_EXCLAMATION_');
   			return $result;
   		} else {
   			$res = $model->saveValue($data, $aCoverId);
   		}
   	}
   	if ($res) {
   		$result['status'] = 1;
   		$result['path_1140'] = getThumbImageById($aCoverId, 1140, 230);
   		$result['path_273'] = getThumbImageById($aCoverId, 273, 70);
   	} else {
   		$result['info'] = L('_FAIL_OPERATE_').L('_EXCLAMATION_');
   	}
   	return $result;
   }
   public function getCoverInfo(){
   	$map = getUserConfigMap('user_cover');
   	$map['role_id'] = 0;
   	$model = D('Ucenter/UserConfig');
   	$cover = $model->findData($map);
   	$my_cover['cover_id'] = $cover['value'];
   	$my_cover['cover_path'] = getThumbImageById($cover['value'], 348, 70);
   	return $my_cover;
   }
}
