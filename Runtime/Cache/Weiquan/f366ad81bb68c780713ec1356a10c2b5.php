<?php if (!defined('THINK_PATH')) exit();?><div class="praise_pop _praise_pop_<?php echo ($weiquanId); ?>" style="border-bottom">
<?php if(empty($like) != true): ?><div class="praise_pop_content">
        <div class="praise2 left"></div>
        <div class=" small_img left">	
            <?php $_count=1; foreach($like as $row){ if($_count>17){break;} ?> 
            <a href="<?php echo U('@'.$row[user][username]);?>"><img src="<?php echo ($row["user"]["avatar64"]); ?>" alt="<?php echo ($row["user"]["nickname"]); ?>"/></a>
            <?php $_count++;} ?>
        </div>
        <?php if(count($like)>17){ ?>
        <a href="<?php echo U('Index/weiquanDetail',array('id'=>$weiquanId,'type'=>'like'));?>" title="<?php echo L('_VIEW_MORE_');?>"><ul class="praise_more left">
                <li></li>
                <li></li>
                <li></li>
            </ul></a>
        <?php } ?>
        <div class="clear"></div>
        </div><?php endif; ?>
    </div>