//点击登录弹出登录注册框
//弹出登录框

//$('.login').on('click', function () {
//    layer.open({
//        type: 2,
//        title: false,
//        shadeClose: true, //点击遮罩关闭层
//        area: ['482px', '365px'],
//        content: 'http://127.0.0.1:8020/pc/usercenter/login.html'
//    });
//});
//$('#login').on('click', function () {
//    layer.open({
//        type: 2,
//        title: false,
//        shadeClose: true, //点击遮罩关闭层
//        area: ['482px', '365px'],
//        content: 'http://127.0.0.1:8020/pc/usercenter/login.html'
//    });
//});

////弹出ta的全部号码
//$('#hisnumber').on('click', function () {
//    layer.open({
//        type: 2,
//        title: false,
//        shadeClose: true, //点击遮罩关闭层
//        area: ['484px', '380px'],
//        content: 'http://127.0.0.1:8020/pc/oneyuan/itsallnumber.html'
//    });
//});
////弹出商品近况
//$('#gsrecentmore').on('click', function () {
//    layer.open({
//        type: 2,
//        title: false,
//        shadeClose: true, //点击遮罩关闭层
//        area: ['350px', '360px'],
//        content: 'http://127.0.0.1:8020/pc/oneyuan/goodsrecent.html'
//    });
//});
//
////跳转注册页
//$(".regester").click(function () {
//    window.location.href = "usercenter/注册页.html"
//});

//个人中心-一元爱拍
//点击再发一期出现的提示
$('.agissue').on('click', function () {
    layer.open({
        type: 1,
        title: false,
        shadeClose: true, //点击遮罩关闭层
        content: $('.agissuemsg'),
    });
});
//发布一元弹出层
$('.submitnow').on('click', function () {
    layer.open({
        type: 1,
        title: false,
        area: ['360px', 'auto'],
        shadeClose: true, //点击遮罩关闭层
        content: $('.rechargebox'),
    });
});
/*林强的充值弹窗*/
$('.submitnow').on('click', function () {
    layer.open({
        type: 1,
        title: false,
        area: ['500px', '310px'],
        shadeClose: true, //点击遮罩关闭层
        content: $('.recharge_pop'),
    });
});



//发起的一元爱拍
//返回列表
$('.gobefore').click(function () {
    $('.bklistbox').hide();
    $('.launch_pai').show();
});
//点击查看
$('.lookpai').click(function () {
    $('.launch_pai').hide();
    $('.bklistbox').show();
});

////短信验证码倒计时
//var countdown = 60;
//
//function settime(obj) {
//    if (countdown == 0) {
//        obj.removeAttribute("disabled");
//        obj.value = "免费获取验证码";
//        countdown = 60;
//        return;
//    } else {
//        obj.setAttribute("disabled", true);
//        obj.value = "重新发送(" + countdown + ")";
//        countdown--;
//    }
//    setTimeout(function () {
//        settime(obj)
//    }, 1000)
//}
//
////短信验证码倒计时(兼容模式处理)
//var countdown = 60;
//$("#btn").click(function () {
//    if (countdown == 0) {
//        obj.removeAttribute("disabled");
//        obj.value = "免费获取验证码";
//        countdown = 60;
//        return;
//    } else {
//        obj.setAttribute("disabled", true);
//        obj.value = "重新发送(" + countdown + ")";
//        countdown--;
//    }
//    setTimeout(function () {
//        settime(obj);
//    }, 1000);
//});

//爱拍nav切换激活样式
$('.tppaimenu li').click(function () {
    $(this).addClass('active').siblings().removeClass('active');

})
//全国商品/本地服务切换激活样式
//$('.nationorlocal div').click(function() {
//		$(this).addClass('active').siblings().removeClass('active');
//	})
//一元爱拍选项卡切换添加边框效果

$('.nationgoods li').click(function () {
    $(this).addClass('active').siblings().removeClass('active');
});
//一元爱拍排序种类点击选中样式
$('.sort li').click(function () {
    $(this).addClass('active').siblings().removeClass('active');
});


//一元爱拍进行时商品轮播
$(function () {
    try {
        $(".jqzoom").imagezoom();
        $("#thumblist li a").hover(function () {
            $(this).parents("li").addClass("tb-selected").siblings().removeClass("tb-selected");
            $(".jqzoom").attr('src', $(this).find("img").attr("mid"));
            $(".jqzoom").attr('rel', $(this).find("img").attr("big"));
        });
    } catch (e) {

    }
});
//一元爱拍报名数增减
$(document).ready(function () {
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

//一元爱拍本期爱拍者鼠标放用户图片实时添加移除白色蒙板
    $(".sponsorpic").mouseenter(function () {
        var html = " <div class='whitemask'></div>";
        $(this).append(html);
        $(this).mouseleave(function () {
            $('.whitemask').remove();
        });
    });
//一元爱拍相关微商鼠标放用户图片实时添加移除白色蒙板
    $(".userpic").mouseenter(function () {
        var html = " <div class='whitemask'></div>";
        $(this).append(html);
        $(this).mouseleave(function () {
            $('.whitemask').remove();
        });
    });

//一元爱拍详情页点击四个信息选项卡激活切换
    var $div_li = $("div.four_menu ul li");
    $div_li.click(function () {
        $(this).addClass("selected")
                .siblings().removeClass("selected");
        var index = $div_li.index(this);
        $("div.four_box > div")
                .eq(index).show()
                .siblings().hide();
    }).hover(function () {
        $(this).addClass("active");
    }, function () {
        $(this).removeClass("active");
    });

//一元爱拍详情爱拍记录与规格选项卡
    var $div_litwo = $("div.tab_menu ul li");
    $div_litwo.click(function () {
        $(this).addClass("active").siblings().removeClass("active");
        var index = $div_litwo.index(this);
        $("div.tab_box > div")
                .eq(index).show()
                .siblings().hide();
    });

//晒单旋转图片
    $(".toright").click(function () {
        //图片旋转,通过$.animate()方法
        $(this).parents('.watchbox').find(".watchpic").animate({
            rotate: "+=90deg" //为rotate属性赋值,注意：deg为角度单位
        }, 'fast');
    });
    $(".toleft").click(function () {
        //图片旋转,通过$.animate()方法
       $(this).parents('.watchbox').find(".watchpic").animate({
            rotate: "-=90deg" //为rotate属性赋值,注意：deg为角度单位
        }, 'fast');
    });


//点击小图显示大图
    $(".mysungoodspic").click(function () {
        $('.watchbox').hide();
        var $watch = $(".mysungoodspic");
        var index = $watch.index(this);
        $('.watchbox').eq(index).toggle();
    });
//晒单图片点击激活样式
    $('.mysungoodspic img').click(function () {
        $('.mysungoodspic img').removeClass('active');
        $(this).addClass('active').siblings().removeClass('active');

    });
//收起
    $(".packup").click(function () {
        $('.watchbox').hide();
        $('.mysungoodspic img').removeClass('active');
    })
    //点击大图直接关闭
    $(".watchpic").click(function () {
        $('.watchbox').hide();
        $('.mysungoodspic img').removeClass('active');
    })
    $(".mysungoodspic .active").click(function () {
        $('.watchbox').hide();
        $('.mysungoodspic img').removeClass('active');
    })
    //点击图片变换图片路径

    $(".mysungoodspic img").bind("click", function () {
        var imgSrc = $(this).attr("data-mid");
        var img = "<img src='" + imgSrc + "' data-img='" + $(this).attr("data-img") + "' alt='' />";
        $(".watchpic").html(img);
    });


//查看原图，独立的窗口
    $(".artwork").hover(
            function () {
                var pass = $(".watchpic img").attr("data-img");
                $(this).wrap("<a href='" + pass + "' target='_blank' title='点击查看原图'></a>")
            },
            function () {
                $(this).unwrap()
            }
    );

});


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



//发布爱拍2选项卡切换
$('.gs').click(function () {
    $(this).addClass('active');
    $('.sv').removeClass('active');
    $('.fp_gs').show();
    $('.fp_sv').hide();
})
$('.sv').click(function () {
    $(this).addClass('active');
    $('.gs').removeClass('active');
    $('.fp_gs').hide();
    $('.fp_sv').show();
})
//发布一元
$('#myEditor').focus(function () {
    $(this).empty();
})
//发布图片样式激活
//$('.uppicbox').click(function() {
//	var html = " <div class='setindex'>设为首图</div>";
//	$(this).append(html).siblings().remove(html);
//})
//$('.uppic').hover(function() {
//	var index = $(".uppicbox .uppic").index(this);
//	if ($('.uppic .setindex').eq(index).length) {
//		if ($('body:has(div)'))
//			return;
//	} else {
//		$('.setindex').eq(index).toggle();
//
//	}
//});
//悬浮图片出现选择设为首图文字
$('.uppic').mousedown(function () {
    var index = $(".uppicbox .uppic").index(this);
    $('.setindex').eq(index).hide();
});

$('.uppic').mouseenter(function () {
    var index = $(".uppicbox .uppic").index(this);
    $('.setindex').eq(index).show();
});
$('.uppic').mouseout(function () {
    var index = $(".uppicbox .uppic").index(this);
    $('.setindex').eq(index).hide();

});


//删除发布图片
$('.forkbox').click(function () {
    $(this).parent().remove();
});
//旋转发布图片
$(".xuanbox").click(function () { //click事件,每点击一次,顺时针旋转45°.
    //图片旋转,通过$.animate()方法
    var $watch = $(".xuanbox");
    var index = $watch.index(this);
    $(".uppicbox").eq(index).animate({
        rotate: "+=90deg" //为rotate属性赋值,注意：deg为角度单位
    }, 'fast');
});
//设为首图
$('.uppic').click(function (event) {
    $(this).parent().insertBefore($('.uppicbox').first());
});

//选择定时上架
//定时上架
$(".timing1").click(function () {
    $('.timing2').removeAttr("checked");
});
$(".timing2").click(function () {
    $('.timing1').removeAttr("checked");
});


//个人主页——一元爱拍
//选项卡切换
//按每种状态独立划分页面了
//var $div_tabthree = $("div.ucentertab_menu ul li");
//$div_tabthree.click(function() {
//	$(this).addClass("active")
//		.siblings().removeClass("active");
//	var index = $div_tabthree.index(this);
//	$("div.ucentertab_box > div")
//		.eq(index).show()
//		.siblings().hide();
//});
//鼠标移到图片上出现遮罩层
$(".productpic").mouseenter(function () {
    var html = " <div class='picmask'></div>";
    $(this).append(html);
    $(this).mouseleave(function () {
        $('.picmask').remove();
    });
});


//全国爱拍本地服务鼠标放商品图片实时添加移除黑色蒙板
$(".g_pic").mouseenter(function () {
    var html = " <div class='picmask'></div>";
    $(this).append(html);
    $(this).mouseleave(function () {
        $('.picmask').remove();
    });
});
//全国爱拍本地服务鼠标放用户图片实时添加移除白色蒙板
$(".u_pic").mouseenter(function () {
    var html = " <div class='whitemask'></div>";
    $(this).append(html);
    $(this).mouseleave(function () {
        $('.whitemask').remove();
    });
});

//各个页面点击按钮后高亮该按钮
$('.butgroup .btnnum').click(function () {
    $('.butgroup span').removeClass('active');
    $(this).addClass('active');
})


