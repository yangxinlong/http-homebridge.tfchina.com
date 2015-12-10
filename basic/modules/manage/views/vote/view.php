<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\Articles\models\Articles */

$this->title = $model['title'];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '评价列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<h1><?= Html::encode($this->title) ?></h1>
<form action="" method="post">
    <table width="100%" class="table">
        <tr>
            <th width="5%">标题</th>
            <td><?= $this->title ?></td>
        </tr>
        <tr>
            <th>内容</th>
            <td><?php echo htmlspecialchars($model['contents']); ?></td>
        </tr>
        <tr>
            <th>选项</th>
            <td></td>
        </tr>
        <?php foreach ($model['opt'] as $key) { ?>
            <tr>
                <td><img style="width: 10px;height: 10px" src="images/dot.png"/></td>
                <td><?= $key['contents']; ?>
                    <br>
                    <img src="<?= $key['url']; ?>"/>
                </td>
            </tr>
        <?php } ?>
    </table>
</form>
