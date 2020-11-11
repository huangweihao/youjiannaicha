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
                                <{$workBtn|raw}>
                            </div>
                        </div>
                    </div>
                    <div class="am-u-sm-12 am-u-md-9 page_toolbar">
                        <div class="am fr ">
                            <form method="get" name="search" action="">
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
                                <th>选择</th>
                                <th>用户</th>
                                <th>值班开始</th>
                                <th>值班结束</th>
                                <th>创建时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <{foreach $data['data'] as $key=>$item }>
                                <tr>
                                    <td class="am-text-middle"><{$key+1}></td>
                                    <td class="am-text-middle"><input type="checkbox" name="chk" value="<{$item['id']}>"></td>
                                    <td class="am-text-middle"><{$item['users']}></td>
                                    <td class="am-text-middle"><{$item['schedule_begin']}></td>
                                    <td class="am-text-middle"><{$item['schedule_end']}></td>
                                    <td class="am-text-middle"><{$item.ctime}></td>
                                    <td class="am-text-middle">
                                        <div class="tpl-table-black-operation">
                                            <{if $columnPower['update']}>
                                            <a href="<{$editUrl}><{$item['id']}>">
                                                <i class="am-icon-pencil"></i> 查看
                                            </a>
                                            <{/if}>
                                        </div>
                                    </td>
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