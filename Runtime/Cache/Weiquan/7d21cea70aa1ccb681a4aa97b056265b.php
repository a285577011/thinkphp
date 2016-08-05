<?php if (!defined('THINK_PATH')) exit(); if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$weiquan): $mod = ($i % 2 );++$i; echo W('Weiquan/WeiquanDetail/detail',array('weiquan_id'=>$weiquan,'can_hide'=>0,'indexpage'=>TRUE,'show_user'=>$show_user)); endforeach; endif; else: echo "" ;endif; ?>
<?php if(!empty($lastId)){ ?>
    <script>
        weiquan.lastId = '<?php echo ($lastId); ?>';
    </script>
<?php } ?>
<?php if($not_more){ ?>
    <script>
        $(function(){
        	 $('#load_more').remove();
        })
    </script>
<?php } ?>