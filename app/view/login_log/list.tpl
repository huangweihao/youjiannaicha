<div class="row-content am-cf">
    <div class="row">
        <div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
            <div class="widget am-cf">
                <{if !$isLayerOpen}>
                <div class="widget-head am-cf">
                    <div class="widget-title a m-cf">登陆日志</div>
                </div>
                <{/if}>
                <div class="widget-body am-fr">
                    <{if $data['total']>0}>
                    <div class="am-scrollable-horizontal am-u-sm-12" >
                        <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>管理员</th>
                                <th>登陆ip</th>
                                <th>登陆时间</th>
                            </tr>
                            </thead>
                            <tbody>
                            <{foreach $data['data'] as $key=>$item }>
                            <tr>
                                <td class="am-text-middle"><{$key+1}></td>
                                <td class="am-text-middle"><{$managerData[$item['manager_id']] ? $managerData[$item['manager_id']]:'-'}></td>
                                <td class="am-text-middle"><{$item['ip']|long2ip}></td>
                                <td class="am-text-middle"><{$item.ctime|date="Y-m-d H:i:s"}></td>
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