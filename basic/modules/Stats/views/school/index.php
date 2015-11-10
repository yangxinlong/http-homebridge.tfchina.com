<?php
/**
 * User: gjc
 *  2015/6/15 10:41
 */
use dosamigos\datepicker\DatePicker;
use yii\helpers\Html;
use yii\widgets\LinkPager;
$this->params['breadcrumbs'][] = '注册学校';
?>
<script language="javascript">
    var edit_url = 'index.php?r=Stats/school/edit';
</script>
<?= Html::jsFile('@web/js/listtable.js') ?>
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
            <input id="sch_name" type="input" placeholder="用户名或邮件地址" class="form-control"></button>
        </div>
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
            <th>审核</th>
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
                <td><?= Html::img('@web/images/'.$v['ispassed'].'.png',['onclick'=>"listTable.toggle(this, 'ispassed',".$v['id'].")"])?></td>
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
    var s = "<?=$s;?>"
    var e = "<?=$e;?>"
    var sch_name = "<?=$sch_name;?>"
    if (s == '') s = '开始日期';
    if (e == '') e = '结束日期';
    $("#s").val(s);
    $("#e").val(e);
    $("#ss").val(s);
    $("#ee").val(e);
    $("#sch_name").val(sch_name);
    $("#status").click(function () {
        var s = $("#s").val();
        var e = $("#e").val();
        var sch_name = $("#sch_name").val();
        window.location.href = 'index.php?r=Stats/school/index&s=' + s + '&e=' + e+ '&sch_name=' + sch_name;
    });
</script>



