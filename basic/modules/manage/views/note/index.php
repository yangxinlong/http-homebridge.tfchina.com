<?php

use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ArticlesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '评价列表';
$this->params['breadcrumbs'][] = $this->title;
?>
<script language="javascript">
    var edit_url = 'index.php?r=manage/note/edit-note';
</script>
<?= Html::jsFile('@web/js/jquery.js') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.js') ?>
<?= Html::jsFile('@web/js/jstree.min.js') ?>
<?= Html::jsFile('@web/js/listtable.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<table class="table table-striped table-hover">
    <tr>
        <th width="30%">通知标题</th>
        <th width="10%">作者</th>
        <th width="12%"><span class="dropdown">
  <button id="dLabel" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <?= $shenhe ?>
      <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
      <li><a href="index.php?r=manage/note/index">全部</a></li>
      <li><a href="index.php?r=manage/note/index&ispassed=211">是</a></li>
      <li><a href="index.php?r=manage/note/index&ispassed=212">否</a></li>
  </ul>
</span></th>
        <th width="15%">创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($note_list as $kk => $vv) { ?>
        <tr>
            <td><?= $vv['title'] ?></td>
            <td><?= $vv['author_name'] ?></td>
            <td><?= Html::img('@web/images/' . $vv['ispassed'] . '.png', ['onclick' => "listTable.toggle(this, 'ispassed'," . $vv['id'] . ")"]) ?></td>
            <td><?= $vv['createtime'] ?></td>
            <td>
                <a href="javascript:if(confirm('确定删除')){window.location.href='index.php?r=manage/note/delete&id=<?= $vv['id'] ?>';}"><span
                        class="glyphicon glyphicon-trash"></span> 删除</a>
                <a href="index.php?r=manage/note/view&id=<?= $vv['id'] ?>"><span
                        class="glyphicon glyphicon-eye-open"></span> 详情</a>
            </td>
        </tr>
    <?php } ?>
</table>
<div class="">
    <?php
    echo LinkPager::widget([
        'pagination' => $pages,
    ]);
    ?>
    <span class="pull-right"><?= $pages->totalCount ?></span>
</div>