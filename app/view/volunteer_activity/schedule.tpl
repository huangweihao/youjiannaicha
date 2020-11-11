<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl"><{$columnName}>排班</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 活动标题 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" readonly name="title"  value="<{$data['title']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 举办地址 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" class="tpl-form-input" readonly name="address"  value="<{$data['address']}>">
                                </div>
                            </div>

                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 活动内容/附件 </label>
                                <div class="am-u-sm-10" style="position: sticky;">
                                    <textarea  class="tpl-form-input" id="activity" name="content" ><{$data['content']}></textarea>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 开始时间 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" id="picker_begin_time" readonly class="tpl-form-input"  name="begin_time"  value="<{$data['begin_time']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 结束时间 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" id="picker_end_time" readonly class="tpl-form-input" name="end_time"  value="<{$data['end_time']}>">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 排班标题 </label>
                                <div class="am-u-sm-10">
                                    <input type="text"  class="tpl-form-input"  value="" name="schedule_title" >
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 排班开始时间 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" id="picker_schedule_begin"  class="tpl-form-input"  name="schedule_begin" >
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 排班结束时间 </label>
                                <div class="am-u-sm-10">
                                    <input type="text" id="picker_schedule_end"  class="tpl-form-input" name="schedule_end" >
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 选择用户 </label>
                                <div class="am-u-sm-10">
                                    <button style="margin-left: 10px;" type="button" onclick="TB_Common._OpenBig(this,'/user/choose')" data-i="<{$data['id']}>" data-n="排班" class="am-btn am-btn-success">选择用户</button>
                                </div>
                            </div>
                            <div class="am-form-group">
                                <lable class="am-u-sm-2 am-form-label"></lable>
                                <div class="am-u-sm-10" id="chooseShowUser">

                                </div>
                                <input type="hidden" name="user_id" id="chooseUser">
                            </div>
                            <{if $columnPower['insert'] || $columnPower['update']}>
                            <div class="am-form-group">
                                <div class="am-u-sm-10 am-u-sm-push-2 am-margin-top-lg">
                                    <input type="hidden" name="activity_id" value="<{$data['id']}>">
                                    <button id="btn-submit" type="button" class="j-submit am-btn am-btn-secondary" onclick="TF_Schedule._submit('<{$workUrl}>')">确认提交</button>
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