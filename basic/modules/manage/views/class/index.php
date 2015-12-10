<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\LinkPager;
/* @var $this yii\web\View */
/* @var $searchModel app\modules\Admin\Classes\models\ClassesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->params['breadcrumbs'][] = '班级管理';

?>
<script language="javascript">
var edit_url = 'index.php?r=manage/class/edit-class';
</script>

<?= Html::cssFile('@web/css/token-input.css') ?>
<?= Html::cssFile('@web/css/js_tree/default/style.min.css') ?>


<?= Html::jsFile('@web/js/jquery.js') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.js') ?>
<?= Html::jsFile('@web/js/jstree.min.js') ?>
<?= Html::jsFile('@web/js/listtable.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>

<h1>班级管理</h1>

<div class="">
<?php if($message <> ''){?>
<div class="alert alert-success" role="alert" id="add_alert">
<button type="button" class="close" data-dismiss="alert" onclick="$('#add_alert').hide();"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
<?= $message?></div>
<?php }?>
<form action="" method="post">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
  <tr><td width="10%"><label for="class_name" class="control-label">班级名称</label></td>
    <td width="25%"><input type="text" class="form-control" name="class_name" size="10"></td>
    <td width="10%"><label for="class_name" class="control-label">主班老师</label></td>
    <td width="25%"><input type="text" class="form-control" name="teacher_id" size="10" id="teacher_id" style="display:none;"></td>
    <td><input type="hidden" name="r" value="manage/class/index" /><input type="submit" class="btn btn-primary" value="添加班级"></td>
  </tr>
</table>
</form>

<script type="text/javascript">
  var default_texting = function running(ob){
							  if(ob.html() == ''){
								 ob.jstree({
										  'core' : {
											'data' : {
												  'url' : 'index.php?r=manage/class/all-teacher',
												  'data' : function (node) {
														return { 'wwid' : node.id };
												  }
											  }
										   },
										   'plugins' : ['unique','wholerow']
								 })
								 .on('changed.jstree', function (e, data) {
									if(data && data.selected && data.selected.length && data.node) {
									   //check if id exite
									   //get input value
									   var ob = $("#teacher_id").tokenInput("get");
									   for(item in ob){
										  if(parseInt(ob[item].id) == parseInt(data.selected)){
											  exit();
										  }
									   }
									   $("#teacher_id").tokenInput("add", {id: data.selected, name: data.node.text});
									   return false;
									}
								})
							  }
							};

  $("#teacher_id").tokenInput('index.php?r=manage/class/search-teacher',{hintText:default_texting,noResultsText: "没有搜索结果",searchingText: "搜索中",tokenLimit: 1});
</script>




</div>


<table class="table table-striped table-hover">
<tr>
<th>班级名称</th>
<th>班级CODE</th>
<th>主班老师</th>
<th>是否有效</th>
<th>是否已毕业</th>
<th>创建时间</th>
<th>操作</th>
</tr>
<?php foreach($models as $kk => $vv){?>
<tr>
<td><span onclick="listTable.edit(this, 'name', <?= $vv['id']?>)"><?= $vv['name']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
<td><?= $vv['code']?></td>
<td><?= $vv['teacher_name']?></td>
<td><?= Html::img('@web/images/'.$vv['ispassed'].'.png',['onclick'=>"listTable.toggle(this, 'ispassed',".$vv['id'].")"])?></td>
<td><?= Html::img('@web/images/'.$vv['isgraduated'].'.png',['onclick'=>"listTable.toggle(this, 'isgraduated',".$vv['id'].")"])?></td>
<td><?= $vv['createtime']?></td>
<td><a href="javascript:if(confirm('确定删除')){window.location.href='index.php?r=manage/class/delete&id=<?= $vv['id']?>';}"><span class="glyphicon glyphicon-trash"></span> 删除</a> <a href="index.php?r=manage/customs/index&type=2&class_id=<?= $vv['id']?>"><span class="glyphicon glyphicon-eye-open"></span> 查看班级学生</a></td>
</tr>
<?php }?>
</table>

<?php
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>


