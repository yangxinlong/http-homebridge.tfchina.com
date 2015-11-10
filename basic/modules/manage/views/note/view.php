<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\Articles\models\Articles */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Notes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<form action="" method="post">
<table width="100%" class="table">
  <tr>
    <th>标题</th>
    <td><?= $model->title?></td>
  </tr>
  <tr>
    <th>内容</th>
    <td><?php echo htmlspecialchars($model->contents); ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td></td>
  </tr>
</table>
</form>
