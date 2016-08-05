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
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/style.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/pai_details.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/sprites.css" />
    <link rel="stylesheet" href="/Theme/Green/Common/Static/css/pai_default.css" />



    <script src="/Public/js/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="/Theme/Green/Common/Static/js/jquery.imagezoom.min.js"></script>    

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

    <!--爱拍详情页面-->
    <div class="lovepaicontainer">
        <!--商品详情期号-->
        <div class="lovepaititle">
            首页<span>></span>一元爱拍<span>></span><span class="orange">商品详情 [期号：<?php echo (sprintf("%03d",$row["periods"])); ?>]</span>
        </div>
        <!--商品详细信息以及用户信息-->
        <div class="top_pai">
            <div class="goodsinfo" style="float: left;">
                <!--商品轮播-->
                <div class="goodspiclun">
                    <div class="box">
                        <div class="tb-booth tb-pic  ">
                            <a href="<?php echo (getthumbimagebyid($row['productinfo']['imgs'][0],1024,768)); ?>">
                                <img src="<?php echo (getthumbimagebyid($row['productinfo']['imgs'][0],380,380)); ?>" alt="<?php echo ($row['productinfo']['name']); ?>" rel="<?php echo (getthumbimagebyid($row['productinfo']['imgs'][0],1024,768)); ?>" class="jqzoom" />
                            </a>
                        </div>
                        <div style="clear: both;"></div>
                        <ul class="tb-thumb" id="thumblist" style="margin: 10px  0px;padding: 0px;">                            
                            <?php if(is_array($row['productinfo']['imgs'])): $i = 0; $__LIST__ = $row['productinfo']['imgs'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><li class="<?php if(($i) == "1"): ?>tb-selected<?php endif; ?>">
                                    <div class="tb-pic tb-s40">
                                        <a href="javascript:void(0);">
                                            <img src="<?php echo (getthumbimagebyid($v,60,60)); ?>" mid="<?php echo (getthumbimagebyid($v,380,380)); ?>" big="<?php echo (getthumbimagebyid($v,1024,768)); ?>" />
                                        </a>
                                    </div>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>                           
                            <div class="clear"></div>
                        </ul>
                        <div class="clear"></div>
                    </div>
                    <style type="text/css">
                        .bdsharebuttonbox{display: inline-block; vertical-align: middle;}
                    </style>
                    <div class="shareto">分享到
                        <?php echo W('Common/Share/detailShare',array('data'=>array('share_text'=>$row['productinfo']['name'],'share_pic'=>getThumbImageById($row['productinfo']['imgs'][0],200,200))));?>
                    </div>
                </div>

                <div class="goodsinforight">
                    <div class="qihao">第<?php echo (sprintf("%03d",$row["periods"])); ?>期</div>
                    <div class="topinfo">
                        <h1><?php echo ($row['productinfo']['name']); ?></h1>
                        <p class="price"><span style="font-size: 19px;">¥&nbsp;</span><?php echo ($row['need_num']); ?></p>
                        <div class="persontiao" style="position: relative;">
                            <ul id="skill">
                                <li><span class="expand html5" style="width:<?php echo ($row["ratio"]); ?>%;overflow: hidden;"></span></li>
                            </ul>
                        </div>
                        <div class="personnum">
                            <ul>
                                <li style="text-align: left;width: 35%;">
                                    <p class=""><?php echo ($row['join_num']); ?></p>
                                    <p class="gray">已参与人次</p>
                                </li>
                                <li style="width: 25%;">
                                    <p class=""><?php echo ($row['need_num']); ?></p>
                                    <p class="gray">总需人次</p>
                                </li>
                                <li style="width: 40%;">
                                    <p class=""><?php echo ($row['surplus_num']); ?></p>
                                    <p class="gray">剩余人次</p>
                                </li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                        <?php if($row["status"] == 2): ?><div class="applynum">

                                参与<span class="reduce">—</span>
                                <input type="text" value="1" class="tpnum" /><span class="plus">＋</span>人次
                                <div style="float: right;width: 303px;">
                                    <div class="applytip">
                                        <!--<div class="sanjiaotip"></div>-->
                                        <div class="tipcontent"> 不算多，再加把劲，概率翻倍概率翻倍</div>
                                    </div>
                                </div>  
                            </div>
                            <div class="operationbutton">
                                <button class="rightnow _rightnow" data-pid="<?php echo ($row["id"]); ?>">立即爱拍</button>
                                <button class="addcar _addcar" data-pid="<?php echo ($row["id"]); ?>"><span class="ap_spcar"></span>加入购物车</button>
                                <div class="clear"></div>
                            </div><?php endif; ?> 
                        <?php if($row["status"] == 3): ?><div class="applynum">
                                <div class="passway">历时太久，未凑满份额，本次爱拍失败</div>
                            </div>
                            <div class="operationbutton">
                                <button class="rightnow lostbuy" disabled="disabled">立即爱拍</button>
                                <button class="addcar lostaddcar" disabled="disabled"><span class="ap_lostcar"></span>加入购物车</button>
                                <div class="clear"></div>
                            </div><?php endif; ?>
                    </div>
                    <div class="bottominfo">
                        <div class="svpromise">
                            <ul class="terms">
                                <li style="color: #fd7603;">服务承诺</li>
                                <li><span class="ap_equity"></span>权益保障</li>
                                <li><span class="ap_bso"></span>正品保证</li>
                                <li><span class="ap_nomail"></span>免邮</li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                        <div class="tab" style="margin-top: 15px;">
                            <div class="tab_menu">
                                <ul>
                                    <li class="active">我的爱拍记录</li>
                                    <li>爱拍规则</li>
                                </ul>
                                <?php if(count($my_record) > 3): ?><span style="float: right;margin-top: 8px;font-size: 12px; cursor: pointer;" class="_btn_show_all_record">更多&nbsp;></span>
                                    <div style="display: none" class="_show_all_record">
                                        <div class="tab_box">
                                            <div class="record" style="margin: 5px 10px;">
                                                <ul>
                                                    <?php for($_i=3;$_i < count($my_record);$_i++){ ?>
                                                    <li> 
                                                        <span>幸运号码：</span>
                                                        <span class="username"><?php echo ($my_record[$_i]['code']); ?></span>
                                                        <span class="recordtime"><?php echo ($my_record[$_i]['time']); ?></span>
                                                    </li>
                                                    <?php } ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div><?php endif; ?>
                                <div class="clear"></div>
                            </div>
                            <div class="clear"></div>
                            <div class="tab_box _tab_box">
                                <div class="record">
                                    <?php if(is_login()){ ?>
                                    <ul>
                                        <?php if($my_record): if(is_array($my_record)): $i = 0; $__LIST__ = $my_record;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$record): $mod = ($i % 2 );++$i; if($i>3)break; ?>
                                                <li> 
                                                    <span>幸运号码：</span>
                                                    <span class="username"><?php echo ($record["code"]); ?></span>
                                                    <span class="recordtime"><?php echo ($record["time"]); ?></span>
                                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                                            <?php else: ?>
                                            您还没参加本次爱拍哦！<?php endif; ?>
                                    </ul>
                                    <?php }else{ ?>
                                    请登录
                                    <?php } ?>
                                    <div class="clear"></div>
                                </div>
                                <div class="size" style="display: none;">
                                    <?php $str=str_replace("\r\n",'\r',modC('PRODUCT_RULE','','IPAI')); $_rule= explode('\r', $str); foreach($_rule as $s){ ?>
                                    <p><?php echo ($s); ?></p>
                                    <?php } ?>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="sponsorcont">
                <div class="sponsor">
                    <div class="sponsorinfo">
                        <h3>本期爱拍发起者</h3>
                        <div class="sponsorpic">
                            <img src="<?php echo ($row['user']['avatar64']); ?>" alt="<?php echo ($row['user']['nickname']); ?>" />
                            <?php if($vo.user.ishot): ?><span class="user_fire rlposition"></span><?php endif; ?>
                        </div>
                    </div>
                    <div class="comeout"></div>
                    <div style="text-align: center;margin-top: 10px;">
                        <button class="pay" data-is="<?php echo ($is_follow['is_following']); ?>"><?php if($is_follow['is_following'] == 1): ?>已关注<?php else: ?>关注<?php endif; ?></button>
                    </div>
                    <div class="level">
                        <p>微信：<?php echo ($row['user']['weixin']); ?></p>
                        <p>等级：<span class="greenlevel">Lv.<?php echo ($row['user']['level']); ?></span></p>
                    </div>
                    <div class="concern"><?php echo ($row['user']['category']); ?></div>
                </div>
                <div class="goodsrecent">
                    <p class="goodsmore">商品近况
                    <?php if(count($near) > 6): ?><span class="_btn_show_more_near">更多&gt;</span>
                        <div style="display:none" class="_show_more_near">
                            <div style="margin:5px 10px">
                                <table style="width:100%">
                                    <tr><th style="width:30%">期号</th><th style="width:70%">揭晓时间</th></tr>
                                    <?php if(is_array($near)): $i = 0; $__LIST__ = $near;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nr): $mod = ($i % 2 );++$i;?><tr>
                                            <td><?php echo ($nr["periods"]); ?>期</td>
                                            <td><?php echo date_fmt('Y-m-d h:i:s',$nr['open_time'],FALSE); ?></td>
                                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                </table>
                            </div>
                        </div><?php endif; ?>
                    </p>                   
                    <div class="recentime">
                        <ul>
                            <li class="vstime px14" style="border-bottom: 1px solid #EEE;border-top: 1px solid #EEE;">期号</li>
                            <li class="opentime px14" style="border: 1px solid #EEE;border-right:none ;">中奖人员</li>
                        </ul>
                    </div>                  
                    <div class="clear"></div>                    
                    <div class="timerecord" style="font-size: 12px;padding: 12px 0px;">
                        <?php if(is_array($near)): $i = 0; $__LIST__ = $near;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$nr): $mod = ($i % 2 );++$i; if($i>6)break; ?>
                            <div class="recentime">
                                <ul>
                                    <li class="vstime"><a href="<?php echo U('Index/details', array('id'=>$nr['id']));?>"><?php echo ($nr["issue_num"]); ?></a></li>
                                    <li class="opentime"><a href="<?php echo U('@'.$nr['win_user']['username']);?>"><?php echo ($nr["win_user"]["nickname"]); ?></a></li>
                                </ul>
                            </div><?php endforeach; endif; else: echo "" ;endif; ?>                        
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="clear"></div>
        <!--左侧相关微商举例右侧四个选项卡-->
        <div class="bot_pai">
            <div class="weiaside" style="float: left;">
                <div class="correlation">相关微商</div>
                <div style="padding: 0px 15px;">
                    <?php if(is_array($related)): $i = 0; $__LIST__ = $related;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$r): $mod = ($i % 2 );++$i;?><div class="wechat_user" style="<?php if(count($related) == $i): ?>border: none;<?php endif; ?>">
                            <ul>
                                <li class="userpic">
                                    <a href="<?php echo U('@'.$r['username']);?>"><img src="<?php echo ($r['avatar64']); ?>" alt="<?php echo ($r['nickname']); ?>" /></a>      
                                <?php if($r.ishot): ?><span class="user_fire"></span><?php endif; ?>                               
                                </li>
                                <li class="userinfo">
                                    <div class="level">
                                        <p>微信：<?php echo ($r['weixin']); ?></p>
                                        <p>等级：<span class="greenlevel">Lv.<?php echo ($r["level"]); ?></span></p>
                                    </div>
                                    <div class="concern"><?php echo ($r['category']); ?></div>
                                </li>
                            </ul>
                        </div><?php endforeach; endif; else: echo "" ;endif; ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="fourtab">
                <div class="four_menu">
                    <ul>
                        <?php if($row['productinfo']['type_first'] == 1): ?><li class="selected">商品详情</li>
                            <?php else: ?>
                            <li class="selected">服务详情</li><?php endif; ?>  
                        <li>参与者</li>                       
                    </ul>
                </div>
                <div class="clear"></div>
                <div class="four_box">
                    <?php if($row['productinfo']['type_first'] == 1): ?><div class="goodsdt ">
                            <?php echo ($row['productinfo']['content']); ?>
                        </div>
                        <?php else: ?>
                        <div class="servicedt">
                            <div class="notes">
                                <div class="notestil">服务须知</div>
                                <div class="notescont">
                                    <ul>
                                        <li class="field">有效期</li>
                                        <li class="fieldcont"><?php echo date('Y年m月d日',$row['begin_time']).'至'.date('Y年m月d日',$row['end_time']); ?></li>
                                    </ul>
                                    <div class="clear"></div>
                                </div>

                                <div class="notescont">
                                    <ul>
                                        <li class="field">可用时间</li>
                                        <li class="fieldcont"><?php echo ($row["use_time"]); ?></li>
                                    </ul>
                                    <div class="clear"></div>
                                </div>

                                <div class="notescont">
                                    <ul>
                                        <li class="field">预约提示</li>
                                        <li class="fieldcont">
                                            <?php echo ($row['productinfo']['reservation_msg']); ?>
                                        </li>
                                    </ul>
                                    <div class="clear"></div>
                                </div>
                                <div class="notescont">
                                    <ul>
                                        <li class="field">服务位置</li>
                                        <li class="fieldcont"><?php echo ($row['productinfo']['server_address']); ?></li>
                                    </ul>
                                    <div class="clear"></div>
                                </div>
                                <div class="notescont">
                                    <ul>
                                        <li class="field">手机号</li>
                                        <li class="fieldcont"><?php echo ($row['productinfo']['contact']); ?></li>
                                    </ul>
                                    <div class="clear"></div>
                                </div>

                            </div>

                            <div class="svdescribe">
                                <h1>服务描述</h1>
                                <div class="svdescribecont" style="width: 100%;height: 500px;background-color: #EEE;">
                                    <?php echo ($row['productinfo']['content']); ?>
                                </div>
                            </div>
                        </div><?php endif; ?>
                    <!--参与者-->
                    <div class="actor hide _record_rows _data_rows">
                        <?php echo A('Ipai/Index')->orderRecord($row['id']);?>
                    </div>
                    <script>
                        $(function () {
                            $('.reduce').click(function () {
                                var value = parseInt($('.tpnum').val()) - 1;
                                if (value <= -1) {
                                    return false;
                                } else if (value <= 0) {
                                    return false;
                                } else {
                                    $('.tpnum').val(value);
                                }
                            });

                            $('.plus').click(function () {
                                var value = parseInt($('.tpnum').val()) + 1;
                                $('.tpnum').val(value);
                            });


                            $("div.four_menu ul li").click(function () {
                                $(this).addClass("selected").siblings().removeClass("selected");
                                var index = $(this).index();
                                $("div.four_box > div").eq(index).show().siblings().hide();
                            }).hover(function () {
                                $(this).addClass("active");
                            }, function () {
                                $(this).removeClass("active");
                            });

                            $("div.tab_menu ul li").click(function () {
                                $(this).addClass("active").siblings().removeClass("active");
                                var index = $(this).index();
                                $("div._tab_box > div").eq(index).show().siblings().hide();
                            });

                            $('._btn_show_all_record').on('click', function () {
                                var str = $('._show_all_record').html();
                                layer.open({
                                    type: 1,
                                    title: false,
                                    shadeClose: true,
                                    area: ['400px', '340px'],
                                    content: str
                                });
                            });

                            $('._btn_show_more_near').on('click', function () {
                                var str = $('._show_more_near').html();
                                layer.open({
                                    type: 1,
                                    title: false,
                                    shadeClose: true,
                                    area: ['300px', '240px'],
                                    content: str
                                });
                            });


                            $('._data_rows').delegate('.pages a', 'click', function (e) {
                                e.preventDefault();
                                var url = $(this).attr('href');
                                upCommentLikePage(url, $(this).parents('._data_rows'));
                            });

                            $('._addcar').click(function () {
                                var id = $(this).attr('data-pid');
                                var num = $('.tpnum').val();
                                $.post("<?php echo U('Ipai/Cart/addCart');?>", {pid: id, num: num}, function (msg) {
                                    alert(msg.message);
                                }, 'json');
                            });

                            $('._rightnow').click(function () {
                                var id = $(this).attr('data-pid');
                                var num = $('.tpnum').val();
                                $.post("<?php echo U('Ipai/Cart/setCartGoodsNum');?>", {pid: id, num: num}, function (msg) {
                                    location.href = "<?php echo U('Order/join', array('id'=>$row['id']));?>";
                                }, 'json');
                            });

                            $('.pay').click(function () {
                                var obj = this;
                                var sess_id = '<?php echo session_id();?>';
                                var uid = '<?php echo ($row["uid"]); ?>';
                                var is_follow = $(this).attr('data-is');
                                follow(uid, sess_id, is_follow, function (data) {
                                    if (data.status == 1) {
                                        if (is_follow == "0") {
                                            $(obj).attr('data-is', '1');
                                            $(obj).text("已关注");
                                        } else {
                                            $(obj).attr('data-is', '0');
                                            $(obj).text("关注");
                                        }
                                    } else {
                                        alert(data.info);
                                        //toast.error(data.info, '温馨提示');
                                    }
                                });

                            });
                        });




                        function goTo(jqclass, obj) {
                            var cls = $(obj).parent().find(jqclass);
                            var num = parseInt(cls.val());
                            if (isNaN(num))
                                return;
                            var url = $(obj).attr('data-url').replace(/p\/(\d)+\.?/g, "p/" + num + ".");
                            upCommentLikePage(url, $(obj).parents('._data_rows'));
                        }

                        function upCommentLikePage(url, cls) {
                            $.get(url, function (msg) {
                                $(cls).html(msg);
                            });
                        }
                    </script>
                </div>
            </div>
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