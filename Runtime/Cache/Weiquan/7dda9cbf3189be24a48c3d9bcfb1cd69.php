<?php if (!defined('THINK_PATH')) exit();?><div class="one_people" id="weibo_<?php echo ($weibo["id"]); ?>">
       <?php if($show_user){ ?> 
        <div class="trends_section">     
    <div class="trends_head left">
        <a href="<?php echo U('@'.$weibo['user']['username']);?>" ucard="<?php echo ($weibo["user"]["uid"]); ?>"><img src="<?php echo ($weibo["user"]["avatar64"]); ?>" alt=""/></a>
    </div>       
    <div class="trends_detail left">     
        <div class="trends_data">
            <a href="<?php echo U('@'.$weibo['user']['username']);?>"><p class="trends_name left"><?php echo ($weibo["user"]["nickname"]); ?></p></a>
            <?php if(modC('SHOW_TITLE',1)): ?><span class="left trends_grade<?php if($weibo['user']['type'] == 2): ?>2<?php endif; ?> px12"><i>Lv.<?php echo ($weibo["user"]["level"]); ?></i></span><?php endif; ?>                               
            <?php if($weibo['user']['ipai']): ?><div class="yiyuan left"></div><?php endif; ?>
            <a href="<?php echo U('Home/Index/hot@',array('tag'=>$weibo['user']['catid']));?>"><p class="keywords<?php if($weibo['user']['type'] == 1): ?>2<?php endif; ?> left cl50 ellipsis"><?php echo ($weibo['user']['category']); ?></p></a> 
            <?php echo W('Common/UserRank/render',array($weibo['uid']));?>
            <span class="right _weiquan_type_right" style="display:none;">                 
            <?php if($weibo['can_delete']): ?>&nbsp;<span class="trash right _delete_weiquan "  data-weibo-id="<?php echo ($weibo["id"]); ?>" title="<?php echo L('_DELETE_');?>" data-role="del_weibo"></span><?php endif; ?>
            <?php if($weibo['user']['uid'] != is_login()): echo hook('report',array('type'=>$MODULE_ALIAS.'/'.$MODULE_ALIAS,'url'=>"Index/weiquanDetail?id=$weibo[id]",'data'=>array('weiquan-id'=>$weibo['id']))); endif; ?>
            </span>
            
        </div>
        <div class="weibo_content_p clear cl50">
           <?php echo ($weibo["fetchContent"]); ?>  
        </div>  
        <div class="trends_footer clear px12 cl50">
            <p class="trends_time left _data_id" >
    <a  href="<?php echo U('Index/weiquanDetail',array('id'=>$weibo['id']));?>"><?php echo (friendlydate($weibo["create_time"])); ?></a>&nbsp;   
</p>
<?php $sourceId =$weibo['data']['sourceId']?$weibo['data']['sourceId']:$weibo['id']; ?>
<div class="comment_box right" data-id="<?php echo ($weibo['id']); ?>">
    <div class="comment left"></div>
    <p class="left"><?php echo L('_WEIQUAN_COMMENT_');?>&nbsp;<span><?php if($weibo['comment_count']): echo ($weibo["comment_count"]); endif; ?></span></p>
</div>

<div class="praise_box right like_btn _data_id" data-id="<?php echo ($weibo['id']); ?>" data-uid="<?php echo is_login();?>" data-detail="<?php echo ($is_detail); ?>">
    <div class="praise left"></div>
    <?php if($is_like): ?><p class="left _lng _is_true"><span class="_like_text"><?php echo L('_CANCEL_');?></span>&nbsp;<span class="_count"></span></p>
        <?php else: ?>
        <p class="left _lng"><span class="_like_text"><?php echo L('_WEIQUAN_LIKE_');?></span>&nbsp;<span class="_count"><?php if($weibo['like_count']): echo ($weibo["like_count"]); endif; ?></span></p><?php endif; ?>
</div>
<div class="forward_box right">
    <div class="forward left"></div>
    <a class="left" data-role="send_repost" href="<?php echo U('Index/sendrepost',array('sourceId'=>$sourceId,'weiboId'=>$weibo['id']));?>"><?php echo L('_WEIQUAN_REPOST_');?>&nbsp;<span><?php if($weibo['repost_count']): echo ($weibo["repost_count"]); endif; ?></span></a>
</div>



        </div>
    </div>
    <div class="clear"></div> 
</div>

       <?php }else{ ?>
        <div class="trends_section2"> 
    <div class="trends_detail2 left"> 
        <div class="weibo_content_p clear cl50" style=" padding-right: 50px; position: relative;">
                         <?php if($weibo['can_delete']): ?><div data-weibo-id="<?php echo ($weibo["id"]); ?>" title="<?php echo L('_DELETE_');?>" data-role="del_weibo" class="trash right _weiquan_type_right" style="position: absolute; right: 0; display: none;"></div><?php endif; ?>           
            <?php echo ($weibo["fetchContent"]); ?>
        </div>
        <div class="trends_footer clear px12 cl50">
            <p class="trends_time left _data_id" >
    <a  href="<?php echo U('Index/weiquanDetail',array('id'=>$weibo['id']));?>"><?php echo (friendlydate($weibo["create_time"])); ?></a>&nbsp;   
</p>
<?php $sourceId =$weibo['data']['sourceId']?$weibo['data']['sourceId']:$weibo['id']; ?>
<div class="comment_box right" data-id="<?php echo ($weibo['id']); ?>">
    <div class="comment left"></div>
    <p class="left"><?php echo L('_WEIQUAN_COMMENT_');?>&nbsp;<span><?php if($weibo['comment_count']): echo ($weibo["comment_count"]); endif; ?></span></p>
</div>

<div class="praise_box right like_btn _data_id" data-id="<?php echo ($weibo['id']); ?>" data-uid="<?php echo is_login();?>" data-detail="<?php echo ($is_detail); ?>">
    <div class="praise left"></div>
    <?php if($is_like): ?><p class="left _lng _is_true"><span class="_like_text"><?php echo L('_CANCEL_');?></span>&nbsp;<span class="_count"></span></p>
        <?php else: ?>
        <p class="left _lng"><span class="_like_text"><?php echo L('_WEIQUAN_LIKE_');?></span>&nbsp;<span class="_count"><?php if($weibo['like_count']): echo ($weibo["like_count"]); endif; ?></span></p><?php endif; ?>
</div>
<div class="forward_box right">
    <div class="forward left"></div>
    <a class="left" data-role="send_repost" href="<?php echo U('Index/sendrepost',array('sourceId'=>$sourceId,'weiboId'=>$weibo['id']));?>"><?php echo L('_WEIQUAN_REPOST_');?>&nbsp;<span><?php if($weibo['repost_count']): echo ($weibo["repost_count"]); endif; ?></span></a>
</div>



        </div>
    </div>
    <div class="clear"></div> 
</div>

       <?php } ?>
    <!--点赞-->
    <?php echo W('Weiquan/Like/likeUser',array('weiquan_id'=>$weibo['id']));?>
    <!--点赞END-->
    <!--评论-->
    <div class="comment_pop _comment_pop_<?php echo ($weibo['id']); ?>"  style="display:none">
        <div class="pointer2"></div>
        <?php if($indexpage){ ?>
        <?php echo W('Weiquan/Comment/someCommentIndex',array('weiquan_id'=>$weibo['id']));?>
        <?php }else{ ?>
        <?php echo W('Weiquan/Comment/someComment',array('weiquan_id'=>$weibo['id']));?>
        <?php } ?>
        <div class="clear"></div>
    </div>
    <!--评论END-->
</div>