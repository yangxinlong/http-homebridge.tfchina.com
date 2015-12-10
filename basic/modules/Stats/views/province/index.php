<?php
/**
 * User: gjc
 *  2015/6/15 10:41
 */
use yii\widgets\LinkPager;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '统计'), 'url' =>Yii::$app->urlManager->createUrl(['Stats/school'])];
$this->params['breadcrumbs'][] = "注册省市";
?>

<div class="container" style="padding-right:3em;">
    <SELECT id=pro
            name=pro
            style="background:#fff; border:2px; color:#666666; font-size:16px; width:18%; height:40px; line-height:40px;"
            type="text">
        <OPTION value="" selected>所在省</OPTION>
    </SELECT>
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
                    <a href="index.php?r=Stats/class/index&school_id=<?= $v['id'] ?>"><span
                            class="glyphicon glyphicon-eye-open"></span> 查看班级</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="index.php?r=Stats/custom/index&school_id=<?= $v['id'] ?>"><span
                            class="glyphicon glyphicon-eye-open"></span> 查看用户</a>&nbsp;&nbsp;&nbsp;&nbsp;
                    <a href="index.php?r=Stats/info/index&name=<?= $v['name'] ?>&school_id=<?= $v['id'] ?>"><span
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
    var provines_name = 'pro';
    $(document).ready(function () {
        $.getJSON('index.php?r=Schools/schools/provinceslist', function (data) {
            $("#" + provines_name + " option").remove();
            $.each(data, function (j, n) {
                $("#" + provines_name + "").append('<option value=' + n.id + '>' + n.name + '</option>');
            });
            var p = "<?=$pathinfo['p'];?>"
            $("#" + provines_name).val(p);
        });
        $("#" + provines_name + "").change(function () {
            var p = $("#" + provines_name).val();
            window.location.href = 'index.php?r=Stats/province/index&p=' + p;
        });
    });
</script>



