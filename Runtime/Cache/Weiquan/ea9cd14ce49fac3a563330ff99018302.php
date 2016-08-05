<?php if (!defined('THINK_PATH')) exit();?><div class="homepage">
<div class="page_title container">
    <h1 class="px14 left">首页</h1>
    <div class="jiantou left"></div>
    <h1 class="px14 left"><?php if($type == 'all'): ?>微商圈<?php else: ?>个人主页<?php endif; ?></h1>
    <div class="clear"></div>
</div>

<?php if($type!='all'){ ?>
    <div class="home_style container clear" >
    <img style="width: 100%;position: absolute;z-index: -1;height: 250px;"     <?php if($user_info['cover_id']): ?>src="<?php echo ($user_info['cover_path']); ?>"
       <?php else: ?> src="/Theme/Green/Weiquan/Static/images/banner.jpg"<?php endif; ?> class="homebanner uc_top_img_bg" alt=""/>
        <div class="headimg">
            <div>
                <img src="<?php echo ($user_info["avatar128"]); ?>" alt="<?php echo ($user_info["nickname"]); ?>">
            </div>
        </div>
         <?php if(is_login() && $user_info['uid'] == is_login()): ?><div class="change_cover" style="border-radius: 2px;position: absolute;right: 0;top: 0;"><a data-type="ajax" data-url="<?php echo U('Public/changeCover');?>"
                                                         data-toggle="modal" data-title="<?php echo L('_UPLOAD_COVER_');?>" style="color: white;"><img
                                    class="img-responsive" src="/Application/Core/Static/images/fractional.png" style="width: 25px;"></a>
                            </div><?php endif; ?>
        <ul class="wechat_name clear">
        	<li class="wechat"><?php echo ($user_info["nickname"]); ?></li>
        	<?php if((is_login()!=$user_info['uid'])&&$followFlag == 1||$followFlag == 3): ?><li id="remarks" class="remarks px12 hand" data-target="#remarkName" data-toggle="modal" href="<?php echo U('Index/remark',array('uid'=>$uid));?>"><?php if($remark): ?>（<?php echo ($remark); ?>）<?php else: ?>（设置备注）<?php endif; ?></li><?php endif; ?>
           <?php if($user_info['sex']==2){ ?><li class="head_sex"> <?php }elseif($user_info['sex']==1){ ?>
             <li class="head_sex2">
            <?php } ?></li>
            <?php if(is_login()!=$user_info['uid']): ?><li class="head_attention hand">
           		<div class="attention_img<?php switch($followFlag){case 1:echo '2';break;case 3:echo '3';break;case 0:break;} ?>" <?php if(!$followFlag||$followFlag == 2): ?>onclick="addFocus(this)"<?php else: ?>onclick="cancleFocus(this)"<?php endif; ?>></div>
           </li><?php endif; ?>
        </ul>
        <p class="signature clear">
        <?php if($user_info['signature'] == ''): echo L('_NO_IDEA_');?>
            <?php else: ?>
            <attr title="<?php echo ($user_info["signature"]); ?>"><?php echo ($user_info["signature"]); ?></attr><?php endif; ?></p>
        <div class="keybox">
            <ul class="keyword<?php if($user_info['type'] == 2): ?>2<?php endif; ?>">
          <?php if($user_info['category']): ?><li class="li_style"> <a href="<?php echo U('Home/Index/hot@', array('catid'=>$user_info['catid']));?>" target="_blank"><?php echo ($user_info["category"]); ?></a> </li><?php endif; ?>             
                <?php if(is_array($user_info['tags'])): $i = 0; $__LIST__ = $user_info['tags'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tag): $mod = ($i % 2 );++$i;?><li data-url="<?php echo U('people/index/index@',array('tag'=>$tag['id']));?>"><a title="<?php echo ($tag['title']); ?>" href="<?php echo U('/people/index/index@',array('tag'=>$tag['id']));?>"><?php echo ($tag["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>               
            </ul>
        </div>
        <div class="clear"></div>
    </div>
    <?php } ?>
</div>
<script>
$(".home_style").mouseenter(function(){
	$(".upload").fadeIn();
	})
$(".home_style").mouseleave(function(){
	$(".upload").fadeOut();
	})

                    function addFocus(obj){
	                    var phpSessionId='<?php echo session_id();?>';
                    	var url="<?php echo U('Public/follow');?>";
                    	var uid='<?php echo ($user_info[uid]); ?>';
                    	var data={uid:uid,cookie:phpSessionId};
       		         $.ajax({
       				 type: "POST",
       				 url: url,
       				 data: data,
       				 beforeSend : function(){
       				   },
       				         success: function(data){
       				             if(data.status==1){
   				            		 $(obj).removeClass().addClass("attention_img2");
   				            		 $(obj).attr("onclick",'cancleFocus(this)');
   				            		toast.success(data.info, '温馨提示');
   				            		$('.wechat').after('<li href="/index/remark/uid/120.html" data-toggle="modal" data-target="#myModal" class="remarks px12 hand" id="remarks">（设置备注）</li>');
       				             }
       				             else if(data.status==2){
   				            		 $(obj).removeClass().addClass("attention_img3");
   				            		 $(obj).attr("onclick",'cancleFocus(this)');
   				            		toast.success(data.info, '温馨提示');
  				            		$('.wechat').after('<li href="/index/remark/uid/120.html" data-toggle="modal" data-target="#myModal" class="remarks px12 hand" id="remarks">（设置备注）</li>');
       				             }
       				             else{
       				            	$('#login').find('a').trigger('click');
       				            	//toast.success(data.info, '温馨提示');
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
	var uid='<?php echo ($user_info[uid]); ?>';
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
	            	 $('#login').find('a').trigger('click');
	             }
	        	 },
	 error: function(){
	 layer.msg("系统繁忙");
	 }
	       });
}
</script>