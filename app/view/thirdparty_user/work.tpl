<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl"><{$columnName}></div>
                                <div class="am-fr"><a href="<{$viewUrl}>" class="am-btn am-btn-primary am-btn-xs">
                                        <span class="am-icon-reply"></span> 返回列表</a>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label "> 类型 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" readonly class="tpl-form-input" name="type" <{if $data['type'] eq 1}> value='个人' <{else}> value='院校/机构/企业' <{/if}> maxlength="120">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label "> 用户名 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" readonly class="tpl-form-input" name="username"  value="<{$data['username']}>" maxlength="120">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label "> 姓名/单位名称 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" readonly class="tpl-form-input" name="name"  value="<{$data['name']}>" maxlength="120">
                                </div>
                            </div>
                            <{if $data['type'] eq 1}>
                                <div class="am-form-group">
                                    <label class="am-u-sm-2 am-form-label "> 身份证号 </label>
                                    <div class="am-u-sm-10">
                                        <input type="text" readonly class="tpl-form-input" name="name"  value="<{$data['identity']}>" maxlength="120">
                                    </div>
                                </div>
                            <{else}>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label">营业执照 </label>
                                <div class="am-u-sm-10">
                                    <div class="am-form-file">
                                        <div class="am-form-file">
                                            <div class="uploader-list am-cf">
                                                <{if !empty($data['license'])}>
                                                <div class="file-item">
                                                    <a href="<{$data['license']}>" title="点击查看大图" target="_blank">
                                                        <img src="<{$data['license']}>">
                                                    </a>
                                                </div>
                                                <{/if}>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <{/if}>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label "> 合作意向 </label>
                                <div class="am-u-sm-10">
                                    <textarea type="text" readonly class="tpl-form-input" name="intent" maxlength="250" ><{$data['intent']}></textarea>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label "> 社会信誉 </label>
                                <div class="am-u-sm-10">
                                    <textarea type="text" readonly class="tpl-form-input" name="credit" maxlength="250" ><{$data['credit']}></textarea>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label "> 成功案例 </label>
                                <div class="am-u-sm-10">
                                    <textarea type="text" disabled class="tpl-form-input" id="content" name="content" ><{$data['case']}></textarea>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 状态 </label>
                                <div class="am-u-sm-10">
                                    <{if $data['status'] == 0 || $data['status'] == 1}>
                                        <input type="radio"  value="1" name="status" <{if $data['status'] == 1}> checked<{/if}>> 审核通过
                                        <input type="radio"  value="0" name="status" <{if $data['status'] == 0}> checked<{/if}>> 锁定
                                    <{elseif $data['status'] == 3 }>
                                        <input type="radio"  value="3" name="status" <{if $data['status'] == 3}> checked<{/if}>> 待审核
                                        <input type="radio"  value="1" name="status" <{if $data['status'] == 1}> checked<{/if}>> 审核通过
                                        <input type="radio"  value="2" name="status" <{if $data['status'] == 2}> checked<{/if}>> 驳回
                                    <{elseif $data['status'] == 2 }>
                                        <input type="radio"  value="2" name="status" <{if $data['status'] == 2}> checked<{/if}>> 驳回
                                    <{/if}>
                                </div>
                            </div>
                            <{if $columnPower['insert'] || $columnPower['update']}>
                            <div class="am-form-group">
                                <div class="am-u-sm-10 am-u-sm-push-2 am-margin-top-lg">
                                    <input type="hidden" name="key" value="<{$data['id']}>">
                                    <button id="btn-submit" type="button" class="j-submit am-btn am-btn-secondary" onclick="TF_ThirdpartyUser._submit('<{$workUrl}>')">确认提交</button>
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