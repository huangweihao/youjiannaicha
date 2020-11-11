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
                                <label class="am-u-sm-2 am-form-label form-require"> 活动标题 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input"  name="title"  value="<{$data['title']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 招募人数 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input"  name="number"  value="<{$data['number']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 举办地址 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input"  name="address"  value="<{$data['address']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 开始时间 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" id="picker_begin_time" class="tpl-form-input"  name="begin_time"  value="<{$data['begin_time']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 结束时间 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" id="picker_end_time" class="tpl-form-input" name="end_time"  value="<{$data['end_time']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 报名开始时间 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" id="picker_join_begin"  name="join_begin"  value="<{$data['join_begin']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 报名结束时间 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" id="picker_join_end" name="join_end"  value="<{$data['join_end']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require">封面 </label>
                                <div class="am-u-sm-10" style="position: sticky;">
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
                                <label class="am-u-sm-2 am-form-label form-require"> 活动内容/附件 </label>
                                <div class="am-u-sm-10" style="position: sticky;">
                                    <textarea  class="tpl-form-input" id="activity" name="content" ><{$data['content']}></textarea>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 当前状态 </label>
                                <div class="am-u-sm-10">
                                    <label class="am-radio-inline">
                                        <input type="radio" value="0" name="status"<{if $data['status'] eq 0}> checked<{/if}>> 待审核
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" value="1" name="status"<{if $data['status'] eq 1}> checked<{/if}>> 审核通过
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" value="2" name="status"<{if $data['status'] eq 2}> checked<{/if}>> 未通过审核
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" value="3" name="status"<{if $data['status'] eq 3}> checked<{/if}>> 已发布
                                    </label>
                                </div>
                            </div>
                            <{if $columnPower['insert'] || $columnPower['update']}>
                            <div class="am-form-group">
                                <div class="am-u-sm-10 am-u-sm-push-2 am-margin-top-lg">
                                    <input type="hidden" name="key" value="<{$data['id']}>">
                                    <button id="btn-submit" type="button" class="j-submit am-btn am-btn-secondary" onclick="TF_VolunteerActivity._submit('<{$workUrl}>')">确认提交</button>
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