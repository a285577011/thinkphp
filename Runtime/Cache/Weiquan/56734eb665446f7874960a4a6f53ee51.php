<?php if (!defined('THINK_PATH')) exit();?><script type="text/javascript" src="<?php echo getRootUrl();?>Addons/InsertWvideo/_static/js/video.js"></script>
<div onclick="video.show_box()" style="cursor:pointer">
<div class="video left"></div>
<p class="left" style="margin-left:8px;">视频</p>
</div>
<input type="hidden" id="insert_video_search_url" value="<?php echo addons_url('InsertWvideo://Wvideo/getVideoInfo',array(),true,true);?>">