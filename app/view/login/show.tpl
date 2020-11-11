{__NOLAYOUT__}
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <title><{$webName}></title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="renderer" content="webkit"/>
    <meta name="csrf-token" content="<{:token()}>">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="stylesheet" href="<{$elementPath}>css/login.css?v=<{$version}>"/>
</head>
<body class="page-login-v3">
    <div class="container">
        <div id="wrapper" class="login-body">
            <div class="login-content">
                <div class="brand">
                    <!--<img alt="logo" class="brand-img" src="<{$elementPath}>img/logo.png?v=<{$version}>">-->
                    <h2 class="brand-text"><{$webName}></h2>
                </div>
                <form id="login-form" class="login-form">
                    <div class="form-group">
                        <input name="username" placeholder="请输入用户名" type="text" maxlength="30">
                    </div>
                    <div class="form-group">
                        <input name="password" placeholder="请输入密码" type="password" maxlength="30">
                    </div>
                    <div class="form-group">
                        <button id="btn-submit" type="button" onclick="TF_Login._submit('<{$loginUrl}>')">登录</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="<{$elementPath}>common/js/jquery.min.js"></script>
<script src="<{$elementPath}>common/plugins/layer/layer.js"></script>
<script src="<{$elementPath}>js/action.js?v=<{$version}>"></script>
</html>