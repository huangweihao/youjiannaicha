<?php /*a:4:{s:76:"/Users/huang/Documents/project/jining/xingzhengguanli/app/view/main/show.tpl";i:1604234363;s:73:"/Users/huang/Documents/project/jining/xingzhengguanli/app/view/layout.tpl";i:1604975841;s:87:"/Users/huang/Documents/project/jining/xingzhengguanli/app/view/upload/tpl_file_item.tpl";i:1590067177;s:86:"/Users/huang/Documents/project/jining/xingzhengguanli/app/view/upload/file_library.tpl";i:1590067177;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title><?php echo htmlentities($webName); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="renderer" content="webkit"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="apple-mobile-web-app-title" content="<?php echo htmlentities($webName); ?>"/>
    <?php if(in_array($columnAction,['i','e','examine','rewardUp'])): ?>
<meta name="csrf-token" content="<?php echo token(); ?>">
    <?php endif; if(!in_array($columnController,['Msg'])): ?>
    <script src="<?php echo htmlentities($elementPath); ?>js/inner.js"></script>
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo htmlentities($elementPath); ?>common/css/amazeui.min.css"/>
    <link rel="stylesheet" href="<?php echo htmlentities($elementPath); ?>common/plugins/umeditor/themes/default/css/umeditor.css"/>
    <link rel="stylesheet" href="<?php echo htmlentities($elementPath); ?>common/plugins/webuploader/webuploader.css"/>
    <link rel="stylesheet" href="<?php echo htmlentities($elementPath); ?>common/css/amazeui.datetimepicker.css"/>
    <link rel="stylesheet" href="<?php echo htmlentities($elementPath); ?>css/app.css?v=<?php echo htmlentities($version); ?>"/>
    <link rel="stylesheet" href="//at.alicdn.com/t/font_783249_fc0v7ysdt1k.css">
</head>
<body>
<div class="tpl-content-wrapper">
    <div class="page-home row-content am-cf">
    <!-- 商城统计 -->
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12 am-margin-bottom">
            <div class="widget am-cf">
                <div class="widget-head">
                    <div class="widget-title">系统统计</div>
                </div>
                <div class="widget-body am-cf">
                    <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                        <div class="widget-card card__blue am-cf">
                            <div class="card-header">资讯数</div>
                            <div class="card-body">
                                <div class="card-value">0</div>
                                <div class="card-description">已发布的资讯</div>
                                <span class="card-icon iconfont icon-order"></span>
                            </div>
                        </div>
                    </div>

                    <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                        <div class="widget-card card__red am-cf">
                            <div class="card-header">志愿者数</div>
                            <div class="card-body">
                                <div class="card-value">0</div>
                                <div class="card-description">已通过审核的志愿者数</div>
                                <span class="card-icon iconfont icon-user"></span>
                            </div>
                        </div>
                    </div>

                    <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                        <div class="widget-card card__violet am-cf">
                            <div class="card-header">总活动</div>
                            <div class="card-body">
                                <div class="card-value">0</div>
                                <div class="card-description">活动总量</div>
                                <span class="card-icon iconfont am-icon-tree"></span>
                            </div>
                        </div>
                    </div>

                    <div class="am-u-sm-12 am-u-md-6 am-u-lg-3">
                        <div class="widget-card card__primary am-cf">
                            <div class="card-header">三方共建人数</div>
                            <div class="card-body">
                                <div class="card-value">0</div>
                                <div class="card-description">通过审核的三方共建数</div>
                                <span class="card-icon iconfont am-icon-car"></span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12 am-margin-bottom">
            <div class="widget am-cf">
                <div class="widget-head">
                    <div class="widget-title">今日概况</div>
                </div>
                <div class="widget-body am-cf">
                    <div class="am-u-sm-6 am-u-md-6 am-u-lg-3">
                        <div class="widget-outline dis-flex flex-y-center">
                            <div class="outline-left">
                                <img src="data:image/png;base64,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" alt="">
                            </div>
                            <div class="outline-right dis-flex flex-dir-column flex-x-between">
                                <div style="color: rgb(102, 102, 102); font-size: 1.3rem;">资讯数量</div>
                            </div>
                        </div>
                    </div>
                    <div class="am-u-sm-6 am-u-md-6 am-u-lg-3">
                        <div class="widget-outline dis-flex flex-dir-column flex-x-between">
                        </div>
                    </div>
                    <div class="am-u-sm-6 am-u-md-6 am-u-lg-3">
                        <div class="widget-outline dis-flex flex-y-center">
                            <div class="outline-left">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAI4AAACMCAMAAACd62ExAAAABGdBTUEAALGPC/xhBQAAAAFzUkdCAK7OHOkAAAAzUExURUdwTPn///D4/+/2//T6//D3//H3//D3//D3//H5/2aq/7DT/5HC/3az/8Ld/9zs/53I/+kzVBAAAAAKdFJOUwAOY/8hooXE4kIJnP2wAAADmUlEQVR42u2ca3fCIAyGLeFqW+j//7XTOWedbUkgYHaO784+6p6FJNxCTqdyDVpZY5zz3gN475wzVoXh1F9DsO7CsCnvjNJ9USAn3wdpUHmUu5xtTBQM0ORUM1carIcCmdACRlsolWMH0gZqxAtUCcMMpIBDhifMggcmKYZwMsCn6hHjM81NtorGArdcuQdpB/zyQchA1Q2YglZygwS3qeEx0FJevy/bVPMMDlqLEmDtaSj2MQCCeCz0ES6+etHgeBT0k8nPU9BT6v0h/qQgIahW4TVIcZy8+2joL/XebPwiLWeovrPPXlT5t+DsRRciqmIcqYqxLLpCFmY8F2lOJYvnrB/P50LNBd6cNc50LtZITz4548Tv/3NakHqCX8jmQRkn6wV3peUxUpffiZybUZ6DpIm/ppnHFDE4f82TD6vrt6MMM/66/BR/BjmPY6nTAw5nZZgl/fpcHgcG4tyJwfljGAqOIq6Pszgr913Sc0ROxJnLV+PE+eG+LwkCgbN25gCVOFujRMSxtL3MEc70iOvN9InB8aSxOsIZdwxDwnmsMzBjdYQzreK6HMeQ9p0HOIcJG43jSUvk5jj32BpABo4iuE4HHEM5smiP4ym7q/Y4P84DUnAUYSc8t8exeE++pt6xMY6hHL6lBI1xHM+RDhcO8BxbsOFo5HTeCSeg47wLjsLOWN1w9AfnIA+GD85BWv7gfHBKXVl/cP7PJCFsCpW2wBC2/EIuTuM8xx6LU4s2wdxj6Y7Mgx22fYaw7Tt32vYJ2xRLOzIQdqAi7LhJ2GGcsKNKaQe5QQSO4boE4MEJXFckLDiedoF08DdZcCztem3a/1oWHE27fLxd6y9bVR9nBhxHLWs6LnpIu6u2gqtZTCZMMxknLbfPUC+ucalnoeE87tQjyZHRE0WK47Lxs4WTDm5rM45MWKIiI+thmPOSSBmZoSruL864d6eONk6VeZ5wVsUGmFHaMU6NeVY4T7Uy6C/QRaVoOZx7XFMMsxVWleWvN5zXWhmkdspgi8sYrziFhnleWfAUea6nD5phXmYrDm9O1LjO+nFdgXC8GSaWfFY1KJ9Ol6kiFX3S/KPicmml98IeJkh7tiHtUYu0Jz/SHkRJey4m7TGdsKeG0h5iinumKu0Rr7QnztIegEt7Hi+ueQBLC4yVaRiaYYhqPCGuLYe4piXSWrqIa3gjrh2QuGZJ5FZS3nZpbiWo0Za8NmQbTdqAq0nbF7i46IS8tSAEAAAAAElFTkSuQmCC" alt="">
                            </div>
                            <div class="outline-right dis-flex flex-dir-column flex-x-between">
                                <div style="color: rgb(102, 102, 102); font-size: 1.3rem;">审批数量</div>
                            </div>
                        </div>
                    </div>
                    <div class="am-u-sm-6 am-u-md-6 am-u-lg-3">
                        <div class="widget-outline dis-flex flex-dir-column flex-x-between">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12 am-margin-bottom">
            <div class="widget am-cf">
                <div class="widget-head">
                    <div class="widget-title">近七日走势（人员/活动/公告）</div>
                </div>
                <div class="widget-body am-cf">
                    <div id="echarts-trade" class="widget-echarts"></div>
                </div>
            </div>
        </div>
    </div>

</div>
</div>
</div>
<script src="<?php echo htmlentities($elementPath); ?>common/js/jquery.min.js"></script>
<script src="//at.alicdn.com/t/font_783249_e5yrsf08rap.js"></script>
<script src="<?php echo htmlentities($elementPath); ?>common/js/amazeui.min.js"></script>
<script src="<?php echo htmlentities($elementPath); ?>common/js/amazeui.datetimepicker.min.js"></script>
<script src="<?php echo htmlentities($elementPath); ?>js/app.js?v=<?= $version ?>"></script>
<script src="<?php echo htmlentities($elementPath); ?>js/file.library.js?v=<?php echo htmlentities($version); ?>"></script>
<script src="<?php echo htmlentities($elementPath); ?>js/action.js?v=<?php echo htmlentities($version); ?>"></script>
<?php if(in_array($columnAction,['choose'])): ?>
    <script>
        TB_Choose._init('<?php echo htmlentities($inputDom); ?>');
    </script>
<?php endif; if(in_array($columnController,['Train','ThirdpartyUser','Lesson','Schedule','Lesson','VolunteerActivity','Shop','Article','Advert']) && in_array($columnAction,['i','e','show','schedule'])): ?>
    <script>
        IMGUP_URL = '<?php echo htmlentities($uploadUrl); ?>';
        <?php if(in_array($columnController,['VolunteerActivity','ShopPool','Schedule','Advert']) && in_array($columnAction,['i','e','schedule'])): ?>
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
        <?php endif; if(in_array($columnController,['Shop']) && in_array($columnAction,['i','e'])): ?>
        function setCoordinate(value) {
            var $coordinate = $('#coordinate');
            $coordinate.val(value);
            $coordinate.trigger('change');
        }
        <?php endif; ?>
    </script>
    <!-- 图片文件列表模板 -->
    <script id="tpl-file-item" type="text/template">
    {{ each list }}
    <div class="file-item">
        <a href="{{ $value.file_path }}" title="点击查看大图" target="_blank">
            <img src="{{ $value.file_path }}">
        </a>
        <input type="hidden" name="{{ name }}" value="{{ $value.file_path }}">
        <i class="iconfont icon-shanchu file-item-delete"></i>
    </div>
    {{ /each }}
</script>



    <!-- 文件库弹窗 -->
    <!-- 文件库模板 -->
<script id="tpl-file-library" type="text/template">
    <div class="row">
        <div class="file-group">
            <ul class="nav-new">
                <li class="ng-scope {{ is_default ? 'active' : '' }}" data-group-id="-1">
                    <a class="group-name am-text-truncate" href="javascript:void(0);" title="全部">全部</a>
                </li>
                <li class="ng-scope" data-group-id="0">
                    <a class="group-name am-text-truncate" href="javascript:void(0);" title="未分组">未分组</a>
                </li>
                {{ each group_list }}
                <li class="ng-scope"
                    data-group-id="{{ $value.group_id }}" title="{{ $value.group_name }}">
                    <a class="group-edit" href="javascript:void(0);" title="编辑分组">
                        <i class="iconfont icon-bianji"></i>
                    </a>
                    <a class="group-name am-text-truncate" href="javascript:void(0);">
                        {{ $value.group_name }}
                    </a>
                    <a class="group-delete" href="javascript:void(0);" title="删除分组">
                        <i class="iconfont icon-shanchu1"></i>
                    </a>
                </li>
                {{ /each }}
            </ul>
            <a class="group-add" href="javascript:void(0);">新增分组</a>
        </div>
        <div class="file-list">
            <div class="v-box-header am-cf">
                <div class="h-left am-fl am-cf">
                    <div class="am-fl">
                        <div class="group-select am-dropdown">
                            <button type="button" class="am-btn am-btn-sm am-btn-secondary am-dropdown-toggle">
                                移动至 <span class="am-icon-caret-down"></span>
                            </button>
                            <ul class="group-list am-dropdown-content">
                                <li class="am-dropdown-header">请选择分组</li>
                                {{ each group_list }}
                                <li>
                                    <a class="move-file-group" data-group-id="{{ $value.group_id }}"
                                       href="javascript:void(0);">{{ $value.group_name }}</a>
                                </li>
                                {{ /each }}
                            </ul>
                        </div>
                    </div>
                    <div class="am-fl tpl-table-black-operation">
                        <a href="javascript:void(0);" class="file-delete tpl-table-black-operation-del"
                           data-group-id="2">
                            <i class="am-icon-trash"></i> 删除
                        </a>
                    </div>
                </div>
                <div class="h-rigth am-fr">
                    <div class="j-upload upload-image">
                        <i class="iconfont icon-add1"></i>
                        上传图片
                    </div>
                </div>
            </div>
            <div id="file-list-body" class="v-box-body">
                {{ include 'tpl-file-list' file_list }}
            </div>
            <div class="v-box-footer am-cf"></div>
        </div>
    </div>

</script>

<!-- 文件列表模板 -->
<script id="tpl-file-list" type="text/template">
    <ul class="file-list-item">
        {{ include 'tpl-file-list-item' data }}
    </ul>
    {{ if last_page > 1 }}
    <div class="file-page-box am-fr">
        <ul class="pagination">
            {{ if current_page > 1 }}
            <li>
                <a class="switch-page" href="javascript:void(0);" title="上一页" data-page="{{ current_page - 1 }}">«</a>
            </li>
            {{ /if }}
            {{ if current_page < last_page }}
            <li>
                <a class="switch-page" href="javascript:void(0);" title="下一页" data-page="{{ current_page + 1 }}">»</a>
            </li>
            {{ /if }}
        </ul>
    </div>
    {{ /if }}
</script>

<!-- 文件列表模板 -->
<script id="tpl-file-list-item" type="text/template">
    {{ each $data }}
    <li class="ng-scope" title="{{ $value.file_name }}" data-file-id="{{ $value.file_id }}"
        data-file-path="{{ $value.file_url }}/{{$value.file_name}}">
        <div class="img-cover"
             style="background-image: url('{{ $value.file_url }}/{{$value.file_name}}')">
        </div>
        <p class="file-name am-text-center am-text-truncate">{{ $value.file_name }}</p>
        <div class="select-mask">
            <img src="/static/admin/img/chose.png">
        </div>
    </li>
    {{ /each }}
</script>

<!-- 分组元素-->
<script id="tpl-group-item" type="text/template">
    <li class="ng-scope" data-group-id="{{ group_id }}" title="{{ group_name }}">
        <a class="group-edit" href="javascript:void(0);" title="编辑分组">
            <i class="iconfont icon-bianji"></i>
        </a>
        <a class="group-name am-text-truncate" href="javascript:void(0);">
            {{ group_name }}
        </a>
        <a class="group-delete" href="javascript:void(0);" title="删除分组">
            <i class="iconfont icon-shanchu1"></i>
        </a>
    </li>
</script>

    <script src="<?php echo htmlentities($elementPath); ?>common/js/webuploader.html5only.js"></script>
    <script src="<?php echo htmlentities($elementPath); ?>common/js/art-template.js"></script>
    <script src="<?php echo htmlentities($elementPath); ?>common/plugins/layer/layer.js"></script>
    <script src="<?php echo htmlentities($elementPath); ?>common/js/vue.min.js"></script>
    <script src="<?php echo htmlentities($elementPath); ?>common/js/ddsort.js"></script>
    <script src="<?php echo htmlentities($elementPath); ?>common/plugins/umeditor/umeditor.config.js?v=<?php echo htmlentities($version); ?>"></script>
    <script src="<?php echo htmlentities($elementPath); ?>common/plugins/umeditor/umeditor.min.js"></script>
    <script src="<?php echo htmlentities($elementPath); ?>common/plugins/umeditor/umeditor.min.js"></script>
    <script src="<?php echo htmlentities($elementPath); ?>common/plugins/webuploader/webuploader.js"></script>
    <script>
        <?php if(in_array($columnController,['Train','Lesson','ThirdpartyUser','Schedule','VolunteerActivity','Article','Advert']) && in_array($columnAction,['i','e','show','schedule'])): ?>
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
                swf: '<?php echo htmlentities($elementPath); ?>common/plugins/webuploader/Uploader.swf',

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
        <?php endif; ?>
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
    <?php endif; ?>
</body>
</html>