<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = '添加用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1>添加用户所需时间较长,请进行其它操作!页面5秒后自动返回</h1>
</div>
<script>
    var int=self.setInterval(function(){
        history.go(-1);
    },5000)
</script>