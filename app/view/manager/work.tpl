<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" action="doWork"  class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl"><{$columnName}></div>
                                <div class="am-fr"><a href="<{$viewUrl}>" class="am-btn am-btn-primary am-btn-xs"><span class="am-icon-reply"></span> 返回列表</a></div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">所属权限组 </label>
                                <div class="am-u-sm-9 am-u-end"><{$roleSelectStr|raw}></div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">管理员名称 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="text" class="tpl-form-input" name="username" value="<{$data.name}>" placeholder="请输入操作人名">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-3 am-u-lg-2 am-form-label form-require">登录密码 </label>
                                <div class="am-u-sm-9 am-u-end">
                                    <input type="password" class="tpl-form-input" name="pwd" value="" placeholder="请输入密码" >
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
                                <div class="am-u-sm-9 am-u-sm-push-3 am-margin-top-lg">
                                    <input type="hidden" name="key" value="<{$data['id']}>">
                                    <button type="button" onclick="TF_Manager._submit('<{$workUrl}>')" class="j-submit am-btn am-btn-secondary">确认提交
                                    </button>
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