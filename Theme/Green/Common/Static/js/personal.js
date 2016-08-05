
/*设置菜单下拉*/
 var o = 0
$(".set_logo").eq(0).addClass("set_logo2")
$(".set_title").click(function(){
	o=$(".set_title").index(this);
	$(".data_down").eq(o).slideToggle();
	$(".set_logo").eq(o).toggleClass("set_logo2")
	})



/*删除按钮显示隐藏*/
 var k = 0
 $(".message_section").mouseenter(function(){
	 k= $(".message_section").index(this)
	 $(".delete_icon").eq(k).fadeIn();
	 })
$(".message_section").mouseleave(function(){
	 k= $(".message_section").index(this)
	 $(".delete_icon").eq(k).fadeOut();
	 })
$(".delete_icon").click(function(){
	k=$(".delete_icon").index(this);
	$(".message_section").eq(k).hide();
	})
/*系统消息、互动消息、爱拍消息切换*/
$(".aipai_message").click(function(){
	$(this).addClass("message_style").siblings().removeClass("message_style")
	$(".aipai_body").show();
	$(".interact_body").hide();
	$(".system_body").hide();
	})
$(".interact_message").click(function(){
	$(this).addClass("message_style").siblings().removeClass("message_style")
	$(".interact_body").show();
	$(".aipai_body").hide();
	$(".system_body").hide();
	})
	$(".system_message").click(function(){
	$(this).addClass("message_style").siblings().removeClass("message_style")
	$(".system_body").show();
	$(".interact_body").hide();
	$(".aipai_body").hide();
	})
	
//短信验证码倒计时
var countdown = 60;

function settime(obj) {
	if (countdown == 0) {
		obj.removeAttribute("disabled");
		obj.value = "免费获取验证码";
		countdown = 60;
		return;
	} else {
		obj.setAttribute("disabled", true);
		obj.value = "重新发送(" + countdown + ")";
		countdown--;
	}
	setTimeout(function() {
		settime(obj)
	}, 1000)
}

//短信验证码倒计时(兼容模式处理)
var countdown = 60;
$("#btn").click(function() {
	if (countdown == 0) {
		obj.removeAttribute("disabled");
		obj.value = "免费获取验证码";
		countdown = 60;
		return;
	} else {
		obj.setAttribute("disabled", true);
		obj.value = "重新发送(" + countdown + ")";
		countdown--;
	}
	setTimeout(function() {
		settime(obj);
	}, 1000);
})


/*设为默认地址出现的样式*/
var p = 0
$(".set_default").show().eq(0).hide();
	$(".default").hide().eq(0).fadeIn();
	$(".address_box").eq(0).addClass("address_style").siblings().removeClass("address_style");
	$(".selected").hide().eq(0).fadeIn();
	
$(".set_default").click(function(){
	p=$(".set_default").index(this)
	$(".set_default").show().eq(p).hide();
	$(".default").hide().eq(p).fadeIn();
	$(".address_box").eq(p).addClass("address_style").siblings().removeClass("address_style");
	$(".selected").hide().eq(p).fadeIn();
	
	})
$(".delete").click(function(){
	p=$(".delete").index(this);
	$(".address_box").eq(p).hide();
	})

/*支付方式的切换按钮*/
var q = 0
$(".recharge_select").eq(0).addClass("recharge_select2")
$(".recharge_box").click(function(){
	q=$(".recharge_box").index(this);
	$(".recharge_select").removeClass("recharge_select2").eq(q).addClass("recharge_select2");
	$(".recharge_way").hide().eq(q).show();
	$(".recharge_box").removeClass("recharge_style").eq(q).addClass("recharge_style");
	}
	)
/*平台支付和余额支付的切换按钮*/


$(".balance_way").click(function(){
	$(".part_way").eq(1).removeClass("way_style")
	$(".balance_way").addClass("way_style")
	$(".alipay_box").eq(1).hide();
	$(".inupt_money").eq(0).hide();
	$(".inupt_money").eq(1).show();
	})
$(".part_way").eq(1).click(function(){
	$(".balance_way").removeClass("way_style")
	$(".part_way").eq(1).addClass("way_style")
	$(".alipay_box").eq(1).show();
	$(".inupt_money").eq(0).show();
	$(".inupt_money").eq(1).hide();
	})
/*添加的银行卡和支付宝的删除按钮*/
$(".card_delete").click(function(){
	$(this).parent().hide();
	})

/*关注、取消关注的切换*/

$(".attention").click(function(){
	$(".attention").hide();
	$(".attention2").fadeIn();
	})




/*晒单图片按钮*/
var n=0
$(".imgbox_section").mouseenter(function(){
	n=$(".imgbox_section").index(this);
	$(".delete").eq(n).fadeIn();
	})
$(".imgbox_section").mouseleave(function(){
	n=$(".imgbox_section").index(this);
	$(".delete").eq(n).fadeOut();
	})
$(".delete").click(function(){
	$(this).parent().hide();
	})	
	
$(".attention").click(function(){
	$(".attention").hide();
	$(".attention2").fadeIn();
	})

//帮助中心搜索页标题变色
$('.articlebox').hover(function(){
	var index=$('.articlebox').index(this);
	$('.articlebox .px14').eq(index).toggleClass('orange');
})

	
// JavaScript Document