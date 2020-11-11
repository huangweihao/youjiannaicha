<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <div class="widget-head am-cf">
                    <div class="widget-title a m-cf"><{$columnName}></div>
                </div>
                <div class="am-u-sm-12 am-u-md-8 page_toolbar" style="width: 100%;">
                    <div class="am fr">
                        <form method="get" class="sub" name="search" id="sform" action="<{$searchUrl}>">
                            <{$groupSelectStr|raw}>
                            <{$workSelectStr|raw}>
                            <div class="am-form-group am-fl">
                                <div class="am-input-group am-input-group-sm tpl-form-border-form" style="width: 33px;">
                                    <div class="am-input-group-btn">
                                        <button class="am-btn am-btn-default am-icon-search" type="submit"></button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="widget-body am-fr">
                    <{if $data['total']>0}>
                    <div class="am-scrollable-horizontal am-u-sm-12" >
                        <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>操作内容</th>
                                <th>操作时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <{foreach $data['data'] as $key=>$item }>
                            <tr>
                                <td class="am-text-middle"><{$key+1}></td>
                                <td class="am-text-middle"><{$item['content']}></td>
                                <td class="am-text-middle"><{$item['ctime']|date="Y-m-d H:i:s"}></td>
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