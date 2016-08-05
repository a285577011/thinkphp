
//爱说分享方式按钮点击显示
$('.sayshare').click(function(){
//	$(".sayshare > div").hide();
	var index = $('.sayshare').index(this);
	$(".sayshare > div")
		.eq(index).toggle();
})

//$('.share').mouseleave(function(){
//	$(".sayshare > div").hide();
//})

//提交评论时，编辑框隐藏，不过还欠缺一个提示成功框
$('.discuss').click(function(){
	var index = $('.discuss').index(this);
	$(".mylun").eq(index).toggle();
})

//点击赞、踩、论、分享按钮变化图标
$('.zan').click(function(){	
//	切换显示具体赞的头像
	var index = $('.zan').index(this);
	$(".zanout").eq(index).toggle(); 
	if($(this).hasClass("say_zan2")){
		$(this).removeClass("say_zan2");
		$(this).addClass("say_zan1");
	}
	else if($(this).hasClass("say_zan1")){
			$(this).removeClass("say_zan1");
		$(this).addClass("say_zan2");
	}
})
$('.cai').click(function(){	
	if($(this).hasClass("say_cai2")){
		$(this).removeClass("say_cai2");
		$(this).addClass("say_cai1");
	}
	else if($(this).hasClass("say_cai1")){
			$(this).removeClass("say_cai1");
		$(this).addClass("say_cai2");
	}
})
$('.lun').click(function(){	
//	//	切换显示具体评论详情
//	var index = $('.lun').index(this);
//	$(".lunout").eq(index).toggle();
//	//	隐藏掉赞的头像
//	$(".zanout").hide();
	if($(this).hasClass("say_lun2")){
		$(this).removeClass("say_lun2");
		$(this).addClass("say_lun1");
	}
	else if($(this).hasClass("say_lun1")){
			$(this).removeClass("say_lun1");
		$(this).addClass("say_lun2");
	}
})
$('.share').click(function(){	
	if($(this).hasClass("say_share2")){
		$(this).removeClass("say_share2");
		$(this).addClass("say_share1");
	}
	else if($(this).hasClass("say_share1")){
			$(this).removeClass("say_share1");
		$(this).addClass("say_share2");
	}
})



//主页查看评论
$('.saybtn .lun').click(function(){	
//	$(".lunout").hide();
	//	切换显示具体评论详情
	var index = $('.saybtn .lun').index(this);
	$(".lunout").eq(index).toggle();
	//	隐藏掉赞的头像
	$(".zanout").hide();
	
})
//测试使用
//对别人的评论进行评论，跳转到评论中的评论页面
$('.otherbtn .lun').click(function(){
	window.location.href="lunzhonglun.html";
})
