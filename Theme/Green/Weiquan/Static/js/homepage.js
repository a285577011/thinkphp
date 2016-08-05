
$(document).ready(function(e) {	

/*评论赞弹窗*/
var k = 0
//$(".praise_pop").hide();
//$(".comment_pop").hide();
//$(".praise_box").click(function(){
//	k=$(".praise_box").index(this);
//	$(".praise_pop").eq(k).toggle();
//	$(".comment_pop").eq(k).hide();
//	})
//
//
//$(".comment_box").click(function(){
//	k=$(".comment_box").index(this);
//	$(".comment_pop").eq(k).toggle();
//	$(".praise_pop").eq(k).hide();
//	})




/*资料弹窗*/

$(".data_pop").hide();
$(".more_box2").hide();
$(".more_box").click(function(){
	$(".data_pop").slideToggle();
	
	})
$(".more_box").click(function(){
	$(".more_box").hide();
	$(".more_box2").show();
	$(".data_pop").slideDown();
	})
$(".more_box2").click(function(){
	$(".more_box2").hide();
	$(".more_box").show();
	$(".data_pop").slideUp();
	})
        
/*二维码*/
$(".code_box").hide();
$(".icon3").mouseenter(function(){
	$(".code_box").slideDown();
	
	})
$(".personal_section2").mouseleave(function(){
	$(".code_box").slideUp();
	
	})

/*查看更多下拉*/
$(".look_more").mouseenter(function(){
	$(".look_more p").addClass("more_color");
	$(".look_more .down").addClass("down_new");
	$(".look_more .down2").addClass("down_new2");
	})
$(".look_more").mouseleave(function(){
	$(".look_more p").removeClass("more_color");
	$(".look_more .down").removeClass("down_new");
	$(".look_more .down2").removeClass("down_new2");
	})

})

// JavaScript Document