<div class="am-margin-vertical am-padding am-text-sm">
    <div class="am-u-sm-12 am-u-md-12 am-u-lg-12 main_page_bg am-padding-lg">
        <div class="am-u-sm-3 am-text-right" style="font-size: 150px; padding-right: 30px">
            <i class="<{$noticeIcon}>"></i>
        </div>
        <div class="am-u-sm-9 am-padding-left-lg" style="padding-top: 70px">
            <h2><{$noticeTitle|raw}></h2>
            <p><{$noticeMsg}></p>
            <{if !empty($msg)}><p><{$msg}></p><{/if}>
            <p>&nbsp;</p>
            <a href="javascript:void(0);" onclick="history.back(-1);">[返回上一步]</a> <{if !empty($url)}><a href="<{$url}>">[跳转指定页面]</a><{/if}></p>
        </div>
    </div>
</div>