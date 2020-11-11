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
                                <label class="am-u-sm-2 am-form-label form-require"> 类型名称 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" name="title"  value="<{$data['title']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 状态 </label>
                                <div class="am-u-sm-10">
                                    <label class="am-radio-inline">
                                        <input type="radio" value="0" name="status"<{if $data['status'] == 0}> checked<{/if}>> 待审核
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" value="1" name="status" <{if $data['status'] == 1}> checked<{/if}>> 通过审核
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" value="2" name="status" <{if $data['status'] == 2}> checked<{/if}>> 未通过审核
                                    </label>
                                </div>
                            </div>
                            <{if $columnPower['insert'] || $columnPower['update']}>
                            <div class="am-form-group">
                                <div class="am-u-sm-10 am-u-sm-push-2 am-margin-top-lg">
                                    <input type="hidden" name="key" value="<{$data['id']}>">
                                    <button id="btn-submit" type="button" class="j-submit am-btn am-btn-secondary" onclick="TF_ArticleType._submit('<{$workUrl}>')">确认提交</button>
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