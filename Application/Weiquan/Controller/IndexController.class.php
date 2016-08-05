<?php

namespace Weiquan\Controller;

use Think\Controller;
use Think\Hook;

class IndexController extends BaseController {
	public function _initialize() {
		parent::_initialize ();
		$uid = isset ( $_GET ['uid'] ) ? op_t ( $_GET ['uid'] ) : is_login ();
		// 调用API获取基本信息
		// $this->userInfo($uid);
		// $this->_fans_and_following($uid);
		// $this->_tab_menu();
	}
	
	/**
	 * index 微商圈首页
	 */
	public function index() {
	    unset($_GET['username']);
		$output = [ ];
		$tab_config = get_kanban_config ( 'WEIQUAN_DEFAULT_TAB', 'enable', array (
				'all',
				'related' 
		) );
		if (is_login () != $this->rUid) {
			$this->redirect ( 'Weiquan/Index/WeiUc' );
		}
		if (! is_login ()) {
			$_key = array_search ( 'related', $tab_config );
			unset ( $tab_config [$_key] );
		}
		// getPageHtml();
		// 获取参数
		$aType = I ( 'get.type', reset ( $tab_config ), 'op_t' );
		$aUid = is_login ();
		$aPage = I ( 'get.p', 1, 'intval' );
		if (! in_array ( $aType, $tab_config )) {
			$this->error ( L ( '_ERROR_PARAM_' ) );
		}
		// 查询条件
		$weiquanModel = D ( 'Weiquan' );
		
		$param = array ();
		if ($aPage == 1) {
			$param ['limit'] = 10;
		} else {
			$param ['page'] = $aPage;
			$param ['count'] = 30;
		}
		$param = $this->filterWeiquan ( $aType, $param );
		$param ['where'] ['status'] = 1;
		$param ['where'] ['is_top'] = 0;
		//$param ['where'] ['uid'] = $aUid;
		// 查询
		$show_user = $aType == 'related' ? 0 : 1;		
		list($output ['list'],$output ['total_count']) = $weiquanModel->getWeiquanList ( $param,$show_user );
		//$output ['total_count'] = $weiquanModel->getWeiquanCount ( $param ['where'],$show_user );
		//print_r($output ['list']);die;
		//echo $weiquanModel->getLastSql();die;
		$this->assign('isMore',$output ['total_count']>10?true:false);
		// 获取置顶微博
		$output ['top_list'] = $weiquanModel->getWeiquanList ( array (
				'where' => array (
						'status' => 1,
						'is_top' => 1 
				) 
		) );
		
		$output ['type'] = $aType;
		$output ['tab_config'] = $tab_config;
		if ($aType == 'related') {
			$output ['title'] = L ( '_MY_RELATED_' );
			$output ['filter_tab'] = 'related';
		} else {
			$output ['title'] = L ( '_ALL_WEBSITE_WEIQUAN_' );
			$output ['filter_tab'] = 'all';
			if(! is_login ()){
			    $this->error('请登录');
			}
		}
		$this->setTitle ( '{$title}' . L ( '_LINE_LINE_' ) . L ( '_MODULE_' ) );
		$this->assignSelf ();
		if (is_login ()) { // && check_auth('Weiquan/Index/doSend')) {
			$output ['show_post'] = true;
		}
		
		if ($show_user) {
			$output ['loadMoreUrl'] = U ( 'loadweiquan', array (
					'su' => $show_user 
			) );
		} else {
			$output ['loadMoreUrl'] = U ( 'loadweiquan', array (
					'uid' => $aUid,
					'su' => $show_user 
			) );
		}
		M('UserStatistic')->where(array('uid'=>$aUid))->setInc('views');//增加统计表
		M('Member')->where(array('uid'=>aUid))->save(array('update_time'=>time()));//更新增量
		$output ['tab'] = 'index';
		$output ['uid'] = $aUid;
		$output ['banner_nav'] ['index'] = 'index';
		$output ['page'] = $aPage;
		$output ['user_info'] = get_user_info ( $aUid, TRUE );
		$output ['user_info'] ['show_field'] = get_user_field_show ( $aUid );
		//$output ['user_info'] ['tag_link']=query_user(array('username','homepage','promote_link'),$aUid);
		foreach ( $output ['user_info'] ['show_field'] as &$v ) {
			$v = 1;
		}
		$this->assign ( $output );
		$this->display ();
	}
	
	/**
	 * 查看他人动态
	 * 
	 * @author zhangby
	 */
	public function weiUc() {
		$output = [ ];
		// 获取参数
		$aUid = I ( 'get.uid', 0, 'intval' );
		$aUid = $this->rUid ? $this->rUid : $aUid;
		$aPage = I ( 'get.p', 1, 'intval' );
		
		if ($aUid == is_login () && is_login ()) {
			$this->redirect ( 'Weiquan/Index/Index' );
		}
		
		$weiquanModel = D ( 'Weiquan' );
		// 查询条件
		$param = array ();
		if ($aUid) {
			$param ['where'] ['uid'] = $aUid;
		}
		$param ['where'] ['status'] = 1;
		$param ['where'] ['is_top'] = 0;
		if ($aPage == 1) {
			$param ['limit'] = 10;
		} else {
			$param ['page'] = $aPage;
			$param ['count'] = 30;
		}
		
		// 查询
		list($output ['list'],$output ['total_count']) = $weiquanModel->getWeiquanList ( $param );
		//$output ['total_count'] = $weiquanModel->getWeiquanCount ( $param ['where'] );
		//echo $weiquanModel->getLastSql();die;
		$this->assign('isMore',$output ['total_count']>10?true:false);
		// 获取置顶微博
		$output ['top_list'] = $weiquanModel->getWeiquanList ( array (
				'where' => array (
						'status' => 1,
						'is_top' => 1,
						'uid' => $aUid 
				) 
		) );
		
		//$output ['user_info'] = get_user_info ( $aUid );
		
		$output ['loadMoreUrl'] = U ( 'loadweiquan', array (
				'uid' => $aUid,
				'su' => 0 
		) );
		$output ['title'] = $output ['user_info'] ['nickname'] . '的动态';
		$this->setTitle ( '{$title}' . L ( '_LINE_LINE_' ) . L ( '_MODULE_' ) );
		$this->assignSelf ();
		if (is_login ()){// && check_auth ( 'Weiquan/Index/doSend' )) {
			$output ['show_post'] = true;
		}
		M('UserStatistic')->where(array('uid'=>$aUid))->setInc('views');//增加统计表
		M('Member')->where(array('uid'=>aUid))->save(array('update_time'=>time()));//更新增量
		$output ['page'] = $aPage;
		$output ['tab'] = 'index';
		$output ['uid'] = $aUid;
		$output ['banner_nav'] ['index'] = 'index';
		$output ['user_info'] = get_user_info ( $aUid, TRUE );
		$output ['user_info'] ['show_field'] = get_user_field_show ( $aUid );
		$output['remark']=query_user(array('weiquanRemark'),$aUid,is_login ())['weiquanRemark'];
		$output ['user_info'] ['tag_link']=query_user(array('username','homepage','promote_link'),$aUid);
		$this->assign('followFlag',R('Ucenter/index/getFollowFlag',array(is_login(),$aUid)));
		$this->assign ( $output );
		$this->display ( 'wei_uc' );
	}
	private function filterWeiquan($aType, $param) {
		if ($aType == 'related') {
			$param ['where'] ['uid'] = is_login ();
		}
		
		return $param;
	}
	
	/**
	 * loadweibo 滚动载入
	 */
	public function loadweiquan() {
		$expect_type = array (
				'hot',
				'fav' 
		);
		$aType = I ( 'get.type', '', 'text' );
		$aPage = I ( 'get.p', 1, 'intval' );
		$aLastId = I ( 'get.lastId', 0, 'intval' );
		$aLoadCount = I ( 'get.loadCount', 0, 'intval' );
		$aUid = I ( 'get.uid', 0, 'intval' );
		$show_user = I ( 'get.su', 0, 'intval' );
		$param = array ();
		$param ['where'] = array (
				'status' => 1,
				'is_top' => 0 
		);
		if ($aUid) {
			$param ['where'] ['uid'] = $aUid;
		}
		$param = $this->filterWeiquan ( $aType, $param );
		if ($aPage == 1) {
			if (! in_array ( $aType, $expect_type )) {
				$param ['limit'] = 10;
				$param ['where'] ['id'] = array (
						'lt',
						$aLastId 
				);
			} else {
				$param ['page'] = $aLoadCount;
				$param ['count'] = 10;
			}
		} else {
			$param ['page'] = $aPage;
			$param ['count'] = 30;
		}
		 $weiquanModel = D ( 'Weiquan' );
		list($list,$total) = $weiquanModel->getWeiquanList ( $param,$show_user );
		if($aPage==1&&$aLoadCount>1){
			$nowCount=count($list);
			if($nowCount<10||($nowCount=10&&$total==20)){
				$this->assign ('not_more',1);
			}
		}
		//$weiquanModel = D ( 'Weiquan' );
		//$list = $weiquanModel->getWeiquanList ( $param );
		$show_user = $show_user ? 1 : 0;
		$this->assign ( 'show_user', $show_user );
		$this->assign ( 'list', $list );
		$this->assign ( 'lastId', end ( $list ) );
		$this->display ();
		
	}
	
	/**
	 * doSend 发布微博
	 */
	public function doSend() {
		$aContent = I ( 'post.content', '', 'op_t' );
		$aType = I ( 'post.type', 'feed', 'op_t' );
		$aAttachIds = I ( 'post.attach_ids', '', 'op_t' );
		$aExtra = I ( 'post.extra', array (), 'convert_url_query' );
		$types = D ( 'Weiquan' )->getType ();
		if (! in_array ( $aType, $types )) {
			$class_str = 'Addons\\Insert' . ucfirst ( $aType ) . '\\Insert' . ucfirst ( $aType ) . 'Addon';
			$class_exists = class_exists ( $class_str );
			if (! $class_exists) {
				$this->error ( L ( '_ERROR_CANNOT_SEND_THIS_' ) );
			} else {
				$class = new $class_str ();
				if (method_exists ( $class, 'parseExtra' )) {
					$res = $class->parseExtra ( $aExtra );
					if (! $res) {
						$this->error ( $class->error );
					}
				}
			}
		}
		$this->checkIsLogin (); // 判断登录
		                       // $this->checkAuth(null, -1, L('_INFO_AUTHORITY_LACK_'));//权限判断
		/*$return = check_action_limit ( 'add_weiquan', 'weiquan', 0, is_login (), true );
		if ($return && ! $return ['state']) {
			$this->error ( $return ['info'] );
		}
		*/
		$feed_data = array ();
		if (! empty ( $aAttachIds )) {
			$feed_data ['attach_ids'] = $aAttachIds;
		} else {
			if ($aType == 'image') {
				$this->error ( L ( '_ERROR_AT_LEAST_ONE_' ) );
			}
		}
		if (! empty ( $aExtra ))
			$feed_data = array_merge ( $feed_data, $aExtra );
			
			// 执行发布，写入数据库
		$weiquan_id = send_weiquan ( $aContent, $aType, $feed_data,'', $aAttachIds);
		if (! $weiquan_id) {
			$this->error ( L ( '_FAIL_PUBLISH_' ) );
		}
		$result ['html'] = R ( 'WeiquanDetail/weiquanContentHtml', array (
				'weiquan_id' => $weiquan_id 
		), 'Widget' );
		
		$result ['status'] = 1;
		$result ['info'] = L ( '_SUCCESS_PUBLISH_' ) . L ( '_EXCLAMATION_' ) . cookie ( 'score_tip' );
		// 返回成功结果
		$this->ajaxReturn ( $result );
	}
	
	/**
	 * sendrepost 发布转发页面
	 */
	public function sendrepost() {
		$aSourceId = I ( 'get.sourceId', 0, 'intval' );
		$aWeiboId = I ( 'get.weiboId', 0, 'intval' );
		
		$weiboModel = D ( 'Weiquan' );
		$result = $weiboModel->getWeiquanDetail ( $aSourceId );
		
		$this->assign ( 'sourceWeibo', $result );
		$weiboContent = '';
		if ($aSourceId != $aWeiboId) {
			$weibo1 = $weiboModel->getWeiquanDetail ( $aWeiboId );
			$weiboContent = '//@' . $weibo1 ['user'] ['nickname'] . ' ：' . $weibo1 ['content'];
		}
		$this->assign ( 'weiboId', $aWeiboId );
		$this->assign ( 'weiboContent', $weiboContent );
		$this->assign ( 'sourceId', $aSourceId );
		
		$this->display ();
	}
	
	/**
	 * doSendRepost 执行转发
	 */
	public function doSendRepost() {
		$this->checkIsLogin ();
		$aContent = I ( 'post.content', '', 'op_t' );
		
		$aType = I ( 'post.type', '', 'op_t' );
		
		$aSourceId = I ( 'post.sourceId', 0, 'intval' );
		
		$aWeiboId = I ( 'post.weiboId', 0, 'intval' );
		
		$aBeComment = I ( 'post.becomment', 'false', 'op_t' );
		
		//$this->checkAuth ( null, - 1, L ( '_INFO_AUTHORITY_TRANSMIT_LACK_' ) );
		/*
		$return = check_action_limit ( 'add_weiquan', 'weiquan', 0, is_login (), true );
		if ($return && ! $return ['state']) {
			$this->error ( $return ['info'] );
		}
		*/
		if (empty ( $aContent )) {
			$this->error ( L ( '_ERROR_CONTENT_CANNOT_EMPTY_' ) );
		}
		
		$weiboModel = D ( 'Weiquan' );
		$feed_data = '';
		$source = $weiboModel->getWeiquanDetail ( $aSourceId );
		$sourceweibo = $source ['weiquan'];
		$feed_data ['source'] = $sourceweibo;
		$feed_data ['sourceId'] = $aSourceId;
		// 发布微博
		$new_id = send_weiquan ( $aContent, $aType, $feed_data );
		
		if ($new_id) {
			D ( 'weiquan' )->where ( 'id=' . $aSourceId )->setInc ( 'repost_count' );
			$aWeiboId != $aSourceId && D ( 'weiquan' )->where ( 'id=' . $aWeiboId )->setInc ( 'repost_count' );
			S ( 'weiquan_' . $aWeiboId, null );
			S ( 'weiquan_' . $aSourceId, null );
		}
		// 发送消息
		$user = query_user ( array (
				'nickname' 
		), is_login () );
		$toUid = D ( 'weiquan' )->where ( array (
				'id' => $aWeiboId 
		) )->getField ( 'uid' );
		D ( 'Common/Message' )->sendMessage ( $toUid, L ( '_PROMPT_TRANSMIT_' ), $user ['nickname'] . L ( '_TIP_TRANSMITTED_' ) . L ( '_EXCLAMATION_' ), 'Weiquan/Index/weiquanDetail', array (
				'id' => $new_id 
		), is_login (), 1 );
		
		// 发布评论
		if ($aBeComment == 'true') {
			$commentData=send_comment ( $aWeiboId, $aContent );		
			$result ['comment_html'] = R ( 'Comment/comment_html', array (
			        'comment_id' => $commentData
			), 'Widget' );			
		}
		
		$result ['html'] = R ( 'WeiquanDetail/weiquan_html', array (
				'weiquan_id' => $new_id 
		), 'Widget' );
		// 返回成功结果
		
		$result ['status'] = 1;
		$result ['info'] = '转发成功！' . cookie ( 'score_tip' );
		$this->ajaxReturn ( $result );
	}
	
	/**
	 * doComment 发布评论
	 */
	public function doComment() {
		$this->checkIsLogin ();
		$aWeiboId = I ( 'post.weiquan_id', 0, 'intval' );
		$aContent = I ( 'post.content', 0, 'op_t' );
		$aCommentId = I ( 'post.comment_id', 0, 'intval' );
		
		// /$this->checkAuth(null, -1, L('_INFO_AUTHORITY_COMMENT_LACK_') . L('_PERIOD_'));
		/*$return = check_action_limit ( 'add_weiquan_comment', 'weiquan_comment', 0, is_login (), true );
		if ($return && ! $return ['state']) {
			$this->error ( $return ['info'] );
		}
		*/
		if (empty ( $aContent )) {
			$this->error ( L ( '_ERROR_CONTENT_CANNOT_EMPTY_' ) );
		}
		// 发送评论
		$result ['data'] = send_comment ( $aWeiboId, $aContent, $aCommentId );
		
		$result ['html'] = R ( 'Comment/comment_html', array (
				'comment_id' => $result ['data'] 
		), 'Widget' );
		
		$result ['status'] = 1;
		$result ['info'] = L ( '_SUCCESS_COMMENT_' ) . L ( '_EXCLAMATION_' ) . cookie ( 'score_tip' );
		// 返回成功结果
		$this->ajaxReturn ( $result );
	}
	
	/**
	 * checkIsLogin 判断是否登录
	 */
	private function checkIsLogin() {
		if (! is_login ()) {
			$this->error ( L ( '_ERROR_PLEASE_FIRST_LOGIN_' ) );
		}
	}
	
	/**
	 * commentlist 评论列表
	 */
	public function commentlist() {
		$aWeiboId = I ( 'post.weiquan_id', 0, 'intval' );
		$aPage = I ( 'post.page', 1, 'intval' );
		$aShowMore = I ( 'post.show_more', 0, 'intval' );
		$list = D ( 'WeiquanComment' )->getCommentList ( $aWeiboId, $aPage, $aShowMore );
		$this->assign ( 'list', $list );
		$this->assign ( 'page', $aPage );
		$this->assign ( 'weiboId', $aWeiboId );
		$weobo = D ( 'Weiquan' )->getWeiquanDetail ( $aWeiboId );
		$this->assign ( 'weiboCommentTotalCount', $weobo ['comment_count'] );
		$this->assign ( 'show_more', $aShowMore );
		$html = $this->fetch ( 'commentlist' );
		$this->ajaxReturn ( $html );
	}
	
	/**
	 * doDelComment 删除评论
	 */
	public function doDelComment() {
		$aCommentId = I ( 'post.comment_id', 0, 'intval' );
		$this->checkIsLogin ();
		$comment = D ( 'Weiquan/WeiquanComment' )->getComment ( $aCommentId );
		$this->checkAuth ( null, $comment ['uid'], L ( '_INFO_AUTHORITY_COMMENT_DELETE_LACK_' ) . L ( '_PERIOD_' ) );
		
		// 删除评论
		$result = D ( 'Weiquan/WeiquanComment' )->deleteComment ( $aCommentId );
		action_log ( 'del_weiquan_comment', 'weiquan_comment', $aCommentId, is_login () );
		if ($result) {
			$return ['status'] = 1;
			$return ['info'] = L ( '_SUCCESS_DELETE_' );
		} else {
			$return ['status'] = 0;
			$return ['info'] = L ( '_FAIL_DELETE_' );
		}
		// 返回成功信息
		$this->ajaxReturn ( $return );
	}
	
	/**
	 * doDelWeibo 删除微博
	 */
	public function doDelWeiquan() {
		$aWeiboId = I ( 'post.weiquan_id', 0, 'intval' );
		$weiboModel = D ( 'Weiquan' );
		
		$weibo = $weiboModel->getWeiquanDetail ( $aWeiboId );
		
		$this->checkAuth ( null, $weibo ['uid'], L ( '_INFO_AUTHORITY_COMMENT_DELETE_LACK_' ) . L ( '_PERIOD_' ) );
		
		// 删除微博
		$result = $weiboModel->deleteWeiquan ( $aWeiboId );
		action_log ( 'del_weiquan', 'weiquan', $aWeiboId, is_login () );
		if (! $result) {
			$return ['status'] = 0;
			$return ['status'] = L ( '_ERROR_INSET_DB_' );
		} else {
			$return ['status'] = 1;
			$return ['status'] = L ( '_SUCCESS_DELETE_' );
		}
		M('UserStatistic')->where(array('uid'=>$weibo ['uid']))->setDec('trends');//统计表
		// 返回成功信息
		$this->ajaxReturn ( $return );
	}
	
	/**
	 * setTop 置顶
	 */
	public function setTop() {
		$aWeiboId = I ( 'post.weiquan_id', 0, 'intval' );
		
		$this->checkAuth ( null, - 1, L ( '_INFO_FAIL_STICK_AUTHORITY_LACK_' ) . L ( '_PERIOD_' ) );
		$weiboModel = D ( 'Weiquan' );
		$weibo = $weiboModel->find ( $aWeiboId );
		if (! $weibo) {
			$this->error ( L ( '_INFO_FAIL_STICK_WEIBO_CANNOT_EXIST_' ) . L ( '_PERIOD_' ) );
		}
		if ($weibo ['is_top'] == 0) {
			if ($weiboModel->where ( array (
					'id' => $aWeiboId 
			) )->setField ( 'is_top', 1 )) {
				action_log ( 'set_weiquan_top', 'weiquan', $aWeiboId, is_login () );
				S ( 'weiquan_' . $aWeiboId, null );
				$this->success ( L ( '_SUCCESS_STICK_' ) . L ( '_PERIOD_' ) );
			} else {
				$this->error ( L ( '_FAIL_STICK_' ) . L ( '_PERIOD_' ) );
			}
		} else {
			if ($weiboModel->where ( array (
					'id' => $aWeiboId 
			) )->setField ( 'is_top', 0 )) {
				action_log ( 'set_weiquan_down', 'weiquan', $aWeiboId, is_login () );
				S ( 'weiquan_' . $aWeiboId, null );
				$this->success ( L ( '_SUCCESS_STICK_CANCEL_' ) . L ( '_PERIOD_' ) );
			} else {
				$this->error ( L ( '_FAIL_STICK_CANCEL_' ) . L ( '_PERIOD_' ) );
			}
		}
	}
	
	/**
	 * assignSelf 输出当前登录用户信息
	 */
	private function assignSelf() {
		$self = query_user ( array (
				'title',
				'avatar128',
				'nickname',
				'uid',
				'space_url',
				'score',
				'title',
				'fans',
				'following',
				'weiquancount',
				'rank_link' 
		) );
		// 获取用户封面id
		$map = getUserConfigMap ( 'user_cover' );
		$map ['role_id'] = 0;
		$model = D ( 'Ucenter/UserConfig' );
		$cover = $model->findData ( $map );
		$self ['cover_id'] = $cover ['value'];
		$self ['cover_path'] = getThumbImageById ( $cover ['value'], 273, 80 );
		
		$this->assign ( 'self', $self );
	}
	
	/**
	 * commentlist 点赞列表
	 */
	public function likeList() {
		$aWeiboId = I ( 'post.weiquan_id', 0, 'intval' );
		$aPage = I ( 'post.page', 1, 'intval' );
		$list = array ();
		$list = D ( 'WeiquanLike' )->getLikeList ( $aWeiboId, $aPage, 36 );
		foreach ( $ids as $v ) {
			$list [] = D ( 'WeiquanLike' )->getLikeById ( $v );
		}
		
		$this->assign ( 'list', $list );
		$this->assign ( 'page', $aPage );
		$this->assign ( 'weiquanId', $aWeiboId );
		$weiquan = D ( 'Weiquan' )->getWeiquanDetail ( $aWeiboId );
		$this->assign ( 'weiquanLikeTotalCount', $weiquan ['like_count'] );
		$html = $this->fetch ( 'likelist' );
		$this->ajaxReturn ( $html );
	}
	
	// /**
	// * 点赞详情
	// * @param type $wid
	// */
	// public function likeDetail($wid) {
	// $weibo = D('Weiquan')->getWeiquanDetail($wid);
	// if ($weibo === null) {
	// $this->error(L('_INEXISTENT_404_'));
	// }
	// $weibo['user'] = query_user(array('space_url', 'avatar128', 'nickname', 'title'), $weibo['uid']);
	// //显示页面
	//
	// $this->assign('weibo', $weibo);
	// $this->userInfo($weibo['uid']);
	//
	// //$supported = D('Weiquan')->getSupportedPeople($weibo['id'], array('nickname', 'space_url', 'avatar128', 'space_link'), 12);
	//
	//
	// $this->setTitle('{$weibo.content|op_t}' . L('_LINE_LINE_') . L('_WEIQUAN_DETAIL_'));
	// $this->assign('tab', 'index');
	// $this->display();
	// }
	
	/**
	 * weiboDetail 微博详情页
	 * 
	 * @param
	 *        	$id
	 *        	
	 */
	public function weiquanDetail($id, $type = 'like') {
		// 读取微博详情
		$weibo = D ( 'Weiquan' )->getWeiquanDetail ( $id );
		if ($weibo === null) {
			$this->error ( L ( '_INEXISTENT_404_' ) );
		}
		$weibo ['user'] = query_user ( array (
				'space_url',
				'avatar128',
				'nickname',
				'title' 
		), $weibo ['uid'] );
		$user_info = get_user_info ( $weibo ['uid'] );
		
		// $supported = D('Weiquan')->getSupportedPeople($weibo['id'], array('nickname', 'space_url', 'avatar128', 'space_link'), 12);
		// $this->assign('supported', $supported);
		$is_like = D ( 'Weiquan/WeiquanLike' )->getLikeByWidAndUid ( $id, is_login () );
		$follow = get_follow ( $weibo ['uid'] );
		
		$this->setTitle ( '{$weibo.content|op_t}' . L ( '_LINE_LINE_' ) . L ( '_WEIQUAN_DETAIL_' ) );
		
		$this->assign ( $follow );
		$this->assign ( 'weibo', $weibo );
		$this->assign ( 'user_info', $user_info );
		$this->assign ( 'is_like', $is_like );
		$this->assign ( 'is_detail', 1 );
		$this->assign ( 'tab', 'index' );
		$this->assign ( 'type', $type );
		$this->display ();
	}
	public function loadComment() {
		$aWeiboId = I ( 'post.weiquan_id', 0, 'intval' );
		$return ['html'] = R ( 'Comment/someCommentHtml', array (
				'weiquan_id' => $aWeiboId 
		), 'Widget' );
		$return ['status'] = 1;
		// 返回成功信息
		$this->ajaxReturn ( $return );
	}
	public function search() {
		$aKeywords = $this->parseSearchKey ( 'keywords' );
		$aKeywords = text ( $aKeywords );
		$aPage = I ( 'get.page', 1, 'intval' );
		$r = 30;
		$param ['where'] ['content'] = array (
				'like',
				"%{$aKeywords}%" 
		);
		$param ['where'] ['status'] = 1;
		$param ['order'] = 'create_time desc';
		$param ['page'] = $aPage;
		$param ['count'] = $r;
		// 查询
		$list = D ( 'Weiquan' )->getWeiquanList ( $param );
		$totalCount = D ( 'Weiquan' )->where ( $param ['where'] )->count ();
		$this->assign ( 'totalCount', $totalCount );
		$this->assign ( 'r', $r );
		$this->assign ( 'list', $list );
		$this->assign ( 'search_keywords', $aKeywords );
		$this->assignSelf ();
		$this->display ();
	}
	
	/**
	 * 点赞
	 */
	public function doLike($is_detail = 0) {
		$this->checkIsLogin ();
		$aWeiquanId = I ( 'post.weiquan_id', 0, 'intval' );
		$like = D ( 'Weiquan/WeiquanLike' )->getLikeByWidAndUid ( $aWeiquanId, is_login () );
		if ($like) {
			$result ['status'] = 0;
			$result ['info'] = L ( '_ERROR_REPEAT_POINT_LIKE_' );
		} else {
			// 发送点赞
			$result ['data'] = D ( 'Weiquan/WeiquanLike' )->addLike ( is_login (), $aWeiquanId );
			if ($result ['data']) {
				$aJump = U ( 'Weiquan/Index/weiquanDetail', array (
						'id' => $aWeiquanId 
				) );
				$aWeiquan = D ( 'Weiquan/Weiquan' )->getWeiquanDetail ( $aWeiquanId );
				$user = query_user ( array (
						'uid',
						'nickname',
						'avatar64',
						'space_url',
						'rank_link',
						'title' 
				), is_login () );
				D ( 'Common/Message' )->sendMessage ( $aWeiquan ['uid'], $title = $user ['nickname'] . L ( '_LIKE_YOU_' ), $user ['nickname'] . L ( '_LIKE_YOU_ADD_' ), $aJump, array (
						'id' => $aWeiquanId 
				) );
				$result ['status'] = 1;
				$result ['info'] = L ( '_SUCCESS_LIKE_' );
				if ($is_detail) {
					$result ['html'] = R ( 'Weiquan/Like/someLike', array (
							'weiquan_id' => $aWeiquanId,
							'count' => 36,
							'return' => TRUE 
					), 'Widget' );
				} else {
					$result ['html'] = R ( 'Weiquan/Like/likeUser', array (
							'weiquan_id' => $aWeiquanId,
							'return' => TRUE 
					), 'Widget' );
				}
				
				$result ['lng_cancel'] = L ( '_CANCEL_' );
				$result ['count'] = $aWeiquan ['like_count'] < 1 ? '' : $aWeiquan ['like_count'];
			} else {
				$result ['status'] = 0;
				$result ['info'] = L ( '_ERROR_LIKE_' );
			}
		}
		// 返回成功结果
		$this->ajaxReturn ( $result );
	}
	public function doDelLike($is_detail = 0) {
		$this->checkIsLogin ();
		
		$aWeiquanId = I ( 'post.weiquan_id', 0, 'intval' );
		// 发送取消点赞
		
		$result = D ( 'Weiquan/WeiquanLike' )->deleteLikeByWidAndUid ( $aWeiquanId, is_login () );
		
		$out = [ ];
		if ($result) {
			$aWeiquan = D ( 'Weiquan/Weiquan' )->getWeiquanDetail ( $aWeiquanId );
			$out ['status'] = 1;
			$out ['info'] = L ( '_SUCCESS_LIKE_CANCEL_' );
			if ($is_detail) {
				$out ['html'] = R ( 'Weiquan/Like/someLike', array (
						'weiquan_id' => $aWeiquanId,
						'count' => 36,
						'return' => TRUE 
				), 'Widget' );
			} else {
				$out ['html'] = R ( 'Weiquan/Like/likeUser', array (
						'weiquan_id' => $aWeiquanId,
						'return' => TRUE 
				), 'Widget' );
			}
			$out ['lng_like'] = L ( '_LIKE_' );
			$out ['count'] = $aWeiquan ['like_count'] < 1 ? '' : $aWeiquan ['like_count'];
		} else {
			$out ['status'] = 0;
			$out ['info'] = L ( '_ERROR_LIKE_CANCEL_' );
		}
		// 返回成功结果
		$this->ajaxReturn ( $out );
	}
	protected function parseSearchKey($key = null) {
		$action = MODULE_NAME . '_' . CONTROLLER_NAME . '_' . ACTION_NAME;
		$post = I ( 'post.' );
		if (empty ( $post )) {
			$keywords = cookie ( $action );
		} else {
			$keywords = $post;
			cookie ( $action, $post );
			$_GET ['page'] = 1;
		}
		
		if (! $_GET ['page']) {
			cookie ( $action, null );
			$keywords = null;
		}
		return $key ? $keywords [$key] : $keywords;
	}
	/**
	 * 用户修改封面
	 *
	 */
	public function changeCover()
	{
	    if (!is_login()) {
	        $this->error(L('_ERROR_NEED_LOGIN_').L('_EXCLAMATION_'));
	    }
	    if (IS_POST) {
	        $aCoverId = I('post.cover_id', 0, 'intval');
	        $result['status'] = 0;
	        if ($aCoverId <= 0) {
	            $result['info'] = L('_ERROR_ILLEGAL_OPERATE_').L('_EXCLAMATION_');
	            $this->ajaxReturn($result);
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
	                $this->ajaxReturn($result);
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
	        $this->ajaxReturn($result);
	    } else {
	        //获取用户封面id
	        $map = getUserConfigMap('user_cover');
	        $map['role_id'] = 0;
	        $model = D('Ucenter/UserConfig');
	        $cover = $model->findData($map);
	        $my_cover['cover_id'] = $cover['value'];
	        $my_cover['cover_path'] = getThumbImageById($cover['value'], 348, 70);
	        $this->assign('my_cover', $my_cover);
	        $this->display('_change_cover');
	    }
	}
	public function quickPost(){
	    if(!is_login()){
	        $this->error('请登录');
	    }
	    $this->display();
	}
	public function addRemark(){
	    $data=array();
	    $uid=intval(I('post.uid'));
	    $remark=op_t(I('post.remark'));
	    if(!$remark){
	        $data['status']=0;
	        $data['info']='备注不能为空';
	    }
	    else if(!D('Fans')->isFollow(is_login(), $uid))//检查是不是关注的
	    {
	        $data['status']=0;
	        $data['info']='参数错误';
	    }
	   else if(is_int(D('Follow')->addRemark(is_login(), $uid,$remark))){
	        $data['status']=1;
	        $data['info']='备注设置成功';
	    }
	    else{
	        $data['status']=0;
	        $data['info']='系统繁忙';
	    }
	    $this->ajaxReturn($data);
	}
}
