$(document).ready(function () {
//生成头部购物车下拉
    gen_cart_list();
    //搜素悬浮框的显示隐藏
    //实现窗口滚动，搜索框不滚动 
    $(window).scroll(function () {
        var top = $(this).scrollTop();
        var flowSearch = $(".a-scroll-box");
        if (top < 160) {
//浮动搜索框隐藏，淡入效果 
            flowSearch.css("display", "none");
        } else {
            flowSearch.css("display", "block");
        }
    });
    /*隐藏的确定删除弹窗*/
    $('.trashs').on('click', function () {
        layer.open({
            type: 1,
            title: false,
            shadeClose: true,
            //点击遮罩关闭层
            area: ['400px', '152px'],
            content: $('.sure_boxs'),
        });
    }); /*头部*/

    //头部购物车的删除按钮   
    $('.a-shop-pop').delegate('.a-delete', 'click', function () {
        var url = $('._cart_del').val().replace("_pid_", $(this).attr('data-id'));
        $.getScript(url, function () {
            if (del_goods_sts.status == 1) {
                gen_cart_list();
            }
        });
    });

    //头部图片的一些遮罩
    var k = 0
    $('.a-shop-img').mouseenter(function () {
        k = $('.a-shop-img').index(this);
        $('.a-shop-img .a-black').eq(k).fadeIn();
    })
    $('.a-shop-img').mouseleave(function () {
        k = $('.a-shop-img').index(this);
        $('.a-shop-img .a-black').eq(k).fadeOut();
    })
    var w = 0
    $('.a-record-img').mouseenter(function () {
        w = $('.a-record-img').index(this);
        $('.a-record-img .a-black').eq(w).fadeIn();
    })
    $('.a-record-img').mouseleave(function () {
        w = $('.a-record-img').index(this);
        $('.a-record-img .a-black').eq(w).fadeOut();
    })

    //	我的弹窗
    $('.my-icn').mouseenter(function () {
        $('.header-cut').eq(0).addClass('a-cut-style');
        $('.my-pop').show();
        $('.my-icn em').addClass('a-orange-style');
        $('.my-icn .a-down').addClass('a-down2');
    })
    $('.my-icn').mouseleave(function () {
        $('.header-cut').eq(0).removeClass('a-cut-style');
        $('.my-pop').hide();
        $('.my-icn em').removeClass('a-orange-style');
        $('.my-icn .a-down').removeClass('a-down2');
    })
    //	购物车弹窗
    $('.a-myshop').mouseenter(function () {
        $('.header-cut').eq(2).addClass('a-cut-style');
        $('.header-cut').eq(1).addClass('a-cut-style');
        $('.a-shop-pop').show();
        $('.a-myshop em').addClass('a-orange-style');
        $('.a-myshop .a-shop').addClass('a-shop2');
        $('.a-myshop .a-down').addClass('a-down2');
    })
    $('.a-myshop').mouseleave(function () {
        $('.header-cut').eq(2).removeClass('a-cut-style');
        $('.header-cut').eq(1).removeClass('a-cut-style');
        $('.a-shop-pop').hide();
        $('.a-myshop em').removeClass('a-orange-style');
        $('.a-myshop .a-shop').removeClass('a-shop2');
        $('.a-myshop .a-down').removeClass('a-down2');
    })
    //	消息列的的变化
    $('.a-messagebox').mouseenter(function () {
        $('.header-cut').eq(0).addClass('a-cut-style');
        $('.header-cut').eq(1).addClass('a-cut-style');
        $('.a-messagebox em').addClass('a-orange-style');
        $('.a-messagebox .a-message').addClass('a-message2');
    })
    $('.a-messagebox').mouseleave(function () {
        $('.header-cut').eq(1).removeClass('a-cut-style');
        $('.header-cut').eq(0).removeClass('a-cut-style');
        $('.a-messagebox em').removeClass('a-orange-style');
        $('.a-messagebox .a-message').removeClass('a-message2');
    })
    //	最新公告的变化
    $('.a-noticebox').mouseenter(function () {
        $('.header-cut').eq(4).addClass('a-cut-style');
        $('.header-cut').eq(5).addClass('a-cut-style');
        $('.a-noticebox em').addClass('a-orange-style');
        $('.a-noticebox .a-notice').addClass('a-notice2');
    })
    $('.a-noticebox').mouseleave(function () {
        $('.header-cut').eq(4).removeClass('a-cut-style');
        $('.header-cut').eq(5).removeClass('a-cut-style');
        $('.a-noticebox em').removeClass('a-orange-style');
        $('.a-noticebox .a-notice').removeClass('a-notice2');
    })
    //	流浪记录弹窗
    $('.a-record').mouseenter(function () {
        $('.header-cut').eq(2).addClass('a-cut-style');
        $('.header-cut').eq(3).addClass('a-cut-style');
        $('.a-record-pop').show();
        $('.a-record em').addClass('a-orange-style');
        $('.a-record .a-eye').addClass('a-eye2');
        $('.a-record .a-down').addClass('a-down2');
    })
    $('.a-record').mouseleave(function () {
        $('.header-cut').eq(2).removeClass('a-cut-style');
        $('.header-cut').eq(3).removeClass('a-cut-style');
        $('.a-record-pop').hide();
        $('.a-record em').removeClass('a-orange-style');
        $('.a-record .a-eye').removeClass('a-eye2');
        $('.a-record .a-down').removeClass('a-down2');
    })

    //	手机弹窗
    $('.a-phonebox').mouseenter(function () {

        $('.header-cut').eq(4).addClass('a-cut-style');
        $('.header-cut').eq(3).addClass('a-cut-style');
        $('.a-phone-pop').show();
        $('.a-phonebox em').addClass('a-orange-style');
        $('.a-phonebox .a-phone').addClass('a-phone2');
        $('.a-phonebox .a-down').addClass('a-down2');
    })
    $('.a-phonebox').mouseleave(function () {
        $('.header-cut').eq(4).removeClass('a-cut-style');
        $('.header-cut').eq(3).removeClass('a-cut-style');
        $('.a-phone-pop').hide();
        $('.a-phonebox em').removeClass('a-orange-style');
        $('.a-phonebox .a-phone').removeClass('a-phone2');
        $('.a-phonebox .a-down').removeClass('a-down2');
    })
    //安卓ios图标字体的变化
    $('.a-iosbox').mouseenter(function () {
        $('.a-ios').addClass('a-ios2')
        $('.a-iosbox span').addClass('a-white-style')
    })
    $('.a-iosbox').mouseleave(function () {
        $('.a-ios').removeClass('a-ios2')
        $('.a-iosbox span').removeClass('a-white-style')
    })
    $('.a-androidbox').mouseenter(function () {
        $('.a-android').addClass('a-android2')
        $('.a-androidbox span').addClass('a-white-style')
    })
    $('.a-androidbox').mouseleave(function () {
        $('.a-android').removeClass('a-android2')
        $('.a-androidbox span').removeClass('a-white-style')
    })
    //清除所有样式旁边箭头js
    $('.page_group4 a:first').mouseenter(function () {
        $('.a-pre').addClass('a-pre2')
    })
    $('.page_group4 a:first').mouseleave(function () {
        $('.a-pre').removeClass('a-pre2')
    })
    $('.page_group4 a:last').mouseenter(function () {
        $('.a-next').addClass('a-next2')
    })
    $('.page_group4 a:last').mouseleave(function () {
        $('.a-next').removeClass('a-next2')
    })


    var o = setInterval(function () {
        $(".roll_keys").animate({
            marginTop: -20
        }, 500, function () {
            $(".roll_keys dd:last").after($(".roll_keys dd:first"));
            $(".roll_keys").css({
                marginTop: 0
            });
        });
    }, 4000)
    $(".rollkeys_box").mouseenter(function () {
        clearInterval(o);
    }).mouseleave(function () {
        o = setInterval(function () {
            $(".roll_keys").animate({
                marginTop: -20
            }, 500, function () {
                $(".roll_keys dd:last").after($(".roll_keys dd:first"));
                $(".roll_keys").css({
                    marginTop: 0
                });
            });
        }, 4000)
    })

//    //弹出城市切换
//    $('.chcity').on('click', function () {
//        layer.open({
//            type: 2,
//            title: false,
//            shadeClose: true,
//            //点击遮罩关闭层
//            //		scrollbar: false,
//            area: ['655px', '380px'],
//            content: 'http://127.0.0.1:8020/pc/oneyuan/changecity.html'
//        });
//    });
//    //点击城市名高亮该城市
//    $('.hotcityname span').click(function () {
//        $(this).addClass('active').siblings().removeClass('active');
//    });
//    $('.procity li').click(function () {
//        $(this).addClass('active').siblings().removeClass('active');
//    });
    //互斥处理
    $('.selfsh').click(function () {
        $('.hotcityname span').removeClass('active');
        $('.pro_menu li').removeClass('active');
        $('.procity li').removeClass('active');
        $('.procity ').hide();
    });
    $('.provincesname').click(function () {
        $('.hotcityname span').removeClass('active');
    });
    $('.hotcityname span').click(function () {
        $('.pro_menu li').removeClass('active');
        $('.procity li').removeClass('active');
        $('.procity ').hide();
    });
    $('.pro_menu li').click(function () {
        $('.procity li').removeClass('active');
    });
    //按省份查询
    //切花出现三角形框框

    var $procity1 = $("div.pro_menu1 ul li");
    var $procity2 = $("div.pro_menu2 ul li");
    var $procity3 = $("div.pro_menu3 ul li");
    $procity1.click(function () {
        $(this).addClass("active").siblings().removeClass("active");
        var index = $procity1.index(this);
        $("div.pro_box1 > div").eq(index).show().siblings().hide();
        $("div.pro_box2 > div").hide();
        $("div.pro_box3 > div").hide();
        $('div.pro_menu2 ul li').removeClass('active');
        $('div.pro_menu3 ul li').removeClass('active');
    });
    //一排切换结束

    $procity2.click(function () {
        $(this).addClass("active").siblings().removeClass("active");
        var index = $procity2.index(this);
        $("div.pro_box2 > div").eq(index).show().siblings().hide();
        $("div.pro_box1 > div").hide();
        $("div.pro_box3 > div").hide();
        $('div.pro_menu1 ul li').removeClass('active');
        $('div.pro_menu3 ul li').removeClass('active');
    });
    //二排切换结束

    $procity3.click(function () {
        $(this).addClass("active").siblings().removeClass("active");
        var index = $procity3.index(this);
        $("div.pro_box3 > div").eq(index).show().siblings().hide();
        $("div.pro_box2 > div").hide();
        $("div.pro_box1 > div").hide();
        $('div.pro_menu2 ul li').removeClass('active');
        $('div.pro_menu1 ul li').removeClass('active');
    });
    //二排切换结束
    $('.procity').click(function () {
//	$(this).hide();
        $('div.pro_menu ul li').removeClass('active');
    })
})


/*跳页的js*/
function goToUrl(jqclass, obj) {
    var cls = $(obj).parent().find(jqclass);
    var num = parseInt(cls.val());
    if (isNaN(num))
        return;
    var url = $(obj).attr('data-url').replace(/p\/(\d)+\.?/g, "p/" + num + ".");
    window.location.href = url;
}


/**
 * 关注、取消关注
 * @param {type} uid 用户ID
 * @param {type} sid phpSessionId
 * @param {type} t 1关注0取消关注
 * @param {type} callback 回调函数
 * @returns {undefined}
 */
function follow(uid, sid, t, callback) {
    if (t == "0") {
        $.post(U('Core/Public/follow@'), {uid: uid, cookie: sid}, callback, 'json');
    } else {
        $.post(U('Core/Public/unfollow@'), {uid: uid, cookie: sid}, callback, 'json');
    }
}

//生成头部购物车下拉视图
function gen_cart_list() {
    $.getScript($('._cart_goods_url').val(), function () {
        var num = '';
        var str = new Array();
        str.push('<h3 class="px12">最近加入的爱拍</h3>');
        if (cart_list.status == 1 && cart_list.data.length > 0) {
            num = cart_list.count;
            for (var i = 0; i < cart_list.data.length; i++) {
                str.push(get_cart_view_html(cart_list.data[i]));
            }
        } else {
            str.push('<li class="">亲，您的购物车空空如也。</li>');
        }
        str.push('<div class="clear"></div>');
        str.push('<div class="clear a-hr "></div>');
        str.push('<a class="a-goshop white back_orange bdr right px12" href="' + $('._cart_list').val() + '">查看我的购物车</a>');
        $('.a-shop-pop').html(str.join(""));
        $('.a-shop-num').html(num);
    });
}

function get_cart_view_html(data) {
    var str = new Array();
    str.push('<li>');
    str.push('<a href="' + data.url + '">');
    str.push('<div class="a-shop-img left">');
    str.push('<p class="a-black"></p>');
    str.push('<img src="' + data.img + '" alt="' + data.title + '"/>');
    str.push('</div>');
    str.push('</a>');
    str.push('<a href="' + data.url + '">');
    str.push('<div class="a-shop-data left">');
    str.push(data.title);
    str.push('</div>');
    str.push('</a>');
    str.push('<div class="a-shop-operate right">');
    str.push('<p>');
    str.push('¥<span class="orange">' + data.price + '</span>');
    str.push('</p>');
    str.push('<a class="a-delete" data-id="' + data.id + '">删除</a>');
    str.push('</div>');
    str.push('</li>');
    return str.join("");
}

/**
 * 检查登录，如未登录则弹窗
 * @returns {undefined}
 */
function check_login_open_win() {
    if (!UID) {
        $('#login a').click();
        return false;
    }
    return true;
}