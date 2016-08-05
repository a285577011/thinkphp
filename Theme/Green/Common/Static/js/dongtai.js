$(document).ready(function(e) {	
	
	
	/*头部*/
$(".mg_tanchuang").hide();	
$(".huiyuan_box").stop(true,true).hide()	
$(".phone").mouseenter(function(){
	$(".phone div").addClass("img12");
	$(".phone a").addClass("as");
	})	
$(".phone").mouseleave(function(){
	$(".phone div").removeClass("img12");
	$(".phone a").removeClass("as");
	})	
$(".help_center").mouseenter(function(){
	$(".help_center div").addClass("img22");
	$(".help_center a").addClass("as");
	})	
$(".help_center").mouseleave(function(){
	$(".help_center div").removeClass("img22");
	$(".help_center a").removeClass("as");
	})
$(".message").mouseenter(function(){
	
	$(".message p").eq(0).addClass("xinfen2");
	$(".message p").eq(1).addClass("mg_sanjiao2");
	$(".message").addClass("message2")
	$(".message a").addClass("as");
	$(".mg_tanchuang").show();
	})	
$(".message").mouseleave(function(){
	$(".message p").eq(0).removeClass("xinfen2");
	$(".message p").eq(1).removeClass("mg_sanjiao2");
	$(".message").removeClass("message2")
	$(".message a").removeClass("as");
	$(".mg_tanchuang").hide();
	})			
$(".use_name").mouseenter(function(){
	$(".use_name p").addClass("img12");
	$(".use_name").addClass("use_name2");
	$(".huiyuan_box").stop(true,true).show()
	})	
$(".use_name").mouseleave(function(){
	$(".use_name p").removeClass("img12");
	$(".use_name").removeClass("use_name2");
	$(".huiyuan_box").stop(true,true).hide()
	})		
	/*导航*/
/*var f=0	
$(".h").mouseenter(function(){
	
	 f=$(".h").index(this);
	$(".h").eq(f).addClass("quxian");
	$(".h").eq(f-1).addClass("quxian2");
	})
$(".h").mouseleave(function(){
	
	f=$(".h").index(this);
	$(".h").eq(f).removeClass("quxian");
	$(".h").eq(f-1).removeClass("quxian2");
	})			
$(".h:eq(0)").mouseenter(function(){
	$(".hh2").addClass("quxian2");
	})
$(".h:eq(0)").mouseleave(function(){
	$(".hh2").removeClass("quxian2");
	
	})	*/
	
	/*banner轮播图*/
	 $(".imgbox").width($(".imgbox img").length*1200);
	var n=0
	function run(){
		if(n<$(".imgbox img").length-1){n=n+1}
		
		else{n=1;$(".imgbox").css({marginLeft:0});}
		if (n==$(".imgbox img").length-1){ $(".button li").eq(0).addClass("current").siblings().removeClass("current")}
		else { $(".button li").eq(n).addClass("current").siblings().removeClass("current")}
		 $(".imgbox").animate({marginLeft:-1200*n},1000)
		
		}
var timer = setInterval(run,2000)

$(".button li").stop(true,true).mouseenter(function(){
	clearInterval(timer);
	n=$(".button li").index(this);
	$(".imgbox").animate({marginLeft:-1200*n},1000);
	
	 $(".button li").siblings().removeClass("current");
	 $(this).addClass("current")
	})	
$(".button li").stop(true,true).mouseleave(function(){
	timer=setInterval(run,2000)
	})
	
	/*爱微商滚动说*/
var t=setInterval(function(){
	$(".gundong dl").animate({marginTop:-40},500,function(){
		$(".gundong dd:last").after($(".gundong dd:first"));
		$(".gundong dl").css({marginTop:0});
		});
	},2000)
$(".gundong").mouseenter(function(){
	clearInterval(t);
	}).mouseleave(function(){
		t=setInterval(function(){
	$(".gundong dl").animate({marginTop:-40},500,function(){
		$(".gundong dd:last").after($(".gundong dd:first"));
		$(".gundong dl").css({marginTop:0});
		});
	},2000)
		})	
	
	
	
		

/*	红人*/
$(".hongren_l img").hide().eq(0).show();
$(".hongren_l").mouseenter(function(){
$(".hongren_l img").stop(true,true).hide().eq(1).show();
	})
$(".hongren_l").mouseleave(function(){
$(".hongren_l img").stop(true,true).hide().eq(0).show();
	})	
$(".hongren_r img").hide().eq(0).show();
$(".hongren_r").mouseenter(function(){
$(".hongren_r img").stop(true,true).hide().eq(1).show();
	})
$(".hongren_r").mouseleave(function(){
$(".hongren_r img").stop(true,true).hide().eq(0).show();
	})	

var m=0	
$(".bufen").mouseenter(function(){
	
	 m=$(".bufen").index(this);
	$(".bufen span").eq(m).addClass("xin");
	 
	})
$(".bufen").mouseleave(function(){
	
	 n=$(".bufen").index(this);
	$(".bufen span").eq(n).removeClass("xin");
	 
	})	
	


var j=0
$(".bufen").mouseenter(function(){
	j=$(".bufen").index(this);
	$(".bufen a p").eq(j).stop(true,false).animate({opacity:0.4});
	})
	$(".bufen").mouseleave(function(){
	$(".bufen a p").eq(j).stop(true,false).animate({opacity:0});
	})	
	

		
	
	
	
/*	最新*/	
$(".zuo img").hide().eq(0).show();
$(".zuo").mouseenter(function(){
$(".zuo img").stop(true,true).hide().eq(1).show();
	})
$(".zuo").mouseleave(function(){
$(".zuo img").stop(true,true).hide().eq(0).show();
	})	
$(".you img").hide().eq(0).show();
$(".you").mouseenter(function(){
$(".you img").stop(true,true).hide().eq(1).show();
	})
$(".you").mouseleave(function(){
$(".you img").stop(true,true).hide().eq(0).show();
	})		
	
var c=0	
$(".bufen2").mouseenter(function(){
	
	 c=$(".bufen2").index(this);
	$(".bufen2 div").eq(c).addClass("xin2");

	})
$(".bufen2").mouseleave(function(){
	
	c=$(".bufen2").index(this);
	$(".bufen2 div").eq(c).removeClass("xin2");
	  
	})		


	

	
	
var l=0
$(".bufen2").mouseenter(function(){
	l=$(".bufen2").index(this);
	$(".bufen2 a p").eq(l).stop(true,false).animate({opacity:0.4});
	})
	$(".bufen2").mouseleave(function(){
	$(".bufen2 a p").eq(l).stop(true,false).animate({opacity:0});
	})	
	
      /*爱拍	*/
	
	var k=0	
$(".aipai").mouseenter(function(){
	
	 k=$(".aipai").index(this);
	$(".aipai p").eq(k).addClass("p_current");
	$(".chanpin").eq(k).addClass("chanpin_current");
	})
$(".aipai").mouseleave(function(){
	
	 k=$(".aipai").index(this);
	$(".aipai p").eq(k).removeClass("p_current");
	$(".chanpin").eq(k).removeClass("chanpin_current");
	})		
	
	
	var i=0	
$(".chanpin").mouseenter(function(){
	 i=$(".chanpin").index(this);
	$(".chanpin span").eq(i).addClass("xying2");
	
	})
$(".chanpin").mouseleave(function(){
	
	 k=$(".chanpin").index(this);
	$(".chanpin span").eq(i).removeClass("xying2");
	
	})	
	
	
	
/*学院	*/
	var u=0	
$(".shuju").mouseenter(function(){
	
	 u=$(".shuju").index(this);
	$(".shuju a").eq(u).addClass("xue");
	$(".shuju .rili").eq(u).hide();
	$(".shuju .rili2").eq(u).show();

	})
$(".shuju").mouseleave(function(){
	 u=$(".shuju").index(this);
	$(".shuju a").eq(u).removeClass("xue");
	$(".shuju .rili").eq(u).show();
	$(".shuju .rili2").eq(u).hide();

	})	
	
var k=0
$(".qie_xinxi").hide().eq(0).show();
$(".qiehuan a").eq(0).addClass("dianji")
$(".qiehuan a").click(function(){
	k=$(".qiehuan a").index(this)
	$(".qie_xinxi").hide();
	$(".qie_xinxi").eq(k).show();
	$(".qiehuan a").removeClass("dianji")
	$(".qiehuan a").eq(k).addClass("dianji")
	})
	
	
	
	
	})




/*搜索js*/
function checkInput(){
	var obj = document.getElementById("input1");
	obj.focus=function(){
		this.value=""
		}
	
	}



// JavaScript Document