<?php
/**
 * User: gjc
 *  2015/6/15 10:41
 */
use app\modules\AppBase\base\cat_def\CatDef;
use yii\widgets\LinkPager;


$this->title = CatDef::getName($pri_type_id);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '俱乐部审核'), 'url' => ['index', 'pri_type_id' => $pri_type_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container" style="padding-right:3em;">
    <table class="table table-striped table-hover">
        <tr>
            <th style="width: 5%">ID</th>
            <th style="width: 8%">作者</th>
            <th style="width: 10%">缩略图</th>
            <th style="width: 10%">标题</th>
            <th style="width: 40%">内容</th>
            <th style="width: 9%">创建时间</th>
            <th>操作</th>
        </tr>

        <?php foreach ($status['data'] as $k => $v) { ?>
            <tr>
                <td><?= $v['id'] ?></td>
                <td><?= $v['name_zh'] ?></td>
                <td>
                    <?php if (!is_null($v['url_thumb'])) { ?>
                        <img width="80%" src="http://www.jyq365.com/<?= $v['url_thumb'] ?>">
                    <?php } ?>
                </td>
                <td><?= mb_substr($v['title'], 0, 20) ?></td>
                <td><?= mb_substr($v['contents'], 0, 100) ?>...</td>
                <td><?= $v['createtime'] ?></td>
                <td>
                    <a href="javascript:if(confirm('确定删除')){window.location.href='index.php?r=Stats/club/delete&pri_type_id=<?= $pri_type_id ?>&id=<?= $v['id'] ?>';}"><span
                            class="glyphicon glyphicon-trash"></span> 删除</a>
                    <a href="index.php?r=Stats/club/view&pri_type_id=<?= $pri_type_id ?>&id=<?= $v['id'] ?>"><span
                            class="glyphicon glyphicon-eye-open"></span> 详情</a>

            </tr>
        <?php } ?>
    </table>
    <?= LinkPager::widget([
        'pagination' => $status['pages'],
    ]);
    ?>
</div>




