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
                                <label class="am-u-sm-2 am-form-label "> 标题 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" readonly class="tpl-form-input" name="title" value='<{$data['title']}>' maxlength="120">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label "> 共建方名称 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" readonly class="tpl-form-input" name="thirduser_name" value='<{$data['thirduser_name']}>' maxlength="120">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label "> 开始时间 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" readonly class="tpl-form-input" name="username"  value="<{$data['begin_time']}>" maxlength="120">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label "> 结束时间 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" readonly class="tpl-form-input" name="name"  value="<{$data['end_time']}>" maxlength="120">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label "> 报名时间 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" readonly class="tpl-form-input" name="username"  value="<{$data['join_begin']}>" maxlength="120">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label "> 报名截止时间 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" readonly class="tpl-form-input" name="name"  value="<{$data['join_end']}>" maxlength="120">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label "> 地址 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" readonly class="tpl-form-input" name="name"  value="<{$data['address']}>" maxlength="120">
                                    <textarea type="text" readonly class="tpl-form-input" name="intent" maxlength="250" >1</textarea>
                                </div>
                            </div>
                            <{if !empty($data['option'])}>
                                <{foreach $data['option'] as $key => $val}>
                                    <div class="am-form-group">
                                        <label class="am-u-sm-2 am-form-label "> 选项<{$key+1}> </label>
                                        <div class="am-u-sm-10">
                                            <input type="text" readonly class="tpl-form-input" name="name"  value="<{$val['title']}>" maxlength="120">
                                            <textarea type="text" readonly class="tpl-form-input" name="intent" maxlength="250" ><{$val['content']}></textarea>
                                        </div>
                                    </div>
                                <{/foreach}>
                            <{/if}>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 状态 </label>
                                <div class="am-u-sm-10">
                                    <{if $data['status'] == 0}>
                                        <input type="radio"  value="0" name="status" <{if $data['status'] == 0}> checked<{/if}>> 待审核
                                        <input type="radio"  value="1" name="status" <{if $data['status'] == 1}> checked<{/if}>> 审核通过
                                        <input type="radio"  value="2" name="status" <{if $data['status'] == 2}> checked<{/if}>> 驳回
                                        <input type="radio"  value="3" name="status" <{if $data['status'] == 3}> checked<{/if}>> 已结束
                                    <{elseif $data['status'] == 3 }>
                                        <input type="radio"  value="3" name="status" <{if $data['status'] == 3}> checked<{/if}>> 已结束
                                    <{elseif $data['status'] == 1 }>
                                <input type="radio"  value="1" name="status" <{if $data['status'] == 1}> checked<{/if}>> 审核通过
                                <input type="radio"  value="3" name="status" <{if $data['status'] == 3}> checked<{/if}>> 已结束
                                    <{elseif $data['status'] == 2 }>
                                        <input type="radio"  value="2" name="status" <{if $data['status'] == 2}> checked<{/if}>> 驳回
                                    <{/if}>
                                </div>
                            </div>
                            <{if $columnPower['insert'] || $columnPower['update']}>
                            <div class="am-form-group">
                                <div class="am-u-sm-10 am-u-sm-push-2 am-margin-top-lg">
                                    <input type="hidden" name="key" value="<{$data['id']}>">
                                    <button id="btn-submit" type="button" class="j-submit am-btn am-btn-secondary" onclick="TF_ThirdActivity._submit('<{$workUrl}>')">确认提交</button>
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