<?php
/**
 * 用户接口
 */

namespace App\Controller;

use Think\Controller;

require_once(APP_PATH . '/User/Conf/config.php');
require_once(APP_PATH. '/User/Common/common.php');

class MemberController extends BaseController {
    
    /**
     * 首页
     */
    public function home(){
        // 轮播广告 TODO 缓存
        $advs = D('Advs')->getAdv(9);
        
        // i.cn说
        $isay_list = D('Isay/Isay')->getIsayHot(4);
        
        // 近期热搜
        //$param['where'] = 
        $param['order'] = 'rand()';
        $param['limit'] = '20';
        $param['field'] = 'id,title';
        $hot_list = D('HotWords')->getList($param);
        
        $this->result['data'] = array('isay_list' => $isay_list, 'hot_search' => $hot_list, 'advs' => $advs);
    }
    
    /**
     * 微信信息
     */
    public function syncWeixin(){
        $http_request_mode = 'post';
        
        // 获取参数
        $post = I ( $http_request_mode.'.', '', 'op_t' );
        
        //$post = json_decode('{"access_token":"OezXcEiiBSKSxW0eoylIeKTy9h5RtaeufoHXuJ5w8_LUW5swkgyB0p4rVlk9YUXru7qQtQXVl4HvLQhprSsmb8URA1gRgASMBcHvvqMvSJlXWFO5C8OmZhENiWyksavtNloOWoR_9VMvszFMHdFj2Q","expires_in":7200,"refresh_token":"OezXcEiiBSKSxW0eoylIeKTy9h5RtaeufoHXuJ5w8_LUW5swkgyB0p4rVlk9YUXrYiCd1ifj4nWJQnk3cvkB5fwHDNJdVSS79sFL2EjfUVJXxDote7HqOL6Q1jqeZWQidC7Q4vNRGnjnZwdNP2Gu_g","openid":"oR6EgwTar_T5dUKgSOmpJUTZQpH8","scope":"snsapi_login","unionid":"oQSE_wUAsSjkK8SA-CYl3Uim4srE"}', true);
        //$post['nickname'] = '爱微商';
        //$post['sex'] = 1;
        
        if(!$post['openid'] || !$post['nickname'] || !isset($post['sex']) || !$post['headimgurl'] || !$post['access_token'] ){
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '参数错误', 'data' => (object)array() );
			exit ();
        }
        
        //$data['uid'] = $post['uid'];
        $data['type_uid'] = $post['openid'];
        $data['oauth_token'] = $post['access_token'];
        $data['oauth_token_secret'] = $post['openid'];
        $data['type'] = 'weixin';

        $session = array('TOKEN' => $data['oauth_token'], 'TYPE' => $data['type'], 'OPENID' => $data['type_uid'], 'ACCESS_TOKEN' => $data['oauth_token']);
        session('SYNCLOGIN', $session);
        
        $aWeixin = A("Addons://SyncLogin/Base");
        
        $user_info['openid'] = $post['openid'];
        $user_info['type'] = 'WEIXIN';
        $user_info['name'] = $post['nickname'];
        $user_info['nick'] = $post['nickname'];
        $user_info['sex'] = $post['sex'];
        $user_info['head'] = $post['headimgurl'];
        $user_info['province'] = $post['province'];
        $user_info['city'] = $post['city'];
        
        $map = array('type_uid' => $data['type_uid'], 'type' => $data['type']);
        if ($info = D('sync_login')->field('uid')->where($map)->find()) {
            $user = UCenterMember()->where(array('id' => $info['uid']))->find();
            /* if (empty($user)) {
                D('sync_login')->where($map)->delete();
                $uid = $aWeixin->addData($user_info); // 微信已存在，未绑定
                $res = 2;
            } elseif( $user && !$user['mobile']){
                $uid = $info ['uid'];
                $res = 2;
            } else {
                $uid = $info ['uid']; // 微信号已绑定，提示登录
                $res = 3;
                $this->result['data'] = array_merge(array('res' => $res), $user); // 直接返回用户详细信息
                exit;
            } */
            if (empty($user)) {
                D('sync_login')->where($map)->delete();
                $uid = $aWeixin->addData($user_info); // 微信已同步，没有用户信息，重新写入
                $res = 1;
            }elseif(!$user['username']){
                $uid = $info ['uid'];
                $res = 1; // 微信已同步，未注册完成
            }else{
                $uid = $info ['uid']; // 微信号已绑定，提示登录
                $res = 2;
                $user = query_user(array('uid', 'username', 'nickname', 'sex', 'catid', 'level', 'fans', 'mobile', 'avatar', 'ipai', 'huoyuan', 'catid', 'tags', 'signature'), $info['uid']);
                $this->result['data'] = array_merge(array('res' => $res), $user); // 直接返回用户详细信息
                exit;
            }
        } else {
            $uid = $aWeixin->addData($user_info); // 创建新用户
            $res = 1;
        }
        if(!$uid){
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '创建用户失败', 'data' => (object)array() );
			exit ();
        }
        $this->result['data'] = array('uid' => $uid, 'res' => $res);
    }
	
	/**
	 * 注册
	 */
	public function register() {

/* 	    $aUsername = I('post.username');
	    $aNickname = I('post.nickname');
	    $aPassword = I('post.password');
	    
	    // 行为限制
	    $return = check_action_limit('reg', 'ucenter_member', 1, 1, true);
	    if ($return && !$return['state']) {
	        $this->error($return['info'], $return['url']);
	    }
	    
	    
	    $ucenterModel = UCenterMember();
	    $uid = $ucenterModel->register($aUsername, $aNickname, $aPassword);
	    if (0 < $uid) { //注册成功
	        $this->addSyncLoginData($uid);
	    
	        $config =  D('addons')->where(array('name'=>'SyncLogin'))->find();
	        $config   =   json_decode($config['config'], true);
	    
	        $this->initRoleUser($config['role'], $uid); //初始化角色用户
	    
	        $uid = $ucenterModel->login($aUsername, $aPassword, 1); //通过账号密码取到uid
	        $this->doLogin($uid);
	        $this->success('绑定成功！', session('login_http_referer'));
	    } else { //注册失败，显示错误信息
	        $this->error(A('Ucenter/Member')->showRegError($uid));
	    } */

	    // 注册开关判断
	    if (! modC ( 'REG_SWITCH', '', 'USERCONFIG' )) {
	        $this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '注册已关闭', 'data' => (object)array() );
	        exit ();
	    }
	    
	    // 注册行为限制判断
	    $return = check_action_limit ( 'reg', 'ucenter_member', 1, 1, true );
	    if ($return && ! $return ['state']) {
	        $this->result = array ( 'code' => self::ERROR_CODE, 'msg' => $return ['info'], 'data' => (object)array() );
	        exit ();
	    }
	    
		$http_request_mode = 'post';
		
		// 获取参数
		$post = I ( $http_request_mode.'.', '', 'op_t' );
		$reg_type = $post['reg_type'] == 2 ? 2 : 1; //默认1手机注册
        $post['username'] = $post['account'];
		
    	$ucenterMemberModel = D('User/UcenterMember');
		
		if($reg_type == 2){
		    // 微信注册
    		
    		$res = $ucenterMemberModel->registerMutil($post, $reg_type);
    		if (!$res){
    		    $this->result = array('code'=>self::ERROR_CODE, 'msg'=>$ucenterMemberModel->getError(),  'data' => (object)array());
    		    exit;
    		}
		    
		}else{
		    //手机注册
    		
    		// 短信验证码 // TODO 打开
    		$verify_code = $post['verify_code'];
    		if ( !D('Verify')->checkVerify($post['mobile'], 'mobile', $verify_code, 0)) {
    		    $this->result = array('code'=>self::ERROR_CODE, 'msg'=>'验证码错误或已过期',  'data' => (object)array());
    		    exit;
    		}
    		
    		$res = $ucenterMemberModel->registerMutil($post, $reg_type);
    		if (!$res){
    		    $this->result = array('code'=>self::ERROR_CODE, 'msg'=>$ucenterMemberModel->getError(),  'data' => (object)array());
    		    exit;
    		}
		    
		}

		$config =  D('addons')->where(array('name'=>'SyncLogin'))->find();
		$config   =   json_decode($config['config'], true);
        $ucenterMemberModel->initRoleUser($config['role'], $res); //初始化角色用户
        
        $this->result['data'] = query_user(array('uid', 'nickname', 'sex', 'catid', 'level', 'fans', 'mobile', 'avatar', 'ipai', 'huoyuan', 'catid', 'tags', 'signature'), $res);
    
        //$uid = UCenterMember()->login($post['username'], $post['passwprd'], 1); //通过账号密码取到uid
        //$this->doLogin($uid);
		
		/* 检测验证码 */
		/* if (check_verify_open('reg')) {
			if (! check_verify ( $post ['verify'] )) {
				$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '验证码错误', 'data' => (object)array() );
				exit ();
			}
		}
		
		if (modC('MOBILE_VERIFY_TYPE', 0, 'USERCONFIG') == 0 && ! D ( 'Verify' )->checkVerify ( $post['account'], 'mobile', $post ['verify_code'], 0 )) {
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '短信验证码错误', 'data' => (object)array() );
			exit ();
		} */
	}
	
	/**
	 * 登录
	 *
	 * @return number|Ambigous <boolean, NULL>|unknown
	 */
	public function login() {
		$http_request_mode = 'post';
		
		$account = I($http_request_mode.'.account', '', 'op_t');
		$password = I($http_request_mode.'.password', '', 'op_t');

		if(!$account || !$password ){
		    $this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '参数错误', 'data' => (object)array() );
		    exit ();
		}
		
		if(check_mobile($account)){
		    $map['mobile'] = $account;
		}else{
		    $map['username'] = $account;
		}

		$userMode = D('User/UcenterMember');
		
		// TODO 缓存
		$user = $userMode->where ( $map )->find ();
		
		$return = check_action_limit ( 'input_password', 'ucenter_member', $user ['id'], $user ['id'] );
		if ($return && ! $return ['state']) {
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => $return ['info'], 'data' => (object)array() );
			exit;
		}
		
		if (is_array ( $user ) && $user ['status']) {
			/* 验证用户密码 */
			if (think_ucenter_md5 ( $password, UC_AUTH_KEY ) === $user ['password']) {
				$userMode->updateLogin ( $user ['id'] ); // 更新用户登录信息
			} else {
				action_log ( 'input_password', 'ucenter_member', $user ['id'], $user ['id'] );
				$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '帐号或密码错误', 'data' => (object)array() );
				exit;
			}
		} else {
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '用户不存在或被禁用', 'data' => (object)array() );
			exit;
		}
		
		$info = query_user(array('uid', 'nickname', 'sex', 'catid', 'level', 'fans', 'mobile', 'avatar', 'ipai', 'huoyuan', 'catid', 'tags', 'signature'), $user['id']);
		
		$this->result['data'] = $info;
		exit ();
	}
	
	/**
	 * 绑定手机号
	 */
	public function bind(){
	    $http_request_mode = 'post';
	    $data = I ( $http_request_mode.'.', '', 'op_t' );

	    //手机注册
	    
	    // 短信验证码 // TODO 打开
	    $verify_code = $data['verify_code'];
	    if ( !D('Verify')->checkVerify($data['mobile'], 'mobile', $verify_code, 0)) {
	        $this->result = array('code'=>self::ERROR_CODE, 'msg'=>'验证码错误或已过期',  'data' => (object)array());
	        exit;
	    }

	    $ucenterMemberModel = D('User/UcenterMember');
	    $res = $ucenterMemberModel->bindMobile($data['uid'], $data);
	    if (!$res){
	        $this->result = array('code'=>self::ERROR_CODE, 'msg'=>$ucenterMemberModel->getError(),  'data' => (object)array());
	        exit;
	    }
		
		$info = query_user(array('uid', 'nickname', 'sex', 'catid', 'level', 'fans', 'mobile', 'avatar', 'ipai', 'huoyuan', 'catid', 'tags', 'signature'), $data['uid']);
		
		$this->result['data'] = $info;
		exit ();
	}
	
	/**
	 * 获取用户信息(个人资料)
	 */
	public function info(){
		$http_request_mode = 'request';
		$uid = I($http_request_mode.'.uid', 0, 'intval');
		if(!$uid){
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '用户不存在', 'data' => (object)array() );
			exit;
		}

		$user = query_user(array('uid', 'nickname', 'weixin', 'catid', 'sex', 'mobile', 'status', 'level', 'qrcode', 'custom_domain', 'catid', 'avatar', 'pos_province', 'pos_city', 'pos_district', 'catid', 'signature', 'tags'), $uid );
		if ( !is_array ( $user ) || !$user ['status']) {
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '用户不存在或被禁用', 'data' => (object)array() );
			exit;
		}
		$user['pos_province'] && $user['pos_province'] = D('District')->getNameById($user['pos_province']);
		$user['pos_city'] && $user['pos_city'] = D('District')->getNameById($user['pos_city']);
		$user['pos_district'] && $user['pos_district'] = D('District')->getNameById($user['pos_district']);
		$user['expand_info'] = D('Common/Field')->getExpandFieldInfo($uid);
		$this->result['data'] = $user;
	}
	
	/**
	 * 基本资料(查看他人)
	 */
	public function detail(){
		$http_request_mode = 'request';
		$uid = I($http_request_mode.'.uid', 0, 'intval'); // uid
		$login_uid = I($http_request_mode.'.login_uid', 0, 'intval'); // uid
		if(!$uid){
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '用户不存在', 'data' => (object)array() );
			exit;
		}

		$user = query_user(array('uid', 'nickname', 'weixin', 'catid', 'sex', 'mobile', 'status', 'level', 'qrcode', 'custom_domain', 'catid', 'avatar', 'pos_province', 'pos_city', 'pos_district', 'catid', 'is_following', 'is_followed', 'signature', 'tags'), $uid, $login_uid );
		if ( !is_array ( $user ) || !$user ['status']) {
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '用户不存在或被禁用', 'data' => (object)array() );
			exit;
		}
		$user['pos_province'] && $user['pos_province'] = D('District')->getNameById($user['pos_province']);
		$user['pos_city'] && $user['pos_city'] = D('District')->getNameById($user['pos_city']);
		$user['pos_district'] && $user['pos_district'] = D('District')->getNameById($user['pos_district']);
		$user['expand_info'] = D('Common/Field')->getExpandFieldInfo($uid, $uid+1);
		$this->result['data'] = $user;
	}
	
	
	/**
	 * 微商列表
	 */
	public function lists(){
		$http_request_mode = 'request';
		$uid = I($http_request_mode.'.uid', 0, 'intval');
		$keyword = I($http_request_mode.'.keyword', '', 'op_t');
		$keyword_type = I($http_request_mode.'.keyword_type', 1, 'intval');  // 1标签 2微信名称
		$tagid = I($http_request_mode.'.tagid', 0, 'intval');
		$catid = I($http_request_mode.'.catid', 0, 'intval');
		$type = I($http_request_mode.'.type', 0, 'intval');
		$push = I($http_request_mode.'.push', 0, 'intval');
		$sex= I($http_request_mode.'.sex', 0, 'intval');
		$areaid = I($http_request_mode.'.areaid', 0, 'intval');
		$order = I($http_request_mode.'.order', '', 'op_t');
		$sort = I($http_request_mode.'.sort', '', 'op_t');
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');
        $page_size = I($http_request_mode.'.page_size', 10, 'intval'); //默认10条
        $order_arr = array(
                'level' => '等级',
                'fans' => '粉丝数量',
                'view' => '访问量',
                'distance' => '距离',
        );
        
        $sort_arr = array('asc', 'desc');
        
		$map = array();
		$map['where']['status'] = 1;
		$catid && $map['where']['catid'] = $catid;
		$tagid && $map['where']['tags'] = $tagid;
		$type && $map['where']['type'] = $type;
		$sex && $map['where']['sex'] = $sex;
		$areaid && $map['where']['pos_city'] = $areaid;
        //$push == 1 ? $map['where']['hot'] = 1 : ($push == 2 ? $map['where']['hot'] = 0 : '');
		//$keyword && $map['where']['nickname'] = array('like','%'.$keyword.'%');
		switch($keyword_type){
		    case 2:
		        $map['keyword'] = $keyword;
		        break;
		    default:
        		if($keyword !== ''){
        		    if($push == 2){ // 非主推
            		    $tags = D('Ucenter/UserTag')->getAllTags(array('keyword'=>$keyword, 'tag_type'=>2)); // 搜索副标签
            		    if($tags['ids']){
            		        $map['where']['tags'] = explode(',', $tags['ids']);
            		    }
        		    }else{ // 主推
            		    // $tags = D('Ucenter/UserTag')->getAllTags(array('keyword'=>$keyword));
            		    // 关键词主标签精确搜索
            		    $catinfo1 = D('Ucenter/UserCategory')->getByTitle($keyword, 1);
            		    $catinfo2 = D('Ucenter/UserCategory')->getByTitle($keyword, 2);
            		    if(!$catinfo1 && !$catinfo2){
            		        $map['where']['catid'] = -1;
            		    }else{
            		        $catinfo1 && $catids[] = $catinfo1['id'];
            		        $catinfo2 && $catids[] = $catinfo2['id'];
            		        $map['where']['catid'] = $catids;
            		    }
        		    }
        		}
		}
		
		$sort = $sort_arr[$sort] ? $sort_arr[$sort] : 'desc'; // 默认降序
		
		$map['page'] = $page_no;
		$map['limit'] = $page_size;
		
	    switch($order){
	        case 'level':
	            $map['order'] = 'score1 '. $sort;
	            break;
	        case 'fans':
	            $map['fans'] = 'fans '. $sort;
	            break;
	        case 'view':
	            $map['order'] = 'views '. $sort;
	            break;
	        default:
	            $map['order'] = 'score1 '. $sort;
	            break;
	    }

		$res = D('Common/Member')->getMemberList($map);
		$total = $res['total'];
		$list = array();
		if($res['list']){
    		$list = $res['list'];
    		foreach ($list as $k => &$v){
    		    $v = query_user(array('uid', 'nickname', 'sex', 'catid', 'level', 'fans', 'avatar', 'ipai', 'huoyuan', 'catid', 'tags', 'is_following', 'is_followed', 'signature'), $v['id'], $uid);
    		}
    		unset($v);
		}
		$this->result['data'] = $list;
	}
	
	/**
	 * 最新微商
	 */
	public function newest(){
		$http_request_mode = 'request';
		$uid = I($http_request_mode.'.uid', 0, 'intval');
		$tag_type = I($http_request_mode.'.tag_type', 0, 'intval');
		$type = I($http_request_mode.'.type', 0, 'intval');
		$sex= I($http_request_mode.'.sex', 0, 'intval');
		$areaid = I($http_request_mode.'.areaid', 0, 'intval');
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');
        $page_size = I($http_request_mode.'.page_size', 10, 'intval');
        
        $sort_arr = array('asc', 'desc');
        
		$map = array();
		//$map['field'] = 'uid';
		$map['page'] = $page_no;
		$map['limit'] = $page_size;
		$map['order'] = 'reg_time DESC';
		$map['where']['status'] = 1;
		$areaid && $map['where']['pos_city'] = $areaid;
		$sex && $map['where']['sex'] = $sex;
		
		$filter = array();
		
        $res = D('Common/Member')->getMemberList($map);
        $total = $res['total'];
        $list = $res['list'];
		foreach ($list as $k => &$v){
		    $v = query_user(array('uid', 'nickname', 'sex', 'catid', 'level', 'fans', 'avatar', 'ipai', 'huoyuan', 'catid', 'tags', 'is_following', 'is_followed', 'signature'), $v['id'], $uid);
		}
		unset($v);
		$this->result['data'] = array('filter' => $filter, 'list' => $list);
	}
	
	/**
	 * 修改用户资料
	 */
	public function edit(){
		$http_request_mode = 'post';
		
		$uid = I($http_request_mode.'.uid', 0, 'intval');
		$type = I($http_request_mode.'.type', 1, 'intval'); // 默认1，修改用户基本资料
		$field_name = I($http_request_mode.'.field_name', '', 'op_t');
		$field_value = I($http_request_mode.'.field_value', '', 'op_t');
		$visiable = I($http_request_mode.'.visiable', 0, 'intval'); // 仅对type=2有效
		
		$userinfo = query_user(array('uid'), $uid);
		if(!$userinfo){
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '用户不存在', 'data' => (object)array() );
			exit;
		}
		
		if($type == 2){
		    $res = D('Field')->saveField( $uid, $field_name, $field_value, $visiable);
		    if(!$res){
    			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '修改失败'.D('Field')->getError(), 'data' => (object)array() );
    			exit;
		    }
		}else{
		    $data['uid'] = $uid;
		    switch ($field_name){
		        case 'avatar': // 头像
		            $data['path'] = $field_value;
		            $res = D('Ucenter/Avatar')->saveAvatar($data);
        		    if(!$res){
            			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '修改失败'.D('Ucenter/Avatar')->getError(), 'data' => (object)array() );
            			exit;
        		    }
		            break;
		        case 'qrcode': // 二维码
		            $data = getUserConfigMap('qrcode', '', $uid);
		            $res = D('Ucenter/UserConfig')->saveValue($data, $field_value);
    		        if(!$res){
    		            $this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '修改失败'.D('Ucenter/UserConfig')->getError(), 'data' => (object)array() );
    		            exit;
    		        }
		            break;
		        case 'tags': // 副标签，逗号隔开
                    $user_tag = str_replace("，", ',', $field_value);
                    $tags = array();
                    $ids = array();
                    $tags = explode(',', $user_tag);
                    if($tags){
                        $tags = array_slice($tags, 0, 4); // 取数组前4个
                        foreach ($tags as $v) {
                            $ids[] = D('Ucenter/UserTag')->insertData(array('title' => trim($v), 'status' => 1));
                        }
                    }
                    $res = D('Ucenter/UserTagLink')->editData(implode(',', $ids), $uid);
    		        if(!$res){
    		            $this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '修改失败'.D('Ucenter/UserConfig')->getError(), 'data' => (object)array() );
    		            exit;
    		        }
		            break;
		        case 'category': // 主标签
		            $userinfo = query_user(array('catid'), $uid);

		            // 主关键词
		            $userCategoryModel = D('Ucenter/UserCategory');
		            $cat['type'] = $userinfo['type'] ? $userinfo['type'] : 1;
		            $cat['title'] = $field_value;
		            
		            $catid = $userCategoryModel->addCategory($cat);
		            if(!$catid){
    		            $this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '修改失败'.$userCategoryModel->getError(), 'data' => (object)array() );
    		            exit;
		            }
		            $data['catid'] = $catid;
    		        D('Member')->save($data);
    		        $field_name = 'catid';
		            break;
		        case 'type': // 主标签类型
		            $userinfo = query_user(array('catid'), $uid);
		            
		            if(!$userinfo['category']){
    		            $this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '请先设置主标签', 'data' => (object)array() );
    		            exit;
		            }
		            if($userinfo['type'] == $field_value){
		                return true;
		            }

		            // 主关键词
		            $userCategoryModel = D('Ucenter/UserCategory');
		            $cat['type'] = $field_value;
		            $cat['title'] = $userinfo['category'];
		            
		            $catid = $userCategoryModel->addCategory($cat);
		            if(!$catid){
    		            $this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '修改失败'.$userCategoryModel->getError(), 'data' => (object)array() );
    		            exit;
		            }
		            $data['catid'] = $catid;
    		        D('Member')->save($data);
    		        $field_name = 'catid';
		            break;
		        default:
    		        $data[$field_name] = $field_value;
    		        $data = D('Member')->create($data);
    		        if(!$data){
    		            $this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '修改失败'.D('Member')->getError(), 'data' => (object)array() );
    		            exit;
    		        }
    		        D('Member')->save($data);
    		        break;
		    }
		    clean_query_user_cache($uid, array($field_name));
		}
	}
	
	/**
	 * 修改密码
	 */
	public function changePassword(){
		$http_request_mode = 'post';
		
		$uid = I($http_request_mode.'.uid', 0, intval);
		$old_password = I($http_request_mode.'.password', '', 'op_t');
		$new_password = I($http_request_mode.'.new_password', '', 'op_t');
		
		if (!$uid) {
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '用户不存在', 'data' => (object)array() );
			exit;
		}
		
		if (!$old_password) {
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '请输入旧密码', 'data' => (object)array() );
			exit;
		}
		
		if (!$new_password) {
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '请输入新密码', 'data' => (object)array() );
			exit;
		}

		$userModel = D('User/UcenterMember');
		
		//检查旧密码是否正确
		if (!$userModel->verifyUser($uid, $old_password)) {
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '旧密码不正确', 'data' => (object)array() );
			exit;
		}
		//更新用户信息
		$data = array('id'=> $uid, 'password' => $new_password);
		$data = $userModel->create($data);
		if (!$data) {
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => $userModel->getError (), 'data' => (object)array() );
			exit;
		}
		$userModel->where(array('id' => $uid))->save($data);
		//返回成功信息
		clean_query_user_cache($uid, 'password');//删除缓存
		D('user_token')->where('uid=' . $uid)->delete();
	}
	
	/**
	 * 忘记密码/找回密码
	 */
	public function findPassword(){
		$http_request_mode = 'post';
		
		$mobile = I($http_request_mode.'.mobile', '', 'op_t');
		$password = I($http_request_mode.'.password', '', 'op_t');
		$verify_code = I($http_request_mode.'.verify_code', '', 'op_t');
		
		if (!$mobile) {
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '请输入手机号', 'data' => (object)array() );
			exit;
		}
		
		$userModel = D('User/UcenterMember');
		$uid = $userModel->getUidByMobile($mobile);
		
		if(!$uid){
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '手机号未注册', 'data' => (object)array() );
			exit;
		}
		
		if (!$password) {
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '请输入密码', 'data' => (object)array() );
			exit;
		}
		
		// 短信验证码
		if ( !D('Verify')->checkVerify($mobile, 'mobile', $verify_code, 0)) {
		    $this->result = array('code'=>self::ERROR_CODE, 'msg'=>'验证码错误或已过期',  'data' => (object)array());
		    exit;
		}
		
		//更新用户信息
		$data = array('password' => $password);
		$data = $userModel->create($data);
		if (!$data) {
			$this->result = array ( 'code' => self::ERROR_CODE, 'msg' => $userModel->getError (), 'data' => (object)array() );
			exit;
		}
		$userModel->where(array('id' => $uid))->save($data);
		//返回成功信息
		clean_query_user_cache($uid, 'password');//删除缓存
		D('user_token')->where('uid=' . $uid)->delete();
	}
	
	/**
	 * 粉丝列表
	 */
	public function fans() {
		$http_request_mode = 'request';
		$uid = I($http_request_mode.'.uid', 0, 'intval');
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');
        $page_size = I($http_request_mode.'.page_size', 10, 'intval'); //默认10条

        $map = array();
        //$map['page'] = $page_no;
        //$map['limit'] = $page_size;
		$res = D('Fans')->getFans($uid, $map);
		
		if($res['list']){
		    foreach ($res['list'] as $k => &$v){
		        $userinfo = query_user(array('uid', 'nickname', 'sex', 'catid', 'level', 'fans', 'avatar', 'ipai', 'huoyuan', 'catid', 'tags', 'is_following', 'is_followed', 'signature'), $v, $uid);
		        $v = $userinfo;
		    }
		}
		unset($res['ids']);
		
		// 新增粉丝
		$res['new_fans']['num'] = D('NewFans')->getNewFansCount($uid);
		if($res['new_fans']['num']){
		    $newfans = D('NewFans')->getLastNewFans($uid);
		    $newfansinfo = query_user(array('avatar'), $newfans);
		    $res['new_fans']['avatar'] = $newfansinfo['avatar'];
		}
		
		$this->result ['data'] = $res;
	}
	
	/**
	 * 新增粉丝
	 */
	public function newFans(){
		$http_request_mode = 'request';
		$uid = I($http_request_mode.'.uid', 0, 'intval');
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');
        $page_size = I($http_request_mode.'.page_size', 10, 'intval'); //默认10条

        $total = D('NewFans')->getNewFansCount($uid);
        $list = array();
        if($total){
            $map = array();
            $map['page'] = $page_no;
            $map['limit'] = $page_size;
    		$list = D('NewFans')->getNewFansList($uid, $map);
    		
    		if($list){
    		    foreach ($list as $k => &$v){
    		        $userinfo = query_user(array('uid', 'nickname', 'sex', 'catid', 'level', 'fans', 'avatar', 'ipai', 'huoyuan', 'catid', 'tags', 'is_following', 'is_followed', 'signature'), $v);
    		        $v = $userinfo;
    		    }
    		}else{
    		    $list = array();
    		}
        }
		
		$this->result ['data'] = array('total' => $total, 'list' => $list);
	}
	
	/**
	 * 删除新粉丝
	 */
	public function delNewFans(){
		$http_request_mode = 'post';
		$uid = I($http_request_mode.'.uid', 0, 'intval');
	    D('NewFans')->clearNewFans($uid);
	}
	
	/**
	 * 关注
	 */
	public function doFollow() {
		$http_request_mode = 'post';
		$uid_follow = I($http_request_mode.'.uid_follow', 0, 'intval');
		$follow_uid = I($http_request_mode.'.follow_uid', 0, 'intval');

		$res = D('Follow')->addFollow($uid_follow, $follow_uid);

		if (!$res) {
		    $this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '关注失败'.D('Follow')->getError(), 'data' => (object)array() );
		    exit;
		}
	}
	
	/**
	 * 取消关注
	 */
	public function unFollow() {
		$http_request_mode = 'post';
		$uid_follow = I($http_request_mode.'.uid_follow', 0, 'intval');
		$follow_uid = I($http_request_mode.'.follow_uid', 0, 'intval');

		$res = D('Follow')->unFollow($uid_follow, $follow_uid);

		if (!$res) {
		    $this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '取消关注失败'.D('Follow')->getError(), 'data' => (object)array() );
		    exit;
		}
	}
	
	/**
	 * 关注列表
	 */
	public function follow() {
		$http_request_mode = 'request';
		$uid = I($http_request_mode.'.uid', 0, 'intval');
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');
        $page_size = I($http_request_mode.'.page_size', 10, 'intval'); //默认10条

        $map = array();
        //$map['page'] = $page_no;
        //$map['limit'] = $page_size;
		$res = D('Follow')->getFollowList($uid, $map);
		
		if($res['list']){
		    foreach ($res['list'] as $k => &$v){
		        $userinfo = query_user(array('uid', 'nickname', 'sex', 'catid', 'level', 'fans', 'avatar', 'ipai', 'huoyuan', 'catid', 'tags', 'is_following', 'is_followed', 'signature'), $v, $uid);
		        $v = $userinfo;
		    }
		}
		unset($res['ids']);
		
		// 新增粉丝
		$res['new_fans']['num'] = D('NewFans')->getNewFansCount($uid);
		if($res['new_fans']['num']){
		    $newfans = D('NewFans')->getLastNewFans($uid);
		    $newfansinfo = query_user(array('avatar'), $newfans);
		    $res['new_fans']['avatar'] = $newfansinfo['avatar'];
		}
		$this->result ['data'] = $res;
	}
	
	/**
	 * 分类列表
	 */
	public function categoryList(){
		$http_request_mode = 'request';
		$type = I($http_request_mode.'.type', 1, 'intval');
		$hot = I($http_request_mode.'.hot', 0, 'intval');
		$recommend = I($http_request_mode.'.recommend', 0, 'intval');
		$letter = I($http_request_mode.'.letter', '', 'op_t');
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');
        $page_size = I($http_request_mode.'.page_size', 10, 'intval'); //默认10条
        
		$map = array();
		$map['where']['status'] = 1;
		$map['where']['type'] = $type;
		$hot && $map['where']['hot'] = $hot;
		$recommend && $map['where']['recommend'] = $recommend;
		if($letter !== '') $map['where']['letter'] = $letter;
		$map['page'] = $page_no;
		$map['limit'] = $page_size;
		($recommend && !I($http_request_mode.'.page_size')) && $map['limit'] = 135;
		$map['field'] = 'id,title,letter';
        $this->result['data'] = D('UserCategory')->getList($map);
	}
	
	/**
	 * 标签列表
	 */
	public function tagList(){
		$http_request_mode = 'request';
		$type = I($http_request_mode.'.type', 1, 'intval');
		$hot = I($http_request_mode.'.hot', 0, 'intval');
		$recommend = I($http_request_mode.'.recommend', 0, 'intval');
		$letter = I($http_request_mode.'.letter', '', 'op_t');
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');
        $page_size = I($http_request_mode.'.page_size', 10, 'intval'); //默认10条
        
		$map = array();
		$map['where']['status'] = 1;
		//$map['where']['pid'] = $type;
		//$hot && $map['hot'] = $hot;
		//$recommend && $map['recommend'] = $recommend;
		$map['page'] = $page_no;
		$map['count'] = $page_size;
		$map['field'] = 'id,title';
        $this->result['data'] = D('UserTag')->getList($map);
	}
	
	/**
	 * 标签模糊搜索
	 */
	public function tagSearch(){
		$http_request_mode = 'request';
		$keyword = I($http_request_mode.'.keyword', '', 'op_t');
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');

		// TODO 搜索引擎
		$map = array();
		$map['where']['title'] = array('like', "%$keyword%");
		$map['limit'] = 40;
		$map['field'] = 'id,title';
		$map['page'] = $page_no;
        $list = D('ViewUserTags')->getList($map);
        foreach ($list as $k => & $v){
            // TODO 搜索引擎
            $v['num'] = 0;
        }
        $this->result['data'] = $list;
	}
	
	/**
	 * 热门标签
	 */
	public function tagHot(){
	    $data = array();
	    $data['type1'] = D('Ucenter/UserCategory')->getHot(1, 40);
	    $data['type2'] = D('Ucenter/UserCategory')->getHot(2, 40);
        $this->result['data'] = $data;
	}
	
	/**
	 * 大家都在搜
	 */
	public function tagRecommend(){
	    $http_request_mode = 'request';
	    
	    // TODO 搜索引擎
	    $map = array();
	    $map['limit'] = 10;
	    $map['field'] = 'id,title';
	    $list = D('HotWords')->getList($map);
	    $this->result['data'] = $list;
	}
	
	/**
	 * 关注搜索
	 */
	public function followSearch(){
		$http_request_mode = 'request';
		$uid = I($http_request_mode.'.uid', 0, 'intval');
		$keyword = I($http_request_mode.'.keyword', '', 'op_t');
        $page_no = I($http_request_mode.'.page_no', 1, 'intval');
        $page_size = I($http_request_mode.'.page_size', 10, 'intval'); //默认10条

        $map = array();
        $map['page'] = $page_no;
        $map['limit'] = $page_size;
        $map['where']['uid_follow'] = $uid;
        $map['keyword'] = $keyword;
		$res = D('Follow')->followSearch($map);
		
		if($res['list']){
		    foreach ($res['list'] as $k => &$v){
		        $userinfo = query_user(array('uid', 'nickname', 'sex', 'catid', 'level', 'fans', 'avatar', 'ipai', 'huoyuan', 'catid', 'tags', 'is_following', 'is_followed', 'signature'), $v['id'], $uid);
		        $v = $userinfo;
		    }
		}else{
		    $res = array();
		}
		unset($res['ids'], $res['times']);
		
		$this->result ['data'] = $res;
	}
	
	/**
	 * 设置位置
	 */
	public function setLocation(){
		$http_request_mode = 'post';
		$uid = I($http_request_mode.'.uid', 0, 'intval');
		$lat = I($http_request_mode.'.lat', '', 'op_t');
		$lng = I($http_request_mode.'.lng', '', 'op_t');
		$data = array();
		$data['uid'] = $uid;
		$data['lat'] = $lat;
		$data['lng'] = $lng;
		$res = M('user_location')->add($data, '', true);

		if (!$res) {
		    $this->result = array ( 'code' => self::ERROR_CODE, 'msg' => '设置位置失败'.M('user_location')->getError(), 'data' => (object)array() );
		    exit;
		}
	}
	
	/**
	 * 新消息
	 */
	public function newmessage(){
		$http_request_mode = 'request';
		$uid = I($http_request_mode.'.uid', 0, 'intval');
		// 新消息数量
		$data['num'] = D('Weiquan/WeiquanMessage')->getCount($uid);
		
		// 最新消息头像
		if($data['num']){
		    $last_message = D('Weiquan/WeiquanMessage')->getLastOne($uid);
		    $obj_userinfo = query_user(array('avatar'), $last_message['obj_info']['uid']);
		    $data['avatar'] = $obj_userinfo['avatar'];
		}
		$this->result['data'] = $data;
	}
}