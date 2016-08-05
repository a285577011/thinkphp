<?php if (!defined('THINK_PATH')) exit(); echo ($weibo["content"]); ?>
<p style="margin-bottom: 20px;"></p>
<?php if($img_num == 4): ?><div class="trends_product3  _trends_product clear left" data-wid="<?php echo ($weibo["id"]); ?>">
        <?php if(is_array($weibo['weibo_data']['image'])): $i = 0; $__LIST__ = $weibo['weibo_data']['image'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><img src="<?php echo ($vo["small"]); ?>" class="_trends_product_img" alt="<?php echo L('_CLICK_TO_VIEW_BIGGER_');?>" data-img="<?php echo ($vo["big"]); ?>" data-id="<?php echo ($vo["id"]); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>    
<?php elseif(($img_num == 1) OR ($img_num == 2)): ?>    
    <div class="trends_product2 _trends_product clear left" data-wid="<?php echo ($weibo["id"]); ?>">
        <?php if(is_array($weibo['weibo_data']['image'])): $i = 0; $__LIST__ = $weibo['weibo_data']['image'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><img src="<?php echo ($vo["small"]); ?>" class="_trends_product_img" alt="<?php echo L('_CLICK_TO_VIEW_BIGGER_');?>" data-img="<?php echo ($vo["big"]); ?>" data-id="<?php echo ($vo["id"]); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
<?php else: ?>
    <div class="trends_product _trends_product clear left" data-wid="<?php echo ($weibo["id"]); ?>">
        <?php if(is_array($weibo['weibo_data']['image'])): $i = 0; $__LIST__ = $weibo['weibo_data']['image'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><img src="<?php echo ($vo["small"]); ?>" class="_trends_product_img" alt="<?php echo L('_CLICK_TO_VIEW_BIGGER_');?>" data-img="<?php echo ($vo["big"]); ?>" data-id="<?php echo ($vo["id"]); ?>"/><?php endforeach; endif; else: echo "" ;endif; ?>
    </div><?php endif; ?> 

<div class="product_pop clear _product_pop_<?php echo ($weibo["id"]); ?>" style="display: none">
    <div class="retract" data-wid="<?php echo ($weibo["id"]); ?>">
        <div class="retract_img left"></div>
        <p class="left cl50"><?php echo L('_IMAGE_LAYOUT_CLOSE_');?></p>
    </div>
    <div class="big_img left">       
    </div>
    <div class="roll_box clear">
        <div class="left_roll left hand"></div>
        <div class="rollimg_box left">  
            <div class="rollimg_box_x"></div>
        </div>
        <div class="right_roll right hand"></div>
    </div>
</div>