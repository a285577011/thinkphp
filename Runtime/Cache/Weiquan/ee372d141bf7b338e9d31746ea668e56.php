<?php if (!defined('THINK_PATH')) exit();?><div class="weibo_post_box">
    <div class="comment2 left"></div>
    <input type="hidden"  name="reply_id" value="0"/>
    <textarea  class="comment_textarea left px14 weibo-comment-content" id="comment_textarea"></textarea>
    <div class="smile2 clear left getSmile" onclick="insertFace($(this))"></div>
    <input class="comment_button right px14"  data-role="do_comment" type="submit" id="btn_<?php echo ($weiboId); ?>" data-weibo-id="<?php echo ($weiboId); ?>" value="评论"/>
        <div  class=" clear comment_more"></div>
        <div style="margin-left:88px;">
    <div id="emot_content" class="emot_content" style="float: left"></div>
    </div>
    <div id="show_comment_<?php echo ($weiboId); ?>" class="clear " data-comment-count="<?php echo ($weiboCommentTotalCount); ?>">
       <?php if(is_array($comments)): $i = 0; $__LIST__ = $comments;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$comment): $mod = ($i % 2 );++$i;?><div <?php if($i>5){ ?> style="display: none"  <?php } ?> >
                <?php echo W('Weiquan/Comment/detail',array('comment_id'=>$comment['id']));?>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
        <?php $pageCount = ceil($weiboCommentTotalCount / 10); ?>   
          <div id="index_comment_page" style=" <?php if($page == 1): ?>display:none<?php endif; ?>">
            <div class="text-right" style="text-align: center;">
                <div class="pager">
                 <?php echo getPageHtml('weiquan_comment_page',$pageCount,array('weibo_id'=>$weiboId),$page);?>  
                </div>
            </div>
            </div>
    </div> 
     
    <?php if(count($comments)>5){ ?>
    <div  class=" clear comment_more">        
        <a class="comment_more" id="show_all_comment_<?php echo ($weiboId); ?>" href="javascript:" onclick="show_comment('<?php echo ($weiboId); ?>');"><?php echo L('_VIEW_MORE_');?></a>       
    </div>
    <?php } ?>
</div>

<script>
    $(function () {
        var weiboid = '<?php echo ($weiboId); ?>';
        $('#text_' + weiboid + '').keypress(function (e) {
            if (e.ctrlKey && e.which == 13 || e.which == 10) {
                $('#btn_' + weiboid + '').click();
            }
        });
    });
</script>