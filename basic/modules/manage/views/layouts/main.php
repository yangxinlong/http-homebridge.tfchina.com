<!DOCTYPE html>
<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

?>
<?php $this->beginPage() ?>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->head(); ?>
    <title><?= $this->title; ?></title>
    <?= Html::cssFile('@web/css/bootstrap.css') ?>
    <?= Html::cssFile('@web/css/manage_style.css') ?>
    <?= Html::jsFile('@web/js/utils.js') ?>
</head>
<body>
<div><a href="index.php?r=Stats/info/index">Home</a> </div>
<div class="container" style="padding-top:1em;padding-bottom:1em;">
    <div class="row">
        <div class="col-md-4"><a href="index.php?r=manage"><img src=""/></a></div>
        <div class="col-md-8"></div>
    </div>
</div>
<div class="container">
    <ul class="nav nav-tabs" role="tablist" id="myTab">
        <li role="presentation"><a href="#profile" role="tab" data-toggle="tab"></a></li>

        <li role="presentation"  <?php if ($this->context->id == 'school' || $this->context->id == 'customs' || $this->context->id == 'class' || $this->context->id == 'cook-book' || $this->context->id == 'tag') echo 'class="active"'; ?> >
            <a id="school" href="#school" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-cog"></span>
                院所管理</a></li>
        <li role="presentation"  <?php if ($this->context->id == 'article' || $this->context->id == 'pic' || $this->context->id == 'pingjia') echo 'class="active"'; ?> >
            <a id="article" href="#article" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-list"></span>
                内容审核</a></li>

        <p class="navbar-text navbar-right">欢迎您：<span class="label label-success">
      <?= Yii::$app->session['manage_user']['name_zh'] ?>
      </span>园长！[<a href="#" class="navbar-link">查看网站</a>] [<a href="index.php?r=manage/default/login-out">安全退出</a>]</p>
    </ul>
    <div class="tab-content" style="border-bottom:#ddd solid 0px;line-height:3em;">
        <div role="tabpanel" class="tab-pane<?php if ($this->context->id == 'default') echo ' active'; ?>" id="default">
            <div class="container">
                <div class="row">
                    <div class="col-md-1"><a href="index.php?r=manage/article/index">文章管理</a></div>
                    <div class="col-md-1"><a href="index.php?r=manage/pingjia/index">评价管理</a></div>
                    <div class="col-md-1"><a href="">发布文章</a></div>
                </div>
            </div>
        </div>
        <div role="tabpanel"
             class="tab-pane<?php if ($this->context->id == 'school' || $this->context->id == 'customs' || $this->context->id == 'class' || $this->context->id == 'cook-book' || $this->context->id == 'tag') echo ' active'; ?>"
             id="school">
            <div class="container">
                <div class="row">
                    <div class="col-md-1"><a href="index.php?r=manage/school">院所信息</a></div>
                    <div class="col-md-1"><a href="index.php?r=manage/class">班级管理</a></div>
                    <div class="col-md-1"><a href="index.php?r=manage/customs&type=1">教师管理</a></div>
                    <div class="col-md-1"><a href="index.php?r=manage/customs&type=2">学生管理</a></div>
                    <div class="col-md-1"><a href="index.php?r=manage/cook-book">食谱管理</a></div>
                    <div class="col-md-1"><a href="index.php?r=manage/tag">标签管理</a></div>
                </div>
            </div>
        </div>
        <div role="tabpanel"
             class="tab-pane<?php if ($this->context->id == 'article' || $this->context->id == 'pic' || $this->context->id == 'pingjia'|| $this->context->id == 'note'|| $this->context->id == 'vote') echo ' active'; ?>"
             id="article">
            <div class="container">
                <div class="row">
                    <div class="col-md-1"><a href="index.php?r=manage/article/index">文章列表</a></div>
                    <div class="col-md-1"><a href="index.php?r=manage/pic/index">照片列表</a></div>
                    <div class="col-md-1"><a href="index.php?r=manage/pingjia/index">评价列表</a></div>
                    <div class="col-md-1"><a href="index.php?r=manage/note/index">通知列表</a></div>
                    <div class="col-md-1"><a href="index.php?r=manage/vote/index">调查列表</a></div>
                </div>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="web">
            <div class="container">
                <div class="row">
                    <div class="col-md-1"><a href="">导航管理</a></div>
                    <div class="col-md-1"><a href="">文章管理</a></div>
                    <div class="col-md-1"><a href="">相册管理</a></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <?= Breadcrumbs::widget(['itemTemplate' => "<li class='bread_li'>{link}</li>\n", 'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],]) ?>
</div>
<div class="container"> <?php echo $content ?> </div>
<div class="container">
    <div class="footer">
        <div cvlass="" style="float:right;">发现教育科学研究所</div>
    </div>
</div>
</body>
</html>
<?php $this->endPage() ?>
<script language="javascript">
    $("#default").click(function () {
        window.location.href = 'index.php?r=manage';
    });
    $("#school").click(function () {
        window.location.href = 'index.php?r=manage/school';
    });
    $("#article").click(function () {
        window.location.href = 'index.php?r=manage/article/index';
    });
    $("#web").click(function () {
        window.location.href = 'index.php?r=manage';
    });
</script>
