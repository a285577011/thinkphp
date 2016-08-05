<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
    <link type="text/css" rel="stylesheet" href="/Theme/Green/Common/Static/css/love_ws/style.css">
    
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
<block name="header_css">
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
</block>

	<script src="/Theme/Green/Common/Static/js/partjs.js"></script>
</head>
<body>

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

<div class="banner container bs" id="myjQuery">
	<ul class="button" id="myjQueryNav">
		<li class="current"></li>
		<li></li>
		<li></li>
	</ul>
	<div class="imgbox" id="myjQueryContent">
		<?php if(is_array($advs)): $i = 0; $__LIST__ = $advs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div <?php if($key > 0): ?>class="smask"<?php endif; ?>>
			<img src="<?php echo ($vo["pic"]); ?>">
		</div><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
</div>

<div class="roll_box bs">
	<div class="ashuo left"></div>
	<h1 class="px14 left bold orange">i.cn说：</h1>
	<div class="roll left">
		<dl style="margin-top: 0px;">
			<?php if(is_array($isay_list)): $i = 0; $__LIST__ = $isay_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><dd><a href="<?php echo U('/Isay');?>" title="<?php echo ($vo["title"]); ?>" target="_blank"><?php echo ($vo["title"]); ?></a></dd><?php endforeach; endif; else: echo "" ;endif; ?>
		</dl>
	</div>
</div>

<div class="divide container">
	<div class="goods">
		<div class="title">
			<div class="sp left"></div>
			<h1 class="left bold white px16">商品微商</h1>
			<div class="chakan right">
				<a href="<?php echo U('/Home/Index/tag');?>" target="_blank" class="left white">查看更多<div class="ck1 right"></div></a>
			</div>
		</div>
		<div class="content">
		<?php if(is_array($category_list_goods)): $i = 0; $__LIST__ = $category_list_goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 8 );++$i; if(($mod) == "0"): ?><div class="su"><?php endif; ?>
            	<div class="shape <?php if(($vo["hot"]) == "1"): ?>seshape<?php endif; ?>">
					<a href="<?php echo U('Home/Index/tagdetail', array('catid'=>$vo[id]));?>" target="_blank"><?php echo ($vo["title"]); ?></a>
					<?php if(($vo["hot"]) == "1"): ?><div class="hot"></div><?php endif; ?>
				</div>
	        <?php if(($mod) == "7"): ?></div><?php endif; ?>
	        <?php if($i == $count): ?></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
		</div>
	</div>
	<div style="margin-left:16px" class="goods">
		<div class="title2">
			<div class="feisp left"></div>
			<h1 class="left white bold px16">非商品微商</h1>
			<div class="chakan2 right">
				<a href="<?php echo U('/Home/Index/tag', array('type'=>2));?>" target="_blank" class="left white ">查看更多<div class="ck2 right"></div></a>
			</div>
		</div>
		<div class="content">
		<?php if(is_array($category_list_not_goods)): $i = 0; $__LIST__ = $category_list_not_goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 8 );++$i; if(($mod) == "0"): ?><div class="su"><?php endif; ?>
            	<div class="shape <?php if(($vo["hot"]) == "1"): ?>seshape<?php endif; ?>">
					<a href="<?php echo U('Home/Index/tagdetail', array('catid'=>$vo[id]));?>" target="_blank"><?php echo ($vo["title"]); ?></a>
					<?php if(($vo["hot"]) == "1"): ?><div class="hot"></div><?php endif; ?>
				</div>
	        <?php if(($mod) == "7"): ?></div><?php endif; ?>
	        <?php if($i == $count): ?></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
		</div>
	</div>
</div>

<div class="new_box">
	<div class="biaoti2">
		<div class="zx">
		</div>
		<h1>最新微商</h1>
		<a href="<?php echo U('Home/Index/newest@');?>">查看更多
		<div class="ck3">
		</div>
		</a>
	</div>
	<div class="hotman2">
		<div class="heng">
			<?php if(is_array($member_newest)): $i = 0; $__LIST__ = $member_newest;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="section2">
				<a href="<?php echo U('@'.$vo[username]);?>" title="<?php echo ($vo["nickname"]); ?>" target="_blank"><img src="<?php echo ($vo["avatar"]); ?>" alt="<?php echo ($vo["nickname"]); ?>">
				<p>
				</p>
				</a>
				<a href="<?php echo U('@'.$vo[username]);?>" title="<?php echo ($vo["nickname"]); ?>" target="_blank">
				<h1>微信：<?php echo ($vo["nickname"]); ?></h1>
				</a>
				<h2><?php echo (date("Y-m-d H:i",$vo["reg_time"])); ?></h2>
				<a href="<?php echo U('Home/Index/tagdetail', array('catid'=>$vo[catid]));?>">
				<div class="xiangyin2">
					<?php echo ($vo["category"]); ?>
				</div>
				</a>
			</div><?php endforeach; endif; else: echo "" ;endif; ?>
			<div class="clear">
			</div>
		</div>
	</div>
</div>

<div class="hotman_box container clear bs">
	<div class="biaoti container">
		<div class="hr"></div>
		<h1>微商红人</h1>
		<a href="<?php echo U('Home/Index/hot/@');?>">查看更多<div class="ck4"></div></a>
	</div>
	<div class="hotman">
		<?php if(is_array($member_hot)): $i = 0; $__LIST__ = $member_hot;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 8 );++$i; if(($mod) == "0"): ?><div class="heng"><?php endif; ?>
			<div class="section">
				<a href="<?php echo U('@'.$vo[username]);?>" title="<?php echo ($vo["nickname"]); ?>" target="_blank"><img src="<?php echo ($vo["avatar"]); ?>" alt="<?php echo ($vo["nickname"]); ?>">
					<p></p>
				</a>
				<a href="<?php echo U('@'.$vo[username]);?>" title="<?php echo ($vo["nickname"]); ?>" target="_blank"><h1>微信：<?php echo ($vo["nickname"]); ?></h1></a>
				<a href="<?php echo U('@'.$vo[username]);?>" title="<?php echo ($vo["nickname"]); ?>" target="_blank"><h3>等级：</h3>
	        	<?php if(($vo["type"]) == "1"): ?><div class="hezi1">
              	 	<h2 class="dengji1"><i>Lv.<?php echo ($vo["level"]); ?></i></h2>
                </div>
                <?php else: ?>
				<div class="hezi2">
              	 	<h2 class="dengji2"><i>Lv.<?php echo ($vo["level"]); ?></i></h2>
                </div><?php endif; ?>
                </a>
                <a href="<?php echo U('Home/Index/tagdetail', array('catid'=>$vo[catid]));?>"><span class="xiangyin"><?php echo ($vo["category"]); ?></span></a>
			</div>
	    <?php if(($mod) == "7"): ?></div><?php endif; ?>
	    <?php if($i == $count): ?></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
		<div class="clear">
		</div>
	</div>
</div>

<div class="aipai_kuang">
	<div class="biaoti4">
		<div class="ag"></div>
		<h1>一元爱拍</h1>
		<a href="<?php echo U('/@1');?>" target="_blank">查看更多<div class="ck5"></div></a>
	</div>
	<div class="aipai_rongqi">
		<?php if(is_array($ipai_list)): $i = 0; $__LIST__ = $ipai_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="aipai">
			<div class="product">
				<a href="<?php echo U('/Index/details@1', array('id'=>$vo['id']));?>" target="_blank" title="（第<?php echo ($vo["periods"]); ?>期）<?php echo ($vo["productinfo"]["name"]); ?>">
					<div class="product_tu">
						<img src="<?php echo ($vo["productinfo"]["pic"]); ?>">
						<div class="huo_biao"></div>
					</div>
				</a>
				<div class="geren_xinxi">
					<a href="<?php echo U('@'.$vo[userinfo][username]);?>"><img src="<?php echo ($vo["userinfo"]["avatar"]); ?>"></a>
					<a href="<?php echo U('@'.$vo[userinfo][username]);?>"><h1>微信：<?php echo ($vo["userinfo"]["nickname"]); ?></h1></a>
					<a href="">
						<div class="dj_hezi">
							<h3>等级：</h3>
							<h2 class="dj1"><i>Lv.<?php echo ($vo["userinfo"]["level"]); ?></i></h2></div>
					</a>
					<a href="<?php echo U('Home/Index/tagdetail', array('catid'=>$vo[catid]));?>"><span class="xying"><?php echo ($vo["userinfo"]["category"]); ?></span></a>
				</div>

			</div>
			<h5>（第<?php echo ($vo["periods"]); ?>期）<?php echo ($vo["productinfo"]["name"]); ?></h5>
			<div class="sjtiao">
				<div class="sjtiao_xian html5" style="width:<?php echo ($vo['join_num']*100/$vo['need_num']); ?>%"></div>
			</div>
			<h4>总需：<?php echo ($vo["need_num"]); ?>人次</h4>
			<div class="yicanyu">
				<h1><?php echo ($vo["join_num"]); ?></h1>
				<h2>以参与人次</h2>
			</div>
			<div class="shengyu">
				<h1><?php echo ($vo['need_num']-$vo['join_num']); ?></h1>
				<h2>剩余人数</h2>
			</div>
			<a href="<?php echo U('/Order/Join@1', array('id'=>$vo['id']));?>" target="_blank">
				<p class="">立即参与</p>
			</a>
		</div><?php endforeach; endif; else: echo "" ;endif; ?>
	</div>
</div>

<div class="xueyuan_kuang">
	<div class="biaoti3">
		<div class="xy">
		</div>
		<h1>微商学院</h1>
		<a href="">查看更多
		<div class="ck6">
		</div>
		</a>
	</div>
	<div class="xinxi">
		<div class="zuobian">
			<ul class="qiehuan">
				<li><a>微商干货</a></li>
				<li><a>微商段子</a></li>
				<li><a>微商学院</a></li>
				<li><a href="">一手货源</a></li>
			</ul>
			<div class="qie_xinxi">
				<div class="suxinxi">
					<div class="data">
						<div class="rilibox">
							<div class="rili">
								<div class="shang_rili">
									25
								</div>
								<div class="xia_rili">
									2015-12
								</div>
							</div>
						</div>
						<div class="data2">
							<a href="">大数据征信成撬动消费金融的支点</a>
							<p>
								如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
							</p>
						</div>
					</div>
					<div class="changxian">
					</div>
					<div class="data">
						<div class="rilibox">
							<div class="rili">
								<div class="shang_rili">
									25
								</div>
								<div class="xia_rili">
									2015-12
								</div>
							</div>
							< </div>
							<div class="data2">
								<a href="">大数据征信成撬动消费金融的支点</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
						<div class="data">
							<div class="rilibox">
								<div class="rili">
									<div class="shang_rili">
										25
									</div>
									<div class="xia_rili">
										2015-12
									</div>
								</div>
							</div>
							<div class="data2">
								<a href="">大数据征信成撬动消费金融的支点</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
					</div>
					<div class="suxinxi">
						<div style="margin-left:50px" class="data">
							<div class="rilibox">
								<div class="rili">
									<div class="shang_rili">
										25
									</div>
									<div class="xia_rili">
										2015-12
									</div>
								</div>
							</div>
							<div class="data2">
								<a href="">大数据征信成撬动消费金融的支点</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
						<div style="margin-left:50px" class="data">
							<div class="rilibox">
								<div class="rili">
									<div class="shang_rili">
										25
									</div>
									<div class="xia_rili">
										2015-12
									</div>
								</div>
							</div>
							<div class="data2">
								<a href="">大数据征信成撬动消费金融的支点</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
						<div style="margin-left:50px" class="data">
							<div class="rilibox">
								<div class="rili">
									<div class="shang_rili">
										25
									</div>
									<div class="xia_rili">
										2015-12
									</div>
								</div>
							</div>
							<div class="data2">
								<a href="">大数据征信成撬动消费金融的支点</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
					</div>
				</div>
				<div class="qie_xinxi">
					<div class="suxinxi">
						<div class="data">
							<div class="data2">
								<a href="">水井坊的交房的附近发 </a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
						<div class="data">
							<div class="data2">
								<a href="">水井坊的交房的附近发</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
						<div class="data">
							<div class="data2">
								<a href="">水井坊的交房的附近发</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
					</div>
					<div class="suxinxi">
						<div style="margin-left:50px" class="data">
							<div class="data2">
								<a href="">水井坊的交房的附近发支点</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
						<div style="margin-left:50px" class="data">
							<div class="data2">
								<a href="">水井坊的交房的附近发</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
						<div style="margin-left:50px" class="data">
							<div class="data2">
								<a href="">水井坊的交房的附近发</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
					</div>
				</div>
				<div class="qie_xinxi">
					<div class="suxinxi">
						<div class="data">
							<div class="data2">
								<a href="">哈哈哈哈哈啊哈哈</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
						<div class="data">
							<div class="data2">
								<a href="">哈哈哈哈哈啊哈哈</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
						<div class="data">
							<div class="data2">
								<a href="">哈哈哈哈哈啊哈哈支点</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
					</div>
					<div class="suxinxi">
						<div style="margin-left:50px" class="data">
							<div class="data2">
								<a href="">哈哈哈哈哈啊哈哈</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
						<div style="margin-left:50px" class="data">
							<div class="data2">
								<a href="">哈哈哈哈哈啊哈哈</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
						<div style="margin-left:50px" class="data">
							<div class="data2">
								<a href="">哈哈哈哈哈啊哈哈</a>
								<p>
									如果说要近日整个互联网金融领域什么最火，相信必然非消费金额莫属。
								</p>
							</div>
						</div>
						<div class="changxian">
						</div>
					</div>
				</div>
			</div>
			<div class="sutiao">
			</div>
			<div class="youbian">
				<div class="zixun">
					<div class="diqiu">
					</div>
					<h2 class="px14">微商咨询</h2>
				</div>
				<div class="tiao">
					<div class="squre">
					</div>
					<a href="">微商平台香港主机终结袭</a>
				</div>
				<div class="tiao">
					<div class="squre">
					</div>
					<a href="">微商平台香港主机终结者强势来袭</a>
				</div>
				<div class="tiao">
					<div class="squre">
					</div>
					<a href="">微商平台香港主机终结者强势来袭</a>
				</div>
				<div class="tiao">
					<div class="squre">
					</div>
					<a href="">微商平台香港主机终结者强势来袭</a>
				</div>
				<div class="tiao">
					<div class="squre">
					</div>
					<a href="">微商平台香港主机终结者强势来袭</a>
				</div>
				<div class="tiao">
					<div class="squre">
					</div>
					<a href="">微商平台香港主机终结者强势来袭</a>
				</div>
				<div class="tiao">
					<div class="squre">
					</div>
					<a href="">微商平台香港主机终结者强势来袭</a>
				</div>
				<div class="tiao">
					<div class="squre">
					</div>
					<a href="">微商平台香港主机终结者强势来袭</a>
				</div>
				<div class="tiao">
					<div class="squre">
					</div>
					<a href="">微商平台香港主机终结者强势来袭</a>
				</div>
			</div>
		</div>
	</div>


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

<block name="footer_script">
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
</block>

</body>
</html>