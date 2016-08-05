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
    <script type="text/javascript" src="/Public/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="/Public/js/jquery.validate.min.js"></script>
<style>
.uploadify-button-text{
color:white;
    margin-left: 23px;
}
#upload_picture_qrcode,#upload_picture_avatar{
float:left;
}
</style>
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
			<!--  右边内容-->
			<div class="personal_body right">
				<!--标题-->
				<div class="right_title">
					<h2 class="px16 left">修改头像</h2>
				</div>
				<!--修改头像box-->
				<div class="setheadnox">
					<div class="toupicbox">
						<div class="left">
							<div class="left" style="margin-right: 10px;width: 56px;text-align: right;">
								头像：
							</div>
							<div class="right">
								<div class="toupic upload-background-box">
									<img src="<?php echo ($avatar); ?>" alt="我的头像" id="target"/>
								</div>
								<p class="px12 gray80">只支持JPG、PNG、GIF，大小不超过5M</p>
								<div class="headpicbtn">
									<input type="file" id="upload_picture_avatar"/>
									
									<a class="clickuse back_green right" disabled="disabled" id="avatar_submit">应用</a>
								</div>
							</div>
			<input type="hidden" id="imgId" name="imgId" />				
			<input type="hidden" id="img" name="img" />
			<input type="hidden" id="x" name="x" />
			<input type="hidden" id="y" name="y" />
			<input type="hidden" id="w" name="w" />
			<input type="hidden" id="h" name="h" />
						</div>
						<div class="right">
							<div class="left">
								经典头像：
							</div>
							<div class="right expicbox">
						<?php if(!empty($sysAvatar)): if(is_array($sysAvatar)): $i = 0; $__LIST__ = $sysAvatar;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><img src="<?php echo ($vo["imgUrl"]); ?>" onclick="changeImg(this)" data-path="<?php echo ($vo["path"]); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
							 <?php else: endif; ?>
							</div>

						</div>
						<div class="clear"></div>
					</div>
					<!--二维码-->
					<div class="qrcodebox">
						<div class="left">
							<div class="left" style="margin-right: 10px;">
								二维码：
							</div>
							<div class="right">
								<div class="twocode">
									<img src="<?php echo ($qrcode); ?>" alt="我的二维码" id="targettwocode"/>
								</div>
								<p class="px12 gray80">只支持JPG、PNG、GIF，大小不超过5M</p>
								<div class="headpicbtn">
									<input type="file" id="upload_picture_qrcode"/>
									<a class="clickuse back_green right" id="qrcode_submit">应用</a>
								</div>
							</div>

						</div>
					</div>
					<div class="clear"></div>
				</div>

			</div>
        <div class="clear"></div>
    </div>
    <link type="text/css" rel="stylesheet" href="/Theme/Green/Ucenter/Static/css/personal_center.css">
    <script src="/Theme/Green/Ucenter/Static/js/compatible.js"></script>
    <script type="text/javascript" src="/Public/static/uploadify/jquery.uploadify.js"></script>
    <script type="text/javascript" src="/Public/static/jcrop/jquery.Jcrop.min.js"></script>
      <link rel="stylesheet" href="/Public/static/jcrop/jquery.Jcrop.css" type="text/css" />
    <script>
    $("#upload_picture_avatar").uploadify({
        "swf": "/Public/static/uploadify/uploadify.swf",
        "fileObjName": "download",
        "buttonText": "点击上传",
        "buttonClass": "clickup back_orange",
        "uploader": "<?php echo U('Core/File/uploadPicture@',array('session_id'=>session_id(),'width'=>'348','height'=>'70'));?>",
        "width": 100,
        "height":32,
        'removeTimeout': 1,
        'fileTypeExts': '*.jpg; *.png; *.gif;',
        "onUploadSuccess": uploadPictureavatar,
        'overrideEvents': ['onUploadProgress', 'onUploadComplete', 'onUploadStart', 'onSelect'],
        'onFallback': function () {
            alert("<?php echo L('_FLASH_BAD_');?>");
        }, 'onUploadProgress': function (file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
            $("#cover_id_cover").parent().find('.upload-img-box').html(totalBytesUploaded + ' bytes uploaded of ' + totalBytesTotal + ' bytes.');
        }, 'onUploadComplete': function (file) {
            //alert('The file ' + file.name + ' finished processing.');
        }, 'onUploadStart': function (file) {
           // alert('Starting to upload ' + file.name);
        }, 'onQueueComplete': function (queueData) {
            // alert(queueData.uploadsSuccessful + ' files were successfully uploaded.');
        }
    });
    function uploadPictureavatar(file, data) {
    	console.log(data);
        var data = $.parseJSON(data);
        var src = '';
        if (data.status) {
            $("#user_cover").val(data.id);
            src = data.url || data.path_self
            $('.upload-background-box').html(
            		'<img src="' + src + '" alt="我的头像" id="target"/>'
            );
            $("#img").val(src);
			$('#target').Jcrop({
				minSize: [50,50],
				setSelect: [0,0,200,200],
				onSelect: updateCoords,
				aspectRatio: 1
			},
			function(){
				// Use the API to get the real image size
				var bounds = this.getBounds();
				boundx = bounds[0];
				boundy = bounds[1];
				// Store the API in the jcrop_api variable
				jcrop_api = this;
			});
            $('#submit_cover').attr('disabled',false);
            $('#submit_cover').show();
        } else {
            $('#submit_cover').hide();
            
        }
    }
    $("#upload_picture_qrcode").uploadify({
        "swf": "/Public/static/uploadify/uploadify.swf",
        "fileObjName": "download",
        "buttonText": "点击上传",
        "buttonClass": "clickup back_orange",
        "uploader": "<?php echo U('Core/File/uploadPicture@',array('session_id'=>session_id(),'width'=>'348','height'=>'70'));?>",
        "width": 100,
        "height":32,
        'removeTimeout': 1,
        'fileTypeExts': '*.jpg; *.png; *.gif;',
        "onUploadSuccess": uploadPictureqrcode,
        'overrideEvents': ['onUploadProgress', 'onUploadComplete', 'onUploadStart', 'onSelect'],
        'onFallback': function () {
            alert("<?php echo L('_FLASH_BAD_');?>");
        }, 'onUploadProgress': function (file, bytesUploaded, bytesTotal, totalBytesUploaded, totalBytesTotal) {
            $("#cover_id_cover").parent().find('.upload-img-box').html(totalBytesUploaded + ' bytes uploaded of ' + totalBytesTotal + ' bytes.');
        }, 'onUploadComplete': function (file) {
            //alert('The file ' + file.name + ' finished processing.');
        }, 'onUploadStart': function (file) {
           // alert('Starting to upload ' + file.name);
        }, 'onQueueComplete': function (queueData) {
            // alert(queueData.uploadsSuccessful + ' files were successfully uploaded.');
        }
    });
    function uploadPictureqrcode(file, data) {
    	console.log(data);
        var data = $.parseJSON(data);
        var src = '';
        if (data.status) {
            $("#user_cover").val(data.id);
            src = data.url || data.path_self
            $('.twocode').html(
            		'<img src="' + src + '" alt="我的头像" id="targettwocode"/>'
            );
            $("#img").val(src);
			$('#targettwocode').Jcrop({
				minSize: [50,50],
				setSelect: [0,0,200,200],
				onSelect: updateCoords,
				aspectRatio: 1
			},
			function(){
				// Use the API to get the real image size
				var bounds = this.getBounds();
				boundx = bounds[0];
				boundy = bounds[1];
				// Store the API in the jcrop_api variable
				jcrop_api = this;
			});
            $('#submit_cover').attr('disabled',false);
            $('#submit_cover').show();
        } else {
            $('#submit_cover').hide();
            
        }
    }
    //头像裁剪
	var jcrop_api, boundx, boundy;
	
	function updateCoords(c)
	{
		$('#x').val(c.x);
		$('#y').val(c.y);
		$('#w').val(c.w);
		$('#h').val(c.h);
	};
	function checkCoords()
	{
		//if (parseInt($('#w').val())) return true;
		//alert('请选择图片上合适的区域');
		//return false;
		return true;
	};
	function updatePreview(c){
		if (parseInt(c.w) > 0){
			var rx = 112 / c.w;
			var ry = 112 / c.h;
			$('#preview').css({
				width: Math.round(rx * boundx) + 'px',
            	height: Math.round(ry * boundy) + 'px',
            	marginLeft: '-' + Math.round(rx * c.x) + 'px',
            	marginTop: '-' + Math.round(ry * c.y) + 'px'
			});
		}
		{
			var rx = 130 / c.w;
			var ry = 130 / c.h;
			$('#preview2').css({
            	width: Math.round(rx * boundx) + 'px',
            	height: Math.round(ry * boundy) + 'px',
            	marginLeft: '-' + Math.round(rx * c.x) + 'px',
            	marginTop: '-' + Math.round(ry * c.y) + 'px'
			});
		}
		{
			var rx = 200 / c.w;
			var ry = 200 / c.h;
			$('#preview3').css({
				width: Math.round(rx * boundx) + 'px',
				height: Math.round(ry * boundy) + 'px',
				marginLeft: '-' + Math.round(rx * c.x) + 'px',
				marginTop: '-' + Math.round(ry * c.y) + 'px'
			});
		}
	};
	
	$("#avatar_submit").click(function(){
		var img = $("#img").val();
		var x = $("#x").val();
		var y = $("#y").val();
		var w = $("#w").val();
		var h = $("#h").val();
		var imgId = $("#imgId").val();
		if( checkCoords() ){
			$.ajax({
				type: "POST",
				url: "<?php echo U('Public/changeAvatar');?>",
				data: {"img":img,"x":x,"y":y,"w":w,"h":h,"imgId":imgId},
				dataType: "json",
				success: function(msg){
					if( msg.status == 1 ){
						$('.upload-background-box').html(
								'<img src="' + msg.path + '" alt="我的头像" id="target"/>'		
						);
						alert(msg.info);
					} else {
						alert(msg.info);
					}
				}
			});
		}
	});
	$("#qrcode_submit").click(function(){
		var img = $("#img").val();
		var x = $("#x").val();
		var y = $("#y").val();
		var w = $("#w").val();
		var h = $("#h").val();
		if( checkCoords() ){
			$.ajax({
				type: "POST",
				url: "<?php echo U('Public/changeQrcode');?>",
				data: {"img":img,"x":x,"y":y,"w":w,"h":h},
				dataType: "json",
				success: function(msg){
					if( msg.status == 1 ){
						$('.twocode').html(
								'<img src="' + msg.path + '" alt="我的二维码" id="targettwocode"/>'		
						);
						alert(msg.info);
					} else {
						alert(msg.info);
					}
				}
			});
		}
	});
	function changeImg(obj){
		var src=$(obj).attr('src');
		var path=$(obj).attr('data-path');
		var img = $("#imgId").val(path);
		$('.upload-background-box').html(
				'<img src="' + src + '" alt="我的头像" id="target"/>'		
		);
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