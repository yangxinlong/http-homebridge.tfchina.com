<?php
use app\assets\AppAsset;
use app\modules\AppBase\base\CommonFun;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body>
<?php


$this->beginBody()
?>
<div class="container" style="padding-top:1em;padding-bottom:1em;">
    <p class="navbar-text navbar-right">欢迎您：<span class="label label-success">
      <?= Yii::$app->session['admin_user']['nickname'] ?>
      </span>管理员！[<a href="#" class="navbar-link">查看网站</a>] [<a href="index.php?r=Stats/default/login-out">安全退出</a>]</p>
</div>
<div class="container">
    <ul class="nav nav-pills" role="tablist" id="myTab">
        <li role="presentation"  <?php if ($this->context->id == 'default' || $this->context->id == 'school' || $this->context->id == 'usedsch' || $this->context->id == 'class' || $this->context->id == 'custom' || $this->context->id == 'info' || $this->context->id == 'province') echo 'class="active"'; ?> >
            <a id="stats" href="#stats" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-flag"></span>
                统计</a></li>
        <li role="presentation"  <?php if ($this->context->id == 'goschool') echo 'class="active"'; ?> >
            <a id="goschool" href="#goschool" role="tab" data-toggle="tab"><span
                    class="glyphicon glyphicon-globe"></span>
                学校(管理员使用)</a></li>
        <li role="presentation"  <?php if ($this->context->id == 'club') echo 'class="active"'; ?> >
            <a id="club" href="#club" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-globe"></span>
                俱乐部审核(管理员使用)</a></li>
    </ul>
    <div class="tab-content" style="border-bottom:#ddd solid 0px;line-height:3em;">
        <div role="tabpanel"
             class="tab-pane<?php if ($this->context->id == 'default' || $this->context->id == 'school' || $this->context->id == 'usedsch' || $this->context->id == 'class' || $this->context->id == 'custom' || $this->context->id == 'info' ||$this->context->id == 'schinfo' || $this->context->id == 'province') echo ' active'; ?>"
             id="stats">
            <div class="container">
                <div class="row">
                    <div class="col-md-1"><a href="index.php?r=Stats/school/index">注册学校</a></div>
                    <div class="col-md-1"><a href="index.php?r=Stats/province/index">注册省市</a></div>
                    <div class="col-md-1"><a href="index.php?r=Stats/info/index&school_id=0">使用情况</a></div>
                </div>
            </div>
        </div>
        <div role="tabpanel"
             class="tab-pane<?php if ($this->context->id == 'club') echo ' active'; ?>"
             id="stats">
            <div class="container">
                <div class="row">
                    <div class="col-md-1"><a href="index.php?r=Stats/club/index&pri_type_id=101">话题</a></div>
                    <div class="col-md-1"><a href="index.php?r=Stats/club/index&pri_type_id=102">求助</a></div>
                    <div class="col-md-1"><a href="index.php?r=Stats/club/index&pri_type_id=103">教师学习</a></div>
                    <div class="col-md-1"><a href="index.php?r=Stats/club/index&pri_type_id=104">家长学习</a></div>
                    <div class="col-md-1"><a href="index.php?r=Stats/club/index&pri_type_id=105">招生安全</a></div>
                    <div class="col-md-1"><a href="index.php?r=Stats/club/index&pri_type_id=106">政策趋势</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div>
    <input id="ontype" value="0" hidden>
    <input id="ss" value="<?= CommonFun::getCurrentDateTime() ?>" hidden>
    <input id="ee" value="0" hidden>
    <input id="ty" value="0" hidden>
</div>
<div class="alert alert-warning alert-dismissible" role="alert" hidden>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span>
    </button>
    <strong>Warning!</strong> Better check yourself, you're not looking too good.
</div>
<div class="container">
    <?= Breadcrumbs::widget(['itemTemplate' => "<li class='bread_li'>{link}</li>\n", 'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
</div>
<div class="container" style="border-style:outset;">
    <?= $content ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
<script language="javascript">
    var s = $("#ss").val();
    var e = $("#ee").val();
    $("#stats").click(function () {
        window.location.href = 'index.php?r=Stats/school/index';
    });
    $("#goschool").click(function () {
        window.location.href = 'index.php?r=Schools/schools';
    });
    $("#club").click(function () {
        window.location.href = 'index.php?r=Stats/club/index&pri_type_id=101';
    });
</script>
