<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\Admin\Custom\models\CustomsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '学生管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<script language="javascript">
var edit_url = 'index.php?r=manage/customs/edit-custom';
</script>
<?= Html::cssFile('@web/css/token-input.css') ?>
<?= Html::cssFile('@web/css/js_tree/default/style.min.css') ?>
<?= Html::cssFile('@web/css/style.min.css') ?>

<?= Html::jsFile('@web/js/jquery.js') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.js') ?>
<?= Html::jsFile('@web/js/jstree.min.js') ?>
<?= Html::jsFile('@web/js/listtable.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<h1>学生管理</h1>
<!--<div class="">
  <?php if($message <> ''){?>
  <div class="alert alert-success" role="alert" id="add_alert">
    <button type="button" class="close" data-dismiss="alert" onclick="$('#add_alert').hide();"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <?= $message?>
  </div>
  <?php }?>
  <form action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
      <tr>
        <td width="10%"><label for="class_name" class="control-label">学生名称</label></td>
        <td width="25%"><input type="text" class="form-control" name="name" size="10"></td>
        <td width="10%"><label for="class_name" class="control-label">密码</label></td>
        <td width="25%"><input type="text" class="form-control" name="password" size="15"></td>
		<td width="10%"><label for="class_name" class="control-label">手机号</label></td>
        <td width="25%"><input type="text" class="form-control" name="phone" size="15"></td>
        <td><input type="hidden" name="r" value="manage/class/index" />
          <input type="submit" class="btn btn-primary" value="添加班级"></td>
      </tr>
    </table>
  </form>
</div>
-->
<table class="table table-striped">
  <tr>
    <th>学生名称</th>
    <th>修改密码</th>
    <th>电话号码</th>
    <th>所属班级</th>
	<th>是否有效</th>
    <th>创建时间</th>
    <th>积分</th>
    <th>操作</th>
  </tr>
  <?php foreach($models as $kk => $vv){?>
  <tr>
    <td><span onclick="listTable.edit(this, 'name', <?= $vv['id']?>)"><?= $vv['name_zh']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'password', <?= $vv['id']?>)">点击修改密码 <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'phone', <?= $vv['id']?>)"><?= $vv['phone']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><?= $vv['class_name']?></td>
	<td><?= Html::img('@web/images/'.$vv['ispassed'].'.png',['onclick'=>"listTable.toggle(this, 'ispassed',".$vv['id'].")"])?></td>
    <td><?= $vv['createtime']?></td>
    <td><?= $vv['points']?></td>
    <td><a href="javascript:if(confirm('确定删除')){window.location.href='index.php?r=manage/customs/delete&id=<?= $vv['id']?>';}">删除</a></td>
  </tr>
  <?php }?>
</table>
<?php
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>
