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
                                <label class="am-u-sm-2 am-form-label form-require"> 用户名 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" readonly name="username" maxlength="6" value="<{$data['username']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 手机号 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" readonly name="phone" maxlength="30" value="<{$data['phone']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 身份证 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" readonly name="identity" maxlength="30" value="<{$data['identity']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 年龄 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" name="age" maxlength="30" value="<{$data['age']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 健康状况 </label>
                                <div class="am-u-sm-10">
                                    <input type="radio" value="1" name="health" <{if $data['health'] == 1}> checked<{/if}>> 健康
                                    <input type="radio" value="2" name="health" <{if $data['health'] == 2}> checked<{/if}>> 一般(无慢性、传染性疾病)
                                    <input type="radio" value="3" name="health" <{if $data['health'] == 3}> checked<{/if}>> 体弱(无慢性、传染性疾病)
                                    <input type="radio" value="4" name="health" <{if $data['health'] == 4}> checked<{/if}>> 有无慢性、传染性疾病)

                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 当前状态 </label>
                                <div class="am-u-sm-10">
                                    <label class="am-radio-inline">
                                        <input type="radio" value="1" name="status"<{if $data['status'] eq 1}> checked<{/if}>> 正常
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" value="0" name="status"<{if $data['status'] eq 0}> checked<{/if}>> 未审核
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" value="2" name="status"<{if $data['status'] eq 2}> checked<{/if}>> 未通过审核
                                    </label>
                                    <label class="am-radio-inline">
                                        <input type="radio" value="3" name="status"<{if $data['status'] eq 3}> checked<{/if}>> 黑名单
                                    </label>
                                </div>
                            </div>
                            <{if $columnPower['insert'] || $columnPower['update']}>
                            <div class="am-form-group">
                                <div class="am-u-sm-10 am-u-sm-push-2 am-margin-top-lg">
                                    <input type="hidden" name="key" value="<{$data['id']}>">
                                    <button id="btn-submit" type="button" class="j-submit am-btn am-btn-secondary" onclick="TF_Volunteer._submit('<{$workUrl}>')">确认提交</button>
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