<?php
/**
 * User: gjc
 *  2015/6/15 10:41
 */
use yii\widgets\LinkPager;
$item = '注册用户';
$this->params['breadcrumbs'][] = $item;
?>
<div class="container" style="padding-right:3em;">
    <div class="row">
        <div class="col-md-1"><?= $item ?></div>
        <div class="col-md-3"><?= $name; ?></div>
    </div>
    <table class="table table-striped table-hover">
        <tr>
            <th>ID</th>
            <th>姓名</th>
            <th>类型</th>
            <th>电话</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>

        <?php foreach ($status['data'] as $k => $v) { ?>
            <tr>
                <td><?= $v['id'] ?></td>
                <td><?= $v['name_zh'] ?></td>
                <td><?= $v['cat_default_id'] ?></td>
                <td><?= $v['phone'] ?></td>
                <td><?= $v['createtime'] ?></td>
                <td></td>
            </tr>
        <?php } ?>
    </table>
    <?= LinkPager::widget([
        'pagination' => $status['pages'],
    ]);
    ?>
</div>
<script language="javascript">
    var s = "<?=$s;?>"
    var e = "<?=$e;?>"
    if (s == '') s = '开始日期';
    if (e == '') e = '结束日期';
    $("#ss").val(s);
    $("#ee").val(e);
</script>



