<?php
/**
 * User: gjc
 *  2015/6/15 10:41
 */
use dosamigos\datepicker\DatePicker;
use yii\widgets\LinkPager;

$this->params['breadcrumbs'][] = '使用的学校';
?>
<div class="container" style="padding-top:1em;padding-bottom:1em;">
    <div class="row">
        <div class="col-md-3"><?= DatePicker::widget([
                'id' => 's',
                'name' => 'startdate',
                'value' => '开始时间',
                'attribute' => 'date',
                'template' => '{addon}{input}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]);
            ?></div>
        <div class="col-md-3"><?= DatePicker::widget([
                'id' => 'e',
                'name' => 'enddate',
                'value' => '结束时间',
                'attribute' => 'date',
                'template' => '{addon}{input}',
                'clientOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ]);
            ?></div>
        <div class="col-md-2">
            <button id="status" type="button" class="btn btn-default">统计</button>
        </div>
    </div>
</div>
<div class="container" style="padding-right:3em;">
    <table class="table table-striped table-hover">
        <tr>
            <th>ID</th>
            <th>学校名称</th>
            <th>省</th>
            <th>市</th>
            <th>地区</th>
            <th>创建时间</th>
            <th>操作</th>
        </tr>

        <?php foreach ($status['data'] as $k => $v) { ?>
            <tr>
                <td><?= $v['id'] ?></td>
                <td><?= $v['name'] ?></td>
                <td><?= $v['province'] ?></td>
                <td><?= $v['city'] ?></td>
                <td><?= $v['district'] ?></td>
                <td><?= $v['createtime'] ?></td>
                <td>
                    <a href="index.php?r=Stats/class/index&school_id=<?= $v['id'] ?>&name=<?= $v['name'] ?>&s=<?= $s; ?>&e=<?= $e; ?>&school_id=<?= $v['id'] ?>"><span
                            class="glyphicon glyphicon-eye-open"></span> 查看班级</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="index.php?r=Stats/custom/index&school_id=<?= $v['id'] ?>&name=<?= $v['name'] ?>&s=<?= $s; ?>&e=<?= $e; ?>&school_id=<?= $v['id'] ?>"><span
                            class="glyphicon glyphicon-eye-open"></span> 查看用户</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="index.php?r=Stats/info/index&school_id=<?= $v['id'] ?>&name=<?= $v['name'] ?>&s=<?= $s; ?>&e=<?= $e; ?>"><span
                            class="glyphicon glyphicon-eye-open"></span> 使用情况</a>
                </td>
            </tr>
        <?php } ?>
    </table>
    <?= LinkPager::widget([
        'pagination' => $status['pages'],
    ]);
    ?>
</div>
<script language="javascript">
    var type = "<?=$type;?>"
    var s = "<?=$s;?>"
    var e = "<?=$e;?>"
    if (s == '') s = '开始日期';
    if (e == '') e = '结束日期';
    $("#ty").val(type);
    $("#s").val(s);
    $("#e").val(e);
    $("#ss").val(s);
    $("#ee").val(e);
    $("#status").click(function () {
        var s = $("#s").val();
        var e = $("#e").val();
        var type = $("#ty").val();
        window.location.href = 'index.php?r=Stats/usedsch/index&type='+type+'&s=' + s + '&e=' + e;
    });
</script>



