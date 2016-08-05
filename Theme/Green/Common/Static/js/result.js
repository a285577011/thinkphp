$(document).ready(function(e) {	

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

})
// JavaScript Document