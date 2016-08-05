<?php if (!defined('THINK_PATH')) exit();?>
<p class="word-wrap"><?php echo ($weibo["content"]); ?></p>

    <div data-src="<?php echo (urldecode($weibo_data['swf_src'])); ?>" data-role="show_video" style="cursor: pointer;width: 150px;">
        <i class="video_icon"></i>
                <img style="width:150px;height: 100px;" src="<?php echo (urldecode($weibo_data['img_url'])); ?>">
            </div>