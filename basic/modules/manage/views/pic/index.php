<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->title = '图片列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<script language="javascript">
var edit_url = 'index.php?r=manage/pic/edit-pic';
</script>
<?= Html::jsFile('@web/js/jquery.js') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.js') ?>
<?= Html::jsFile('@web/js/jstree.min.js') ?>
<?= Html::jsFile('@web/js/listtable.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<table class="table table-striped table-hover">
<tr>
<th width="20%">图片</th>
<th width="10%">作者</th>
<th width="10%">班级</th>
<th width="8%">类型</th>
<th width="15%"><span class="dropdown">
  <button id="dLabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
   <?= $shenhe?>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
   <li><a href="index.php?r=manage/pic/index">全部</a></li>
   <li><a href="index.php?r=manage/pic/index&ispassed=211">是</a></li>
   <li><a href="index.php?r=manage/pic/index&ispassed=212">否</a></li>
  </ul>
</span></th>
<th width="15%">创建时间</th>
<th>操作</th>
</tr>
<?php foreach($pic_list as $kk => $vv){?>
<tr>
<td>
<a href="http://homebrisge.tfchina.com/<?= $vv['url']?>" class="thumbnail" target="_blank">
<?= Html::img('http://homebridge.tfchina.com/'.$vv['url_thumb'])?>
</td>
</td>
<td><?= $vv['author_name']?></td>
<td><?= $vv['class_name']?></td>
<td><?= $vv['type_name']?></td>
<td><?= Html::img('@web/images/'.$vv['ispassed'].'.png',['onclick'=>"listTable.toggle(this, 'ispassed',".$vv['id'].")"])?></td>
<td><?= $vv['createtime']?></td>
<td><a href="javascript:if(confirm('确定删除')){window.location.href='index.php?r=manage/pic/delete&id=<?= $vv['id']?>';}"><span class="glyphicon glyphicon-trash"></span> 删除</a>
</td>
</tr>
<?php }?>
</table>
<div class="">
<?php
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>
<span class="pull-right"><?= $pages->totalCount?></span>
</div>