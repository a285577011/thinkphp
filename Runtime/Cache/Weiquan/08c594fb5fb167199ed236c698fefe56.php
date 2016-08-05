<?php if (!defined('THINK_PATH')) exit();?>    <?php echo ($weibo["content"]); ?>   
    <?php if(empty($weibo["source_weibo"]["uid"])): echo L('_ORIGINAL_HAS_DELETED_');?>
        <?php else: ?>
        <a ucard="<?php echo ($weibo["source_weibo"]["user"]["uid"]); ?>"  href="<?php echo ($weibo["source_weibo"]["user"]["space_url"]); ?>" class="at_name">@<?php echo (htmlspecialchars($weibo["source_weibo"]["user"]["nickname"])); ?></a>
        <?php echo ($weibo["source_weibo"]["fetchContent"]); ?>
        <!--span class="text-primary pull-left" style="font-size: 12px;"><a href="<?php echo U('Weiquan/Index/weiquanDetail',array('id'=>$weibo['source_weibo']['id']));?>"><?php echo (friendlydate($weibo["source_weibo"]["create_time"])); ?></a-->   </span>
        &nbsp;<?php endif; ?>
    <?php if($weibo['source_weibo']['uid']): ?><script>
            var html='<a href="<?php echo ($weibo["source_weibo"]["user"]["space_url"]); ?>" ucard="<?php echo ($weibo["source_weibo"]["user"]["uid"]); ?>" style="position: absolute;margin-top: 32px;margin-left: -32px;"><img src="<?php echo ($weibo["source_weibo"]["user"]["avatar32"]); ?>"   class="avatar-img"   style="width: 32px;"/> </a>';
            $('#weibo_<?php echo ($weibo["id"]); ?>').find('.s_avatar').after(html);
        </script><?php endif; ?>