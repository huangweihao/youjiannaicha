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
                                    <input type="text" class="tpl-form-input" name="title"  value="<{$data['title']}>" maxlength="120">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 简介 </label>
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
                                <label class="am-u-sm-2 am-form-label form-require" >视频 </label>
                                <div class="am-u-sm-10" id="video">
                                    <div style="float: left" id="filePicker">选择视频</div>
                                    <{if !empty($data['video'])}>
                                        <input type="text" class="tpl-form-input" readonly name="video" value="<{$data['video']}>">
                                    <{/if}>
                                </div>
                            </div>
                            <{if !empty($data['user_id'])}>
                            <div class="am-form-group ">
                                <label class="am-u-sm-2 am-form-label" >课程来源 </label>
                                <div class="am-u-sm-10" id="video">
                                    <input type="text" class="tpl-form-input" readonly name="" value="共建方">
                                </div>
                            </div>
                            <{else}>
                            <div class="am-form-group ">
                                <label class="am-u-sm-2 am-form-label" >课程来源 </label>
                                <div class="am-u-sm-10" id="video">
                                    <input type="text" class="tpl-form-input" readonly name="" value="馆方">
                                </div>
                            </div>
                            <{/if}>
                            <{if !empty($data['id'])}>
                                <div class="am-form-group">
                                    <label class="am-u-sm-2 am-form-label form-require"> 状态 </label>
                                    <div class="am-u-sm-10">
                                        <{if $data['status'] == 0}>
                                            <input type="radio" value="0" name="status" <{if $data['status'] == 0}> checked<{/if}>> 待审核
                                            <input type="radio" value="1" name="status" <{if $data['status'] == 1}> checked<{/if}>> 审核通过
                                            <input type="radio" value="2" name="status" <{if $data['status'] == 2}> checked<{/if}>> 驳回
                                        <{elseif $data['status'] == 1}>
                                            <input type="radio" value="1" name="status" <{if $data['status'] == 1}> checked<{/if}>> 审核通过
                                            <input type="radio" value="3" name="status" <{if $data['status'] == 3}> checked<{/if}>> 发布
                                            <input type="radio" value="4" name="status" <{if $data['status'] == 4}> checked<{/if}>> 下架
                                        <{elseif $data['status'] == 3 || $data['status'] == 4}>
                                            <input type="radio" value="3" name="status" <{if $data['status'] == 3}> checked<{/if}>> 发布
                                            <input type="radio" value="4" name="status" <{if $data['status'] == 4}> checked<{/if}>> 下架
                                        <{else}>
                                            <input type="radio" value="2" name="status" <{if $data['status'] == 2}> checked<{/if}>> 驳回
                                        <{/if}>
                                    </div>
                                </div>
                            <{/if}>
                            <{if $columnPower['insert'] || $columnPower['update']}>
                            <div class="am-form-group">
                                <div class="am-u-sm-10 am-u-sm-push-2 am-margin-top-lg">
                                    <input type="hidden" name="key" value="<{$data['id']}>">
                                    <button id="btn-submit" type="button" class="j-submit am-btn am-btn-secondary" onclick="TF_Lesson._submit('<{$workUrl}>')">确认提交</button>
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