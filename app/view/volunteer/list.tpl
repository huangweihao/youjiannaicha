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
                            <form method="get" name="search" action="<{$searchUrl}>">
                                <{$statusSelectStr|raw}>
                                <{$beasySelectStr|raw}>
                            <div class="am-form-group am-fl">
                                <div class="am-input-group am-input-group-sm tpl-form-border-form">
                                    <input type="text" class="am-form-field" name="sword" placeholder="请输入志愿者名称" value="<{$searchWord}>">
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
                                <th>名称</th>
                                <th>手机号</th>
                                <th>身份证号</th>
                                <th>年龄</th>
                                <th>健康状况</th>
                                <th>服务经验</th>
                                <th>状态</th>
                                <th>注册时间</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <{foreach $data['data'] as $key=>$item }>
                            <tr>
                                <td class="am-text-middle"><{$key+1}></td>
                                <td class="am-text-middle"><input type="checkbox" name="chk" value="<{$item['id']}>"></td>
                                <td class="am-text-middle"><{$item['username']}></td>
                                <td class="am-text-middle"><{$item['phone']}></td>
                                <td class="am-text-middle"><{$item['identity']}></td>
                                <td class="am-text-middle"><{$item['age']}></td>
                                <td class="am-text-middle"><{$item['health']}></td>
                                <td class="am-text-middle"><{$item['experience']}></td>
                                <td class="am-text-middle"><{$item['status_map']}></td>
                                <td class="am-text-middle"><{$item['ctime']}></td>
                                <td class="am-text-middle">
                                    <div class="tpl-table-black-operation">
                                        <{if $columnPower['update']}>
                                        <a href="<{$editUrl}><{$item['id']}>">
                                            <i class="am-icon-pencil"></i> 编辑
                                        </a>
                                        <{/if}>
                                        <a href="<{$examineUrl}><{$item['id']}>">
                                            <i class="am-icon-pencil-square-o"></i>考核
                                        </a>
                                        <a href="#">
                                            <i class="am-icon-columns"></i>日志
                                        </a>
                                        <a href="<{$rewardUrl}><{$item['id']}>" >
                                            <i class=" am-icon-thumbs-o-up"></i>颁发奖励
                                        </a>
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