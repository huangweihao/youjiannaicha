<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl"><{$columnName}></div>
                                <div class="am-fr"><a href="<{$viewUrl}>" class="am-btn am-btn-primary am-btn-xs"><span class="am-icon-reply"></span> 返回列表</a></div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 标题 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" name="title"  value="<{$data['title']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label "> 作者 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" name="author"  value="<{$data['author']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label"> 简介 </label>
                                <div class="am-u-sm-10">
                                    <textarea type="text" class="tpl-form-input" name="desc" maxlength="120" ><{$data['desc']}></textarea>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require">封面 </label>
                                <div class="am-u-sm-10">
                                    <div class="am-form-file">
                                        <div class="am-form-file">
                                            <button type="button" class="upload-file-1 am-btn am-btn-secondary am-radius">
                                                <i class="am-icon-cloud-upload"></i> 选择图片
                                            </button>
                                            <div class="uploader-list am-cf">
                                                <{if !empty($data['cover'])}>
                                                <div class="file-item">
                                                    <a href="<{$data['cover']}>" title="点击查看大图" target="_blank">
                                                        <img src="<{$data['cover']}>">
                                                    </a>
                                                    <input type="hidden" name="cover" value="<{$data['cover']}>"> <i class="iconfont icon-shanchu file-item-delete"></i>
                                                </div>
                                                <{/if}>
                                            </div>
                                        </div>
                                        <input type="hidden" name="logo">
                                        <div class="help-block am-margin-top-sm">
                                            <small>尺寸750x750像素以上，大小2M以下 (可拖拽图片调整显示顺序 )</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 内容/附件 </label>
                                <div class="am-u-sm-10">
                                    <textarea type="text" class="tpl-form-input" id="content" name="content" ><{$data['content']}></textarea>
                                </div>
                            </div>
                            <div class="am-form-group ">
                                <label class="am-u-sm-2 am-form-label" >视频 </label>
                                <div class="am-u-sm-10" id="video">
                                    <div style="float: left" id="filePicker">选择视频</div>
                                </div>
                            </div>
                            <{if $columnPower['insert'] || $columnPower['update']}>
                            <div class="am-form-group">
                                <div class="am-u-sm-10 am-u-sm-push-2 am-margin-top-lg">
                                    <input type="hidden" name="key" value="<{$data['id']}>">
                                    <button id="btn-submit" type="button" class="j-submit am-btn am-btn-secondary" onclick="TF_Train._submit('<{$workUrl}>')">确认提交</button>
                                </div>
                            </div>
                            <{/if}>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>