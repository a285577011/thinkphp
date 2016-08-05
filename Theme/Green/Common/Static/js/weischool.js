$(document).ready(function(){

//帖子管理btn
$('.managebtn').hover(function() {
	$('.mgbox').toggle();
});
$('.mgbox').hover(function() {
	$(this).toggle();
});

//点赞收藏悬浮效果两按钮
$('.heart').hover(function() {
	$('.heart > span').toggleClass('weiheart2');
});
$('.weiget').hover(function() {
	$('.weiget > span').toggleClass('weiget2');
});
//举报按钮的出现
$('.jubao').mouseleave(function() {
	var $watch = $(".jubao");
	var index = $watch.index(this);
	$(this).eq(index).hide();
});
$('.jbtrigon1').mouseenter(function() {
	var $watch = $(".jbtrigon1");
	var index = $watch.index(this);
	$('.jubao').eq(index).show();
});
$('.tebox').mouseleave(function() {
	$('.jubao').hide();
});

//		投稿成功
$('.fb').on('click', function() {
	layer.open({
		type: 1,
		title: false,
		shadeClose: true, //点击遮罩关闭层
		content: $('.upsuccbox'),
	});
});

<!--div+css=select-->

function show_select(input, btn, option, value) {
	inputobj = document.getElementById(input);
	btnobj = document.getElementById(btn);
	optionobj = document.getElementById(option);
	valueobj = document.getElementById(value);
	optionobj.style.display = optionobj.style.display == "" ? "none" : "";
	optionobj.onblur = function() {
		optionobj.style.display = "none";
	}
	for (var i = 0; i < optionobj.childNodes.length; i++) {
		optionobj.focus();
		optionobj.childNodes[i].onmouseover = function() {
			this.className = "qty_items_over";
		}
		optionobj.childNodes[i].onmouseout = function() {
			this.className = "qty_items_out";
		}
		optionobj.childNodes[i].onclick = function() {

			inputobj.innerHTML = this.innerHTML;
			valueobj.value = this.innerHTML;
			optionobj.blur();
			optionobj.style.display = "none";
			if (input == "pro_color") {
				loadpic(this.id, "");
			}
		}
	}
}
//			蒙板
$('#pro_qty').click(function() {
	$(this).toggleClass('nobottom');
	$('.topline').toggleClass('botline');
});
$('.fbtriangle').click(function() {
	$('#pro_qty').toggleClass('nobottom');
	$('.topline').toggleClass('botline');
});
$('#qty_items').click(function() {
	$('#pro_qty').removeClass('nobottom');
	$('.topline').toggleClass('botline');
});
//发布爱拍处理
//$('#qty_items_over').click(function() {
//	$("#qty_items").hide();
//});

//帖子分享浮动
$(window).resize(function() {
	var fixleft = ($(window).width() - 1200) / 2 - 60 + 'px';
	$(".sharesix").css("left", fixleft);
});
$(function() {
	var fixleft = ($(window).width() - 1200) / 2 - 60 + 'px';
	$(".sharesix").css("left", fixleft);
});

})

//微商学院收起回复

$('.lunbtnbox .pickupre').click(function(){
	var $div_li = $('.lunbtnbox .pickupre');
		var index = $div_li.index(this);
	$('.eachdiscuss').eq(index).hide();
	$(this).hide();
	$('.lunbtnbox  .lunpen').eq(index).show()
})
$('.lunbtnbox .lunpen').click(function(){
	var $div_li = $('.lunbtnbox .lunpen');
		var index = $div_li.index(this);
	$('.eachdiscuss').eq(index).show();
	$(this).hide();
	$('.lunbtnbox  .pickupre').eq(index).show()
})

//微商学院收起回复对回复的回复
$('.hfreply .pickupre').click(function(){
	var $div_li = $('.hfreply .pickupre');
		var index = $div_li.index(this);
	$('.retore').eq(index).hide();
	$(this).hide();
	$('.hfreply .lunpen').eq(index).show()
})
$('.hfreply .lunpen').click(function(){
	var $div_li = $('.hfreply .lunpen');
		var index = $div_li.index(this);
	$('.retore').eq(index).show();
	$(this).hide();
	$('.hfreply .pickupre').eq(index).show()
})

