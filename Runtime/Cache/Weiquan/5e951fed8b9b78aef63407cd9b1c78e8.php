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
    <link rel="stylesheet" href="/Application/Weiquan/Static/css/photoswipe.css">
    <link type="text/css" rel="stylesheet" href="/Public/css/core.css"/>
    <link type="text/css" rel="stylesheet" href="/Theme/Green/Common/Static/css/cui.css"/>
    <link type="text/css" rel="stylesheet" href="/Public/js/ext/magnific/magnific-popup.css"/>
    <link type="text/css" rel="stylesheet" href="/Theme/Green/Common/Static/css/common.css">
    <link type="text/css" rel="stylesheet" href="/Theme/Green/Weiquan/Static/css/homepage.css">




    <script type="text/javascript" src="/Public/js.php?f=js/jquery-1.8.3.js,js/com/com.toast.class.js"></script>
    <script type="text/javascript" src="/Theme/Green/Common/Static/js/jquerysenior.js"></script>
    <script type="text/javascript" src="/Theme/Green/Weiquan/Static/js/photoswipe.min.js"></script>
    <script type="text/javascript" src="/Theme/Green/Weiquan/Static/js/photoswipe-ui-default.min.js"></script>
    <script type="text/javascript" src="/Theme/Green/Weiquan/Static/js/homepage.js"></script>    

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
    <?php echo W('User/userCenterBanner',array('uid'=>$uid));?>
    <!--分类-->
    <?php if($type != 'all'): ?><ul class="menu_box container" style="max-width:1200px;">
    
    <li><a class="hmpage <?php if(isset($banner_nav['index'])): ?>menu_style<?php endif; ?>" href="http://<?php echo ($user_info['username']); ?>.i.cn<?php echo U('/Index/index',array('type'=>related));?>">主页</a></li>
    <li><a class="aipai <?php if(isset($banner_nav['ipai'])): ?>menu_style1<?php endif; ?>" href="<?php echo U('/Index@1.i.cn');?>">一元爱拍</a></li>
</ul><?php endif; ?>
    <!--BANNER结束-->


    <div class="homepage_body container" style="max-width:1200px;">
        <style>
h2{
padding:0;
margin:0;
}
</style>
<div class="left_body left">
    <div class="left_title">
        <div class="title_section left">
            <a href="<?php echo U('Ucenter/Index/following@',array('uid' => $uid));?>">   <h2><?php echo ($user_info["following"]); ?></h2>
            <p><?php echo L('_FOLLOW_');?></p></a>
        </div>
        <span class="title_hr left"></span>
        <div class="title_section left">
          <a href="<?php echo U('Ucenter/Index/fans@',array('uid' => $uid));?>">  <h2><?php echo ($user_info["fans"]); ?></h2>
            <p><?php echo L('_FANS_');?></p></a>
        </div>
        <span class="title_hr left"></span>
        <div class="title_section left">
            <a href="<?php echo U('Weiquan/Index/index',array('type'=>related));?>"><h2><?php echo ($user_info["weiquancount"]); ?></h2>
            <p><?php echo L('_RELATED_');?></p>
            </a>
        </div>
        <div class="clear"></div>
    </div>

    <div class="personal_box">
        <div class="personal_title">
            <h1 class="px14 left"><?php echo L('_MY_USER_INFO_');?></h1>
           <?php if(is_login() == $user_info['uid']): ?><a href="<?php echo U('Ucenter/Config/index@');?>"><div class="personal_logo right"></div></a><?php endif; ?>
            <div class="clear"></div>
        </div>
        <div class="personal_section">
            <div class="icon1 left"></div>
            <p class="left"><?php echo L('_WESHOP_TYPE_'); echo L('_COLON_'); if($user_info['cat_data']['type'] == 1): ?>商品微商<?php else: ?>非商品微商<?php endif; ?></p>
            <div class="clear"></div>
        </div>
        <div class="personal_section2">
            <div class="icon2 left"></div>
            <p class="left"><?php echo L('_WECHAT_NAME_'); echo L('_COLON_'); echo ($user_info["nickname"]); ?></p>
            <div class="icon3 right"></div>
            <p style="margin-left:34px;margin-bottom:12px" class="clear"><?php echo L('_WECHAT_ACCOUNT_'); echo L('_COLON_'); echo ($user_info["weixin"]); ?></p>
           <?php if($user_info['qrcode']): ?><div class="code_box">
                <div class="code left"><img src='<?php echo ($user_info["qrcode"]); ?>' /></div>
                <div class="code_talk clear left">
                    <p><?php echo L('_SCAN_QR_FRIENDS_');?></p>
                </div>
            </div><?php endif; ?>
            <div class="clear"></div>
        </div>
        <div class="personal_section">
            <div class="icon4 left"></div>
            <p class="left"><?php echo L('_LEVEL_'); echo L('_COLON_');?></p>
            <span class="grade left px12"><i>Lv.<?php echo ($user_info["level"]); ?></i></span>
            <?php if($user_info['ipai']): ?><div class="icon5 left"></div><?php endif; ?>
            <?php if($user_info['ishot']): ?><div class="icon6 left"></div><?php endif; ?>
               <?php if($user_info['uid'] == is_login()): ?><a class="strategy left" href=""><?php echo L('_UPGRADE_FAQ_');?></a><?php endif; ?>
                <div class="clear"></div>
        </div>
        <?php if($user_info['uid'] == is_login()): ?><div class="personal_section">
            <div class="icon7 left"></div>
            <p class="left"><?php echo L('_I_COIN_'); echo L('_COLON_');?><span class="money"><?php echo ($user_info["score2"]); ?></span></p>
            <a class="recharge left px12" href=""><?php echo L('_RECHARGE_');?></a>
            <div class="clear"></div>
        </div>
         <?php if($user_info['ipai']){ ?>
        <div class="personal_section">
            <div class="icon8 left"></div>
            <p class="left"><?php echo L('_OVERAGE_'); echo L('_COLON_');?><span class="money"><?php echo ($user_info["balance"]); ?></span></p>
            <a class="recharge left px12" href=""><?php echo L('_RECHARGE_');?></a><a class="cash left px12" href=""><?php echo L('_WITHDRAW_CASH_');?></a>
            <div class="clear"></div>
        </div>
        <?php } ?>
        <div class="personal_section">
            <div class="icon9 left"></div>
            <p class="left"><?php echo L('_MY_INFO_PROGRESS_'); echo L('_COLON_');?></p>
            <div class="load_box clear left">
                <div style="width:<?php echo ($user_info['step']); ?>%" class="progress-bar"></div>
            </div>
            <span class="precent px12 left"><?php echo ($user_info['step']); ?>%</span>
            <a class="improve left px12" href="<?php echo U('Ucenter/Config/index@');?>"><?php echo L('_NOW_COMPLETE_');?></a>
            <div class="clear"></div>
        </div><?php endif; ?>
        <div class="personal_section">
            <div class="icon10 left"></div>
            <p class="left"><?php echo L('_AREA_'); echo L('_COLON_'); if(empty($user_info['pos_province'])): echo L('_PLACE_');?>
                <?php else: ?>
                <?php echo ($user_info["pos_province"]); ?>&nbsp;&nbsp;<?php echo ($user_info["pos_city"]); ?>&nbsp;&nbsp;<?php echo ($user_info["pos_district"]); ?>&nbsp;&nbsp;<?php echo ($user_info["pos_community"]); endif; ?>
        </p>
            <div class="clear"></div>
        </div>
        <?php if($user_info['show_field']['sread_url'] == 1): ?><div class="personal_section">
                <div class="icon11 left"></div>
                <p class="left"><?php echo L('_MY_DOMAIN_NAME_'); echo L('_COLON_');?><span><?php echo U('/@'.$user_info['username']);?></span></p>
                <div class="clear"></div>
            </div><?php endif; ?>
        <?php if($user_info['show_field']['diy_domain'] == 1): ?><div class="personal_section">
                <div class="icon11 left"></div>
                <p class="left"><?php echo L('_SHARE_URL_'); echo L('_COLON_');?><span><?php echo U('/@'.$user_info['username'].'/13088888888');?></span></p>
                <div class="clear"></div>
            </div><?php endif; ?>    
        <div class="data_pop">
            <?php if($user_info['show_field']['field_1'] == 1): ?><div class="personal_section">
                    <div class="icon12 left"></div>
                    <p class="left"><?php echo L('_MOBILE_NUMBER_'); echo L('_COLON_'); echo ($user_info['ext'][1]['data']); ?></p>
                    <div class="clear"></div>
                </div><?php endif; ?>
            <div class="personal_section">
                <div class="icon13 left"></div>
                <p style="margin-top:4px" class="left"><?php echo L('_QQ_NUMBER_'); echo L('_COLON_'); echo ($user_info['ext'][5]['data']); ?></span></p>
                <div class="clear"></div>
            </div>
            <?php if($user_info['show_field']['field_10'] == 1): ?><div class="personal_section">
                    <div class="icon14 left"></div>
                    <p class="left"><?php echo L('_WEIBO_URL_'); echo L('_COLON_');?> <?php echo ($user_info['ext'][10]['data']); ?></span></p>
                    <div class="clear"></div>
                </div><?php endif; ?>
            <?php if($user_info['show_field']['field_11'] == 1): ?><div class="personal_section">
                    <div class="icon15 left"></div>
                    <p class="left"><?php echo L('_MOMO_NUMBER_'); echo L('_COLON_');?> <?php echo ($user_info['ext'][11]['data']); ?></span></p>
                    <div class="clear"></div>
                </div><?php endif; ?>
            <?php if($user_info['show_field']['field_12'] == 1): ?><div class="personal_section">
                    <div class="icon17 left"></div>
                    <p class="left"><?php echo L('_EMAIL_'); echo L('_COLON_');?> <?php echo ($user_info['ext'][12]['data']); ?></span></p>
                    <div class="clear"></div>
                </div><?php endif; ?>
            <?php if($user_info['show_field']['field_6'] == 1): ?><div class="personal_section">
                    <div class="icon16 left"></div>
                    <p class="left"><?php echo L('_PROFESSION_'); echo L('_COLON_');?> <?php echo ($user_info['ext'][6]['data']); ?></span></p>
                    <div class="clear"></div>
                </div><?php endif; ?>
            <?php if($user_info['show_field']['field_14'] == 1): ?><div class="personal_section">
                    <div class="icon18 left"></div>
                    <p class="left"><?php echo L('_JOBS_'); echo L('_COLON_');?> <?php echo ($user_info['ext'][14]['data']); ?></span></p>
                    <div class="clear"></div>
                </div><?php endif; ?>
            <?php if($user_info['show_field']['field_15'] == 1): ?><div class="personal_section">
                    <div class="icon19 left"></div>
                    <p style="width:320px;" class="left"><?php echo L('_SPECIALTY_'); echo L('_COLON_');?> <?php echo ($user_info['ext'][15]['data']); ?></span></p>
                    <div class="clear"></div>
                </div><?php endif; ?>
            <?php if($user_info['show_field']['field_16'] == 1): ?><div class="personal_section">
                    <div class="icon20 left"></div>
                    <p style="width:320px;" class="left"><?php echo L('_INTEREST_'); echo L('_COLON_');?> <?php echo ($user_info['ext'][16]['data']); ?></span></p>
                    <div class="clear"></div>
                </div><?php endif; ?>           
            <div class="personal_section">
                <div class="icon21 left"></div>
                <p class="left"><?php echo L('_WESHOP_URL_'); echo L('_COLON_'); echo ($user_info['ext'][17]['data']); ?></p>
                <div class="clear"></div>
            </div>
            <div class="personal_section">
                <div class="icon22 left"></div>
                <p style="margin-bottom:12px;width:320px;line-height:20px" class="left">微商地址<?php echo L('_COLON_'); echo ($user_info['ext'][18]['data']); ?></p>
                <div class="clear"></div>
            </div>
        </div>
        <div class="more_box left">
            <div class="look_more">
                <p class="left cl50"><?php echo L('_VIEW_MORE_');?></p>

                <div class="down left"></div>
                <div class="clear"></div>
            </div>

        </div>
        <div class="more_box2 left">
            <div class="look_more">
                <p class="left cl50"><?php echo L('_CLICK_COLLAPSE_');?></p>

                <div class="down2 left"></div>
                <div class="clear"></div>
            </div>

        </div>
        <div class="clear"></div>
    </div>
</div>
        <div class="right_body right">            
            <div class="trends_box" style="margin-top:0px;">
                <ul class="trends_title cl50" id="weibo_filter">
                    <li class="personal_trends left">TA的动态(<?php echo ($total_count); ?>)</li> 
                </ul>

                <?php if($total_count == 0): ?><div class="dynanicimg_box">
                        <div class="dynanicimg"></div>
                    </div>
                    <div style="padding-bottom:726px;" class="dynanicword_box">
                        <div class="dynanicword"></div>
                        <div class="clear"></div>
                    </div>
                <?php else: ?>
                    <div id="weibo_list">
                    </div>
                     <?php if($page==1&&$isMore){ ?>
                        <div id="load_more" class="load_more left" >
                            <a class="cl50 _btn_load_more hand" >加载更多</a>
                        </div>
                    <?php }elseif($page>1){ ?>
                        <div id="load_more" class="load_more left" >
                           <img src="/Theme/Green/Weiquan/Static/images/loading.gif"/>
                        </div>
                        <div class="clear"></div>
                     <?php }elseif(!$isMore){ ?>
                        <div id="not_more" class="load_more left" >
                           <img src="/Theme/Green/Weiquan/Static/images/loading.gif"/>
                        </div>
                        <div class="clear"></div>
                    <?php } ?>
                    <div id="index_weibo_page" style=" <?php if($page==1){ ?>display:none<?php } ?>">
                        <div class="text-right" style="text-align: center">
                            <?php echo weiquanPageLink($total_count,30);?>
                        </div>
                    </div><?php endif; ?>

                <div class="clear"></div>
            </div>       
        </div>
        <div class="clear"></div>
    </div>
    <!--以下是设置备注的弹窗-->

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
    <script type="text/javascript" src="/Theme/Green/Weiquan/Static/js/weiquan.js"></script>
    <script>
                    weiquan.page = '<?php echo ($page); ?>';
                    weiquan.loadCount = 0;
                    weiquan.lastId = parseInt('<?php echo (reset($list)); ?>') + 1;
                    weiquan.url = "<?php echo ($loadMoreUrl); ?>";
                    weiquan.type = "<?php echo ($type); ?>";
                       
                    $(function () {
                        weiquan_bind();
                        bind_like();
                       // 当屏幕滚动到底部时
                    //    if (weiquan.page == 1) {
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



                        weiquan.loadNextPage();//初始化加载
                        //点击更多加载
                        $(document).on('click','._btn_load_more',function () {
                            if (weiquan.noMoreNextPage || weiquan.isLoadingWeibo) {
                                return;
                            }

                            if (weiquan.isLoadMoreVisible()) {
                                weiquan.loadNextPage();
                            }
                        });

                    });
    </script>


<div class="hidden">
	
</div>


	<!-- /底部 -->
</body>
</html>