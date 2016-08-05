<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
	
	<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php $oneplus_seo_meta = get_seo_meta($vars,$seo); ?>
<?php if($oneplus_seo_meta['title']): ?><title><?php echo ($oneplus_seo_meta['title']); ?></title>
<?php else: ?>
<title><?php echo modC('WEB_SITE_NAME','I.CN 爱微商','Config');?></title><?php endif; ?>
<?php if($oneplus_seo_meta['keywords']): ?><meta name="keywords" content="<?php echo ($oneplus_seo_meta['keywords']); ?>" /><?php endif; ?>
<?php if($oneplus_seo_meta['description']): ?><meta name="description" content="<?php echo ($oneplus_seo_meta['description']); ?>" /><?php endif; ?>

    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/reseting.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/common.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/page.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/love_ws/list_style.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/sprites.css" />

 
<script src="/Public/js.php?f=js/jquery-1.9.1.js"></script> 

<script>
    //全局内容的定义
    var _ROOT_ = "";
    var MID = "<?php echo is_login();?>";
    var MODULE_NAME="<?php echo MODULE_NAME; ?>";
    var ACTION_NAME="<?php echo ACTION_NAME; ?>";
    var CONTROLLER_NAME ="<?php echo CONTROLLER_NAME; ?>";
    var initNum = "<?php echo modC('WEIBO_NUM',140,'WEIBO');?>";
    function adjust_navbar(){
        $('#sub_nav').css('top',$('#nav_bar').height());
        $('#main-container').css('padding-top',$('#nav_bar').height()+$('#sub_nav').height()+20)
    }
</script>


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
	
	<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<?php $oneplus_seo_meta = get_seo_meta($vars,$seo); ?>
<?php if($oneplus_seo_meta['title']): ?><title><?php echo ($oneplus_seo_meta['title']); ?></title>
<?php else: ?>
<title><?php echo modC('WEB_SITE_NAME','I.CN 爱微商','Config');?></title><?php endif; ?>
<?php if($oneplus_seo_meta['keywords']): ?><meta name="keywords" content="<?php echo ($oneplus_seo_meta['keywords']); ?>" /><?php endif; ?>
<?php if($oneplus_seo_meta['description']): ?><meta name="description" content="<?php echo ($oneplus_seo_meta['description']); ?>" /><?php endif; ?>

    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/reseting.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/common.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/page.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/love_ws/list_style.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/sprites.css" />

 
<script src="/Public/js.php?f=js/jquery-1.9.1.js"></script> 

<script>
    //全局内容的定义
    var _ROOT_ = "";
    var MID = "<?php echo is_login();?>";
    var MODULE_NAME="<?php echo MODULE_NAME; ?>";
    var ACTION_NAME="<?php echo ACTION_NAME; ?>";
    var CONTROLLER_NAME ="<?php echo CONTROLLER_NAME; ?>";
    var initNum = "<?php echo modC('WEIBO_NUM',140,'WEIBO');?>";
    function adjust_navbar(){
        $('#sub_nav').css('top',$('#nav_bar').height());
        $('#main-container').css('padding-top',$('#nav_bar').height()+$('#sub_nav').height()+20)
    }
</script>



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

<div class="paixu_kuang">
	<div class="title2">
    	<h1 class="px14">首页</h1>
        <div class="jiantou2"></div>
        <h1 class="px14">搜索结果页</h1>
    </div>
    <div class="quanbu2">
    	<h2 class="px14">您搜索的关键字：“<span><?php echo ($keyword); ?></span>”i.cn共为您搜索到<span><?php echo ($total); ?></span>个</h2>
        
       
    </div>
    
    <div class="precision">
    	<div class="exact left ">
        	<div class="exact_box2 hide ">
        		<?php if(is_array($type_list)): $i = 0; $__LIST__ = $type_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo ($vo["url"]); ?>"><p><?php echo ($vo["name"]); ?>&nbsp;<em></em></p></a><?php endforeach; endif; else: echo "" ;endif; ?>
            <div class="clear"></div>
        </div>
        <span class="left">精确查找</span>
        <div class="sanjiao"></div>
        </div>
        <div class="nationwide left">
        <div class="cgcityboxs" style="display: none;">
			<div class="nowcity">
				<div class="left backnation"><a href="<?php echo U('', array('city'=>0));?>">返回全国</a></div>
				<div class="left nowarea px14">
					<span>本地：</span>
					<span id="citynm" class="orange">厦门</span>
				</div>
				<div class="clear"></div>
			</div>
			<div class="shcitybox px14">
				<div class="left bold clear">搜索城市：</div>
				<div class="left selfsh">
					<input type="text" id="cityChoice" class="shinfo" placeholder="查询中国任意城市">
					<button class="ensure">确定</button>
				</div>
				<div class="clear"></div>
			</div>
			<div class="hotcity px14">
				<div class="hot_city left bold">热点城市：</div>
				<div class="left gyline"></div>
				<div class="clear"></div>
			</div>
			<div class="hotcityname">
				<?php if(is_array($area_list["hot"])): $i = 0; $__LIST__ = $area_list["hot"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><span data-city="<?php echo ($vo["id"]); ?>" data-role="city"><a href="<?php echo U('', array('city'=>$vo['id']));?>"><?php echo ($vo["name"]); ?></a></span><?php endforeach; endif; else: echo "" ;endif; ?>
			</div>
			<div class="provincesbox px14">
				<div class="order_province left bold clear ">按省份查询：</div>
				<div style="margin-left: 7px;" class="left gyline"></div>
				<div class="clear"></div>
			</div>
			<div class="provincesname px12">
				<div class="probox">
			        <?php $province_count=count($area_list['city']); ?>
					<?php if(is_array($area_list["city"])): $ck = 0; $__LIST__ = $area_list["city"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($ck % 13 );++$ck; if(($mod) == "0"): ?><div style="margin-top: 10px;" class="pro_tab"><div class="pro_menu pro_menu1"><ul><?php endif; ?>
								<li><?php echo ($vo["name"]); ?></li>
			        <?php if(($mod) == "12"): ?></ul><div class="clear"></div></div></div><?php endif; ?>
			        <?php if($ck == $province_count): ?></ul><div class="clear"></div></div></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
					<div class="clear"></div>
				</div>
			</div>

		</div>
        	<span class="wides">全国</span>
            <div class="sanjiao"></div>
        </div>
        <div class="sex">
        	<span>性别</span>
            	<div class="sex_box hide">
            		<a href="<?php echo U('Home/Index/search');?>"><p>不限</p></a>
            		<?php if(is_array($sex_list)): $i = 0; $__LIST__ = $sex_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo ($vo["url"]); ?>"><p><?php echo ($vo["name"]); ?></p></a><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
            <div class="sanjiao"></div>
        </div>
        <div class="rank">
        	<div class="rank_box hide">
          		<?php if(is_array($sort_list)): $i = 0; $__LIST__ = $sort_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><a href="<?php echo ($vo["url"]); ?>"><p><?php echo ($vo["name"]); ?></p></a><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        	<span>智能排序</span>
            <div class="sanjiao"></div>
        </div>
        <div class="page_group2 right">
        	<span><?php echo ($now_page); ?>/<?php echo ($total_page); ?></span>
            <?php echo ($page_top); ?>
        </div>
    </div>
</div>

<div class="hotman_box clear">
	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="hotman left">
		<div class="hotman_on left">
			<div class="hotman_img left">
				<a href="<?php echo U('/@'.$vo['username']);?>" target="_blank"><img src="<?php echo ($vo["avatar"]); ?>" alt="<?php echo ($vo["nickname"]); ?>"/></a>
			</div>
			<div class="hotman_data left cl50">
				<div class="hotman_name">
					<a href="<?php echo U('/@'.$vo['username']);?>"><p class="cl50 left">微信：<span><?php echo ($vo["nickname"]); ?></span></p></a>
					<?php if($vo['sex'] == 1): ?><div class="hotman_sex left"></div><?php elseif($vo['sex'] == 2): ?><div class="hotman_sex2 left"></div><?php endif; ?>
				</div>
				<?php if($vo['is_following'] AND $vo['is_followed']): ?><div class="attention_img2 right hand" data-follow-who="<?php echo ($vo["uid"]); ?>" data-role="unfollow"></div>
				<?php elseif($vo['is_following']): ?>
				<div class="attention_img3 right hand" data-follow-who="<?php echo ($vo["uid"]); ?>" data-role="unfollow"></div>
				<?php else: ?>
				<div class="attention_img right hand" data-follow-who="<?php echo ($vo["uid"]); ?>" data-role="follow"></div><?php endif; ?>
				<p class="hotman_type clear">
					商品类型：<span><?php if($vo['type'] == 2): ?>非商品微商<?php else: ?>商品微商<?php endif; ?></span>
				</p>
				<p class="hotman-position">
					地区：<span><?php echo ($vo["province"]); ?>&nbsp;<?php echo ($vo["city"]); ?></span>
				</p>
				<div class="hotman_grade left">
					<a href=""><p class="cl50 left">等级：</p><span class="hotman_lv px12 left"><i>Lv.<?php echo ($vo["level"]); ?></i></span></a>
					<?php if($vo['ipai'] == 1): ?><a href=""><div class="hotman_yiyuan left"></div></a><?php endif; ?>
				</div>
				<a href="<?php echo ($domain); ?>">
				<p class="hotman_domain cl50 clear">
					域名：<span><?php echo U('/@'.$vo['username']);?></span>
				</p>
				</a>
				<p class="hotman_signal">
					签名：<span><?php echo ($vo["signature"]); ?></span>
				</p>
			</div>
		</div>
		<ul class="key_box clear px12 ">
			<a href="<?php echo U('Home/Index/tagdetail@', array('catid'=>$vo['catid']));?>" target="_blank"><li class="key_important"><?php echo ($vo["category"]); ?></li></a>
			<?php if($vo['tags'] != ''): if(is_array($vo["tags"])): $i = 0; $__LIST__ = $vo["tags"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vt): $mod = ($i % 2 );++$i; if($i >= 5): break; endif; ?>
           	<a href="<?php echo U('Home/Index/tagdetail@', array('tagid'=>$vt['id']));?>" target="_blank"><li class="key"><?php echo ($vt["title"]); ?></li></a><?php endforeach; endif; else: echo "" ;endif; endif; ?>
		</ul>
		<div class="clear">
		</div>
		<ul class="hotman_down">
			<li class="attentions">关注<span class="cl50">&nbsp;<?php echo ($vo["following"]); ?></span></li>
			<li class="attentions">粉丝<span class="cl50">&nbsp;<?php echo ($vo["fans"]); ?></span></li>
			<li style="border:none" class="attentions">动态<span class="cl50">&nbsp;0</span></li>
		</ul>
		<div class="clear">
		</div>
	</div><?php endforeach; endif; else: echo "" ;endif; ?>
</div>

<?php if($page != ''): ?><div class="hot_butgroup container">
	<div class="page_group1 clear ">
	<?php echo ($page); ?>
	</div>
	<div class="clear"></div>
</div><?php endif; ?>

<script type="text/javascript" src="/Theme/Green/Common/Static/js/partjs.js"></script>
<script>
$(function(){
		$("#btnSave").click(function(){
			var div = "<div style='width:57px;height:80px;background:#FFF;cursor:pointer'></div>";//定义DIV的样式
			$("body").append(div);//需要添加在哪个位置
		});

		/*	列表下拉按钮	*/
		$(".read-more").mouseenter(function(){
		$(".read-more div").addClass("down2")
		})
		$(".read-more").mouseleave(function(){
		$(".read-more div").removeClass("down2")
		})
});
$('[data-role="follow"]').click(function () {
    var $this = $(this);
    var uid = $this.attr('data-follow-who');
    $.post('<?php echo U("Core/Public/follow");?>', {uid: uid}, function (msg) {
        if (msg.status == 1) {
            $this.attr('class', 'attention_img3 right hand');
            $this.attr('data-role', 'unfollow');
        }else if (msg.status == 2) {
            $this.attr('class', 'attention_img2 right hand');
            $this.attr('data-role', 'unfollow');
            //follower.bind_follow();
            //toast.success(msg.info, L('_KINDLY_REMINDER_'));
        } else {
            //toast.error(msg.info, L('_KINDLY_REMINDER_'));
        }
    }, 'json');
})
</script>

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