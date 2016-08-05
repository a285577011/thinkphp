<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
	
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php echo hook('syncMeta');?>

<?php $oneplus_seo_meta = get_seo_meta($vars,$seo); ?>
<?php if($oneplus_seo_meta['title']): ?><title><?php echo ($oneplus_seo_meta['title']); ?></title>
<?php else: ?>
<title><?php echo modC('WEB_SITE_NAME','I.CN 爱微商','Config');?></title><?php endif; ?>
<?php if($oneplus_seo_meta['keywords']): ?><meta name="keywords" content="<?php echo ($oneplus_seo_meta['keywords']); ?>" /><?php endif; ?>
<?php if($oneplus_seo_meta['description']): ?><meta name="description" content="<?php echo ($oneplus_seo_meta['description']); ?>" /><?php endif; ?>

    <link type="text/css" rel="stylesheet" href="/Theme/Green/Common/Static/css/reseting.css">   
    <link rel="stylesheet" href="/Application/Ucenter/Static/css/photoswipe.css">
    <link type="text/css" rel="stylesheet" href="/Public/css/core.css"/>
    <link type="text/css" rel="stylesheet" href="/Theme/Green/Common/Static/css/cui.css"/>
    <link type="text/css" rel="stylesheet" href="/Public/js/ext/magnific/magnific-popup.css"/>
    <link type="text/css" rel="stylesheet" href="/Theme/Green/Common/Static/css/common.css">
    <link type="text/css" rel="stylesheet" href="/Theme/Green/Ucenter/Static/Weiquan/Static/css/homepage.css">
    <link href="/Public/zui/css/zui.css" rel="stylesheet">
    <link type="text/css" rel="stylesheet" href="/Theme/Green/Ucenter/Static/css/fans.css">
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/page.css" />

    
<style>

.container {
    width: 1200px;
    max-width:1200px;
}
}
</style>



    <script type="text/javascript" src="/Public/js.php?f=js/jquery-1.8.3.js,js/com/com.toast.class.js"></script>
    <script type="text/javascript" src="/Theme/Green/Common/Static/js/jquerysenior.js"></script>
    <script type="text/javascript" src="/Theme/Green/Ucenter/Static/js/photoswipe.min.js"></script>
    <script type="text/javascript" src="/Theme/Green/Ucenter/Static/js/photoswipe-ui-default.min.js"></script>
    <script type="text/javascript" src="/Theme/Green/Ucenter/Static/js/homepage.js"></script>    

<?php $config = api('Config/lists'); C($config); $count_code=C('COUNT_CODE'); ?>
<script type="text/javascript">
    var UID=<?php echo is_login();?>;
    var ICN = {
    "ROOT": "",
            "APP": "",
            "PUBLIC": "/Public",
            "DEEP": "<?php echo C('URL_PATHINFO_DEPR');?>",
            "MODEL": ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR": ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"],
            'URL_MODEL': "<?php echo C('URL_MODEL');?>",
    }
    var cookie_config = {
    "prefix":"<?php echo C('COOKIE_PREFIX');?>"
    }
    var Config = {
    'GET_INFORMATION':<?php echo modC('GET_INFORMATION', 1, 'Config');?>,
            'GET_INFORMATION_INTERNAL':<?php echo modC('GET_INFORMATION_INTERNAL', 10, 'Config');?> * 1000
    }
</script>
<script>
    var _ROOT_ = "";
    var MID = "<?php echo is_login();?>";
    var MODULE_NAME="<?php echo MODULE_NAME; ?>";
    var ACTION_NAME="<?php echo ACTION_NAME; ?>";
    var CONTROLLER_NAME ="<?php echo CONTROLLER_NAME; ?>";
    var initNum = "<?php echo modC('WEIBO_NUM',140,'WEIBO');?>";
    function adjust_navbar(){
        /*$('#sub_nav').css('top',$('#nav_bar').height());*/
        /*$('#main-container').css('padding-top',$('#nav_bar').height()+20)*/
    }
</script>
<?php echo hook('pageHeader');?>


</head>
<body>
	<!-- 头部 -->
	<div id="a-scroll-box" class="a-scroll-box">
	<div class="logobox">
		<div class="logo">
			<a href="index.html">
			<div class="a-logo left">
				<img src="/Theme/Green/Common/Static/images/base/a-logo.png"/>
			</div>
			</a>
			<div class="logo-cut left">
			</div>
			<div class="a-love left">
				<img src="/Theme/Green/Common/Static/images/base/a-love.png"/>
			</div>
			<div class="sousuobox">
				<form action="<?php echo U('/Home/Index/Search@');?>" method="POST" id="searchFrom">
				<input class="search" id="search" name="keyword" type="text" placeholder="请输入关键字"/>
				<p class="anniu handle" href="javascript:void(0);" onclick="$('#searchFrom').submit();">
					搜索
				</p>
				</form>
			</div>
			<div class="dianhuabox right">
				<h1 class="px14">24小时微信客服：</h1>
				<h2>130-8888-8888</h2>
			</div>
		</div>
	</div>
</div>

<div class="sure_boxs hide">
	<div class="sure_title">
	</div>
	<p>
		确认要清空所有纪录吗？
	</p>
	<input id="sure_button" type="button" value="确定"/>
	<input id="cancel_button" type="button" value="取消"/>
</div>

<div class="header">
	<ul class="header-top container">
            
		<h3 class="px12 left">欢迎进入爱微商i.cn,世界将因你而不同！</h3>
            
		<ul class="header-right right px12 ">
			<div class="right">
			<?php if(is_login()): $common_header_user = query_user(array('nickname','username','level')); ?>
		        <?php $unreadMessage=D('Common/Message')->getHaventReadMeassageAndToasted(is_login()); ?>
				<li class="my-icn">
				<em><span class="a-wechat-name"><?php echo ($common_header_user["nickname"]); ?></span></em>
				<span class="a-grade back_orange px12"><i class="white">Lv.<?php echo ($common_header_user["level"]); ?></i></span>
				<span class="a-down"></span>
				<ul class="my-pop hide">
					<a href="http://<?php echo ($_SESSION[icn][user_auth]['username']); ?>.i.cn<?php echo U('/Index/index',array('type'=>related));?>">
					<li>
					<span class="a-head"></span>
     				我的主页
					</li>
					</a>
					<a href="<?php echo U('@1');?>">
					<li>
					<span class="a-one2"></span>
     				我的一元爱拍
					</li>
					</a>
					<a href="">
					<li>
					<span class="a-money2"></span>
     				我的一元爱拍
					</li>
					</a>
					<a href="<?php echo U('Ucenter/Config/index@');?>">
					<li>
					<span class="a-set2"></span>
     				账号设置
					</li>
					</a>
					<li class="hand" onclick='javascript:window.location.href="<?php echo U('/Ucenter/Member/logout@');?>"'>
					<span class="a-load2"></span>
     				退出
					</li>
				</ul>
				</li>
				<span class="header-cut"></span>
				<a href="">
				<li class="a-messagebox">
				<span class="a-message"></span>
				<em>消息</em>
				<span class="a-message-num back_orange white">5</span>
				</li>
				</a>
				<span class="header-cut"></span>
				<li class="a-myshop">
				<span class="a-shop"></span>
				<em>购物车</em>
                                <input type="hidden" class="_cart_goods_url" value="<?php echo U('/Comm/cart@1');?>"/>
                                <input type="hidden" class="_cart_list" value="<?php echo U('/Order/join@1');?>"/>
                                <input type="hidden" class="_cart_del" value="<?php echo U('/Comm/delGoods@1',array('pid'=>'_pid_'));?>"/>
				<span class="a-shop-num orange"></span>
				<span class="a-down"></span>
				<ul class="a-shop-pop hide">
					<h3 class="px12">最近加入的爱拍</h3>
					<li class="">
					亲，您的购物车空空如也。
					</li>
					<div class="clear">
					</div>
					<div class="clear a-hr ">
					</div>
					<a class="a-goshop white back_orange bdr right px12" href="<?php echo U('/Order/join@1');?>">查看我的购物车</a>
				</ul>
				</li>
				<span class="header-cut"></span>
				<li class="a-record">
				<span class="a-eye"></span>
				<em>浏览纪录</em>
				<span class="a-down"></span>
				<ul class="a-record-pop hide">
					<div class="a-record-container">
						<li>
						<a href="">
						<div class="a-record-img left bdr">
							<p class="a-black">
							</p>
							<img src="/Theme/Green/Common/Static/images/base/03.jpg" alt=""/>
						</div>
						</a>
						<div class="a-record-right left">
							<a href=""><span class="a-name">微信：旺旺</span>
							<span class="a-man"></span></a><br/>
							<a href=""><span>等级：</span>
							<span class="a-record-grade white back_orange "><i>Lv.13</i></span></a><br/>
							<a href="" class="a-record-keys bdr">白富美</a>
						</div>
						</li>
						<div class="a-record-hr clear ">
						</div>
						<li>
						<a href="">
						<div class="a-record-img left bdr">
							<p class="a-black">
							</p>
							<img src="/Theme/Green/Common/Static/images/base/03.jpg" alt=""/>
						</div>
						</a>
						<div class="a-record-right left">
							<a href=""><span class="a-name">微信：旺旺旺旺旺旺</span>
							<span class="a-man"></span></a><br/>
							<a href=""><span>等级：</span>
							<span class="a-record-grade white back_orange "><i>Lv.13</i></span></a><br/>
							<a href="" class="a-record-keys bdr">白富美</a>
						</div>
						<div class="clear">
						</div>
						</li>
						<div class="a-record-hr clear ">
						</div>
						<li>
						<a href="">
						<div class="a-record-img left bdr">
							<p class="a-black">
							</p>
							<img src="/Theme/Green/Common/Static/images/base/03.jpg" alt=""/>
						</div>
						</a>
						<div class="a-record-right left">
							<a href=""><span class="a-name">微信：旺旺</span>
							<span class="a-man"></span></a><br/>
							<a href=""><span>等级：</span>
							<span class="a-record-grade white back_orange "><i>Lv.13</i></span></a><br/>
							<a href="" class="a-record-keys bdr">白富美</a>
						</div>
						<div class="clear">
						</div>
						</li>
						<div class="a-record-hr clear ">
						</div>
						<li>
						<a href="">
						<div class="a-record-img left bdr">
							<p class="a-black">
							</p>
							<img src="/Theme/Green/Common/Static/images/base/03.jpg" alt=""/>
						</div>
						</a>
						<div class="a-record-right left">
							<a href=""><span class="a-name">微信：旺旺</span>
							<span class="a-woman"></span></a><br/>
							<a href=""><span>等级：</span>
							<span class="a-record-grade white back_orange "><i>Lv.13</i></span></a><br/>
							<a href="" class="a-record-keys bdr">白富美</a>
						</div>
						<div class="clear">
						</div>
						</li>
						<div class="a-record-hr clear ">
						</div>
						<li>
						<a href="">
						<div class="a-record-img left bdr">
							<p class="a-black">
							</p>
							<img src="/Theme/Green/Common/Static/images/base/03.jpg" alt=""/>
						</div>
						</a>
						<div class="a-record-right left">
							<a href=""><span class="a-name">微信：旺旺</span>
							<span class="a-man"></span></a><br/>
							<a href=""><span>等级：</span>
							<span class="a-record-grade white back_orange "><i>Lv.13</i></span></a><br/>
							<a href="" class="a-record-keys bdr">白富美</a>
						</div>
						<div class="clear">
						</div>
						</li>
						<div class="clear">
						</div>
					</div>
					<div class="a-record-footer">
						<div class="page_group4 left">
							<a href=""><span class="page_pre a-pre"></span></a>
							<span class="a-pape-cut left"></span>
							<a href=""><span class="page_next a-next"></span></a>
						</div>
						<div class="a-empty orange left px14 hand trashs">
							清空纪录
						</div>
					</div>
				</ul>
				</li>
				<span class="header-cut"></span>
			<?php else: ?>
				<p class="header-cut hide">
				</p>
				<p class="header-cut hide">
				</p>
				<li class="hand" id="login"><a data-target="#myModal" href="<?php echo U('/Ucenter/Member/Login@');?>" data-toggle="modal">登录</a></li>
				<span class="header-cut"></span>
				<a href="<?php echo U('Ucenter/Member/register@');?>">
				<li class="orange">注册</li>
				</a>
				<span class="header-cut"></span><?php endif; ?>
				<li class="a-phonebox">
				<span class="a-phone"></span>
				<em>手机i.cn</em>
				<span class="a-down"></span>
				<ul class="a-phone-pop hide">
					<li>
					<div class="a-phone-img left bdr">
						<img src="/Theme/Green/Common/Static/images/base/shouye_146.jpg" alt=""/>
					</div>
					<div class="a-phone-right right px14">
						<a href="">
						<div class="a-iosbox bdr ">
							<span class="a-ios"></span>
							<span>iOS版</span>
						</div>
						</a>
						<a href="">
						<div class="a-androidbox bdr ">
							<span class="a-android"></span>
							<span>Android版</span>
						</div>
						</a>
					</div>
					<p class="clear">
						i.cn客户端
					</p>
					</li>
					<div class="a-record-hr clear">
					</div>
					<li>
					<div class="a-phone-img left bdr">
						<img src="/Theme/Green/Common/Static/images/base/shouye_146.jpg" alt=""/>
					</div>
					<div class="a-phone-right right px14">
						<p class="px12">
							关注i.cn官方微信，爱微商资讯早知道！
						</p>
					</div>
					<p class="clear">
						i.cn官方微信
					</p>
					</li>
				</ul>
				</li>
				<span class="header-cut"></span>
				<a href="">
				<li class="a-noticebox">
				<span class="a-notice"></span>
				<em>最新公告</em>
				</li>
				</a>
				<?php if(is_login()): ?><span class="header-cut"></span>
				<span class="a-edit" id="a-release" data-target="#a-release_box" data-toggle="modal" href="<?php echo U('/Weiquan/Index/quickPost@');?>"></span><?php endif; ?>
			</div>
		</ul>
	</ul>
</div>
	<!-- /头部 -->
	
	<!-- 主体 -->
	

    <!-- 主体 -->
    <!--网站全局BANNER-->
    <div class="logobox">
	<div class="logo">
		<a href="<?php echo U('/@');?>">
		<div class="a-logo left">
			<img src="/Theme/Green/Common/Static/images/base/a-logo.png"/>
		</div>
		</a>
		<div class="logo-cut left">
		</div>
		<div class="a-love left">
			<img src="/Theme/Green/Common/Static/images/base/a-love.png"/>
		</div>
		<div class="sousuobox">
			<form action="<?php echo U('/Home/Index/Search@');?>" method="get" id="searchFrom">
			<input class="search" id="search" name="keyword" type="text" placeholder="请输入关键字">
			<p class="anniu handle" href="javascript:void(0);" onclick="$('#searchFrom').submit();">
				搜索
			</p>
			</form>
		    <?php $hotwords = M('hotWords')->where(array('hot'=>1))->getField('id,title'); $hotcount = count($hotwords); ?>
			<div class="remen clear">
				<p class="left">
					近期热搜：
				</p>
				<div class="rollkeys_box left">
					<dl class="roll_keys">
						<?php if(is_array($hotwords)): $i = 0; $__LIST__ = $hotwords;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 8 );++$i; if(($mod) == "0"): ?><dd><?php endif; ?>
						<a href="<?php echo U('home/index/search@',array('keyword'=>$vo));?>" target="_blank"><?php echo ($vo); ?></a>
				        <?php if(($mod) == "7"): ?></dd><?php endif; ?>
				        <?php if($key == $hotcount): ?></dd><?php endif; endforeach; endif; else: echo "" ;endif; ?>
					</dl>
				</div>
			</div>
		</div>
		<div class="dianhuabox right">
			<h1 class="px14">24小时微信客服：</h1>
			<h2>130-8888-8888</h2>
			<h3 class="px14">目前微信粉丝：<span><?php echo weixin_fans();?></span>&nbsp;&nbsp;人</h3>
		</div>
	</div>
</div>
<div class="nav_box">
	<div class="center">
		<ul>
			<?php $__NAV__ = D('Channel')->lists(true);$__NAV__ = list_to_tree($__NAV__, "id", "pid", "_"); if(is_array($__NAV__)): $i = 0; $__LIST__ = $__NAV__;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nav): $mod = ($i % 2 );++$i;?><li>
                <a title="<?php echo ($nav["title"]); ?>" href="<?php echo (get_nav_url($nav["url"])); ?>" target="<?php if(($nav["target"]) == "1"): ?>_blank<?php else: ?>_self<?php endif; ?>" class="<?php if(($key) == "0"): ?>h lv<?php else: ?>h<?php endif; ?> <?php if((get_nav_active($nav["url"])) == "1"): ?>lv<?php else: endif; ?>"><?php if(($key) == "0"): ?><span class="home"></span><?php endif; echo ($nav["title"]); ?></a>
            </li><?php endforeach; endif; else: echo "" ;endif; ?>
        	<li>
                <a title="微商圈" href="<?php echo U('@'.$_SESSION[icn][user_auth][username]);?>" class="h <?php if($_SERVER['HTTP_HOST'] == $_SESSION[icn][user_auth][username].'.i.cn'): ?>lv<?php endif; ?>">微商圈</a>
            </li>
		</ul>
	</div>
</div>
    <!--网站全局BANNER结束-->

    <!--BANNER-->
    <?php echo W('Weiquan/User/userCenterBanner',array('uid'=>$uid));?>
    <?php $user_info['username']=$userInfo['username']; ?>
    <?php print_r($user_info['username']); ?>
<ul class="menu_box container" style="max-width:1200px;">
    
    <li><a class="hmpage <?php if(isset($banner_nav['index'])): ?>menu_style<?php endif; ?>" href="http://<?php echo ($user_info['username']); ?>.i.cn<?php echo U('/Index/index',array('type'=>related));?>">主页</a></li>
    <li><a class="aipai <?php if(isset($banner_nav['ipai'])): ?>menu_style1<?php endif; ?>" href="<?php echo U('/Index@1.i.cn');?>">一元爱拍</a></li>
</ul>

<!--分类-->
   
 <!--粉丝body-->  
<div class="fans_body container">
  <div class="fans_left left ">
    	 <div class=" my_attention <?php if(!intval(I('get.togtherFollow'))): ?>fans_style<?php endif; ?>">
        	<div class="fans_img1 left"></div>
           <a href="<?php echo U('Index/following',array('uid' => $uid));?>"> <p class="left cl50"><?php if($userInfo['uid']==is_login()&&is_login()){ echo '我'; }elseif($userInfo['sex']==2){ echo '她';}else{ echo '他';} ?>的关注&nbsp;<span style="color:#303030"><?php echo ($userInfo['following']); ?></span></p>
       </a> </div>
        <?php if($userInfo['uid'] != is_login()&&is_login()): ?><div class="common_attention <?php if(intval(I('get.togtherFollow'))): ?>fans_style<?php endif; ?>">
        	<span class="yuan left"></span>
            <a href="<?php echo U('Index/following',array('uid' => $uid,'togtherFollow'=>1));?>"><p class="left">共同关注&nbsp;<span><?php echo ($togtherFollowTotal); ?></span></p>
       </a> </div><?php endif; ?>
        <div class="my_fans">
        	<div class="fans_img2 left"></div>
           <a href="<?php echo U('Index/fans',array('uid' => $uid));?>"> <p class=" left cl50"><?php if($userInfo['uid']==is_login()&&is_login()){ echo '我'; }elseif($userInfo['sex']==2){ echo '她';}else{ echo '他';} ?>的粉丝&nbsp;<span style="color:#303030"><?php echo ($userInfo['fans']); ?></span></p>
       </a> </div>
        <div class="clear"></div>
    </div>
    
   <div class="fans_right right">
    	<div class="fans_title">
        	<h1 class="left cl50 px14"><?php if(intval(I('get.togtherFollow'))){echo '共同关注&nbsp';}elseif($userInfo['uid']==is_login()&&is_login()){ echo '我的关注&nbsp;'; }elseif($userInfo['sex']==2){ echo '她的关注&nbsp;';}else{ echo '他的关注&nbsp;';} ?><span><?php if(intval(I('get.togtherFollow'))): echo ($togtherFollowTotal); else: echo ($following['total']); endif; ?></span></h1>
        </div>
        <?php if($isOwner): ?><div class="fans_order cl50">
        	<div class="down_button left hand">
       	  <p class="left orders">排序</p>
            <div class="order_down left"></div>
            <div class="down_pop">
            	<a href="<?php echo U('Index/follow',array('uid' => $uid));?>"><h1 class="px12 cl50">全部关注</h1></a>
                <div class="down_order">
                <?php if($order == 'level'): ?><a href="<?php echo U('Index/following',array_merge(I('get.'),array('sort'=>'level-'.$by)));?>">
                <?php else: ?>
                <a href="<?php echo U('Index/following',array_merge(I('get.'),array('sort'=>'level-desc')));?>"><?php endif; ?>
                	<p class="left">按等级</p>
                	<?php if($order == 'level'): ?><div class="order_img<?php if($by=='asc'){echo 3;} ?> right"></div><?php endif; ?>
                    </a>
                </div>
                 <div class="down_order clear">
                 <?php if($order == 'fans'): ?><a href="<?php echo U('Index/following',array_merge(I('get.'),array('sort'=>'fans-'.$by)));?>">
                 <?php else: ?>
                 <a href="<?php echo U('Index/following',array_merge(I('get.'),array('sort'=>'fans-desc')));?>"><?php endif; ?>
                	<p class="left">按粉丝</p>
                	<?php if($order == 'fans'): ?><div class="order_img<?php if($by=='asc'){echo 3;} ?> right"></div><?php endif; ?>
                    </a>
                    
                </div>
                 <div style="margin-bottom:8px" class="down_order clear">
                 <?php if($order == 'visit'): ?><a href="<?php echo U('Index/following',array_merge(I('get.'),array('sort'=>'visit-'.$by)));?>">
                 <?php else: ?>
                 <a href="<?php echo U('Index/following',array_merge(I('get.'),array('sort'=>'visit-desc')));?>"><?php endif; ?>
                	<p class="left">按访问数</p>
                	<?php if($order == 'visit'): ?><div class="order_img<?php if($by=='asc'){echo 3;} ?> right"></div><?php endif; ?>
                    </a>
                </div>
                <div class="clear"></div>
            </div>
            </div>
            <div class="fanssearch_box right">
            	<input type="text" placeholder="请输入昵称或者备注" class="fans_search left px12" value="<?php echo op_t($_GET['name']);?>" name="name"/>
                <div class="fanssearch_img left" onclick="search()"></div>
            </div>
            <div class="clear"></div>
        </div><?php endif; ?>
        <div class="fans_container">
        <div class="section_box left">
         <?php if(!empty($following["followInfo"])): if(is_array($following["followInfo"])): $i = 0; $__LIST__ = $following["followInfo"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="fans_section fans_<?php echo ($vo["uid"]); ?>">
        	<div class="fans_head left">
            	<a href="<?php echo U('@'.$vo[username]);?>" target="_blank"><img src="<?php echo ($vo['avatar128']); ?>" alt=""/></a>
            </div>
            <div class="fans_data left">
            	<a href="<?php echo U('@'.$vo[username]);?>"><p class="fans_name cl50 ellipsis" title="<?php if($vo['weiquanRemark']): echo ($vo['weiquanRemark']); else: echo ($vo['nickname']); endif; ?>">微信：<span id="remarks"><?php if($vo['weiquanRemark']): echo ($vo['weiquanRemark']); else: echo ($vo['nickname']); endif; ?></span></p></a>
                <p class="fans_position cl50">地区：<?php if(empty($vo['pos_province'])): echo L('_PLACE_');?>
                <?php else: ?>
                <?php echo ($vo["pos_province"]); ?>&nbsp;&nbsp;<?php echo ($vo["pos_city"]); ?>&nbsp;&nbsp;<?php echo ($vo["pos_district"]); ?>&nbsp;&nbsp;<?php echo ($vo["pos_community"]); endif; ?></span></p>
                <a href=""><div class="fans_grade cl50">
                	<p class="left">等级：</p><span class="fans_rank<?php if($vo['type'] == 2): ?>2<?php endif; ?> left px12"><i>Lv.<?php echo ($vo["level"]); ?></i></span>
                    
                    <?php if($vo['ipai']): ?><div class="yiyuan left"></div><?php endif; ?>
                </div></a>
            </div>
            <?php if(is_login()!=$vo[uid]): ?><div class="section_right right">
            	<div <?php if($vo['followFlag']==3){ echo 'class="attention_img2 left hand" onclick=""'; }elseif($vo['followFlag']==1){ echo 'class="attention_img3 left hand" onclick=""';}else{ echo "class='attention_img left hand' onclick='followAction(this,$vo[uid],1)'";} ?>></div>
                <?php if($isOwner): ?><div class="down_button2 left hand">
                <div class="set_pop px12 ">
                    	<?php if($vo['followFlag']==1||$vo['followFlag']==3): ?><!-- <p style="margin-top:8px">设置备注</p> -->
                         <p onclick='followAction(this,<?php echo ($vo[uid]); ?>,2)'>取消关注</p><?php endif; ?>
                        <div class="clear"></div>
                    </div>
                </div><?php endif; ?>
                <?php if($vo['category']): ?><p class="fans_key<?php if($vo['type'] == 2): ?>s<?php endif; ?>2 px12 clear cl50" data-url="<?php echo U('Home/Index/hot', array('catid'=>$vo['catid']));?>"><?php echo ($vo["category"]); ?></p><?php endif; ?>
            </div><?php endif; ?>
            <div class="clear"></div>
        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                  <?php else: ?>
                              <div class="fans-none">
         			<span class="fans-none-img"></span><br/>
         			<span>您搜索的<span class="orange"><?php echo op_t($_GET['name']);?></span>没有相关的昵称和备注，请重新搜索</span>
         		</div><?php endif; ?> 
        </div>
       
        </div> 
<div class="page_group1 clear"><?php echo ($_page); ?></div>
   </div> 
   <div class="clear"></div>
   </div> 
   
<script type="text/javascript">
    $(function(){
        $(window).resize(function(){
            $("#main-container").css("min-height", $(window).height() - 343);
        }).resize();
    })
</script>

	<!-- /主体 -->

	<!-- 底部 -->
	
<div class="footer">
	<ul class="footer-center container">
		<li class="px12 gray80s">
		<h2 class="black30 px16"><span class="a-guide"></span>新手指南</h2>
		<a href="">了解爱微商</a>
		<a href="">成为会员</a>
		<a href="">会员介绍</a>
		<a href="">商品微商与非商品微商</a>
		<a href="">用户协议</a>
		<a href="">联系客服</a>
		</li>
		<span class="footer-cut"></span>
		<li class="px12 gray80s">
		<h2 class="black30 px16"><span class="a-way"></span>支付方式</h2>
		<a href="">在线支付</a>
		<a href="">爱币支付</a>
		<a href="">余额支付</a>
		<a href="">保证金</a>
		</li>
		<span class="footer-cut"></span>
		<li class="px12 gray80s">
		<h2 class="black30 px16"><span class="a-shape"></span>供应商服务</h2>
		<a href="">入驻资质</a>
		<a href="">入驻流程</a>
		<a href="">供货商协议</a>
		<a href="">常见问题</a>
		</li>
		<span class="footer-cut"></span>
		<li class="px12 gray80s">
		<h2 class="black30 px16"><span class="a-service"></span>特色服务</h2>
		<a href="">微商红人</a>
		<a href="">一元爱拍</a>
		<a href="">微商学院</a>
		<a href="">一手货源</a>
		</li>
		<span class="footer-cut"></span>
		<li class="px12 gray80s">
		<h2 class="black30 px16"><span class="a-ensure"></span>服务保障</h2>
		<a href="">维权与举报</a>
		<a href="">防骗指南</a>
		<a href="">帮助中心</a>
		</li>
	</ul>
	<div class="clear">
	</div>
</div>
<!-- 模态框（Modal） -->
		<div class="modal  fade" id="a-release_box" tabindex="-1" role="dialog" aria-labelledby="a-release_boxLabel" aria-hidden="true" >
			<div class="modal-dialog " style="width: 600;">
				<div class="modal-content" >
				</div>
				<!-- /.modal-content -->
			</div>
			<!-- /.modal -->
		</div>
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog " style="width: 480px;">
		<div class="modal-content">
		</div>
	</div>
</div>
    <div class="modal fade" id="remarkName" tabindex="-1" role="dialog" aria-labelledby="remarkNameLabel" aria-hidden="true">
	<div class="modal-dialog " style="width: 294px;">
		<div class="modal-content">
		</div>
	</div>
</div>
<!--底部-->
<div class="bottom-box">
	<div class="bottom-center container">
		<div class="bottoms black30">
			<a href="">关于爱微商</a>
			<span class="bottom-cut"></span>
			<a href="">联系我们</a>
			<span class="bottom-cut"></span>
			<a href="">帮助中心</a>
			<span class="bottom-cut"></span>
			<a href="">免责声明</a>
		</div>
		<p class="px12 black30">
			Copyright© 2013-2015 厦门云游数码科技有限公司（i.cn 爱微商） 闽ICP备13017663号-7
		</p>
	</div>
	<div class="clear">
	</div>
</div>


    <script>
    $(window).resize(adjust_navbar).resize();
</script>
<!-- 为了让html5shiv生效，请将所有的CSS都添加到此处 -->
<link type="text/css" rel="stylesheet" href="/Public/static/qtip/jquery.qtip.css"/>


<!--广告-->
<!--<script type='text/javascript' src=''></script>-->
<!--广告js-->
<script src="/Public/js.php?t=js&f=js/com/com.functions.js,js/com/com.notify.class.js,js/com/com.ucard.js,static/qtip/jquery.qtip.js,js/ext/slimscroll/jquery.slimscroll.min.js,js/ext/magnific/jquery.magnific-popup.min.js,js/ext/placeholder/placeholder.js,js/ext/atwho/atwho.js,js/ext/lazyload/lazyload.js&v=<?php echo ($site["sys_version"]); ?>.js"></script>
<script>
     var weiquan_comment_order = "<?php echo modC('COMMENT_ORDER',0,'WEIQUAN');?>";
    $(function () {
        if (!document.getElementById('sub_nav')) {
            $('#main-container').css('margin-top', 70 + 'px');
        }
    });
</script>
<script type="text/javascript" src="/Theme/Green/Weiquan/Static/js/zui.js"></script>
<script type="text/javascript" src="/Public/static/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="/Theme/Green/Common/Static/js/core.js"></script>
<script type="text/javascript" src="/Public/js/ext/layer/layer.js" ></script>
<script type="text/javascript" src="/Theme/Green/Common/Static/js/common.js"></script>
    <script type="text/javascript" src="/Theme/Green/Ucenter/Static/js/weiquan.js"></script>
    <script>
                    weiquan.page = '<?php echo ($page); ?>';
                    weiquan.loadCount = 0;
                    weiquan.lastId = parseInt('<?php echo (reset($list)); ?>') + 1;
                    weiquan.url = "<?php echo ($loadMoreUrl); ?>";
                    weiquan.type = "<?php echo ($type); ?>";

                    $(function () {
                        weiquan_bind();
                        bind_like();
                        //当屏幕滚动到底部时
//                        if (weiquan.page == 1) {
//                            $(window).on('scroll', function () {
//                                if (weiquan.noMoreNextPage||weiquan.isLoadingWeibo) {
//                                    return;
//                                }
//                               
//                                if (weiquan.isLoadMoreVisible()) {
//                                    weiquan.loadNextPage();
//                                }
//                            });
//                            $(window).trigger('scroll');
//                        }

                        $(".trends_title li").click(function () {
                            location.href = $(this).attr('data-url');
                        });


                        weiquan.loadNextPage();//初始化加载
                        //点击更多加载
                        $('._btn_load_more').click(function () {
                            if (weiquan.noMoreNextPage || weiquan.isLoadingWeibo) {
                                return;
                            }

                            if (weiquan.isLoadMoreVisible()) {
                                weiquan.loadNextPage();
                            }
                        });
                    });
                    function followAction(obj,uid,flag){
                    	var url="<?php echo U('Index/followAction');?>";
                    	var data={uid:uid,flag:flag};
       		         $.ajax({
       				     type: "POST",
       				 url: url,
       				 data: data,
       				 beforeSend : function(){
       				   },
       				         success: function(data){
       				        	 if(flag==1){
       				             if(data.status==1){
   				            		 $(obj).removeClass().addClass("attention_img3 left hand");
   				            		 $(obj).attr("onclick",'');
   				            		toast.success(data.info, '温馨提示');
       				             }
       				             else if(data.status==2){
   				            		 $(obj).removeClass().addClass("attention_img2 left hand");
   				            		 $(obj).attr("onclick",'');
   				            		toast.success(data.info, '温馨提示');
       				             }
       				             else{
       				            	$('#login').find('a').trigger('click');
       				             }
       				         }else if(flag==2||flag==3){
       				        	 if(data.status==1){
				            		 $('.fans_'+uid).remove();
				            		 toast.success(data.info, '温馨提示');
       				        	 }
       				        	 else{
       				        		$('#login').find('a').trigger('click');
       				        	 }
       				         }
       				        	 },
       				 error: function(){
       				 layer.msg("系统繁忙");
       				 }
       				       });
                    }
                    function search(){
                    	var url='<?php echo U('Index/following',I('get.'));?>';
                    	var name=$("input[name='name']").val();
                    	if(url.indexOf("/name/") > 0 ){
                    	   if(name){
                          		url=url.replace(/\/name\/(.*?)([\.|\/])/,'/name/'+name+'$2');
                    		}
                		else{
                			url=url.replace(/\/name\/(.*?)([\.|\/])/,'$2');
                		   }
                    	}
                    	else{
                    	    url=url.replace('.html','/name/'+name+'.html');
                    	}
                    	if(!name){
                    		//toast.success('请输入关键字!', '温馨提示');
                    		//return false;
                    	}
                    	//var param=$('#searchFans').serialize();
                    	window.location.href=url;
                    	//$('form').submit();
                    }
                    //回车搜索
                    $(".fans_search").keyup(function (e) {
                        if (e.keyCode === 13) {
                            $(".fanssearch_img").click();
                            return false;
                        }
                    });
                   
    </script>


<div class="hidden">
	
</div>


	<!-- /底部 -->
</body>
</html>