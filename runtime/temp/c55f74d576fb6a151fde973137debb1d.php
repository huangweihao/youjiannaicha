<?php /*a:1:{s:72:"/Users/huang/Documents/project/jining/xingzhengguanli/app/view/frame.tpl";i:1603707034;}*/ ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="renderer" content="webkit"/>
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <meta name="apple-mobile-web-app-title" content="<?php echo htmlentities($webName); ?>"/>
    <title><?php echo htmlentities($webName); ?></title>
    <link rel="stylesheet" href="<?php echo htmlentities($elementPath); ?>common/css/amazeui.min.css"/>
    <link rel="stylesheet" href="<?php echo htmlentities($elementPath); ?>css/app.css?v=<?= $version ?>"/>
    <link rel="stylesheet" href="//at.alicdn.com/t/font_783249_fc0v7ysdt1k.css">
</head>
<style>
    body, html{ height:100%; width:100%; overflow:hidden;}
</style>
<body>
<table id="m_apptable" cellpadding="0" cellspacing="0" width="100%" height="100%" style="width: 100%; min-width: 1000px">
    <tr>
        <td colspan="2" height="50">
            <header class="tpl-header">
                <!-- 右侧内容 -->
                <div class="tpl-header-logo-show"><?php echo htmlentities($webName); ?></div>
                <div class="tpl-header-fluid">
                    <!-- 侧边切换 -->
                    <div class="am-fl tpl-header-button switch-button">
                        <i class="iconfont icon-menufold"></i>
                    </div>
                    <!-- 刷新页面 -->
                    <div class="am-fl tpl-header-button refresh-button">
                        <i class="iconfont icon-refresh"></i>
                    </div>
                    <!-- 其它功能-->
                    <div class="am-fr tpl-header-navbar">
                        <ul>
                            <!-- 欢迎语 -->
                            <li class="am-text-sm">
                                <a href="javascript:void(0)"><span></span></a>
                            </li>
                            <!-- 欢迎语 -->
                            <li class="am-text-sm tpl-header-navbar-welcome">
                                <a href="<?php echo htmlentities($moduleUrl); ?>pwd" target="main">欢迎你，<span><?php echo htmlentities($cacheMenu['name']); ?></span></a>
                            </li>
                            <!-- 退出 -->
                            <li class="am-text-sm">
                                <a href="<?php echo htmlentities($logoutUrl); ?>">
                                    <i class="iconfont icon-tuichu"></i> 退出
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
        </td>
    </tr>
    <tr>
        <td valign="top" width="250" height="100%" id="menu">
            <div class="left-sidebar dis-flex">
                <!-- 一级菜单 -->
                <ul class="sidebar-nav">
                    <li class="sidebar-nav-link">
                        <a href="javascript:void(0);" data-i="index" class="active">
                            <i class="iconfont sidebar-nav-link-logo icon-home"></i>首页
                        </a>
                    </li>
                    <?php echo $cacheMenu['parent']; ?>
                </ul>
                <!-- 子级菜单-->
                <ul class="left-sidebar-second">
                    <div id="second_index">
                        <li class="sidebar-second-title">首页</li>
                        <li class="sidebar-second-item">
                            <a href="<?php echo htmlentities($moduleUrl); ?>main" target="main" class="active">我的首页</a>
                            <a href="<?php echo htmlentities($moduleUrl); ?>log" target="main">登录记录</a>
                        </li>
                    </div>
                    <?php echo $cacheMenu['child']; ?>
                </ul>
            </div>
        </td>
        <td valign="top" width="100%" height="100%">
            <iframe id="iframe" name="main" src="<?php echo htmlentities($moduleUrl); ?>main" allowtransparency="true" width="100%" height="100%" frameborder="0" scrolling="yes" style="overflow:visible;"></iframe>
        </td>
    </tr>
</table>
</body>
<script src="<?php echo htmlentities($elementPath); ?>common/js/jquery.min.js"></script>
<script src="<?php echo htmlentities($elementPath); ?>common/plugins/layer/layer.js"></script>
<script src="//at.alicdn.com/t/font_783249_e5yrsf08rap.js"></script>
<script src="<?php echo htmlentities($elementPath); ?>js/frame.js"></script>
</html>