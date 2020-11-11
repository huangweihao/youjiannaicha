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
                                <{if $columnPower['insert']}>
                                <a class="am-btn am-btn-default am-btn-secondary" href="<{$insertUrl}>"><span class="am-icon-plus"></span> 新增</a>
                                <{/if}>
                            </div>
                        </div>
                    </div>
                    <div class="am-u-sm-12 am-u-md-9 page_toolbar">
                        <div class="am fr ">
                            <form method="get" name="search" action="<{$searchUrl}>">
                            <div class="am-form-group am-fl">
                                <div class="am-input-group am-input-group-sm tpl-form-border-form">
                                    <input type="text" class="am-form-field" name="sword" placeholder="请输入留言内容" value="<{$searchWord}>">
                                    <div class="am-input-group-btn">
                                        <button class="am-btn am-btn-default am-icon-search" type="submit"></button>
                                    </div>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    <{if !empty($data)}>
                    <div class="am-scrollable-horizontal am-u-sm-12">
                        <table width="100%" class="am-table am-table-compact am-table-striped tpl-table-black am-text-nowrap">
                            <thead>
                            <tr>
                                <th>序号</th>
                                <th>选择</th>
                                <th>所属留言</th>
                                <th>回复人</th>
                                <th>回复内容</th>
                                <th>点赞数量</th>
                                <th>创建时间</th>
                                <th>更新时间</th>
                                <th>状态</th>
                            </tr>
                            </thead>
                            <tbody>
                            <{foreach $data['data'] as $key=>$item }>
                            <tr>
                                <td class="am-text-middle"><{$key+1}></td>
                                <td class="am-text-middle"><input type="checkbox" name="chk" value="<{$item['id']}>"></td>
                                <td class="am-text-middle"><{$item['mes_content']}></td>
                                <td class="am-text-middle"><{$item['username']}></td>
                                <td class="am-text-middle"><{$item['content']}></td>
                                <td class="am-text-middle"><{$item['praise']}></td>
                                <td class="am-text-middle"><{$item['ctime']}></td>
                                <td class="am-text-middle"><{$item['utime']}></td>
                                <td class="am-text-middle">
                                    <{if $item['status']==0}>
                                        <span class="am-text-danger">锁定</span>
                                    <{elseif $item['status']==1}>
                                        <span class="am-text-success">正常</span>
                                    <{/if}>
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
