<?php if (!defined('THINK_PATH')) exit();?><div class="page_title container">
    <h1 class="px14 left">首页</h1>
    <div class="jiantou left"></div>
    <h1 class="px14 left"><?php if($type == 'all'): ?>微商圈<?php else: ?>个人主页<?php endif; ?></h1>
    <div class="clear"></div>
</div>

<div class="homepage">    
    <div class="home_style container clear">
        <div class="headimg">
            <div>
                <img src="<?php echo ($user_info["avatar128"]); ?>" alt="<?php echo ($user_info["nickname"]); ?>">
            </div>
        </div>
        <div class="wechat_name clear left">
            <?php if($user_info.sex==2){ ?>
            <a class="wechat"><?php echo ($user_info["nickname"]); ?></a>
            <?php }else{ ?>
             <a class="wechat2"><?php echo ($user_info["nickname"]); ?></a>
            <?php } ?>
        </div>

        <p class="signature clear">
        <?php if($user_info['signature'] == ''): echo L('_NO_IDEA_');?>
            <?php else: ?>
            <attr title="<?php echo ($user_info["signature"]); ?>"><?php echo ($user_info["signature"]); ?></attr><?php endif; ?></p>
        <div class="keybox">
            <ul class="keyword">                
                <?php if(is_array($user_info['tags'])): $i = 0; $__LIST__ = $user_info['tags'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$tag): $mod = ($i % 2 );++$i;?><li data-url="<?php echo U('people/index/index',array('tag'=>$tag['id']));?>"><?php echo ($tag["title"]); ?></li><?php endforeach; endif; else: echo "" ;endif; ?>               
            </ul>
        </div>
        <div class="clear"></div>
    </div>
</div>