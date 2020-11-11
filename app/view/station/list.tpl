<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title a m-cf"><{$columnName}></div>
                </div>
                <div class="widget-body am-fr">
                    <div class="am-u-sm-12 am-u-md-3">
                        <div class="am-form-group">
                            <div class="am-btn-group am-btn-group-xs">
                            </div>
                        </div>
                    </div>
                    <div class="am-u-sm-12 am-u-md-9 page_toolbar">
                        <div class="am fr ">
                            <form method="get" name="search" action="">
                                <div class="am-form-group am-fl">
                                    <div class="am-input-group am-input-group-sm tpl-form-border-form">
                                        <div class="am-input-group-btn">
                                            <a target="_self" href="<{$excelUrl}>" style="background: #4db14d;color: #ffffff;float: right;" class="am-btn am-btn-default am-btn-secondary" type="button">导出数据</a>
                                        </div>
                                    </div>
                                </div>
                            <div class="am-form-group am-fl">
                            </div>
                            </form>
                        </div>
                    </div>
                    <{if $data['total']>0}>
                    <div class="am-scrollable-horizontal am-u-sm-12" >
                        <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>用户</th>
                                <th>手机号</th>
                                <th>身份证</th>
                                <th>服务时长</th>
                            </tr>
                            </thead>
                            <tbody>
                            <{foreach $data['data'] as $key=>$item }>
                                <tr>
                                    <td class="am-text-middle"><{$key+1}></td>
                                    <td class="am-text-middle"><{$item['username']}></td>
                                    <td class="am-text-middle"><{$item['phone']}></td>
                                    <td class="am-text-middle"><{$item['identity']}></td>
                                    <td class="am-text-middle"><{$item['experience']}></td>
                                </tr>
                                <{/foreach}>
                            </tbody>
                        </table>
                    </div>
                    <{$pageHtml|raw}>
                    <{else}>
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <div class="am-center am-padding am-text-center am-text-xs">暂无数据</div>
                    </div>
                    <{/if}>
                </div>
            </div>
        </div>
    </div>
</div>