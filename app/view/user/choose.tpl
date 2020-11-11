<div class="row-content am-cf">
    <div class="am-padding-vertical am-text-sm am-fr">
        <form class="am-form-inline" role="form">
            <div class="am-form-group">
                <input type="text" name="keyword" class="am-form-field choose-search" placeholder="查询关键词">
            </div>
            <button type="button" id="btn-submit" class="am-btn am-text-sm am-btn-default" onclick="TB_Choose._search('<{$searchUrl}>')">搜索</button>
        </form>
    </div>
    <div class="am-cf"></div>
    <div class="am-panel am-panel-default am-text-sm">
        <div class="am-panel-hd">选择区</div>
        <div class="am-panel-bd">
            <ul class="am-avg-sm-4 am-thumbnails choose-zone" id="chooseZone"><{$chooseHtml|raw}> </ul>
        </div>
    </div>
    <div class="am-panel am-panel-default am-text-sm">
        <div class="am-panel-hd">已选择</div>
        <div class="am-panel-bd">
            <ul class="am-avg-sm-4 am-thumbnails choose-zone" id="choosedZone"></ul>
        </div>
    </div>
    <div style="display: none"><textarea id="choosedPool"></textarea></div>
    <button type="button" class="am-btn am-btn-primary am-btn-block" id="chooseBtn" data-l="<{$limit}>" onclick="TB_Choose._confirm('<{$chooseType}>','<{$showDom}>','<{$inputDom}>','<{$searchDom}>')">确认选择</button>
</div>