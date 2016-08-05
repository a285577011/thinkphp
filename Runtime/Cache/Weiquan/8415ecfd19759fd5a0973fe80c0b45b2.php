<?php if (!defined('THINK_PATH')) exit(); if(!$isLoadScript){ ?>
<script type="text/javascript" charset="utf-8" src="/Public/js/ext/webuploader/js/webuploader.js"></script>
<link href="/Public/js/ext/webuploader/css/webuploader.css" type="text/css" rel="stylesheet">
<?php } ?>


<div class="controls multiImage">
    <div id="web_uploader_wrapper_<?php echo ($id); ?>" style="padding-bottom: 5px;"><?php echo ($config['text']); ?></div>
    <input class="attach" type="hidden" name="<?php echo ($id); ?>" value="<?php echo ($value); ?>"/>
    <div class="upload-img-box">
        <div class="upload-pre-item popup-gallery">
            <?php if(is_array($images)): $i = 0; $__LIST__ = $images;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i; if(($vo) != ""): ?><div class="each">
                        <a href="<?php echo (get_cover($vo,'path')); ?>" title="点击查看大图">
                            <img src="<?php echo (thumb($vo,$width,$height)); ?>"></a>
                        <div class="text-center opacity del_btn"></div>
                        <div onclick="image_upload.removeImage($(this),<?php echo ($vo); ?>)" class="text-center del_btn">删除</div></div><?php endif; endforeach; endif; else: echo "" ;endif; ?>
        </div>
    </div>
</div>
<script>
    $(function () {
        var id = "#web_uploader_wrapper_<?php echo ($id); ?>";
        var limit = parseInt('<?php echo ($limit); ?>');
        var uploader_<?php echo ($id); ?>  = WebUploader.create({
            // swf文件路径
            swf: 'Uploader.swf',
            // 文件接收服务端。
            server: U('Core/File/uploadPicture@'),
            // 选择文件的按钮。可选。
            // 内部根据当前运行是创建，可能是input元素，也可能是flash.
            pick: {'id': id, 'multi': true},
            fileNumLimit: limit,
            accept: {
                title: 'Images',
                extensions: 'gif,jpg,jpeg,bmp,png',
                mimeTypes: 'image/*'
            }
        });
        uploader_<?php echo ($id); ?>.on('fileQueued', function (file) {
            uploader_<?php echo ($id); ?>.upload();
            $("#web_uploader_file_name_<?php echo ($id); ?>").text('正在上传...');
        });
        uploader_<?php echo ($id); ?>.on('uploadFinished', function (file) {
            uploader_<?php echo ($id); ?>.reset();
        });
        /*上传成功*/
        uploader_<?php echo ($id); ?>.on('uploadSuccess', function (file, ret) {
            if (ret.status == 0) {
                $("#web_uploader_file_name_<?php echo ($id); ?>").text(ret.info);
                toast.error(ret.info);
            } else {
                var data = ret.data.file;
                var ids = $("[name='<?php echo ($id); ?>']").val();
                ids = ids.split(',');
                if( ids.indexOf(data.id) == -1){
                    var rids = upAttachVal('add',data.id, $("[name='<?php echo ($id); ?>']").parents('.controls').find('.attach'));
                    if(rids.length>limit){
                        toast.error("<?php echo L('_PIC_LIMIT_OVERLOAD_');?>");
                        return;
                    }
                    $("[name='<?php echo ($id); ?>']").parent().find('.upload-pre-item').append(
                            ' <div class="each"><a href="'+ data.path+'" title="'+"<?php echo L('_PIC_CLICK_TO_VIEW_BIGGER_');?>"+'"><img src="'+ data.path+'"></a><div class="text-center opacity del_btn" ></div>' +
                                    '<div onclick="image_upload.removeImage($(this),'+data.id+')"  class="text-center del_btn"><?php echo L("_DELETE_");?></div></div>'
                    );
                    imageUpload.callback();

                }else{
                    toast.error("<?php echo L('_PIC_ALREADY_EXIST_');?>");
                }

            }
        });
    });


    image_upload = {
        removeImage: function (obj, attachId) {
            // 移除附件ID数据
            upAttachVal('del', attachId,obj.parents('.controls').find('.attach'));
            obj.parents('.each').remove();
            imageUpload.removeCallback();

        }
    }




</script>