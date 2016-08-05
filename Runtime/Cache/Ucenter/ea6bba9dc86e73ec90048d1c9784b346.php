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
<link type="text/css" rel="stylesheet" href="/Theme/Green/Common/Static/css/common.css">


 
<script src="/Public/js.php?f=js/jquery-1.9.1.js"></script> 

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
    <div class="personal_center container bs">
        <!--左边菜单-->
        <div class="data_menu left">
    <div class="id_set">
        <div class="set_title">
            <h1 style="border:none" class="id_name left px16">账号设置</h1>
            <div class="set_logo right set_logo2"></div>
            <div class="clear"></div>
        </div>
        <div class="data_down">
            <a href="<?php echo U('Ucenter/Config/index');?>"><h2 <?php if(ACTION_NAME == 'index'): ?>class="basic_data clear menu_style"<?php endif; ?>>基本资料</h2></a>
            <a href="<?php echo U('Ucenter/Config/avatar');?>"> <h2 <?php if(ACTION_NAME == 'avatar'): ?>class="basic_data clear menu_style"<?php endif; ?>>修改头像</h2></a>
            <a href="<?php echo U('Ucenter/Config/accountManagemen');?>"> <h2 <?php if(ACTION_NAME == 'accountmanagemen'): ?>class="basic_data clear menu_style"<?php endif; ?>>账号管理</h2></a>
            <a href="<?php echo U('Ucenter/Address/items');?>"><h2 <?php if(ACTION_NAME == 'items'): ?>class="basic_data clear menu_style"<?php endif; ?>>地址管理</h2></a>
            <div class="clear"></div>
        </div>
        <div class="set_title">
            <h1 class="aipai px16 left">一元爱拍</h1>
            <div class="set_logo right"></div>
            <div class="clear"></div>
        </div>
        <div class="data_down">
            <a href="<?php echo U('Ucenter/Ipai/myIpai');?>"><h2 class=" clear">发起记录</h2></a>
            <a href="<?php echo U('Ucenter/Ipai/myParticipate');?>"> <h2 class="">参与记录</h2></a>
            <a href="<?php echo U('Ucenter/Ipai/myWinning');?>"><h2 class="">中奖纪录</h2></a>
            <a href="<?php echo U('Ucenter/Ipai/authRealName');?>"><h2 class="">认证一元爱拍</h2></a>
            <a href=""><div class="clear"></div>
            </a></div><a href="">
        </a><a href=""><h1 class="approve_goods px16">认证一手货源</h1></a>
        <a href=""><h1 class="money_magage px16">财务管理</h1></a>
        <a href=""><h1 class="my_message px16">我的消息</h1></a>
        <div class="set_title">
            <h1 class="rights px16 left">维权管理</h1>
            <div class="set_logo right"></div>
            <div class="clear"></div>
        </div>
        <div class="data_down hide">
            <a href=""><h2 class=" clear">我的投诉</h2></a>
            <a href=""> <h2 class="">我的反馈</h2></a>       
            <a href=""><div class="clear"></div>
            </a></div><a href="">
            <div class="clear"></div> 
        </a>

    </div>
    <a href="">
        <div class="clear"></div>  
    </a>
</div>
        <!--  右边内容-->
        <div class="personal_body right">
            <form action="<?php echo U('Ucenter/Config/Index');?>" method="post">
                <div class="right_title">
                    <h2 class="px16 "><?php echo L('_BASE_INFO_');?></h2>
                </div>
                <!-- 不一样的地方这里开始-->
                <div class="complete_box">
                    <div class="left_secton left">
                        <p class="right"><?php echo L('_INFO_FULL_PROCESS_');?>：</p>
                    </div>
                    <div class="right_secton left">
                        <div class="complete_img left">
                            <div style="width:<?php echo ($user["step"]); ?>%" class="complete_img2"></div>
                        </div>
                        <p class="clete_amount orange left px18"><?php echo ($user["step"]); ?>%</p>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="form_section clear">
                    <div class="left_secton left"><label for="we-chat">
                            <p class="right"><?php echo L('_WEIXIN_ACCOUNT_');?>：</p>
                            <div class="xin right"></div>
                        </label></div>
                    <div class="right_secton left">
                        <input type="text" name="weixin" id="we-chat" class="we-chat cl50" value='<?php echo ($user["weixin"]); ?>'>
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="wechat_name">
                            <p class="right"><?php echo L('_WEIXIN_NAME_');?>：</p>
                            <div class="xin right"></div>
                        </label></div>
                    <div class="right_secton left">
                        <input type="text" name="nickname"  class="we-chat cl50" value="<?php echo (op_t($user["nickname"])); ?>">
                    </div>
                    <div class="clear"></div>
                </div>
               <?php if(false): ?><div class="form_section">
                    <div class="left_secton left"><label for="telephone">
                            <p class="right"><?php echo L('_MOBILE_NUM_');?>：</p>
                            <div class="xin right"></div>
                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="telephone" name="mobile" disabled="disabled" value="<?php echo ($user["mobile"]); ?>" class="telephone cl50 left">
                        <input type="checkbox" class="left" name="mobile_show"  id="edit_phone" value="1" <?php if(($field_show["mobile"]) == "1"): ?>checked<?php endif; ?> ><label for="edit_phone"></label>
                        <a href=""><span class="alter_telephone px12 orange "><?php echo L('_CHANGE_MOBILE_');?></span></a>
                    </div>

                    <div class="clear"></div>
                </div><?php endif; ?>
                <div class="form_section">
                    <div class="left_secton left">
                        <p class="right"><?php echo L('_WEISHOP_TYPE_');?>：</p>
                        <div class="xin right"></div>
                    </div>
                    <div class="right_secton left">
                        <p class="left"><input type="radio" id="sp_wechat" class="sp_wechat left" name="wechat_type"  value="1"  <?php if(($user["category"]["type"]) == "1"): ?>checked<?php endif; ?>><label for="sp_wechat"><?php echo L('_WEI_BUSINESS_');?></label></p>
                        <p class="left"><input type="radio" id="nsp_wechat" class="nsp_wechat" name="wechat_type" value="2"  <?php if(($user["category"]["type"]) == "2"): ?>checked<?php endif; ?>><label for="nsp_wechat"><?php echo L('_NO_WEI_BUSINESS_');?></label></p>
                        <span class="tip_img left"></span>
                        <span class="tip px12 left"><?php echo L('_CHANGE_WEI_TYPE_DESC');?></span>
                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="wechat_key">
                            <p class="right"><?php echo L('_PUT_MAIN_KEY_');?>：</p>
                            <div class="xin right"></div>
                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="wechat_key" name="cate_title" value="<?php echo ($user["category"]["title"]); ?>" class="wechat_key cl50 left">
                        <span class="tip_img left"></span>
                        <span class="tip px12 left"><?php echo L('_CHANGE_MAIN_KEY_DESC');?></span>

                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="else_key">
                            <p class="right"><?php echo L('_PUT_OTHER_KEY_');?>：</p>
                            <div class="xin right"></div>
                        </label></div>
                    <div class="right_secton left" style="display:none;">
                        <?php $tag=''; foreach($user['tag'] as $v) { $tag.=empty($tag)?$v['title']:(','.$v['title']); } ?>
                        <input type="text" id="else_key" class="else_key cl50 left px12" name="user_tag" value="<?php echo ($tag); ?>" placeholder="<?php echo L('_PUT_OTHER_KEY_PLACEHOLDER_');?>">

                    </div>
            <div class="right_secton left">
            	<input type="text" id="else_key" class="else_key cl50 left px12" name="user_tag[]" value="<?php echo ($user['tag'][0]['title']); ?>"/>
    			<input type="text" class="else_key cl50 left px12" name="user_tag[]" value="<?php echo ($user['tag'][1]['title']); ?>"/><br/>
    			<input type="text" class="else_key cl50 left px12" name="user_tag[]" value="<?php echo ($user['tag'][2]['title']); ?>"/>
    			<input type="text" class="else_key cl50 left px12" name="user_tag[]" value="<?php echo ($user['tag'][3]['title']); ?>"/>
            </div>
                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left">
                        <p class="right"><?php echo L('_IDENTITY_MARK_');?>：</p>
                        <div class="xin right"></div>
                    </div>
                    <div class="right_secton left">
                        <span class="grade back_orange white px12 left"><i>Lv<?php echo ($user["level"]); ?></i></span>
                        <div class="yiyuan left"></div>
                        <div class="huo left"></div>
                        <div class="left"><a class="orange upgrade" href="">升级攻略</a></div>
                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section">
                    <div class="left_secton left">
                        <p class="right"><?php echo L('_SEX_');?>：</p>
                        <div class="xin right"></div>
                    </div>
                    <div class="right_secton left">
                        <p class="left"><input type="radio" id="man" class="man left" name="sex" value="1"  <?php if(($user["sex"]) == "1"): ?>checked<?php endif; ?>><label for="man"><?php echo L('_MAN_');?></label></p>
                        <p class="left"><input type="radio" id="woman" class="woman" name="sex" value="2"  <?php if(($user["sex"]) == "2"): ?>checked<?php endif; ?>><label for="woman"><?php echo L('_WOMAN_');?></label></p>
                        <p class="left"><input type="radio" id="secrecy" class="woman" name="sex" value="0"  <?php if(($user["sex"]) == "0"): ?>checked<?php endif; ?>><label for="secrecy"><?php echo L('_SECRECY_');?></label></p>
                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section">
                    <div class="left_secton left"><label for="signature">
                            <p class="right"><?php echo L('_DIY_SIGNATURE_');?>：</p>

                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="signature" class="signature cl50 left px12" name="signature" value="<?php echo (htmlspecialchars($user["signature"])); ?>" placeholder="<?php echo L('_DIY_SIGNATURE_PLACEHOLDER_');?>">
                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section">
                    <div class="left_secton left">
                        <p class="right"><?php echo L('_NOW_ADDRESS_');?>：</p>

                    </div>
                    <div class="right_secton left">
                        <fieldset id="city_china" style="border: none;">
                            <select class="province" id="province"  name="pos_province"></select>
                            <select class="city" id="city"  name="pos_city" style="display:none;"></select>
                            <select class="area" id="area"  name="pos_district" style="display:none;"></select>
                        </fieldset>
                        <input type="text" class="detail_position cl50 px12" name="field_9" id="detail_position" value="<?php echo ($user_ext[9]['value']['field_data']); ?>" placeholder="<?php echo L('_INPUT_FULL_ADDRESS_PLACEHOLDER_');?>">
                        <script type="text/javascript">
                            $(function(){
                            var pid = "<?php if($user["pos_province"] != ''): echo ($user["pos_province"]); else: ?>0<?php endif; ?>"; // 默认省份id
                            var cid = "<?php if($user["pos_city"] != ''): echo ($user["pos_city"]); else: ?>0<?php endif; ?>"; // 默认城市id
                            var did = "<?php if($user["pos_district"] != ''): echo ($user["pos_district"]); else: ?>0<?php endif; ?>"; // 默认区县市id
                           
                            function change_province(pid){
                            $.post("<?php echo U('Home/Common/getProvince@');?>", {pid: pid}, function(result){
                            $("#province").html(result);
                            });
                            }

                            function change_city(p_pid, p_cid){
                            $.post('<?php echo U("Home/Common/getCity@");?>', {pid: p_pid, cid: p_cid}, function(result){
                            $("#city").show().html(result);
                            });
                            }
                            function change_district(p_cid, p_did){
                            $.post('<?php echo U("Home/Common/getDistrict@");?>', {cid: p_cid, did: p_did}, function(result){
                            $("#area").show().html(result);
                            });
                            }

                            change_province(pid);
                            change_city(pid, cid);
                            change_district(cid, did);
                            $('#province').change(function(){
                            var pid_g = $(this).children('option:selected').val();
                            change_city(pid_g)
                                    change_district(0);
                            });
                            $('#city').change(function(){
                            var cid_g = $(this).children('option:selected').val();
                            change_district(cid_g)


                            });
                            $('#area').change(function(){
                            var did_g = $(this).children('option:selected').val();
                            });
                            });
                            /*修复联动不及时的bug，陈一枭end*/
                        </script>
                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="personal_domain">
                            <p class="right"><?php echo L('_SPREAD_URL_');?>：</p>

                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="personal_domain" disabled="disabled" value="<?php echo U('/@'.$user['username']);?>" class="personal_domain cl50 left">
                        <input type="checkbox" class="left" id="selecting1" value="1" name="show_sread_url" <?php if(($field_show["sread_url"]) == "1"): ?>checked<?php endif; ?> ><label for="selecting1"></label>
                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="promote">
                            <p class="right"><?php echo L('_DIY_MY_DOMAIN_');?>：</p>

                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="promote" disabled="disabled" class="promote cl50 left" value="<?php echo U('/@'.$user['username'].'/13088888888');?>" />
                        <input type="checkbox" class="left" id="selecting2" name="show_diy_domain" value="1"  <?php if(($field_show["diy_domain"]) == "1"): ?>checked<?php endif; ?> ><label for="selecting2"></label>

                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="qq">
                            <p class="right"><?php echo ($user_ext[1]['field_name']); ?>：</p>

                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="qq" class="qq cl50 left" name="field_1" value="<?php echo ($user_ext[1]['value']['field_data']); ?>" />                    
                        <input type="checkbox" class="left" id="selecting3" name="show_field_1" value="1" <?php if(($field_show["field_1"]) == "1"): ?>checked<?php endif; ?> ><label for="selecting3"></label>

                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="webo">
                            <p class="right"><?php echo ($user_ext[10]['field_name']); ?>：</p>

                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="webo" class="webo cl50 left" name="field_10" value="<?php echo ($user_ext[10]['value']['field_data']); ?>" />
                        <input type="checkbox" class="left" id="selecting4" name="show_field_10" value="1"  <?php if(($field_show["field_10"]) == "1"): ?>checked<?php endif; ?> ><label for="selecting4"></label>

                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="momo">
                            <p class="right"><?php echo ($user_ext[11]['field_name']); ?>：</p>

                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="momo" class="momo cl50 left" name="field_11" value="<?php echo ($user_ext[11]['value']['field_data']); ?>" />
                        <input type="checkbox" class="left" id="selecting5" name="show_field_11" value="1" <?php if(($field_show["field_11"]) == "1"): ?>checked<?php endif; ?> ><label for="selecting5"></label>

                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="e-mail">
                            <p class="right"><?php echo ($user_ext[12]['field_name']); ?>：</p>

                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="e-mail" class="e-mail cl50 left" name="field_12" value="<?php echo ($user_ext[12]['value']['field_data']); ?>" />
                        <input type="checkbox" class="left" id="selecting6" name="show_field_12" value="1" <?php if(($field_show["field_12"]) == "1"): ?>checked<?php endif; ?> ><label for="selecting6"></label>

                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="business">
                            <p class="right"><?php echo ($user_ext[13]['field_name']); ?>：</p>

                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="business" class="business cl50 left" name="field_13" value="<?php echo ($user_ext[13]['value']['field_data']); ?>" />
                        <input type="checkbox" class="left" id="selecting7" name="show_field_13" value="1"  <?php if(($field_show["field_13"]) == "1"): ?>checked<?php endif; ?> ><label for="selecting7"></label>

                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="profession">
                            <p class="right"><?php echo ($user_ext[14]['field_name']); ?>：</p>

                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="profession" class="profession cl50 left" name="field_14" value="<?php echo ($user_ext[14]['value']['field_data']); ?>" />
                        <input type="checkbox" class="left" id="selecting8" name="show_field_14" value="1" <?php if(($field_show["field_14"]) == "1"): ?>checked<?php endif; ?> ><label for="selecting8"></label>

                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="speciality">
                            <p class="right"><?php echo ($user_ext[15]['field_name']); ?>：</p>

                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="speciality" class="speciality cl50 left" name="field_15" value="<?php echo ($user_ext[15]['value']['field_data']); ?>" />
                        <input type="checkbox" class="left" id="selecting9" name="show_field_15" value="1"  <?php if(($field_show["field_15"]) == "1"): ?>checked<?php endif; ?> ><label for="selecting9"></label>

                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="interests">
                            <p class="right"><?php echo ($user_ext[16]['field_name']); ?>：</p>

                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="interests" class="interests cl50 left" name="field_16" value="<?php echo ($user_ext[16]['value']['field_data']); ?>" />
                        <input type="checkbox" class="left" id="selecting10" name="show_field_16" value="1"  <?php if(($field_show["field_16"]) == "1"): ?>checked<?php endif; ?> ><label for="selecting10"></label>

                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="wechat_url">
                            <p class="right"><?php echo ($user_ext[17]['field_name']); ?>：</p>

                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="wechat_url" class="wechat_url cl50 left" name="field_17" value="<?php echo ($user_ext[17]['value']['field_data']); ?>" />
                    </div>

                    <div class="clear"></div>
                </div>
                <div class="form_section ">
                    <div class="left_secton left"><label for="wechat_address">
                            <p class="right"><?php echo ($user_ext[18]['field_name']); ?>：</p>
                        </label></div>
                    <div class="right_secton left">
                        <input type="text" id="wechat_address" class="wechat_address cl50 left" name="field_18" value="<?php echo ($user_ext[18]['value']['field_data']); ?>" />
                    </div>

                    <div class="clear"></div>
                </div>
                <div class="remind_box clear px12">
                    <span class="remind_xin"></span>
                    <span>为必填选项，不可放空。</span>
                    <span class="remind_velidation"></span>
                    <span>勾选即可让更多志同道合的人了解您</span><br>
                    <input type="submit" value="<?php echo L('_SAVE_');?>" class="personal_sure ">
                    <div class="clear"></div>
                </div> 
            </form>
            <!-- 不一样的地方这里结束-->    
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
    </div>
    <link type="text/css" rel="stylesheet" href="/Theme/Green/Ucenter/Static/css/personal_center.css">

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


<!-- 用于加载js代码 -->
<script>
    $(window).resize(adjust_navbar).resize()

</script>
<!-- 为了让html5shiv生效，请将所有的CSS都添加到此处 -->
<link type="text/css" rel="stylesheet"  href="/Public/static/qtip/jquery.qtip.css" />
<link type="text/css" rel="stylesheet"  href="/Public/js/ext/layer/skin/layer.css" />


<!--广告-->
<!--<script type='text/javascript' src=''></script>-->
<!--广告js-->

<script src="/Public/js.php?t=js&f=js/com/com.notify.class.js,js/com/com.toast.class.js,js/com/com.functions.js,static/qtip/jquery.qtip.js,js/ext/slimscroll/jquery.slimscroll.min.js,js/ext/magnific/jquery.magnific-popup.min.js,js/ext/placeholder/placeholder.js,js/ext/lazyload/lazyload.js,js/ext/layer/layer.js&v=<?php echo ($site["sys_version"]); ?>.js"></script>
<script type="text/javascript" src="/Public/static/jquery.iframe-transport.js"></script>

<script>
    $(function () {
        if (!document.getElementById('sub_nav')) {
            $('#main-container').css('margin-top', 70 + 'px');
        }

    })
</script>
<script type="text/javascript" src="/Public/js/bootstrap.min.js"></script>
<script type="text/javascript" src="/Theme/Green/Common/Static/js/common.js"></script>
<link rel="stylesheet" href="/Public/css/bootstrap.min.css" />


<div class="hidden">
	
</div>


	<!-- /底部 -->
</body>
</html>