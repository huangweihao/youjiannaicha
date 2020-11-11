<?php /*a:1:{s:77:"/Users/huang/Documents/project/jining/xingzhengguanli/app/view/login/show.tpl";i:1600396169;}*/ ?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta content="IE=edge" http-equiv="X-UA-Compatible">
    <title><?php echo htmlentities($webName); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="renderer" content="webkit"/>
    <meta name="csrf-token" content="<?php echo token(); ?>">
    <meta http-equiv="Cache-Control" content="no-siteapp"/>
    <link rel="stylesheet" href="<?php echo htmlentities($elementPath); ?>css/login.css?v=<?php echo htmlentities($version); ?>"/>
</head>
<body class="page-login-v3">
    <div class="container">
        <div id="wrapper" class="login-body">
            <div class="login-content">
                <div class="brand">
                    <!--<img alt="logo" class="brand-img" src="<?php echo htmlentities($elementPath); ?>img/logo.png?v=<?php echo htmlentities($version); ?>">-->
                    <h2 class="brand-text"><?php echo htmlentities($webName); ?></h2>
                </div>
                <form id="login-form" class="login-form">
                    <div class="form-group">
                        <input name="username" placeholder="请输入用户名" type="text" maxlength="30">
                    </div>
                    <div class="form-group">
                        <input name="password" placeholder="请输入密码" type="password" maxlength="30">
                    </div>
                    <div class="form-group">
                        <button id="btn-submit" type="button" onclick="TF_Login._submit('<?php echo htmlentities($loginUrl); ?>')">登录</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="<?php echo htmlentities($elementPath); ?>common/js/jquery.min.js"></script>
<script src="<?php echo htmlentities($elementPath); ?>common/plugins/layer/layer.js"></script>
<script src="<?php echo htmlentities($elementPath); ?>js/action.js?v=<?php echo htmlentities($version); ?>"></script>
</html>