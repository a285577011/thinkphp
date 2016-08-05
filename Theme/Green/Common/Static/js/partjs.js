$(document).ready(function() {
	//新的bannner图
	jQuery(function($) {
		var index = 0;
		var maximg = 5;
		//$('<div id="flow"></div>').appendTo("#myjQuery");

		//滑动导航改变内容	
		$("#myjQueryNav li").hover(function() {
			if (MyTime) {
				clearInterval(MyTime);
			}
			index = $("#myjQueryNav li").index(this);
			MyTime = setTimeout(function() {
				ShowjQueryFlash(index);
				$('#myjQueryContent').stop();
			}, 400);

		}, function() {
			clearInterval(MyTime);
			MyTime = setInterval(function() {
				ShowjQueryFlash(index);
				index++;
				if (index == maximg) {
					index = 0;
				}
			}, 3000);
		});
		//滑入 停止动画，滑出开始动画.
		$('#myjQueryContent').hover(function() {
			if (MyTime) {
				clearInterval(MyTime);
			}
		}, function() {
			MyTime = setInterval(function() {
				ShowjQueryFlash(index);
				index++;
				if (index == maximg) {
					index = 0;
				}
			}, 3000);
		});
		//自动播放
		var MyTime = setInterval(function() {
			ShowjQueryFlash(index);
			index++;
			if (index == maximg) {
				index = 0;
			}
		}, 3000);
	});




	function ShowjQueryFlash(i) {
		$("#myjQueryContent div").eq(i).animate({
			opacity: 1
		}, 1000).css({
			"z-index": "1"
		}).siblings().animate({
			opacity: 0
		}, 1000).css({
			"z-index": "0"
		});
		//$("#flow").animate({ left: 652+(i*76) +"px"}, 300 ); //滑块滑动
		$("#myjQueryNav li").eq(i).addClass("current").siblings().removeClass("current");
	}



	/*爱微商滚动说*/
	var t = setInterval(function() {
		$(".roll dl").animate({
			marginTop: -40
		}, 500, function() {
			$(".roll dd:last").after($(".roll dd:first"));
			$(".roll dl").css({
				marginTop: 0
			});
		});
	}, 5000)
	$(".roll").mouseenter(function() {
		clearInterval(t);
	}).mouseleave(function() {
		t = setInterval(function() {
			$(".roll dl").animate({
				marginTop: -40
			}, 500, function() {
				$(".roll dd:last").after($(".roll dd:first"));
				$(".roll dl").css({
					marginTop: 0
				});
			});
		}, 5000)
	})


//	var c = 0
//	$(".section").mouseenter(function() {
//
//		c = $(".section").index(this);
//		$(".section .xiangyin").eq(c).addClass("xin");
//
//	})
//	$(".section").mouseleave(function() {
//
//		c = $(".section").index(this);
//		$(".section .xiangyin").eq(c).removeClass("xin");
//
//	})
//
//	var l = 0
//	$(".section a p").hide();
//	$(".section").mouseenter(function() {
//		l = $(".section").index(this);
//		$(".section a p").eq(l).stop(true, false).show();
//	})
//	$(".section").mouseleave(function() {
//		$(".section a p").eq(l).stop(true, false).hide();
//	})
//
//	var c = 0
//	$(".section2").mouseenter(function() {
//		c = $(".section2").index(this);
//		$(".section2 div").eq(c).addClass("xin2");
//	})
//	$(".section2").mouseleave(function() {
//		c = $(".section2").index(this);
//		$(".section2 div").eq(c).removeClass("xin2");
//	})
//
//	var l = 0
//	$(".section2 a p").hide();
//	$(".section2").mouseenter(function() {
//		l = $(".section2").index(this);
//		$(".section2 a p").eq(l).stop(true, false).show();
//	})
//	$(".section2").mouseleave(function() {
//		$(".section2 a p").eq(l).stop(true, false).hide();
//	})

	/*爱拍	*/
	var k = 0
	$(".aipai").mouseenter(function() {
		k = $(".aipai").index(this);
		$(".aipai p").eq(k).addClass("p_current");
		$(".procuct").eq(k).addClass("procuct_current");
	})
	$(".aipai").mouseleave(function() {

		k = $(".aipai").index(this);
		$(".aipai p").eq(k).removeClass("p_current");
		$(".procuct").eq(k).removeClass("procuct_current");
	})

//	var q = 0
//	$(".product").mouseenter(function() {
//		q = $(".product").index(this);
//		$(".product .xying").eq(q).addClass("xying2");
//	})
//	$(".product").mouseleave(function() {
//		q = $(".product").index(this);
//		$(".product .xying").eq(q).removeClass("xying2");
//	})

	/*学院	*/
	var u = 0
	$(".data").mouseenter(function() {
		u = $(".data").index(this);
		$(".data a").eq(u).addClass("xue");
		$(".rili").eq(u).addClass("rili" + 2)
		$(".shang_rili").eq(u).addClass("shang_rili" + 2)
		$(".xia_rili").eq(u).addClass("xia_rili" + 2)
	})
	$(".data").mouseleave(function() {
		u = $(".data").index(this);
		$(".data a").eq(u).removeClass("xue");
		$(".rili").eq(u).removeClass("rili" + 2)
		$(".shang_rili").eq(u).removeClass("shang_rili" + 2)
		$(".xia_rili").eq(u).removeClass("xia_rili" + 2)
	})

	var k = 0
	$(".qie_xinxi").hide().eq(0).show();
	$(".qiehuan a").eq(0).addClass("dianji")
	$(".qiehuan a").click(function() {
		k = $(".qiehuan a").index(this)
		$(".qie_xinxi").hide();
		$(".qie_xinxi").eq(k).show();
		$(".qiehuan a").removeClass("dianji")
		$(".qiehuan a").eq(k).addClass("dianji")
	})

	/*微商红人最新微商，搜索类表弹窗*/
	$(".exact").mouseenter(function() {
		$(".exact_box").show();
	})
	$(".exact").mouseleave(function() {
		$(".exact_box").hide();
	})
	$(".exact").mouseenter(function() {
		$(".exact_box2").show();
	})
	$(".exact").mouseleave(function() {
		$(".exact_box2").hide();
	})
	$(".sex").mouseenter(function() {
		$(".sex_box").show();
	})
	$(".sex").mouseleave(function() {
		$(".sex_box").hide();
	})

	$(".rank").mouseenter(function() {
		$(".rank_box").show();
	})
	$(".rank").mouseleave(function() {
		$(".rank_box").hide();
	})
	$(".nationwide").mouseenter(function() {
		$(".cgcityboxs").show();
	})
	$(".nationwide").mouseleave(function() {
		$(".cgcityboxs").hide();
	})

/*$("#province option").mouseenter(function(){
		$("#province option").show();
	});
$(".nationwide").mouseenter(function(){
	$(".nation_box").show();
	})
$(".nationwide").mouseleave(function(){
	$(".nation_box").hide();
	
	})	*/
	
//	商品列表页的搜索框js
	$('.xsousuo').mouseenter(function(){
	$('.xsousuo #input2').addClass("border-orange");
	$('.fangda').addClass("fangda2")
})
$('.xsousuo').mouseleave(function(){
	$('.xsousuo #input2').removeClass("border-orange");
	$('.fangda').removeClass("fangda2")
})
	
	

	//切换城市
	//采用预先写好，点击才出现处理的城市切换
	//点击换城市,选择具体城市更改名称
	//$(".hotcityname > span").click(function() {
	//	var cityname = $(this).html();
	//	$("#citynm").html(cityname);
	//})
	//$(".procity ul li").click(function() {
	//	var cityname = $(this).html();
	//	$("#citynm").html(cityname);
	//})
	//$(".ensure").click(function() {
	//		var cityname = $('#city').val();
	//		$("#citynm").html(cityname);
	//	})
	//点击城市名高亮该城市
	$('.hotcityname span').click(function() {
		$(this).addClass('active').siblings().removeClass('active');
	});
	$('.procity li').click(function() {
		$(this).addClass('active').siblings().removeClass('active');
	});

	//互斥处理
	$('.selfsh').click(function() {
		$('.hotcityname span').removeClass('active');
		$('.pro_menu li').removeClass('active');
		$('.procity li').removeClass('active');
		$('.procity ').hide();
	});
	$('.provincesname').click(function() {
		$('.hotcityname span').removeClass('active');
	})
	$('.hotcityname span').click(function() {
		$('.pro_menu li').removeClass('active');
		$('.procity li').removeClass('active');
		$('.procity ').hide();
	})

	$('.pro_menu li').click(function() {
		$('.procity li').removeClass('active');
	})
	//按省份查询
	//切花出现三角形框框

	var $procity1 = $("div.pro_menu1 ul li");
	var $procity2 = $("div.pro_menu2 ul li");
	var $procity3 = $("div.pro_menu3 ul li");
	$procity1.click(function() {
		$(this).addClass("active").siblings().removeClass("active");
		var index = $procity1.index(this);
		$("div.pro_box1 > div").eq(index).show().siblings().hide();
		$("div.pro_box2 > div").hide();
		$("div.pro_box3 > div").hide();
		$('div.pro_menu2 ul li').removeClass('active');
		$('div.pro_menu3 ul li').removeClass('active');
	});
	//一排切换结束

	$procity2.click(function() {
		$(this).addClass("active").siblings().removeClass("active");
		var index = $procity2.index(this);
		$("div.pro_box2 > div").eq(index).show().siblings().hide();
		$("div.pro_box1 > div").hide();
		$("div.pro_box3 > div").hide();
		$('div.pro_menu1 ul li').removeClass('active');
		$('div.pro_menu3 ul li').removeClass('active');
	});
	//二排切换结束

	$procity3.click(function() {
		$(this).addClass("active").siblings().removeClass("active");
		var index = $procity3.index(this);
		$("div.pro_box3 > div").eq(index).show().siblings().hide();
		$("div.pro_box2 > div").hide();
		$("div.pro_box1 > div").hide();
		$('div.pro_menu2 ul li').removeClass('active');
		$('div.pro_menu1 ul li').removeClass('active');
	});
	//二排切换结束
	$('.procity').click(function() {
		//	$(this).hide();
		$('div.pro_menu ul li').removeClass('active');
	})

	$(function() {
		$("#btnSave").click(function() {
			var div = "<div style='width:57px;height:80px;background:#FFF;cursor:pointer'></div>"; //定义DIV的样式
			$("body").append(div); //需要添加在哪个位置
		});
	});

	/*	列表下拉按钮	*/
	$(".read-more").mouseenter(function() {
		$(".read-more div").addClass("down2")
	})
	$(".read-more").mouseleave(function() {
		$(".read-more div").removeClass("down2")
	})

	/*	查看全部下拉*/
	$(function() {
		var slideHeight = 50; // px
		var defHeight = $('#center_box').height();
		var a = "<a style='background:red;width:100px;height:20px;'></a>"
		if (defHeight >= slideHeight) {
			$('#center_box').css('height', slideHeight + 'px');
			$('#read-more').click(function() {
				var curHeight = $('#center_box').height();
				if (curHeight == slideHeight) {
					$('#center_box').animate({
						height: defHeight
					}, "normal");
				} else {
					$('#center_box').animate({
						height: slideHeight
					}, "normal");
				}
				return false;
			});
		}
	});
})
// JavaScript Document