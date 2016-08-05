<?php if (!defined('THINK_PATH')) exit();?><div class="comment_section clear weibo_comment" id="comment_<?php echo ($comment["id"]); ?>" data-weibo-id="<?php echo ($comment["weiquan_id"]); ?>" data-comment-id="<?php echo ($comment["id"]); ?>">
    <img class="left userLogo" src="<?php echo ($comment["user"]["avatar32"]); ?>" alt="<?php echo ($comment["user"]["nickname"]); ?>" ucard="<?php echo ($comment["user"]["uid"]); ?>" />
    <div class="comment_data left">
        <p class="comment_talk px14"><span class="comment_name"><?php echo ($comment["user"]["nickname"]); ?></span><span class="talk cl50">：<?php echo ($comment["content"]); ?></span></p>	
        <p class="comment_time px12 cl50 left"><?php echo (friendlydate($comment["create_time"])); ?></p>
        <div class="comment_tool right px12 cl50">
        <!--   <?php echo hook('report',array('type'=>$MODULE_ALIAS.'/'.L('_COMMENTS_'),'url'=>"Weiquan/Index/weiquanDetail?id=$comment[weibo_id]#comment_$comment[id]",'data'=>array('comment_id'=>$comment['id'],'weibo-id'=>$weibo['id'])));?>--> 
         <?php if($comment['can_delete']): ?><a class="comment_del hand" data-role="comment_del"><?php echo L('_DELETE_');?></a><?php endif; ?>
        <a class="comment_answer right px12 cl50 hand" data-role="weibo_reply" data-user-nickname="<?php echo ($comment["user"]["nickname"]); ?>"><?php echo L('_REPLY_');?></a>
        </div>     
        
    </div>
    <div class="clear"></div>
</div>