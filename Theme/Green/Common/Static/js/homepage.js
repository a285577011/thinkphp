
$(document).ready(function(e) {	

$(".keyword li").eq(0).addClass("li_style")




$(".home_style").mouseenter(function(){
	$(".upload").fadeIn();
	})
$(".home_style").mouseleave(function(){
	$(".upload").fadeOut();
	})



/*评论赞弹窗*/
var k = 0

$(".praise_box").click(function(){
	k=$(".praise_box").index(this);
	$(".praise_pop").eq(k).toggle();
	$(".comment_pop").eq(k).hide();
	})


$(".comment_box").click(function(){
	k=$(".comment_box").index(this);
	$(".comment_pop").eq(k).toggle();
	$(".praise_pop").eq(k).hide();
	})

/*照片弹窗*/
var n=0

$(".trends_product").click(function(){
	n=$(".trends_product").index(this)
	$(".product_pop").eq(n).fadeIn()
	$(".trends_product").eq(n).hide();
	})
$(".retract").click(function(){
	n=$(".retract").index(this)
	$(".product_pop").eq(n).hide()
	$(".trends_product").eq(n).show();
	})


$(".trends_product2").click(function(){
	n=$(".trends_product2").index(this)
	$(".product_pop2").eq(n).fadeIn()
	$(".trends_product2").eq(n).hide();
	})
$(".retract2").click(function(){
	n=$(".retract2").index(this)
	$(".product_pop2").eq(n).hide()
	$(".trends_product2").eq(n).show();
	})







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

/*回复弹窗*/
var o = 0
$(".comment_answer").click(function(){
	o=$(".comment_answer").index(this)
	$(".answer_pop").eq(o).slideToggle();
	
	})




/*关注的变化*/

$(".attention").click(function(){
	$(".attention").hide();
	$(".attention2").fadeIn();
	})
$(".attention2").mouseenter(function(){
	$(".attention2").fadeOut();
	$(".attention3").fadeIn(2000)
	})




/*粉丝关注变化*/
var l=0
$(".section_attention").click(function(){
	l=$(".section_attention").index(this);
	$(".section_attention").eq(l).hide();
	$(".section_attention2").eq(l).fadeIn();
	})
/*$(".section_attention2").click(function(){
	$(".section_attention2").eq(l).hide();
	$(".section_attention").eq(l).fadeIn();
	})*/


/*下路三角的样式变化*/
$(".down_button").mouseenter(function(){
	
	$(".order_down").addClass("down_style")
	})
$(".down_button").mouseleave(function(){
	
	$(".order_down").removeClass("down_style")
	})

var k=0	
$(".down_button2").click(function(){
	k=$(".down_button2").index(this);
	$(".set_pop").eq(k).slideToggle()
	})


$(".set_pop").mouseleave(function(){
	
	$(".set_pop").eq(k).slideUp();
	})	




$(".down_button").click(function(){
	
	$(".down_pop").slideToggle()
	})
$(".down_pop").mouseleave(function(){
	
	$(".down_pop").slideUp();
	})	
var m=0
$(".down_order").mouseenter(function(){
	m=$(".down_order").index(this);
	$(".down_order p").eq(m).addClass("downp_style")
	$(".order_img").eq(m).addClass("order_img2")
	})
$(".down_order").mouseleave(function(){
	m=$(".down_order").index(this);
	$(".down_order p").eq(m).removeClass("downp_style")
	$(".order_img").eq(m).removeClass("order_img2")
	})



/*以下是垃圾桶和举报的jq*/
 var j = 0
$(".trends_section").mouseenter(function(){
	j=$(".trends_section").index(this);
	$(".report2").eq(j).fadeIn();
	
	})
$(".trends_section").mouseleave(function(){
	
	$(".report2").eq(j).fadeOut();
	})
	
$(".trends_section2").mouseenter(function(){
	j=$(".trends_section2").index(this);
	$(".trash").eq(j).fadeIn();
	$(".report2").eq(j).fadeIn();
	})
$(".trends_section2").mouseleave(function(){
	$(".report2").eq(j).fadeOut();
	$(".trash").eq(j).fadeOut();
	})


$(".personal_box").mouseenter(function(){
	j=$(".personal_box").index(this);
	$(".report").eq(j).fadeIn();
	
	})
$(".personal_box").mouseleave(function(){
	
	$(".report").eq(j).fadeOut();
	})



/*layer弹窗*/



/*修改备注弹窗*/
$('#remarks').on('click', function() {
				layer.open({
					type: 1,
					title: false,
					shadeClose: true, //点击遮罩关闭层
					content: $('.remarks_box'),
				});
			});



})


/*隐藏的举报弹窗*/
$('.report2').on('click', function() {
				layer.open({
					type: 1,
					title: false,
					shadeClose: true, //点击遮罩关闭层
					area: ['510px', '250px'],
					content: $('.report_box'),
				});
			});
$('.report').on('click', function() {
				layer.open({
					type: 1,
					title: false,
					shadeClose: true, //点击遮罩关闭层
					area: ['510px', '250px'],
					content: $('.report_box'),
				});
			});

/*隐藏的确定删除弹窗*/
$('.trash').on('click', function() {
				layer.open({
					type: 1,
					title: false,
					shadeClose: true, //点击遮罩关闭层
					area: ['400px', '152px'],
					content: $('.sure_box'),
				});
			});
/*隐藏的转发弹窗*/
$('.forward_box').on('click', function() {
				layer.open({
					type: 1,
					title: false,
					shadeClose: true, //点击遮罩关闭层
					area: ['510px', '270px'],
					content: $('.forward_pop'),
				});
			});


// JavaScript Document