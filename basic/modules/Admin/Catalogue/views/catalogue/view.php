<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\Admin\Catalogue\models\Catalogue */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Catalogues', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="catalogue-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'school_id',
            'cat_default_id',
            'parent_id',
            'path',
            'name',
            'name_zh',
            'priority',
            'describe',
            'createtime',
            'updatetime',
            'last_admin_id',
            'ispassed',
            'isdelete',
        ],
    ]) ?>

</div>
