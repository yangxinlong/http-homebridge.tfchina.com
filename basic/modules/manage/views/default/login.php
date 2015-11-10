<?php
use yii\helpers\Html;

?>
<?= Html::cssFile('@web/css/bootstrap.css') ?>
<?= Html::jsFile('@web/js/jquery.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<title>登录</title>
<script type="text/javascript">
    var userAgent = navigator.userAgent,
        rMsie = /(msie\s|trident.*rv:)([\w.]+)/,
        rFirefox = /(firefox)\/([\w.]+)/,
        rOpera = /(opera).+version\/([\w.]+)/,
        rChrome = /(chrome)\/([\w.]+)/,
        rSafari = /version\/([\w.]+).*(safari)/;
    var browser;
    var version;
    var ua = userAgent.toLowerCase();
    function uaMatch(ua) {
        var match = rMsie.exec(ua);
        if (match != null) {
            return {browser: "IE", version: match[2] || "0"};
        }
        var match = rFirefox.exec(ua);
        if (match != null) {
            return {browser: match[1] || "", version: match[2] || "0"};
        }
        var match = rOpera.exec(ua);
        if (match != null) {
            return {browser: match[1] || "", version: match[2] || "0"};
        }
        var match = rChrome.exec(ua);
        if (match != null) {
            return {browser: match[1] || "", version: match[2] || "0"};
        }
        var match = rSafari.exec(ua);
        if (match != null) {
            return {browser: match[2] || "", version: match[1] || "0"};
        }
        if (match != null) {
            return {browser: "", version: "0"};
        }
    }
    var browserMatch = uaMatch(userAgent.toLowerCase());
    if (browserMatch.browser) {
        browser = browserMatch.browser;
        version = browserMatch.version;
    }
    if (browser == "IE" && version != "11.0" && version != "12.0")
        alert("对不起，您的浏览器版本过低，会影响网页的正常浏览,为保证您的正常浏览,请用火狐浏览器，ie11.0以上浏览器或谷歌浏览器！");
</script>
</head>
<body>
<div class="container" style="margin-top:15em;">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-5">
            <?php if (isset($message)) { ?>
                <div class="alert alert-warning"><?= $message ?></div>
            <?php } else { ?>

            <?php } ?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">园长登录</h3>
                </div>
                <div class="panel-body">
                    <form action="index.php?r=manage/default/login" method="post">
                        <div class="row">
                            <div class="col-md-3" style="padding-top:0.2em;">
                                <label for="user_name" class="control-label">用户名</label>
                            </div>
                            <div class="col-md-9" style="padding-top:0.2em;">
                                <input type="text" name="user_name" class="form-control"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="padding-top:0.2em;">
                                <label for="user_name" class="control-label">密码</label>
                            </div>
                            <div class="col-md-9" style="padding-top:0.2em;">
                                <input type="password" name="password" class="form-control"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3" style="padding-top:0.2em;"></div>
                            <div class="col-md-9" style="padding-top:0.2em;">
                                <input type="hidden" name="r" value="Articles/articles/daily"/>
                                <input type="submit" class="btn btn-default" value="登录"/>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>
</div>
</body>
</html>
