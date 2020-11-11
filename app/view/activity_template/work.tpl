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
                                <label class="am-u-sm-2 am-form-label form-require"> 模板名称 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" name="name" maxlength="6" value="<{$data['name']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require">模板背景 </label>
                                <div class="am-u-sm-10">
                                    <div class="am-form-file">
                                        <div class="am-form-file">
                                            <button type="button" class="upload-file am-btn am-btn-secondary am-radius">
                                                <i class="am-icon-cloud-upload"></i> 选择图片
                                            </button>
                                            <div class="uploader-list am-cf">
                                                <{if !empty($data['photo'])}>
                                                <div class="file-item">
                                                    <a href="<{$data['photo']}>" title="点击查看大图" target="_blank">
                                                        <img src="<{$data['photo']}>">
                                                    </a>
                                                    <input type="hidden" name="photos[]" value="<{$data['photo']}>"> <i class="iconfont icon-shanchu file-item-delete"></i>
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
                                <label class="am-u-sm-2 am-form-label form-require"> 当前状态 </label>
                                <div class="am-u-sm-10">
                                    <label class="am-radio-inline">
                                        <input type="radio" value="1" name="status"<{if $data['status'] eq 1}> checked<{/if}>> 正常
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" value="0" name="status"<{if $data['status'] eq 0}> checked<{/if}>> 锁定
                                    </label>
                                </div>
                            </div>
                            <{if $columnPower['insert'] || $columnPower['update']}>
                            <div class="am-form-group">
                                <div class="am-u-sm-10 am-u-sm-push-2 am-margin-top-lg">
                                    <input type="hidden" name="key" value="<{$data['id']}>">
                                    <button id="btn-submit" type="button" class="j-submit am-btn am-btn-secondary" onclick="TF_ActivityTemplate._submit('<{$workUrl}>')">确认提交</button>
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