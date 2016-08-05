<?php if (!defined('THINK_PATH')) exit();?><div>
    <ul>
        <li class="actortime">
            &nbsp;
        </li>
        <li class="actorpic">
            <span class="ap_flag"></span>
        </li>
        <li class="actorcont">
            &nbsp;
        </li>
    </ul>
    <div class="clear"></div>
</div>
<?php $_d=''; foreach($rows as $k=>$v){ ?>
<?php if($k != $_d): ?><div class="bigtime">
        <ul>
            <li class="actortime">
                <?php echo ($k); ?>
            </li>
            <li class="actorpic">
                <span class="ap_disc"></span>
            </li>
            <li class="actorcont">
                &nbsp;
            </li>
        </ul>
        <div class="clear"></div>
    </div><?php endif; ?>
<?php if(is_array($v)): $i = 0; $__LIST__ = $v;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><div class="actorpersonal">
        <ul>
            <li class="actortime">
            <?php echo date_fmt('h:i:s',$vo['create_time']); ?>
            </li>
            <li class="actorpic">
                <span class="ap_square"></span>
            </li>
            <li class="actorcont">
                <img src="<?php echo ($vo["user"]["avatar64"]); ?>" alt="<?php echo ($vo["user"]["nickname"]); ?>" /><span class="username"><?php echo ($vo["user"]["nickname"]); ?></span>
                <span class="addip">(<?php echo ($vo["ip"]); ?>）</span><span class="takepart">参与了 <span class="count"><?php echo ($vo["num"]); ?>人次</span></span>
            </li>
        </ul>
        <div class="clear"></div>
    </div><?php endforeach; endif; else: echo "" ;endif; ?>
<?php $_d=$k; } ?>
<!--翻页按钮组-->
<div class="page_group1 clear  pages _record_pages">
    <?php echo getPageView($count,15,array('mod'=>'Ipai/Index/orderRecord','param'=>array('pid'=>$pid)),TRUE,5);?>
    
</div>