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
    <link type="text/css" rel="stylesheet" href="/Theme/Green/Common/Static/css/ordercss.css">



    <script type="text/javascript" src="/Public/js/jquery-1.9.1.js" ></script>
    <script type="text/javascript" src="/Theme/Green/Common/Static/js/layer/layer.js" ></script>
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
	

    <div class="navigate_box">
        <div class="navigate">
            <a href=""><img src="/Theme/Green/Common/Static/images/base/order_logo.png" class="order_logo"/></a>
            <p class="cut"></p>
            <a href=""><img class="aipai_logo" src="/Theme/Green/Common/Static/images/base/oneyuan.png"/></a>
            <div style="background-position:0 -40px" class="navigation">
                <span class="submit">1.提交订单</span>
                <span style="color:#FFF" class="pay">2.支付订单</span>
                <span class="get">3.获得号码,等待揭晓</span>
                <span class="result">4.揭晓中奖号码</span>
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <form id='getnum' action="<?php echo U('Order/getnumber@1');?>" method="POST">
        <!--新增的收获地址部分-->
        <div class="container addaddress-box">
            <!--地址列表-->
        </div>
        <!--商品-->
        <div class="order_box">
            <div class="order_title2">

                <p>商品</p>
                <ul class="list">
                    <li class="value2">价值</li>
                    <li class="price2">爱拍价</li>
                    <li class="number2">爱拍人次</li>
                    <li class="money2">金额</li>

                </ul>
            </div>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="order_goods2">
                    <h1><a href="<?php echo U('Index/details', array('id'=>$vo['goods']['id']));?>"><?php echo ($vo["goods"]["productinfo"]["name"]); ?></a></h1>
                    <p class="amount2"><?php echo ($vo["goods"]["need_num"]); ?></p>
                    <p class="single2">1爱拍币</p>
                    <p class="people"><?php echo ($vo["num"]); ?></p>
                    <p class="money_total2">¥<?php echo ($vo["num"]); ?></p>
                    <input  value=<?php echo ($vo["goods"]["id"]); ?> name='ids[]' type="hidden"/>
                    <div class="clear"></div> 
                </div><?php endforeach; endif; else: echo "" ;endif; ?>			

            <div class="account_box2 right">
                <h2 class="px16 right">总计：<b class="orange px20"><?php echo ($talnum); ?></b>爱拍币</h2>
                <?php if($my_user['ipai']): ?><p class="px12 gray80 clear right">账号可用余额：￥<span><?php echo ($my_user["balance"]); ?></span></p><?php endif; ?>
                <p class="px12 gray80 clear right">爱币余额：<span><?php echo ($my_user["score2"]); ?></span>爱币</p>
                <div class="clear right sub-box">
                    <?php if($list): ?><a class="submit-order white  px16 bdr right" data-toggle="modal" data-target="#no-address">提交订单</a><?php endif; ?>
                    <a href="<?php echo U('Order/Join');?>" class="return2 bdr right">返回购物车修改＞</a>
                </div>
            </div>
            <input type="hidden" name="token_form" value="<?php echo ($token_form); ?>" />
            <div class="clear"></div>
        </div>
    </form>

    <!-- 修改地址模态框（Modal） -->
    <div class="modal fade edit-address-pop" id="edit-address-pop" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div style="width: 700px !important;" class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                        &times;
                    </button>
                    <p>添加收获地址</p>
                </div>
                <div class="modal-body">

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal -->
    </div>

    <script>
        $(function () {
            load_address_list(true);
        });

        function init() {
            $('._address-new').click(function () {
                load_edit_address();
            });

            $('.edit-address').click(function () {
                var id = $(this).parents('li').attr('data-id');
                load_edit_address(id);
            });

            $('.delete-address').click(function () {
                set_del(this);
            });

            $('.modal-body').delegate('.save-address','click',function () {
                save_address();
            });


            $(".addaddress li ").click(function () {
                var v = $(".addaddress li ").index(this);
                $(this).addClass("active").siblings().removeClass("active");
                $(".operations").hide().eq(v).show();
            });

            //设置默认地址
            $('._set_default').click(function () {
                set_default(this);
            });

//		toggle不行我就用显示隐藏的土方法了
            $('.a1').click(function () {
                $('.a2').show().siblings().hide()
            });
            $('.a2').click(function () {
                $('.a1').show().siblings().hide()
            });


        }

        function save_address() {
            var address = $("._txt_address").val();
            var postcode = $('._post_code').val();
            var name = $('._get_name').val();
            var mobile = $('._get_call').val();
            var pro = $('#province').val();
            var city = $('#city').val();
            var area = $('#area').val();
            var id = $('input[name=id]').val();
            var is_default = 0;
            if ($('._is_default').is(':checked')) {
                is_default = 1;
            }
            var params={id:id, realname:name, province:pro, city:city, area:area, address:address, postcode:postcode, mobile:mobile, is_defaule:is_default}
           
            $.ajax({
                type: "GET",
                url: "<?php echo U('Ucenter/Address/save@');?>",
                data: params,
                dataType: 'jsonp',
                jsonp: "callback",
                jsonpCallback: "jsonpReturn",
                success: function (msg) {
                    if (msg.status == 1) {                                      
                        $(".edit-address-pop").modal('hide');
                        load_address_list();
                    } else {
                        layer.msg(msg.msg);
                    }
                }
            });
        }

        /**
         * 删除地址
         * @param {type} obj
         * @returns {undefined}
         */
        function set_del(obj) {
            var id = $(obj).parents('li').attr('data-id');
            var params = id ? ('id=' + id) : '';
            $.ajax({
                type: "POST",
                url: "<?php echo U('Ucenter/Address/del@');?>",
                data: params,
                dataType: 'jsonp',
                jsonp: "callback",
                jsonpCallback: "jsonpReturn",
                success: function (msg) {
                    if (msg.status == 1) {
                        load_address_list();
                    } else {
                        if (msg.msg) {
                             layer.msg(msg.msg);
                        } else {
                             layer.msg(msg.info);
                        }
                    }
                }
            });
        }

        /**
         * 设置默认地址
         * @param {type} obj
         * @returns {undefined}
         */
        function set_default(obj) {
            var id = $(obj).parents('li').attr('data-id');
            var params = id ? ('id=' + id) : '';
            $.ajax({
                type: "POST",
                url: "<?php echo U('Ucenter/Address/setDefault@');?>",
                data: params,
                dataType: 'jsonp',
                jsonp: "callback",
                jsonpCallback: "jsonpReturn",
                success: function (msg) {
                    if (msg.status == 1) {
                        load_address_list();
                    }
                }
            });
        }

        /**
         * 查看更多地址
         * @returns {undefined}
         */
        function show_more_address() {
            var slideHeight = 102; // px
            var defHeight = $('#addaddress').height();
            var a = "<a style='background:red;width:100px;height:20px;'></a>"
            if (defHeight >= slideHeight) {
                $('#addaddress').css('height', slideHeight + 'px');
                $('.addaddress-box').delegate('#address-more', 'click', function () {
                    var curHeight = $('#addaddress').height();
                    if (curHeight == slideHeight) {
                        $('#addaddress').animate({
                            height: defHeight
                        }, "normal");
                    } else {
                        $('#addaddress').animate({
                            height: slideHeight
                        }, "normal");
                    }
                    return false;
                });
            }
        }

        /**
         * 加载新增编辑模板
         * @returns {undefined}
         */
        function load_edit_address(id) {
            var params = id ? ('id=' + id) : '';
            $.ajax({
                type: "GET",
                url: "<?php echo U('Ucenter/Address/edit@');?>",
                data: params,
                dataType: 'jsonp',
                jsonp: "callback",
                jsonpCallback: "jsonpReturn",
                success: function (msg) {
                    if (msg.status == 1) {
                        var html = msg.data;
                        $('.edit-address-pop').find('.modal-body').html(html);
                        $("#edit-address-pop").modal('show');
                    } else {
                         layer.msg(msg.msg);
                    }
                }
            });
        }

        /**
         * 加载地址列表
         * @returns {undefined}
         */
        function load_address_list(move_def_first) {
            $.ajax({
                type: "GET",
                url: "<?php echo U('Ucenter/Address/selectList@');?>",
                dataType: 'jsonp',
                jsonp: "callback",
                jsonpCallback: "jsonpReturn",
                success: function (msg) {
                    if (msg.status == 1) {
                        $('.addaddress-box').html(msg.data);
                        if (move_def_first) { //设置默认地址到第一行
                            $("ul.addaddress").prepend($("ul.addaddress").find('li.active'));
                            show_more_address();
                        }
                        init();
                    } else {
                         layer.msg(msg.info);
                    }
                }
            });
        }

//        $("#getpay").click(function () {
//            $("#getnum").submit();
//        })
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