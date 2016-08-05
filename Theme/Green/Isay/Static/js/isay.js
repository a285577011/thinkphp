/**
 * Created by Administrator on 15-5-7.
 */
var pre_reply = '';
var at_uid = 0;

$(function () {
    bind_reply_isay_comment();
    bind_send_isay_comment();
    bind_delete_isay_comment();
    bind_like();
    bind_reply_comment();
    bind_reply_at_comment();
    bind_comment_like();
    bind_share_tool();
})

$(function () {
    $.post(U('Core/Public/atWhoJson'), {}, function (res) {
        atwho_config = {
            at: "@",
            data: res,
            tpl: "<li data-value='[at:@${id}]'><img class='avatar-img' style='width:2em;margin-right: 0.6em' src='${avatar32}'/>${nickname}</li>",
            show_the_at: true,
            search_key: 'search_key',
            start_with_space: false
        };
        $('.comments textarea').atwho(atwho_config);
    }, 'json');

    $('.myicnbox').delegate('._isay_lun', 'click', function () {
        if ($(this).hasClass("say_lun2")) {
            $(this).removeClass("say_lun2").addClass("say_lun1");
            $(this).parents('.myicnbox').find('.lunout').fadeIn();
        } else {
            $(this).removeClass("say_lun1").addClass("say_lun2");
            $(this).parents('.myicnbox').find('.lunout').fadeOut();
        }
    })
});

var bind_reply_isay_comment = function () {
    $('body').undelegate('[data-role="reply_isay_comment"]', 'click');
    $('body').delegate('[data-role="reply_isay_comment"]', 'click', function () {
        $('._txt_reply_comment_' + $(this).attr('data-cid')).slideToggle();
    })
}


var bind_send_isay_comment = function () {
    $('body').undelegate('[data-role="send_isay_comment"]', 'click');
    $('body').delegate('[data-role="send_isay_comment"]', 'click', function (e) {
        e.preventDefault();
        toast.showLoading();
        var $this = $(this);
        var $textarea = $this.closest('.comments').find('textarea');
        var url = $this.attr('data-url');
        var isay_id = $this.attr('data-isay_id');
        var content = $textarea.val();
        var extra = $this.attr('data-extra');
        //$('#submit-comment').attr('disabled', 'disabled');

        $.post(url, {content: content, isay_id: isay_id, extra: extra}, function (res) {
            if (res.status) {
                var $list = $this.closest('.myicnbox').find('section');
                var $totalCount = $this.closest('.myicnbox').find('._total_count');
                $totalCount.text(parseInt($($totalCount[0]).text()) + 1);
                $list.prepend(res.data);
                $textarea.val('');

                toast.success(res.info);
            } else {
                handleAjax(res);
            }
            toast.hideLoading();
        }, 'json');
    })
}


var bind_delete_isay_comment = function () {
    $('body').undelegate('[data-role="delete_isay_comment"]', 'click');
    $('body').delegate('[data-role="delete_isay_comment"]', 'click', function () {
        var $this = $(this);
        var id = $this.attr('data-id');
        var isay_id = $this.attr('data-isay_id');
        var url = $this.attr('data-del-url');
        $.post(url, {id: id, isay_id: isay_id}, function (msg) {
            if (msg.status) {
                var $totalCount = $this.closest('.myicnbox').find('._total_count');
                $totalCount.text(parseInt($($totalCount[0]).text()) - 1);

                $this.closest('.otherlun').fadeOut();

                toast.success(msg.info, '温馨提示');
            } else {
                toast.error(msg.info, '温馨提示');
            }
        }, 'json');

    });
};

//回复评论的评论
var bind_reply_comment = function () {
    $('body').undelegate('[data-role="send_reply_comment"]', 'click');
    $('body').delegate('[data-role="send_reply_comment"]', "click", function () {
        var $this = $(this);
        var comment_id = $this.attr('data-cid');
        var url = $this.attr('data-url');
        var textarea = $('._txt_reply_comment_' + $(this).attr('data-cid')).find('textarea');
        var content = textarea.val();
        if (content.indexOf(pre_reply) < 0) {
            at_uid = 0;
        }
        var at = at_uid;
        $.post(url, {comment_id: comment_id, content: content, at: at}, function (msg) {
            if (msg.status) {
                var $list = $this.closest('.myicnbox').find('section');
                var $totalCount = $this.closest('.myicnbox').find('._total_count');
                $totalCount.text(parseInt($($totalCount[0]).text()) + 1);
                $list.prepend(msg.data);
                textarea.val('');
                pre_reply = '';
                at_uid = 0;
                toast.success(msg.info, '温馨提示');
            } else {
                toast.error(msg.info, '温馨提示');
            }
        }, 'json');

    });
};

//点赞
var bind_like = function () {
    $('body').undelegate('[data-role="send_dolike"]', 'click');
    $('body').delegate('[data-role="send_dolike"]', 'click', function () {
        var url = $(this).attr('data-href');
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        var obj = this;
        if (MID == 0) {
            toast.error('请在登陆后再点赞。', '温馨提示');
            return;
        }
        $.post(url, {isay_id: id, type: type}, function (msg) {
            if (msg.status == 1) {
                $(obj).parents('.saybtn').html(msg.html);
                $(obj).removeAttr('data-role');
                toast.success(msg.info, '温馨提示');
            } else {
                toast.error(msg.info, '温馨提示');
            }

        }, 'json');


    });
}

//评论点赞
var bind_comment_like = function () {
    $('body').undelegate('[data-role="send_comment_dolike"]', 'click');
    $('body').delegate('[data-role="send_comment_dolike"]', 'click', function () {
        var obj = this;
        var url = $(this).attr('data-href');
        var id = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        if (MID == 0) {
            toast.error('请在登陆后再点赞。', '温馨提示');
            return;
        }
        $.post(url, {cid: id, type: type}, function (msg) {
            if (msg.status == 1) {
                $(obj).removeAttr('data-role').removeClass('say_zan1').addClass("say_zan2");
                $(obj).parent().find('._comment_like_up').text(msg.count);
                toast.success(msg.info, '温馨提示');
            } else {
                toast.error(msg.info, '温馨提示');
            }

        }, 'json');


    });
}


var bind_reply_at_comment = function () {
    $('body').undelegate('[data-role="reply_at_comment"]', 'click');
    $('body').delegate('[data-role="reply_at_comment"]', 'click', function () {
        var $this = $(this);
        var nickname = $this.attr('data-nickname');
        var $textarea = $('._txt_reply_comment').find('textarea');
        pre_reply = '回复 @' + nickname + ' ：';
        $textarea.focus();
        if (nickname != '') {
            $textarea.val(pre_reply);
            at_uid = $(this).attr('data-uid');
        } else {
            $textarea.val('');
        }
    })
}

var bind_share_tool = function () {
    $('body').undelegate('[data-role="share_role"]', 'click');
    $('body').delegate('[data-role="share_role"]', 'click', function () {
        if ($(this).hasClass('say_share2')) {
            $(this).parent().find('.sharepath').show();
            $(this).removeClass('say_share2').addClass('say_share1');
        } else {
            $(this).parent().find('.sharepath').hide();
            $(this).removeClass('say_share1').addClass('say_share2');
        }
    });
}




