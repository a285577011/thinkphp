function is_login() {
    return parseInt(MID);
}


function T() {
return document.location.protocol+'//';
}

function U(url, params, rewrite) {

    var root = _ROOT_;   
    var at=url.split('@');
    if (url.indexOf("@")>=0) { 
        if(!at[1]){
            root=T()+'i.cn';
        }else{
           root = T()+at[1];  
        }       
    }
    url = at[0].split('/'); 
    
    if (url[0] == '' || url[0] == '@')
        url[0] = 'Forum';
    if (!url[1])
        url[1] = 'Index';
    if (!url[2])
        url[2] = 'index';


    if (ICN.MODEL[0] == 2) {
        var website = root + '/';
        website = website + '' + url[0] + '/' + url[1] + '/' + url[2];

        if (params) {
            params = params.join('/');
            website = website + '/' + params;
        }
        if (!rewrite) {
            website = website + '.html';
        }

    } else {
        var website = root + '/index.php';
        website = website + '?s=/' + url[0] + '/' + url[1] + '/' + url[2];
        if (params) {
            params = params.join('/');
            website = website + '/' + params;
        }
        if (!rewrite) {
            website = website + '.html';
        }
    }

    if (typeof (ICN.MODEL[1]) != 'undefined') {
        website = website.toLowerCase();
    }
    return website;
}


var weiquan = {
    page: 1,
    lastId: 0,
    loadCount: 1,
    url: '',
    type: 'all',
    noMoreNextPage: false,
    isLoadingWeibo: false,
    isLoadMoreVisible: function () {
        var visibleHeight = $(window.top).height();
        var loadMoreOffset = $('#load_more').offset();

        return visibleHeight + $(window).scrollTop() >= loadMoreOffset.top;
    },
    loadNextPage: function () {
        if (this.loadCount == 3) {
            $('#index_weibo_page').show();
            $('#load_more').hide();
        }

        if (this.page == 1 && this.loadCount < 3) {
            this.loadCount++;
            this.loadWeiquanList();
        }

        if (this.page > 1) {
            this.loadWeiquanList();
        }
    },
    reloadWeiquanList: function () {
        this.loadCount = 1;
        this.loadWeiquanList(1, function () {
            this.clearWeiboList();
            this.page = 1;
        });
    },
    loadWeiquanList: function () {
        //默认载入第1页
        if (this.page == undefined) {
            this.page = 1;
        }
        //通过服务器载入微博列表
        this.isLoadingWeibo = true;
        $('#load_more_text').html('<img style="margin-top:80px" src="' + ICN.ROOT + '/Application/Weiquan/Static/images/loading-new.gif"/>');
        $.get(this.url, {page: this.page, lastId: this.lastId, type: this.type, loadCount: this.loadCount}, function (a) {
            if (a.status == 0) {
                weiquan.noMoreNextPage = true;
                $('#load_more_text').text('没有了');
            }
            $('#weibo_list').append(a);
            $('#load_more_text').text('');
            weiquan.isLoadingWeibo = false;
            weiquan_bind();
            bind_atwho();

        });
    },
    clearWeiquanList: function () {
        this.page = 0;
        $('#weibo_list').html('');
    }

};

var bind_weiquan_popup = function () {
    $('.popup-gallery').each(function () {
        $(this).magnificPopup({
            delegate: 'a',
            type: 'image',
            tLoading: '正在载入 #%curr%...',
            mainClass: 'mfp-img-mobile',
            gallery: {
                enabled: true,
                navigateByImgClick: true,
                preload: [0, 1]
            },
            image: {
                tError: '<a href="%url%">图片 #%curr%</a> 无法被载入.',
                titleSrc: function (item) {
                    return '';
                },
                verticalFit: false
            }
        });
    });
}


$(function () {
    $('#weibo_content').keypress(function (e) {
        if (e.ctrlKey && e.which == 13 || e.which == 10) {
            $(this).parents('.weibo_post_box').find("[data-role='send_weiquan']").click();
        }
    });
    //send_weiquan();
})


var WEIQUAN_CONTENT_CLASS = '.weibo_post_box';


insert_topic = {
    find: function (obj) {
        return $(this.obj).parents(WEIQUAN_CONTENT_CLASS).find(obj);
    },
    obj: 0,
    InsertTopic: function (obj) {
        this.obj = obj;
        var textbox = this.find("#weibo_content");
        var text = '请在这里输入自定义话题';
        textbox.val(textbox.val() + "#" + text + "#");
        var len = textbox.val().length;
        textbox.selectRange(len - text.length - 1, len - 1);
    }
}



$(function () {
    $.fn.selectRange = function (start, end) {
        return this.each(function () {
            if (this.setSelectionRange) {
                this.focus();
                this.setSelectionRange(start, end);
            } else if (this.createTextRange) {
                var range = this.createTextRange();
                range.collapse(true);
                range.moveEnd('character', end);
                range.moveStart('character', start);
                range.select();
            }
        });
    };

})

insert_image = {
    find: function (obj) {
        return $(this.obj).parents(WEIQUAN_CONTENT_CLASS).find(obj);
    },
    obj: 0,
    insertImage: function (obj) {
        if (insert_image.obj != 0)
            insert_image.close();
        insert_image.obj = obj;
        this.find('#insert_image').attr('onclick', 'insert_image.showBox()');
        var box_url = this.find('#box_url').val();
        $.post(U('Weiquan/type/imagebox'), {}, function (res) {
            var html = '<div class="XT_image XT_insert"><div class="triangle sanjiao" style="margin-left: 30px;"></div><div class="triangle_up sanjiao"  style="margin-left: 30px;"></div>' +
                    '<div class="XT_face_main XT_insert_image" style="margin-left: 0px;"><div class="XT_face_title"><span class="XT_face_bt" style="float: left"><span>共&nbsp;<span id="upload_num_' + res.unid + '">0</span>&nbsp;张，还能上传&nbsp;<span id="total_num_' + res.unid + '">' + res.total + '</span>&nbsp;张（按住ctrl可选择多张）</span></span>' +
                    '<a onclick="insert_image.close()" class="XT_face_close">X</a></div><div id="face" style="padding: 10px;">' + res.html + '</div></div></div>';
            insert_image.find('#hook_show').html(html);
        }, 'json');
    },
    close: function () {
        this.find('.XT_image').remove();
        this.find('.attach_ids').remove();
        this.find('#insert_image').attr('onclick', 'insert_image.insertImage(this)');
        this.obj = 0;
    },
    showBox: function () {
        $('.XT_image').css('z-index', '1005');
    }


}





var weiquan_comment = function () {
    $('[data-role="weibo_comment_btn"]').unbind('click');
    $('[data-role="weibo_comment_btn"]').click(function (e) {
        var weibo_id = $(this).attr('data-weibo-id');
        var weiboCommentList = $('#weibo_' + weibo_id + ' .weibo-comment-list');
        if (weiboCommentList.is(':visible')) {
            hide_weiquan_comment_list(weiboCommentList);
        } else {
            show_weiquan_comment_list(weiboCommentList);
        }
        //取消默认动作
        e.preventDefault();
        return false;
    })
}

var show_weiquan_comment_list = function (weiboCommentList) {

    if (weiboCommentList.text().trim() == '') {
        var weibo_id = weiboCommentList.attr('data-weibo-id');
        $.post(U('Index/loadComment'), {weiquan_id: weibo_id}, function (res) {
            var html = '<div class="col-xs-12"><div class="light-jumbotron weibo-comment-block" style="padding: 1em 2em;"><div class="weibo-comment-container"></div></div></div>';
            weiboCommentList.html(html);
            weiboCommentList.find('.weibo-comment-container').html(res.html);
            weiquan_bind();
            bind_atwho();
        }, 'json');
    }

    weiboCommentList.show();
    show_comment_textarea(weiboCommentList.find('.single_line'))
}

var hide_weiquan_comment_list = function (weiboCommentList) {
    weiboCommentList.hide();
}

var show_all_comment = function (weiboId) {
    $.post(U('Index/commentlist'), {weiquan_id: weiboId, show_more: 1}, function (res) {
        $('#show_comment_' + weiboId).append(res);
        $('#show_all_comment_' + weiboId).hide()
    }, 'json');
}

var show_comment_textarea = function (obj) {
    obj.closest('.col-xs-12').hide();
    obj.closest('.col-xs-12').next().show();
    obj.closest('.col-xs-12').next().find('textarea').focus();
}


var weiquan_reply = function () {
    $('[data-role="weibo_reply"]').unbind('click');
    $('[data-role="weibo_reply"]').click(function () {
        var weibo_comment = $(this).closest('.weibo_comment');
        var weibo_id = weibo_comment.attr('data-weibo-id');
        var comment_id = weibo_comment.attr('data-comment-id');
        var nickname = $(this).attr('data-user-nickname');
        var weibo = $('#weibo_' + weibo_id);
        var textarea = $('.weibo-comment-content', weibo);
        var content = textarea.val();
        var weiboToCommentId = $('[name="reply_id"]', weibo);

        show_comment_textarea($('.single_line', weibo));

        weiboToCommentId.val(comment_id);
        textarea.focus();
        textarea.val('回复 @' + nickname + ' ：');
    })
}


var do_comment = function () {
    $('[data-role="do_comment"]').unbind('click');
    $('[data-role="do_comment"]').click(function () {
        var weiboId = $(this).attr('data-weibo-id');
        var weibo = $('#weibo_' + weiboId);
        var content = $('.weibo-comment-content', weibo).val();
        var url = U('Index/doComment');
        var commitButton = $(this);
        var weiboCommentList = $('.weibo-comment-list', weibo);
        var originalButtonText = commitButton.text();
        commitButton.text('正在发表...').addClass('disabled');
        var weiboToCommentId = $('[name="reply_id"]', weibo);
        var comment_id = weiboToCommentId.val();
        $.post(url, {weiquan_id: weiboId, content: content, comment_id: comment_id}, function (a) {
            handleAjax(a);
            if (a.status) {

                if (weiquan_comment_order == 1) {
                    var comment_list = $('#show_comment_' + weiboId)
                    comment_list.attr('data-comment-count', parseInt(comment_list.attr('data-comment-count')) + 1)
                    var count = comment_list.attr('data-comment-count');
                    weiquan_page(weiboId, Math.ceil(count / 10));
                } else {
                    $('#show_comment_' + weiboId).prepend(a.html);
                }
                commitButton.text(originalButtonText);
                commitButton.removeClass('disabled');
                $('.weibo-comment-content', weibo).val('');
                $('.XT_face').remove();
                weiquan_bind();
            } else {
                commitButton.text(originalButtonText);
                commitButton.removeClass('disabled');
            }
        });

    })
}

var weiquan_comment_page = function (weibo_id, page) {
    $.post(U('Index/commentlist'), {weiquan_id: weibo_id, page: page}, function (res) {
        $('#show_comment_' + weibo_id).html(res);
        weiquan_bind();
        if (page == 1) {
            $('#show_all_comment_' + weibo_id).show()
        } else {
            $('#show_all_comment_' + weibo_id).hide()
        }
    }, 'json');
}

var weiquan_like_page = function (weiquan_id, page) {
    $.post(U('Index/likelist'), {weiquan_id: weiquan_id, page: page}, function (res) {
        $('._praise_pop_' + weiquan_id).html(res);
        weiquan_bind();
    }, 'json');
}




/**
 * 评论微博
 * @param obj
 * @param comment_id 评论ID
 */
var comment_del = function (obj, comment_id) {

    $('[data-role="comment_del"]').unbind('click');
    $('[data-role="comment_del"]').click(function () {

        if (confirm("你确信要删除这条评论？")) {
            var weiquan_comment = $(this).closest('.weibo_comment');
            var comment_id = weiquan_comment.attr('data-comment-id');
            var url = U('Index/doDelComment');
            $.post(url, {comment_id: comment_id}, function (msg) {
                if (msg.status) {
                    weiquan_bind();
                    weiquan_comment.prev().fadeOut();
                    weiquan_comment.fadeOut();
                    toast.success(msg.info, '温馨提示');
                } else {
                    toast.error(msg.info, '温馨提示');
                }
            }, 'json');
        }
    });
};



var del_weiquan = function () {
    $('[data-role="del_weibo"]').unbind('click');
    $('[data-role="del_weibo"]').click(function () {

        if (confirm("你确信要删除这条动态？")) {
            var $this = $(this);
            var weibo_id = $this.attr('data-weibo-id');
            $.post(U('Index/doDelWeiquan'), {weiquan_id: weibo_id}, function (msg) {
                if (msg.status) {
                    weiquan_bind();
                    $this.closest('#weibo_' + weibo_id).fadeOut();
                    toast.success('删除微博成功。', '温馨提示');
                }
            }, 'json');
        }
    });
}

var weiquan_set_top = function () {
    $('[data-role="weibo_set_top"]').unbind('click');
    $('[data-role="weibo_set_top"]').click(function () {
        var weiboId = $(this).attr('data-weibo-id');
        $.post(U('Index/setTop'), {weiquan_id: weiboId}, function (msg) {
            if (msg.status) {
                toast.success(msg.info);
                setTimeout('location.reload()', 500);
            } else {
                toast.error(msg.info);
            }
        });
    })
}






$(function () {
   // weiquan_bind();
    chose_topic();
    $.post(U('Core/Public/atWhoJson@'), {}, function (res) {
        atwho_config = {
            at: "@",
            data: res,
            tpl: "<li data-value='[at:${id}]'><img class='avatar-img' style='width:2em;margin-right: 0.6em' src='${avatar32}'/>${nickname}</li>",
            show_the_at: true,
            search_key: 'search_key',
            start_with_space: false
        };
        bind_atwho();
        $('#weibo_content').atwho(atwho_config);
    }, 'jsonp')





})

var bind_lazy_load = function () {
    $("img.lazy").lazyload({effect: "fadeIn", threshold: 200, failure_limit: 100});
}

//zzl显示隐藏置顶微博
var unshow_top_weiquan_ids = function (unshow_ids, id) {
    var newArr = [];
    if (unshow_ids != undefined) {
        var attachArr = unshow_ids.split(',');
        for (var i in attachArr) {
            if (attachArr[i] !== '' && attachArr[i] !== id.toString()) {
                newArr.push(attachArr[i]);
            }
        }
    }
    newArr.push(id);
    unshow_ids = newArr.join(',');
    return unshow_ids;
}

var hide_top_weiquan = function () {
    $('[data-role="hide_top_weibo"]').unbind('click');
    $('[data-role="hide_top_weibo"]').click(function () {
        var weiboId = $(this).attr('data-weibo-id');
        $(this).parents('.top_can_hide').hide();
        if (!$('[data-role="show_all_top_weibo"]').is(':visited')) {
            $('[data-role="show_all_top_weibo"]').show();
        }
        toast.success('隐藏成功！');
        //写入cookie
        var unshow_top_weibo = $.cookie('Weibo_index_top_hide_ids');
        unshow_top_weibo = unshow_top_weibo_ids(unshow_top_weibo, weiboId);
        $.cookie('Weibo_index_top_hide_ids', unshow_top_weibo, {expires: 365});
    });
}

var show_all_top_weiquan = function () {
    $('[data-role="show_all_top_weibo"]').unbind('click');
    $('[data-role="show_all_top_weibo"]').click(function () {
        $('#top_list').children('.top_can_hide').show();
        $(this).hide();
        toast.success('操作成功！');
        //清空cookie
        $.cookie('Weibo_index_top_hide_ids', null);
    });
}



var bind_image = function () {


    var openPhotoSwipe = function () {
        var pswpElement = document.querySelectorAll('.pswp')[0];

        var options = {
            history: false,
            focus: false,
            showAnimationDuration: 0,
            hideAnimationDuration: 0

        };

        var items = [];

        $(this).children('a').each(function (index) {
            var _src = $(this).attr('data');
            var _size = $(this).attr('data-size').split('x');
            items[index] = {
                src: _src,
                w: _size[0],
                h: _size[1]
            };

        });

        var gallery = new PhotoSwipe(pswpElement, PhotoSwipeUI_Default, items, options);
        gallery.init();
    };
//点击图集元素时触发调用openPhotoSwipe
    $('.photos').click(openPhotoSwipe);

}
var bind_atwho = function () {
    $('.weibo-comment-content').atwho(atwho_config);
}



var clear_weiquan = function () {
    $('.weibo_post_box #weibo_content').val('');
}



var do_send_repost = function () {
    $('[data-role="do_send_repost"]').unbind('click')
    $('[data-role="do_send_repost"]').click(function () {
        //获取参数
        var url = $(this).attr('data-url');
        var content = $('#repost_content').val();
        var button = $(this);
        var originalButtonText = button.val();
        var feedType = 'repost';
        var sourceId = button.attr('data-source-id');
        var weiboId = button.attr('data-weibo-id');
        var becomment = document.getElementsByName("becomment")
        //发送到服务器
        $.post(url, {content: content, type: feedType, sourceId: sourceId, weiboId: weiboId, becomment: becomment[0].checked}, function (a) {
            handleAjax(a);
            if (a.status) {
                $('.mfp-close').click();
                button.attr('class', 'btn btn-primary');
                button.val(originalButtonText);
                if (MODULE_NAME == 'Weiquan' && ACTION_NAME == 'index' && CONTROLLER_NAME == 'Index') {
                    setTimeout(function () {
                        $('#weibo_list').prepend(a.html)
                        weiquan_bind();
                        bind_atwho();
                    }, 1000)
                }

                $('.XT_face').remove();
                insert_image.close();

            }
        });
    });
}


var to_be_number_one = function (tid) {
    $.post(U('weibo/topic/beAdmin@'), {tid: tid}, function (msg) {
        handleAjax(msg);
    })
}


var show_comment = function (weiboId) {
    var obj = $('#show_comment_' + weiboId + ' > div');
    obj.show();
    $('#show_comment_' + weiboId).next().hide()
}


var bind_single_line = function () {
    $('.single_line').unbind('focus');
    $('.single_line').focus(function () {
        show_comment_textarea($(this));
    })
}



var chose_topic = function () {
    $('[data-role="chose_topic"]').click(function () {
        var $textarea = $(this).parents('.weibo_post_box').find('#weibo_content');
        $textarea.val($textarea.val() + $(this).text());
    })
}


var bind_show_video = function () {
    $('[data-role="show_video"]').click(function () {
        var html = '<embed src="' + $(this).attr('data-src') + '" wmode="transparent" allowfullscreen="true" loop="false" type="application/x-shockwave-flash" style="width: 100%;height:350px;" autostart="false"></embed>';
        $(this).html(html).removeAttr('style');
    });
}

var siwth_comment_like = function () {
    $('.comment_box,.praise_box').unbind('mouseover');
    $(".comment_box,.praise_box").mouseover(function () {
        var id = $(this).attr('data-id');
        if ($(this).hasClass("comment_box")) {
            $('._comment_pop_' + id).show();
            $('._praise_pop_' + id).hide();
        } else {
            $('._praise_pop_' + id).show();
            $('._comment_pop_' + id).hide();
        }
    });
}

/**
 * 绑定微商圈点赞事件
 */
var bind_like = function () {

    var LIKE_ADD_URL = U('Index/doLike');
    var LIKE_DEL_URL = U('Index/doDelLike');
    $('.praise_box').unbind('click');
    $('.praise_box').click(function () {
        var is_detail = $(this).attr('data-detail');
        if (is_detail.length > 0 && parseInt(is_detail) != 0) {
            LIKE_ADD_URL = U('/Index/doLike',new Array('is_detail','1'));
            LIKE_DEL_URL = U('/Index/doDelLike',new Array('is_detail','1'));
        }


        var lng = $(this).find('._lng');
        if (MID == 0) {
            toast.error('请在登陆后再点赞。', '温馨提示');
            return;
        }
        var wid = $(this).attr('data-id');
        if ($(this).find("._is_true").length < 1) {
            $.post(LIKE_ADD_URL, {weiquan_id: wid}, function (msg) {
                if (msg.status == 1) {
                    $("._praise_pop_" + wid).html($(msg.html).filter("._praise_pop_" + wid).html());
                    lng.addClass("_is_true");
                    lng.find('span._like_text').html(msg.lng_cancel);
                    lng.find('span._count').html('');
                    //toast.success(msg.info, '温馨提示');
                } else {
                    toast.error(msg.info, '温馨提示');
                }

            }, 'json');
        } else {
            $.post(LIKE_DEL_URL, {weiquan_id: wid}, function (msg) {
                if (msg.status == 1) {
                    $("._praise_pop_" + wid).html($(msg.html).filter("._praise_pop_" + wid).html());
                    lng.removeClass("_is_true");
                    lng.find('span._like_text').html(msg.lng_like);
                    lng.find('span._count').html(msg.count);
                    //toast.success(msg.info, '温馨提示');
                } else {
                    toast.error(msg.info, '温馨提示');
                }

            }, 'json');
        }

    });
}

/**
 * 微圈图片特效
 * @returns {undefined}
 */
var view_scroll_image = function () {
    $('._trends_product_img').unbind('click');
    $('._trends_product_img').click(function () {
        var wp= $(this).parents('.weibo_content_p');
        var wid=$(this).parent().attr('data-wid');
        var list_img = $(this).parent().find('img');
        $(this).parents('.weibo_content_p').find("._product_pop_" + wid + " .rollimg_box_x").html('');
        var imgs_html = "";
        for (var i = 0; i < list_img.length; i++) {
            if ($(this).attr('data-id') == $(list_img[i]).attr('data-id')) {
                imgs_html += '<img class="action hand" src="' + $(list_img[i]).attr('src') + '" data-img="' + $(list_img[i]).attr('data-img') + '" data-id="' + $(list_img[i]).attr('data-id') + '" />';
                var bigImg = '<img  src="' + $(list_img[i]).attr('data-img') + '" />';
                $("._product_pop_" + wid + " .big_img").html(bigImg);
            } else {
                imgs_html += '<img  class="hand" src="' + $(list_img[i]).attr('src') + '" data-img="' + $(list_img[i]).attr('data-img') + '" data-id="' + $(list_img[i]).attr('data-id') + '" />';
            }
        }

        wp.find("._product_pop_" + wid + " .rollimg_box_x").width(list_img.length * 59);
        wp.find("._product_pop_" + wid + " .rollimg_box_x").html(imgs_html);
        wp.find("._product_pop_" + wid + " .rollimg_box_x img.action").click();
        wp.find(this).parent().hide();
        wp.find("._product_pop_" + wid).fadeIn(300);
    });

    $('.retract').unbind('click');
    $(".retract").click(function () {
        $("._product_pop_" + $(this).attr('data-wid')).hide();
        $(this).parent().parent().find("._trends_product").fadeIn(300);
    });

    $(".roll_box").delegate("img", "click", function () {
        var bigImg = $(this).parents(".product_pop").find('.big_img img');
        bigImg.hide();
        bigImg.attr('src', $(this).attr('data-img')).fadeIn(500);
        $(this).siblings().removeClass("action");
        $(this).addClass("action");
        if ($(this).index() > 7) {
            scrollImgNext($(this).parent());
        } else if ($(this).index() < 1) {
            scrollImgPrev($(this).parent());
        }
    });

    $('.big_img').delegate('img', 'mousemove', function (event) {
        var e = event || window.event;
        var scrollX = document.documentElement.scrollLeft || document.body.scrollLeft;
        var scrollY = document.documentElement.scrollTop || document.body.scrollTop;
        var x = e.pageX || e.clientX + scrollX;
        var y = e.pageY || e.clientY + scrollY;
        var iw = $(this).width();
        var ix = $(this).offset().left;
        var ih = $(this).height();
        var iy = $(this).offset().top;

        //右边
        if (x >= (ix + iw / 2) && x < (ix + iw) && y >= iy && y <= (ih + iy)) {
            $(this).removeClass('leftcursor').addClass('rightcursor');
        }

        //左边
        if (x > ix && x <= (ix + iw / 2) && x < (ix + iw) && y >= iy && y <= (ih + iy)) {
            $(this).removeClass('rightcursor').addClass('leftcursor');
        }

    });

    $('.big_img').delegate('img', 'click', function () {
        var currImg = $(this).parents('.product_pop').find(".rollimg_box img.action");
        var first = $(this).parents('.product_pop').find(".rollimg_box img").first();
        var last = $(this).parents('.product_pop').find(".rollimg_box img").last();
        if ($(this).hasClass('rightcursor')) {
            if (currImg.attr('data-id') != last.attr('data-id')) {
                $(this).parents('.product_pop').find(".rollimg_box img.action").removeClass('action').next().click();
                scrollImgNext($(this).parents('.product_pop').find(".rollimg_box_x"));
            }
        }
        if ($(this).hasClass('leftcursor')) {
            if (currImg.attr('data-id') != first.attr('data-id')) {
                $(this).parents('.product_pop').find(".rollimg_box img.action").removeClass('action').prev().click();
                scrollImgPrev($(this).parents('.product_pop').find(".rollimg_box_x"));
            }
        }
    });

    $('.left_roll').unbind('click');
    $('.left_roll').click(function () {
        scrollImgPrev($(this).parent().find('.rollimg_box_x'));
    });

    $('.right_roll').unbind('click');
    $('.right_roll').click(function () {
        scrollImgNext($(this).parent().find('.rollimg_box_x'));
    });

    var scrollImgNext = function (curr_img) {
        if (curr_img.find('img').length < 8)
            return;
        var toL = $(".right_roll").offset().left;
        var currPointX = $(curr_img).offset().left + $(curr_img).width();
        if ((toL - currPointX) < 0) {
            $(curr_img).animate({left: '-59px'}, "slow");
        }
    };
    var scrollImgPrev = function (curr_img) {
        if (curr_img.find('img').length < 8)
            return;
        var toL = $(".left_roll").offset().left;
        var currPointX = $(curr_img).offset().left + $(curr_img).width();
        if ((toL - currPointX) < 0) {
            $(curr_img).animate({left: '+0'}, "slow");
        }
    };
    
}


/*删除绑定*/
$(document).ready(function(){
    $('#weibo_list').delegate('.trends_section,.trends_section2','mouseover',function(){
        $(this).find('._weiquan_type_right').show();
    });
    
     $('#weibo_list').delegate('.trends_section,.trends_section2','mouseout',function(){
        $(this).find('._weiquan_type_right').hide();
    });
});
/*表情*/
function insertFace(obj) {
    $('.XT_insert').css('z-index', '1000');
    $('.XT_face').remove();
    var html = '<div class="XT_face  XT_insert"><div class="triangle sanjiao"></div><div class="triangle_up sanjiao"></div>' +
        '<div class="XT_face_main"><div class="XT_face_title"><span class="XT_face_bt" style="float: left">常用表情</span>' +
        '<a onclick="close_face()" class="XT_face_close">X</a></div><div id="face" style="padding: 10px;"></div></div></div>';
    obj.parents('.weibo_post_box').find('#emot_content').html(html);
    getFace(obj.parents('.weibo_post_box').find('#emot_content'), '');
};
function getFace(obj, pkg) {
    if (typeof pkg == 'undefined') {
        pkg = '';
    }
    $.post(U('Core/Expression/getSmile@'), {pkg: pkg}, function (res) {
        var expression = res.expression;
        var pkgList = res.pkgList;
        var _imgHtml = '';
        if (pkgList.length > 0) {
            if (pkgList.length > 1) {
                _imgHtml = "<div class='face-tab'><ul>";
                for (var e in pkgList) {
                    if (pkgList[e].name == res.pkg) {
                        _imgHtml += "<li class='active' ><a data-role='change_pkg'  data-name='" + pkgList[e].name + "'>" + pkgList[e].title + "</a></li>";
                    } else {
                        _imgHtml += "<li><a data-role='change_pkg' data-name='" + pkgList[e].name + "'>" + pkgList[e].title + "</a></li>";
                    }
                }
                _imgHtml += "</ul></div>";
            }
            for (var k in expression) {
                _imgHtml += '<a href="javascript:void(0)" data-type="' + expression[k].type + '" title="' + expression[k].title + '" onclick="face_chose($(this))";><img src="' + expression[k].src + '" width="24" height="24" /></a>';
            }
            _imgHtml += '<div class="c"></div>';
        } else {
            _imgHtml = '获取表情失败';
        }

        obj.find('#face').html(_imgHtml);
        bind_face_pkg()
    }, 'json');
}
function bind_face_pkg() {
    $('[data-role="change_pkg"]').unbind('click');
    $('[data-role="change_pkg"]').click(function () {
        var $this = $(this)
        var pkg = $this.attr('data-name');
        getFace($this.closest('#emot_content'), pkg);
    })
}
function face_chose(obj) {
    var textarea = obj.parents('.weibo_post_box').find('textarea');
    textarea.focus();
    //textarea.val(textarea.val()+'['+obj.attr('title')+']');

    var pos = getCursortPosition(textarea[0]);
    var s = textarea.val();
    if (obj.attr('data-type') == 'miniblog') {
        textarea.val(s.substring(0, pos) + '[' + obj.attr('title') + ']' + s.substring(pos));
        setCaretPosition(textarea[0], pos + 2 + obj.attr('title').length);
    } else {
        textarea.val(s.substring(0, pos) + '[' + obj.attr('title') + ':' + obj.attr('data-type') + ']' + s.substring(pos));
        setCaretPosition(textarea[0], pos + 3 + obj.attr('title').length + obj.attr('data-type').length);
    }
}
function getCursortPosition(ctrl) {//获取光标位置函数

    var CaretPos = 0;	// IE Support
    if (document.selection) {
        ctrl.focus();
        var Sel = document.selection.createRange();
        Sel.moveStart('character', -ctrl.value.length);
        CaretPos = Sel.text.length;
    }
    // Firefox support
    else if (ctrl.selectionStart || ctrl.selectionStart == '0')
        CaretPos = ctrl.selectionStart;
    return (CaretPos);
}
function setCaretPosition(ctrl, pos) {//设置光标位置函数
    if (ctrl.setSelectionRange) {
        ctrl.focus();
        ctrl.setSelectionRange(pos, pos);
    }
    else if (ctrl.createTextRange) {
        var range = ctrl.createTextRange();
        range.collapse(true);
        range.moveEnd('character', pos);
        range.moveStart('character', pos);
        range.select();
    }
}
function close_face() {
    $('.XT_face').remove();
}
function upAttachVal(type, attachId, obj) {
    var $attach_ids = obj;
    var attachVal = $attach_ids.val();
    var attachArr = attachVal.split(',');
    var newArr = [];
    for (var i in attachArr) {
        if (attachArr[i] !== '' && attachArr[i] !== attachId.toString()) {
            newArr.push(attachArr[i]);
        }
    }
    type === 'add' && newArr.push(attachId);
    $attach_ids.val(newArr.join(','));
    return newArr;
}