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

    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/reseting.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/common.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/page.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/style.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/sprites.css" />   
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/love_ws/list_style.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/pai_default.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/pai_details.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/newaipai.css" />
    <style>
        /*这个状态是共用了首页全部状态下，为了排下只能缩短1到2px*/
        <?php if($param["cat"] == 0): ?>.newpbox:nth-child(4n) {
            margin-right: 0px !important;
        }
        <?php else: ?>
        .newpbox:nth-child(5n) {
            margin-right: 0px !important;
        }

        .newpbox {
            width: 218px;
        }

        .menubox .buybox {
            width: 160px
        }<?php endif; ?>

    </style>



    <script type="text/javascript" src="/Public/js/jquery-1.9.1.js" ></script>
    <script type="text/javascript" src="/Public/js/ext/layer/layer.js" ></script>
    <script type="text/javascript" src="/Public/js/jquery.easing.js" ></script>
    <script type="text/javascript" src="/Theme/Green/Common/Static/js/list_js.js"></script>
    <script type="text/javascript" src="/Theme/Green/Common/Static/js/partjs.js"></script>    	

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
	
    
    <!--下面是logo部分-->
<div class="logobox">
    <div class="logo">
        <a href="<?php echo U('/@');?>">
            <div class="a-logo left">
                <img src="/Theme/Green/Common/Static/images/base/a-logo.png" />
            </div>
        </a>
        <div class="logo-cut left"></div>
        <div class="a-love left">
            <img src="/Theme/Green/Common/Static/images/base/oneyuan.png" alt="logo" />
        </div>

        <div class="sousuobox">

            <input class="search" id="search" type="text" placeholder="请输入关键字">
            <p class="anniu handle">搜索</p>
            <div class="remen clear">
                <p class="left">近期热搜：</p>
                <div class="rollkeys_box left">
                    <dl class="roll_keys">
                        <dd>
                            <a href="">白富美</a>
                            <a href="">高富帅</a>
                            <a href="">萝莉</a>
                            <a href="">饼干西施</a>
                            <a href="">白富美</a>
                            <a href="">高富帅</a>
                            <a href="">萝莉</a>
                            <a href="">饼干西施</a>

                        </dd>
                        <dd>
                            <a href="">白富美</a>
                            <a href="">高富帅</a>
                            <a href="">萝莉</a>
                            <a href="">饼干西施</a>
                            <a href="">白富美</a>
                            <a href="">高富帅</a>
                            <a href="">萝莉</a>
                            <a href="">饼干西施</a>
                        </dd>
                        <dd>
                            <a href="">白富美</a>
                            <a href="">高富帅</a>
                            <a href="">萝莉</a>
                            <a href="">饼干西施</a>
                            <a href="">白富美</a>
                            <a href="">高富帅</a>
                            <a href="">萝莉</a>
                            <a href="">饼干西施</a>
                        </dd>
                        <dd>
                            <a href="">白富美</a>
                            <a href="">高富帅</a>
                            <a href="">萝莉</a>
                            <a href="">饼干西施</a>
                            <a href="">白富美</a>
                            <a href="">高富帅</a>
                            <a href="">萝莉</a>
                            <a href="">饼干西施</a>
                        </dd>

                    </dl>
                </div>
            </div>
        </div>

        <div class="dianhuabox right">
            <h1 class="px14">24小时微信客服：</h1>
            <h2>130-8888-8888</h2>
            <h3 class="px14">目前微信粉丝：<span>8888</span>&nbsp;&nbsp;人</h3>
        </div>
    </div>

</div>
<!--logo结束-->

    <nav class="orangenav">
    <div class="tppaimenu">
        <ul>
            <li style="width: 250px;"> <span class="tp_home"></span>首页</li>
            <li> <span>微商红人</span></li>
            <li> <span>最新微商</span></li>
            <li class="active"> <span>一元爱拍</span></li>
            <li> <span>微商学院</span></li>
            <li> <span>一手货源</span></li>
        </ul>
        <div class="clear"></div>
    </div>
</nav>
    <div class="banner container bs" id="myjQuery">
        <ul class="button" id="myjQueryNav">
            <li class="current"></li>
            <li></li>
            <li></li>
        </ul>
        <div class="imgbox" id="myjQueryContent">
            <?php if(is_array($advs)): $i = 0; $__LIST__ = $advs;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div><img src="<?php echo ($vo["pic"]); ?>"></div><?php endforeach; endif; else: echo "" ;endif; ?>	
        </div>
    </div>

    <!--全国爱拍页面搜索框-->
    <div class="whhite">
        <!--全国商品分类选项按钮-->
        <div class="nationgoods">
            <ul>
                <li class="_cat <?php if(($param["cat"]) == "0"): ?>active<?php endif; ?>" data-url="<?php echo U('Ipai/Index/Index',array('cg'=>0));?>"><span class="ap-all"></span>
                    <p>全部</p>
                </li>
                <li class="_cat <?php if(($param["cat"]) == "2"): ?>active<?php endif; ?>" data-url="<?php echo U('Ipai/Index/Index',array('cg'=>2));?>"><span class="ap-tel"></span>
                    <p>手机平板</p>
                </li>
                <li class="_cat <?php if(($param["cat"]) == "3"): ?>active<?php endif; ?>" data-url="<?php echo U('Ipai/Index/Index',array('cg'=>3));?>"><span class="ap-pc"></span>
                    <p>电脑办公</p>
                </li>
                <li class="_cat <?php if(($param["cat"]) == "4"): ?>active<?php endif; ?>" data-url="<?php echo U('Ipai/Index/Index',array('cg'=>4));?>"><span class="ap-audio"></span>
                    <p>数码影音</p>
                </li>
                <li class="_cat <?php if(($param["cat"]) == "5"): ?>active<?php endif; ?>" data-url="<?php echo U('Ipai/Index/Index',array('cg'=>5));?>"><span class="ap-woman"></span>
                    <p>女性时尚</p>
                </li>
                <li class="_cat <?php if(($param["cat"]) == "6"): ?>active<?php endif; ?>" data-url="<?php echo U('Ipai/Index/Index',array('cg'=>6));?>"><span class="ap-food"></span>
                    <p>美食天地</p>
                </li>
                <li class="_cat <?php if(($param["cat"]) == "7"): ?>active<?php endif; ?>" data-url="<?php echo U('Ipai/Index/Index',array('cg'=>7));?>"><span class="ap-new"></span>
                    <p>潮流新品</p>
                </li>
                <li class="_cat <?php if(($param["cat"]) == "8"): ?>active<?php endif; ?>" data-url="<?php echo U('Ipai/Index/Index',array('cg'=>8));?>"><span class="ap-other"></span>
                    <p>其他商品</p>
                </li>
            </ul>
            <script>
                $(document).ready(function () {
                    $('._cat').click(function () {
                        location.href = $(this).attr('data-url');
                    });
                });
            </script>
            <div class="clear"></div>
        </div>

    </div>

    <div class="container">
        <div class="aipai_kuangkuang " style="<?php if($param["cat"] == 0): ?>width: 968px;margin-left: 0px; float: left;<?php endif; ?>">
            <div class="jingque sort">
                <div class="hbox2">排序：</div>
                <ul>
                    <li class="n1 <?php if(($param["order"]) == "1"): ?>active<?php endif; ?>">
                        <a href="<?php echo U('Index/Index', array('o'=>1,'cg'=>$param['cat']));?>">人气</a>
                    </li>
                    <li class="n1 <?php if(($param["order"]) == "2"): ?>active<?php endif; ?>">
                        <a href="<?php echo U('Index/Index', array('o'=>2,'cg'=>$param['cat']));?>">剩余人次</a>
                    </li>

                    <li class="n1 <?php if(($param["order"]) == "3"): ?>active<?php endif; ?>">
                        <a href="<?php echo U('Index/Index', array('o'=>3,'cg'=>$param['cat']));?>">最新商品</a>
                    </li>
                    <li class="n1 <?php if(($param["order"]) == "4"): ?>active<?php endif; ?>">
                        <a href="<?php echo U('Index/Index', array('o'=>4,'cg'=>$param['cat']));?>">总需人次<span class="sortarrowdown"></span></a>
                    </li>
                    <div class="special">
                        <div class="page_group2 right">
                            <span><?php echo ($page); if($pages): ?>/<?php echo ($pages); endif; ?></span>
                            <a href="javascript:;" class="page_pre tp_leftpg"></a>
                            <a href="javascript:;" class="page_next tp_rightpg"></a>
                        </div>
                        <script>
                            $(function () {
                                $(".tp_leftpg").click(function () {
                                    if ($('.page_group1 .page_pre').length > 0) {
                                        location.href = $('.page_pre').attr('href');
                                    }
                                });
                                $(".tp_rightpg").click(function () {
                                    if ($('.page_group1 .page_next').length > 0) {
                                        location.href = $('.page_next').attr('href');
                                    }
                                });
                            });
                        </script>
                    </div>
                    <div class="clear"></div>
                </ul>

            </div>
            <div class="clear"></div>

            <div class="aipai_rongqi">
                <!--hongyuanqiangstyle-->
                <!--爱拍商品列表 -强子定制-->
                <?php if(is_array($rows)): $k = 0; $__LIST__ = $rows;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($k % 2 );++$k;?><div class="newpbox">
                        <!--pic-->
                        <a href="<?php echo U('Index/details', array('id'=>$vo['id']));?>" target="_blank">
                            <div class="newgpic">                           
                                <img src="<?php echo (getthumbimagebyid($vo['imgs'][0],200,200)); ?>" alt="<?php echo ($vo["name"]); ?>" />                            
                            </div>
                        </a>
                        <!--goodsname-->
                        <div class="newgname">
                            <a href="<?php echo U('Index/details', array('id'=>$vo['id']));?>" target="_blank">
                                （第<?php echo ($vo["periods"]); ?>期）<?php echo ($vo["name"]); ?>
                            </a>
                        </div>
                        <!--总需人次-->
                        <div class="allneednum">总需：<span class="cl00"><?php echo ($vo["need_num"]); ?></span>人次</div>
                        <!--进度条-->
                        <div class="energy">
                            <div class="persontiao" style="position: relative;">
                                <ul id="skill">
                                    <li><span class="expand html5" style="width:<?php echo ($vo["ratio"]); ?>%;"></span></li>
                                </ul>
                            </div>
                            <div class="totalpp ">
                                <ul>
                                    <li class="left">
                                        <p class="cl00"><?php echo ($vo["join_num"]); ?></p>
                                        <p class="cl80">已参与人次</p>
                                    </li>
                                    <li class="right">
                                        <p class="cl00"><?php echo ($vo["surplus_num"]); ?></p>
                                        <p class="cl80">剩余人次</p>
                                    </li>
                                </ul>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <!--用户信息-->
                        <div class="menubox">
                            <div class="nuserbox ">
                                <img src="<?php echo ($vo["user"]["avatar64"]); ?>" alt="<?php echo ($vo["user"]["nickname"]); ?>" />
                            </div>
                            <div class="buybox">
                                <a class="nowbuy" data-pid="<?php echo ($vo["id"]); ?>" href="<?php echo U('Order/join');?>">立即购买</a>
                                <span class="bigcar" data-pid="<?php echo ($vo["id"]); ?>"></span>
                            </div>
                            <!--用户的详细信息弹窗-->
                            <div class="apuserinfobox">
                                <div class="triangle ">
                                    <div class="box px12">
                                        <!--用户头像-->
                                        <div class="txbox">
                                            <img src="<?php echo ($vo["user"]["avatar64"]); ?>" alt="<?php echo ($vo["user"]["nickname"]); ?>" />
                                        </div>
                                        <!--用户关键词-->
                                        <div class="ukeybox">
                                            <div class="px14">
                                                微信：<span class="weiname"><?php echo ($vo["user"]["weixin"]); ?></span>
                                                <?php if($vo.user.sex == 1): ?><span class="hotman_sex"></span><?php endif; ?>
                                                <?php if($vo.user.sex == 2): ?><span class="hotman_sex2"></span><?php endif; ?>

                                                <span class="hotman_yiyuan "></span>
                                            </div>
                                            <div class="lvbox">
                                                等级：<span class="hotman_lv "><i>Lv.<?php echo ($vo["user"]["level"]); ?></i></span>
                                            </div>

                                            <a class="mainkey1">
                                                <?php echo ($vo['user']['category']); ?>
                                            </a>

                                        </div>
                                        <!--互相关注图片按钮-->
                                        <div class="gzbtn right" data-uid="<?php echo ($vo['uid']); ?>">
                                            <?php if($vo["user"]["follow"]["mutually_following"]){ ?>                                         
                                            <span class="attention_img2 " style="cursor:pointer;"></span>
                                            <?php }elseif($vo["user"]["follow"]["is_following"]){ ?>
                                            <span class="attention_img3" style="cursor:pointer;"></span>
                                            <?php }else{ ?>
                                            <span class="attention_img" style="cursor:pointer;"></span>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><?php endforeach; endif; else: echo "" ;endif; ?>
                <!--翻页按钮组-->

                <?php if($count > 24): ?><div class="butgroup pages">
                        <?php echo getPageView($count,24,array('mod'=>'Ipai/Index/Index','param'=>array('o'=>$param['order'],'cg'=>$param['cat'])),TRUE,5);?>  
                    </div><?php endif; ?>
                <div class="clear"></div>
            </div>
            <div class="clear"></div>
        </div>
        <?php if($param["cat"] == 0): ?><!--右侧晒单-->
            <div class="rightshai">
                <div class="shaidanbox orange">
                    <a href="<?php echo U('Index/commentList');?>">晒单分享
                    <span class="shairarrow right"></span></a>
                </div>
                <!--晒单列表-->
                <div class="shailist">
                    <!--lizi-->
                    <?php if(is_array($shaidan)): $i = 0; $__LIST__ = $shaidan;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vs): $mod = ($i % 2 );++$i;?><div class="sexample">
                            <div class="gspic">
                                <a href="<?php echo U('@'.$vs['user']['username']);?>"><img src="<?php echo ($vs['user']['avatar64']); ?>" alt="<?php echo ($vs["user"]["weixin"]); ?>" /></a> 
                            </div>
                            <div class="mysuninfo px12">
                                <p class="px14">微信：<a href="<?php echo U('@'.$vs['user']['username']);?>"><?php echo ($vs["user"]["weixin"]); ?></a> </p>
                                <div class="cl80 ">
                                    <a href="<?php echo U('Index/commentView',array('cid'=>$vs['id']));?>"><?php echo (getshort($vs["content"],20,'...')); ?></a>                                   
                                </div>
                                <p class="cl80 text-right"><?php echo (date('Y-m-d',$vs['create_time'])); ?></p>
                            </div>
                            <div class="clear"></div>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div class="moreshare cl80 text-center">
                    <a href="<?php echo U('Index/commentList');?>">更多分享</a>
                </div>
            </div><?php endif; ?>
        <div class="clear"></div>
    </div>
    <!--fixed窗口-->
    <div class="fixmodal">
    <a href="<?php echo U('/Order/join@1');?>"> <span class="ap_spcar"></span></a>
    <a id="btn_release_ipai" title="发布爱拍" onclick=" check_login();"> <span class="feitop"></span></a>
</div>


<div class="modal  fade" id="telModal"  role="dialog" aria-labelledby="telModalLabel" aria-hidden="true">
    <div class="modal-dialog " style="width: 490px;">
        <div class="modal-content" style="border-radius:0px ;">
            <!--关闭栏-->
            <div class="closebox text-right">
                <span class="left px16 cl66 ml15">绑定手机号</span>
                <span class="lgclose" data-dismiss="modal" aria-hidden="true"></span>
            </div>
            <div class="telbox">
                <div class="px18 cl66 text-center mt25 _lab_title">
                    您未绑定手机号，请先绑定
                </div>
                <div class="telpnume">
                    <div class="left text-right">手机号：</div>
                    <div class="right">
                        <input type="tel" placeholder="请输入您的手机号" class="writetel in _txt_mobile_num" style="width: 230px;" maxlength="11"  />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="yzmbox">
                    <div class="right">
                        <input type="button" id="btn" value="获取验证码" class="codebutton _btn_get_mobile_code" />
                        <span class="_code_no" style="margin-left: 15px;vertical-align: middle;"></span>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="msgnum">
                    <div class="left text-right">短信验证码：</div>
                    <div class="right">
                        <input placeholder="请输入6位验证码" maxlength="6" type="text" class="_verify_code in" style="width: 160px;" />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="nextbox">
                    <div class="right">
                        <a class="next text-center px16 _btn_bind_mobile" >下一步</a>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal -->
</div>
<!--设置密码-->
<!-- 模态框（Modal） -->
<div class="modal  fade" id="passModal" tabindex="-1" role="dialog" aria-labelledby="passModalLabel" aria-hidden="true">
    <div class="modal-dialog " style="width: 490px;">
        <div class="modal-content" style="border-radius:0px ;">
            <!--关闭栏-->
            <div class="closebox text-right">
                <span class="left px16 cl66 ml15">设置密码</span>
                <span class="lgclose" data-dismiss="modal" aria-hidden="true"></span>
            </div>

            <div class="telbox">
                <div class="px14 cl66 text-center mt25 _lab_title" style="margin-top: 35px;">
                </div>
                <div class="telpnume">
                    <div class="left text-right">密&nbsp;&nbsp;码：</div>
                    <div class="right">
                        <input type="password" placeholder="请输入您的密码"  class="in _pwd" style="width: 230px;"  maxlength="20"  />
                    </div>
                    <div class="clear"></div>
                </div>
                <div class="yzmbox">

                    <div class="right px12 cla0">
                        规则：6-20位字符，须同时包含字母和数字

                    </div>
                    <div class="clear"></div>
                </div>

                <div class="msgnum">
                    <div class="left text-right">确认密码：</div>
                    <div class="right">
                        <input placeholder="请输入确认密码"  type="password" style="width: 230px;"  class="in _spwd"/>
                    </div>
                    <div class="clear"></div>
                </div>

                <div class="nextbox">

                    <div class="right">
                        <a class="next text-center px16 _btn_set_pwd" style="margin-bottom: 40px;" >确定</a>
                    </div>
                    <div class="clear"></div>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal -->
    </div>
</div>
<!-- 模态框（Modal） -->
<div class="modal  fade" id="rzModal" tabindex="-1" role="dialog" aria-labelledby="rzModalLabel" aria-hidden="true">
    <div class="modal-dialog " style="width: 490px;">
        <div class="modal-content" style="border-radius:0px ;">
            <!--无认证提示-->
            <div class="closebox text-right">
                <span class="lgclose" data-dismiss="modal" aria-hidden="true"></span>
            </div>
            <div class="tsbox">
                <p class="px18">
                    您没有满足发布条件
                </p>
                <p class="px22">
                    马上认证一元爱拍？
                </p>
                <a class="gocertification px16 text-center" href="<?php echo U('Ucenter/Ipai/authRealName@');?>" >
                    申请认证
                </a>
            </div>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal -->
</div>
<script>
    $(function () {      
        $('._btn_get_mobile_code').click(function () {
            send_mobile_code(this);
        });

        $('._btn_bind_mobile').click(function () {
            send_bind_mobile(this);
        });
        $('._btn_set_pwd').click(function () {
            send_set_pwd(this);
        });
    });

    //短信验证码倒计时
    function settime(obj, countdown) {
        if (countdown == undefined) {
            countdown = 60;
        }
        if (countdown == 0) {
            obj.removeAttribute("disabled");
            obj.value = "免费获取验证码";
            return;
        } else {
            obj.setAttribute("disabled", true);
            obj.value = "重新发送(" + countdown + ")";
            countdown--;
        }
        setTimeout(function () {
            settime(obj, countdown);
        }, 1000);
    }

    function send_mobile_code(obj) {
        $.ajax({
            type: "GET",
            url: "<?php echo U('Ucenter/User/sendMobileVerify@');?>",
            data: 'mobile=' + $('._txt_mobile_num').val(),
            dataType: 'jsonp',
            jsonp: "callback",
            jsonpCallback: "jsonpReturn",
            success: function (msg) {
                if (msg.status == 0) {
                    $(obj).parents('.telbox').find('._lab_title').html("<span style='color:red'>" + msg.msg + "</span>");
                } else {
                    settime(obj);
                    $(obj).parents('.telbox').find('._code_no').text("验证码编号（" + msg.msg + "）");
                    $(obj).parents('.telbox').find('._lab_title').html("验证码已发送，请注意查收。");
                }
            }
        });
    }

    function send_bind_mobile(obj) {
        $.ajax({
            type: "GET",
            url: "<?php echo U('Ucenter/User/doBindMobile@');?>",
            data: 'mobile=' + $('._txt_mobile_num').val() + "&code=" + $('._verify_code').val(),
            dataType: 'jsonp',
            jsonp: "callback",
            jsonpCallback: "jsonpReturn",
            success: function (msg) {
                if (msg.status == 0) {
                    $(obj).parents('.telbox').find('._lab_title').html("<span style='color:red'>" + msg.msg + "</span>");
                } else {                     
                    $('._lab_mobile').find('._lab_title').html('您当前绑定的手机号为：<span class="px18 _lab_mobile">'+$('._txt_mobile_num').val()+'</span>');
                    $("#telModal").modal('hide');
                    $("#passModal").modal('show');
                }
            }
        });
    }

    function send_set_pwd(obj) {
        $.ajax({
            type: "GET",
            url: "<?php echo U('Ucenter/User/doFirstSetPass@');?>",
            data: 'pwd=' + $('._pwd').val() + "&spwd=" + $('._spwd').val(),
            dataType: 'jsonp',
            jsonp: "callback",
            jsonpCallback: "jsonpReturn",
            success: function (msg) {
                if (msg.status == 0) {
                    $(obj).parents('.telbox').find('._lab_title').html("<span style='color:red'>" + msg.msg + "</span>");
                } else {
                    $("#passModal").modal('hide');
                    check_realname();
                }
            }
        });
    }

    function check_realname() {
        $.ajax({
            type: "GET",
            url: "<?php echo U('Ucenter/User/isRealName@');?>",
            dataType: 'jsonp',
            jsonp: "callback",
            jsonpCallback: "jsonpReturn",
            success: function (msg) {
                if (msg.status == 0) {
                    $("#rzModal").modal('show');
                } else {                  
                    location.href = "<?php echo U('/Index/send@1');?>";
                }
            }
        });
    }

    function check_pass() {
        $.ajax({
            type: "GET",
            url: "<?php echo U('Ucenter/User/isSetPass@');?>",
            dataType: 'jsonp',
            jsonp: "callback",
            jsonpCallback: "jsonpReturn",
            success: function (msg) {
                if (msg.status == 0) {
                    $("#passModal").modal('show');
                } else {
                    check_realname();
                }
            }
        });
    }

    function check_login() {
        $.ajax({
            type: "GET",
            url: "<?php echo U('Ucenter/User/isLogin@');?>",
            dataType: 'jsonp',
            jsonp: "callback",
            jsonpCallback: "jsonpReturn",
            success: function (msg) {
                if (msg.status == 0) {
                    check_login_open_win();
                } else {
                    check_bind();
                }
            }
        });
    }

    function check_bind() {
        $.ajax({
            type: "GET",
            url: "<?php echo U('Ucenter/User/isBindMobile@');?>",
            dataType: 'jsonp',
            jsonp: "callback",
            jsonpCallback: "jsonpReturn",
            success: function (msg) {
                if (msg.status == 0) {
                    $("#telModal").modal('show');
                } else {
                    check_pass();
                }
            }
        });
    }



    /**
     * 检查爱拍发布条件
     * @returns {undefined}
     */
    function check_release_ipai() {
        $.ajax({
            type: "GET",
            url: "<?php echo U('Ucenter/User/bindUser@');?>",
            dataType: 'jsonp',
            jsonp: "callback",
            jsonpCallback: "jsonpReturn",
            success: function (msg) {
                if (msg.status == 1) {
                    alert(msg.msg);
                }
            }
        });
    }
</script>
    <script>
        $(document).ready(function () {
            $("#skill span").css("width") == "100%";
            $(this).css("border-radius", "5px");
            //取消关注
            $('.gzbtn').delegate('.attention_img2,.attention_img3', 'click', function () {
                check_login_open_win();
                var obj = this;
                var uid = $(this).parent().attr('data-uid');

                $.ajax({
                    type: "POST",
                    url: "<?php echo U('Core/Public/setFollow@');?>",
                    data: "uid=" + uid + "&sts=1",
                    dataType: 'json',
                    xhrFields: {
                        withCredentials: true
                    },
                    success: function (msg) {
                        if (msg.status == 1) {
                            var att = $(obj).hasClass('attention_img2') ? 'attention_img2' : 'attention_img3';
                            if (msg.is_follow == 0 || msg.is_follow == 2) {
                                $(obj).removeClass(att).addClass('attention_img');
                            }
                        }
                    }
                });
            });

            //关注
            $('.gzbtn').delegate('.attention_img', 'click', function (event) {
                check_login_open_win();

                var obj = this;
                var uid = $(this).parent().attr('data-uid');

                $.ajax({
                    type: "POST",
                    url: "<?php echo U('Core/Public/setFollow@');?>",
                    data: "uid=" + uid + "&sts=0",
                    dataType: 'json',
                    xhrFields: {
                        withCredentials: true
                    },
                    success: function (msg) {
                        if (msg.status == 1) {
                            if (msg.is_follow == 3) {
                                $(obj).removeClass('attention_img').addClass('attention_img2');
                            } else {
                                $(obj).removeClass('attention_img').addClass('attention_img3');
                            }
                        }
                    }
                });
            });

            $(".nuserbox").mouseenter(function () {
                $('.apuserinfobox').hide();
                var index = $(".nuserbox").index(this);
                $('.apuserinfobox').eq(index).show();
            });
            $('.nuserbox').mouseleave(function () {
                $('.apuserinfobox').hide();
            });
            $('.apuserinfobox').mouseenter(function () {
                $(this).show();
            });
            $('.apuserinfobox').mouseleave(function () {
                $(this).hide();
            });

            //鼠标放在图片上出现白色蒙板
            $(".newgpic").mouseenter(function () {
                var html = " <div class='whitemask'></div>";
                $(this).append(html);
                $(this).mouseleave(function () {
                    $('.whitemask').remove();
                });
            });
            //浮动窗口
            $(window).resize(function () {
                var fixright = ($(window).width() - 1200) / 2 - 60 + 'px';
                $(".fixmodal").css("right", fixright);
            });
            $(window).load(function () {
                var fixright = ($(window).width() - 1200) / 2 - 60 + 'px';
                $(".fixmodal").css("right", fixright);
            });

            $('.nowbuy').click(function (event) {
                var login = check_login_open_win();
                if (!login) {
                    event.preventDefault();
                }
                var pid = $(this).attr('data-pid');
                add_cart(pid);
            });


            //加入购物车动画
            $('.bigcar').click(function () {
                check_login_open_win();
                var cart = $('.a-myshop');
                var imgtofly = $(this).parents('.newpbox').find('.newgpic img');
                if (imgtofly) {
                    var imgclone = imgtofly.clone()
                            .offset({top: imgtofly.offset().top, left: imgtofly.offset().left})
                            .css({'opacity': '0.7', 'position': 'absolute', 'height': '200px', 'width': '200px', 'z-index': '1000'})
                            .appendTo($('body'))
                            .animate({
                                'top': cart.offset().top + 10,
                                'left': cart.offset().left + 30,
                                'width': 55,
                                'height': 55
                            }, 500, 'linear');
                    imgclone.animate({'width': 0, 'height': 0}, function () {
                        $(this).detach();
                    });
                    //加到购物车
                    var pid = $(this).attr('data-pid');
                    add_cart(pid);
                }
            });

        });


        function add_cart(pid, callback) {
            $.post("<?php echo U('Ipai/Cart/addCart');?>", {pid: pid, num: 1}, function (msg) {
                if (callback) {
                    callback.call();
                }
                gen_cart_list();
            }, 'json');

        }



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