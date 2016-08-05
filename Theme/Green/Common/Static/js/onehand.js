
      
    /*公告内容最后一个没底边     */
	$(".notice_data:last").addClass("notice_style");
	/*热门商家第一个没左边距*/
	$(".market_section:first").addClass("market_style")
	
	/*banner轮播图*/
	 $(".imgsbox").width($(".imgsbox img").length*900);
	var n=0
	function run(){
		if(n<$(".imgsbox img").length-1){n=n+1}
		
		else{n=1;$(".imgsbox").css({marginLeft:0});}
		if (n==$(".imgsbox img").length-1){ $(".switch li").eq(0).addClass("active").siblings().removeClass("active")}
		else { $(".switch li").eq(n).addClass("active").siblings().removeClass("active")}
		 $(".imgsbox").animate({marginLeft:-900*n},1000)
		
		}
	var timer = setInterval(run,4000)
	
	$(".switch li").stop(true,true).mouseenter(function(){
		clearInterval(timer);
		n=$(".switch li").index(this);
		$(".imgsbox").animate({marginLeft:-900*n},1000);
		
		 $(".switch li").siblings().removeClass("active");
		 $(this).addClass("active")
		})	
	$(".switch li").stop(true,true).mouseleave(function(){
		timer=setInterval(run,4000)
		})



/*热门微商鼠标进入效果*/

$(".business_section").mouseenter(function(){
	$(this).addClass("business_style").siblings().removeClass("business_style");
	})

// JavaScript Document