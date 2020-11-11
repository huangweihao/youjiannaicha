<?php /*a:4:{s:84:"/Users/huang/Documents/project/jining/xingzhengguanli/app/view/article_type/list.tpl";i:1604486321;s:73:"/Users/huang/Documents/project/jining/xingzhengguanli/app/view/layout.tpl";i:1604975841;s:87:"/Users/huang/Documents/project/jining/xingzhengguanli/app/view/upload/tpl_file_item.tpl";i:1590067177;s:86:"/Users/huang/Documents/project/jining/xingzhengguanli/app/view/upload/file_library.tpl";i:1590067177;}*/ ?>
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
    <div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title a m-cf"><?php echo htmlentities($columnName); ?></div>
                </div>
                <div class="widget-body am-fr">
                    <div class="am-u-sm-12 am-u-md-3">
                        <div class="am-form-group">
                            <div class="am-btn-group am-btn-group-xs">
                                <?php echo $workBtn; if($columnPower['insert']): ?>
                                <a class="am-btn am-btn-default am-btn-secondary" href="<?php echo htmlentities($insertUrl); ?>"><span class="am-icon-plus"></span> 新增</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="am-u-sm-12 am-u-md-9 page_toolbar">
                        <div class="am fr ">
                            <form method="get" name="search" action="<?php echo htmlentities($searchUrl); ?>">
                            <div class="am-form-group am-fl">
                                <div class="am-input-group am-input-group-sm tpl-form-border-form">
                                    <input type="text" class="am-form-field" name="sword" placeholder="请输入文章类型" value="<?php echo htmlentities($searchWord); ?>">
                                    <div class="am-input-group-btn">
                                        <button class="am-btn am-btn-default am-icon-search" type="submit"></button>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    <?php if(!empty($data)): ?>
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>选择</th>
                                <th>名称</th>
                                <th>状态</th>
                                <th>更新时间</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($data['data'] as $key=>$item): ?>
                            <tr>
                                <td class="am-text-middle"><?php echo htmlentities($key+1); ?></td>
                                <td class="am-text-middle"><input type="checkbox" name="chk" value="<?php echo htmlentities($item['id']); ?>"></td>
                                <td class="am-text-middle"><?php echo htmlentities($item['title']); ?></td>
                                <td class="am-text-middle"><?php echo htmlentities($item['status_map']); ?></td>
                                <td class="am-text-middle"><?php echo htmlentities($item['utime']); ?></td>
                                <td class="am-text-middle"><?php echo htmlentities($item['ctime']); ?></td>
                                <td class="am-text-middle">
                                    <div class="tpl-table-black-operation">
                                        <?php if($columnPower['update']): ?>
                                        <a href="<?php echo htmlentities($editUrl); ?><?php echo htmlentities($item['id']); ?>">
                                            <i class="am-icon-pencil"></i> 编辑
                                        </a>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php else: ?>
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <div class="am-center am-padding am-text-center am-text-xs">暂无数据</div>
                    </div>
                    <?php endif; ?>
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