<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title><{$webName}></title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="renderer" content="webkit"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="apple-mobile-web-app-title" content="<{$webName}>"/>
    <{if in_array($columnAction,['i','e','examine','rewardUp'])}>
<meta name="csrf-token" content="<{:token()}>">
    <{/if}>
    <{if !in_array($columnController,['Msg'])}>
    <script src="<{$elementPath}>js/inner.js"></script>
    <{/if}>
    <link rel="stylesheet" href="<{$elementPath}>common/css/amazeui.min.css"/>
    <link rel="stylesheet" href="<{$elementPath}>common/plugins/umeditor/themes/default/css/umeditor.css"/>
    <link rel="stylesheet" href="<{$elementPath}>common/plugins/webuploader/webuploader.css"/>
    <link rel="stylesheet" href="<{$elementPath}>common/css/amazeui.datetimepicker.css"/>
    <link rel="stylesheet" href="<{$elementPath}>css/app.css?v=<{$version}>"/>
    <link rel="stylesheet" href="//at.alicdn.com/t/font_783249_fc0v7ysdt1k.css">
</head>
<body>
<div class="tpl-content-wrapper">
    {__CONTENT__}
</div>
</div>
<script src="<{$elementPath}>common/js/jquery.min.js"></script>
<script src="//at.alicdn.com/t/font_783249_e5yrsf08rap.js"></script>
<script src="<{$elementPath}>common/js/amazeui.min.js"></script>
<script src="<{$elementPath}>common/js/amazeui.datetimepicker.min.js"></script>
<script src="<{$elementPath}>js/app.js?v=<?= $version ?>"></script>
<script src="<{$elementPath}>js/file.library.js?v=<{$version}>"></script>
<script src="<{$elementPath}>js/action.js?v=<{$version}>"></script>
<{if in_array($columnAction,['choose'])}>
    <script>
        TB_Choose._init('<{$inputDom}>');
    </script>
<{/if}>
<{if in_array($columnController,['Train','ThirdpartyUser','Lesson','Schedule','Lesson','VolunteerActivity','Shop','Article','Advert']) && in_array($columnAction,['i','e','show','schedule'])}>
    <script>
        IMGUP_URL = '<{$uploadUrl}>';
        <{if in_array($columnController,['VolunteerActivity','ShopPool','Schedule','Advert']) && in_array($columnAction,['i','e','schedule'])}>
        $('#picker_begin_time').datetimepicker({
            format: 'yyyy-mm-dd hh:ii:ss'
        });
        $('#picker_end_time').datetimepicker({
            format: 'yyyy-mm-dd hh:ii:ss'
        });
        $('#picker_join_begin').datetimepicker({
            format: 'yyyy-mm-dd hh:ii:ss'
        });
        $('#picker_join_end').datetimepicker({
            format: 'yyyy-mm-dd hh:ii:ss'
        });
        $('#picker_schedule_begin').datetimepicker({
            format: 'yyyy-mm-dd hh:ii:ss'
        });
        $('#picker_schedule_end').datetimepicker({
            format: 'yyyy-mm-dd hh:ii:ss'
        });
        <{/if}>
        <{if in_array($columnController,['Shop']) && in_array($columnAction,['i','e'])}>
        function setCoordinate(value) {
            var $coordinate = $('#coordinate');
            $coordinate.val(value);
            $coordinate.trigger('change');
        }
        <{/if}>
    </script>
    <!-- 图片文件列表模板 -->
    <{include file="upload/tpl_file_item" /}>
    <!-- 文件库弹窗 -->
    <{include file="upload/file_library" /}>
    <script src="<{$elementPath}>common/js/webuploader.html5only.js"></script>
    <script src="<{$elementPath}>common/js/art-template.js"></script>
    <script src="<{$elementPath}>common/plugins/layer/layer.js"></script>
    <script src="<{$elementPath}>common/js/vue.min.js"></script>
    <script src="<{$elementPath}>common/js/ddsort.js"></script>
    <script src="<{$elementPath}>common/plugins/umeditor/umeditor.config.js?v=<{$version}>"></script>
    <script src="<{$elementPath}>common/plugins/umeditor/umeditor.min.js"></script>
    <script src="<{$elementPath}>common/plugins/umeditor/umeditor.min.js"></script>
    <script src="<{$elementPath}>common/plugins/webuploader/webuploader.js"></script>
    <script>
        <{if in_array($columnController,['Train','Lesson','ThirdpartyUser','Schedule','VolunteerActivity','Article','Advert']) && in_array($columnAction,['i','e','show','schedule'])}>
        $(function () {
            UM.getEditor('remark', {
                initialFrameHeight: 300,
                initialFrameWidth: 756,
            });
            UM.getEditor('content', {
                initialFrameHeight: 300,
                initialFrameWidth: 756,
            });
            UM.getEditor('activity', {
                initialFrameHeight: 300,
                initialFrameWidth: 756,
            });
            // 初始化Web Uploader
            var uploader = WebUploader.create({
                auto:true,
                // swf文件路径
                swf: '<{$elementPath}>common/plugins/webuploader/Uploader.swf',

                // 文件接收服务端。
                server: "/video",

                // 选择文件的按钮。可选。
                // 内部根据当前运行是创建，可能是input元素，也可能是flash.
                pick: '#filePicker',

                // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
                resize: false,
                // 只允许选择图片文件。
                accept: {
                    title: 'Video',
                    extensions: 'mp4',
                    mimeTypes: ''
                }
            });
            uploader.on( 'uploadProgress', function( file, percentage ) {
                $.AMUI.progress.inc(percentage);
            });
            uploader.on( 'uploadSuccess', function( file, e ) {
                if (e.file_id != ''){
                    $('input[name="video"]').remove();
                    $('#video').append("<input type='text' class='tpl-form-input' readonly name='video' value='"+e.file_path+"'>");
                    $.AMUI.progress.done(true);
                }else{
                    layer.msg('上传出错');
                    return false;
                }
            });
        }); 
        <{/if}>
        $(function () {
            // 选择图片
            $('.upload-file-1').selectImages({
                name: 'cover'
                , multiple: false
            });
            // 图片列表拖动
            $('.uploader-list').DDSort({
                target: '.file-item',
                delay: 100, // 延时处理，默认为 50 ms，防止手抖点击 A 链接无效
                floatStyle: {
                    'border': '1px solid #ccc',
                    'background-color': '#fff'
                }
            });
        });
    </script>
    <{/if}>
</body>
</html>