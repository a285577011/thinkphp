<?php if (!defined('THINK_PATH')) exit();?>	<style type="text/css">
		.b-left{border-left: 1px solid #E8E8E8;}
		.clear{clear:both}
		.cl00{color: #000000;}
		.cl80{color: #808080;}
		.single-pop-nv,.single-pop-nan,.single-pop-attention .attention_img,.single-pop-attention .attention_img2,.single-pop-attention .attention_img3{background-image: url(/Theme/Green/Weiquan/Static/images/singel-pop.png);}
		.single-pop{width: 320px;border: 1px solid #E8E8E8;position: relative;background-color: #FFFFFF;}
		.single-pop-back{width: 100%;height: 90px;}
		.single-pop-head{width: 80px;
						height: 80px;
						-moz-border-radius: 50%;
						-webkit-border-radius: 50%;
						border-radius: 50%;
						border: 5px solid #FFFFFF;
						position: absolute;
						left: 16px;
						top: 50px;
							}
		.single-pop	span{display: inline-block;vertical-align: middle;}	
		.single-pop-nv,.single-pop-nan{width: 14px;height: 13px;background-position: 0 0;margin: 0 5px;}	
		.single-pop-nan{background-position: -14px 0;}
		.single-pop-name{color: #25C501;max-width: 70px;white-space: nowrap;text-overflow: ellipsis;overflow: hidden;line-height: 14px;}
		.single-pop-data{margin: 20px 0 0 112px;}
		.single-pop-key,.single-pop-key2{font-size:12px;width: 84px;height: 22px;text-align: center;line-height:22px;color: #FFFFFF;background-color: #25C501;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;padding: 0 5px;box-sizing: border-box;border-radius: 2px;vertical-align: middle;}
		.single-pop-key2{background-color: #FD7603;}
		.single-pop-signature{margin: 13px 0 0 19px;line-height: 14px;display: inline-block;}
		.single-pop-signature span{width: 224px;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;line-height: 14px;}
		.single-pop-datail li{float: left;text-align: center;width: 106px;height: 36px;line-height: 36px;}
		.single-pop-datail{border-top: 1px solid #E8E8E8;border-bottom: 1px solid #E8E8E8;margin-top: 10px;}
		.single-pop-attention{text-align: center;margin: 10px 0}
		.single-pop-attention .attention_img,.single-pop-attention .attention_img2,.single-pop-attention .attention_img3{width: 90px;height: 25px;background-position: -29px 0;}
		.single-pop-attention .attention_img2{background-position: -119px 0;}
		.single-pop-attention .attention_img3{background-position: -209px 0;}
		</style>
		<div class="single-pop">
			<img class="single-pop-back" src="<?php echo ($user["cover_path"]); ?>" alt=""/>
			<a href="<?php echo U('Weiquan/Index/index',array('type'=>related));?>"><img class="single-pop-head" src="<?php echo ($user["avatar"]); ?>" alt=""/></a>
			<div class="single-pop-data">
				<a href="<?php echo U('Weiquan/Index/index',array('type'=>related));?>"><span class="single-pop-name">
				<?php echo ($user["nickname"]); ?>
			</span></a>
			<?php if($user['sex'] == 2): ?><span class="single-pop-nv">
				
			</span>
			<?php elseif($user['sex'] == 1): ?>
			<span class="single-pop-nan">
				
			</span><?php endif; ?>
			<?php if($user['category']): ?><span class="single-pop-key">
				 <?php echo ($user["category"]); ?>
			</span><?php endif; ?>
			</div>
			<p class="single-pop-signature">个性签名：<span class="cl80"><?php echo ((isset($user["signature"]) && ($user["signature"] !== ""))?($user["signature"]):L('_NO_IDEA_')); ?></span></p>
			<ul class="single-pop-datail">
				<li><a href="<?php echo ($user["following_url"]); ?>"><span class="cl00"><?php echo L('_FOLLOWERS_'); echo L('_COLON_'); echo ($user["following"]); ?></span></a></li>
				<li class="b-left"><a href="<?php echo ($user["fans_url"]); ?>"><span class="cl00"><?php echo L('_FANS_'); echo L('_COLON_'); echo ($user["fans"]); ?></span></a></li>
				<li class="b-left"><a href="<?php echo U('Weiquan/Index/index',array('type'=>related));?>">动态&nbsp;<span class="cl00"><?php echo ($user["weiquancount"]); ?></span></a></li>
				
				<div class="clear"></div>
			</ul>
			 <?php if($not_self): ?><div class="single-pop-attention">
			 <?php if($user['followFlag'] == 1): ?><span class="attention_img3" onclick="cancleFocus(this)"></span><?php endif; ?>
              <?php if(!$user['followFlag']||$user['followFlag'] == 2): ?><span class="attention_img" onclick="addFocus(this)"></span><?php endif; ?>
                            <?php if($user['followFlag'] == 3): ?><span class="attention_img2" onclick="cancleFocus(this)"></span><?php endif; ?>
              </div><?php endif; ?>
			<div class="clear"></div>
		</div>
		<script>
                   function addFocus(obj){
	                    var phpSessionId='<?php echo session_id();?>';
                    	var url="<?php echo U('/Public/follow');?>";
                    	var uid='<?php echo ($user[uid]); ?>';
                    	var data={uid:uid,cookie:phpSessionId};
       		         $.ajax({
       				     type: "POST",
       				 url: url,
       				 data: data,
       				 beforeSend : function(){
       				   },
       				         success: function(data){
       				             if(data.status==1){
   				            		 $(obj).removeClass().addClass("attention_img3");
   				            		 $(obj).attr("onclick",'cancleFocus(this)');
   				            		toast.success(data.info, '温馨提示');
       				             }
       				             else if(data.status==2){
   				            		 $(obj).removeClass().addClass("attention_img2");
   				            		 $(obj).attr("onclick",'cancleFocus(this)');
   				            		toast.success(data.info, '温馨提示');
       				             }
       				             else{
       				            	toast.success(data.info, '温馨提示');
       				             }
       				        	 },
       				 error: function(){
       				 layer.msg("系统繁忙");
       				 }
       				       });
                    }
function cancleFocus(obj){
    var phpSessionId='<?php echo session_id();?>';
	var url="<?php echo U('Public/unfollow');?>";
	var uid='<?php echo ($user[uid]); ?>';
	var data={uid:uid,cookie:phpSessionId};
    $.ajax({
	     type: "POST",
	 url: url,
	 data: data,
	 beforeSend : function(){
	   },
	         success: function(data){
	             if(data.status){
           		 $(obj).removeClass().addClass("attention_img");
           		 $(obj).attr("onclick",'addFocus(this)');
           		 $('.remarks').remove();
           		toast.success(data.info, '温馨提示');
	             }
	             else{
	            	toast.success(data.info, '温馨提示');
	             }
	        	 },
	 error: function(){
	 layer.msg("系统繁忙");
	 }
	       });
}
</script>