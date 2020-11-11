<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <form id="my-form" class="am-form tpl-form-line-form" method="post">
                    <div class="widget-body">
                        <fieldset>
                            <div class="widget-head am-cf">
                                <div class="widget-title am-fl">登录密码修改</div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 原登录密码 </label>
                                <div class="am-u-sm-10">
                                    <input type="password" class="tpl-form-input" name="old_pwd" maxlength="30">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 新登录密码 </label>
                                <div class="am-u-sm-10">
                                    <input type="password" class="tpl-form-input" name="new_pwd" maxlength="30">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <label class="am-u-sm-2 am-form-label form-require"> 新密码确认 </label>
                                <div class="am-u-sm-10">
                                    <input type="password" class="tpl-form-input" name="new_pwd_confirm" maxlength="30">
                                </div>
                            </div>
                            <div class="am-form-group">
                                <div class="am-u-sm-10 am-u-sm-push-2 am-margin-top-lg">
                                    <button id="btn-submit" type="button" class="j-submit am-btn am-btn-secondary" onclick="TF_Pwd._submit('<{$editUrl}>')">确认修改</button>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>