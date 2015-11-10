<?php
/**
 * User: gjc
 *  2015/6/15 10:41
 */
use app\modules\AppBase\base\cat_def\CatDef;
use janisto\timepicker\TimePicker;

$item = '使用情况';
$this->params['breadcrumbs'][] = $item;
?>
<div class="container" style="padding-top:1em;padding-bottom:1em;">
    <div class="row">
        <div class="col-md-3"><?= TimePicker::widget([
                'language' => 'zh-CN',
                'id' => 's2',
                'name' => 'startdate',
                'value' => '开始时间',
                'mode' => 'datetime',
                'clientOptions' => [
                    'dateFormat' => 'yy-mm-dd',
                    'timeFormat' => 'HH:mm:ss',
                    'showSecond' => true,
                ]
            ]);
            ?></div>
        <div class="col-md-3"><?= TimePicker::widget([
                'language' => 'zh-CN',
                'id' => 'e2',
                'name' => 'enddate',
                'value' => '结束时间',
                'mode' => 'datetime',
                'clientOptions' => [
                    'dateFormat' => 'yy-mm-dd',
                    'timeFormat' => 'HH:mm:ss',
                    'showSecond' => true,
                ]
            ]);
            ?></div>
        <div class="col-md-2">
            <button id="status" type="button" class="btn btn-default">统计</button>
        </div>
    </div>
</div>
<div class="container" style="padding-right:3em;">
    <div class="row">
        <div class="col-md-1"><?= $item; ?></div>
        <div class="col-md-3">
            <?php
            if ($school_id) { ?>
                <a href="index.php?r=Stats/info/man&school_id=<?= $school_id ?>"> <?= $name ?></a>
            <?php } else {
                echo $name;
            } ?>
        </div>
    </div>
    <table class="table table-striped table-hover">
        <tr>
            <th>分类</th>
            <th>数量</th>
        </tr>
        <tr>
            <td>用户</td>
            <td>
                <?php
                if (!$school_id) { ?>
                    <a href="index.php?r=Stats/usedsch/index&type=<?= CatDef::$mod['custom'] ?>&s2=<?= $s2 ?>&e2=<?= $e2 ?>"> <?= $status['custom']['num'] ?></a>
                <?php } else {
                    echo $status['custom']['num'];
                } ?>
            </td>
        </tr>
        <tr>
            <td>图片</td>
            <td>
                <?php
                if (!$school_id) { ?>
                    <a href="index.php?r=Stats/usedsch/index&type=<?= CatDef::$mod['pic'] ?>&s2=<?= $s2 ?>&e2=<?= $e2 ?>"> <?= $status['pic']['num'] ?></a>
                <?php } else {
                    echo $status['pic']['num'];
                } ?>
            </td>
        </tr>
        <tr>
            <td>文章</td>
            <td>
                <?php
                if (!$school_id) { ?>
                    <a href="index.php?r=Stats/usedsch/index&type=<?= CatDef::$mod['article'] ?>&s2=<?= $s2 ?>&e2=<?= $e2 ?>"> <?= $status['article']['num'] ?></a>
                <?php } else {
                    echo $status['article']['num'];
                } ?>
            </td>
        </tr>
        <tr>
            <td>月评价</td>
            <td>
                <?php
                if (!$school_id) { ?>
                    <a href="index.php?r=Stats/usedsch/index&type=<?= CatDef::$mod['moneva'] ?>&s2=<?= $s2 ?>&e2=<?= $e2 ?>"> <?= $status['moneva']['num'] ?></a>
                <?php } else {
                    echo $status['moneva']['num'];
                } ?>
            </td>
        </tr>
        <tr>
            <td>年评价</td>
            <td>
                <?php
                if (!$school_id) { ?>
                    <a href="index.php?r=Stats/usedsch/index&type=<?= CatDef::$mod['termeva'] ?>&s2=<?= $s2 ?>&e2=<?= $e2 ?>"> <?= $status['termeva']['num'] ?></a>
                <?php } else {
                    echo $status['termeva']['num'];
                } ?>
            </td>
        </tr>
        <tr>
            <td>消息</td>
            <td>
                <?php
                if (!$school_id) { ?>
                    <a href="index.php?r=Stats/usedsch/index&type=<?= CatDef::$mod['msg'] ?>&s2=<?= $s2 ?>&e2=<?= $e2 ?>"> <?= $status['msg']['num'] ?></a>
                <?php } else {
                    echo $status['msg']['num'];
                } ?>
        </tr>
        <tr>
            <td>通知</td>
            <td>
                <?php
                if (!$school_id) { ?>
                    <a href="index.php?r=Stats/usedsch/index&type=<?= CatDef::$mod['note'] ?>&s2=<?= $s2 ?>&e2=<?= $e2 ?>"> <?= $status['note']['num'] ?></a>
                <?php } else {
                    echo $status['note']['num'];
                } ?>
            </td>
        </tr>
        <tr>
            <td>调查</td>
            <td>
                <?php
                if (!$school_id) { ?>
                    <a href="index.php?r=Stats/usedsch/index&type=<?= CatDef::$mod['vote'] ?>&s2=<?= $s2 ?>&e2=<?= $e2 ?>"> <?= $status['vote']['num'] ?></a>
                <?php } else {
                    echo $status['vote']['num'];
                } ?>
        </tr>
    </table>
</div>
<script language="javascript">
    var s2 = "<?=$s2;?>"
    var e2 = "<?=$e2;?>"
    if (s2 == '') s2 = '开始日期';
    if (e2 == '') e2 = '结束日期';
    $("#s2").val(s2);
    $("#e2").val(e2);
    $("#ss2").val(s2);
    $("#ee2").val(e2);
    $("#status").click(function () {
        var s2 = $("#s2").val();
        var e2 = $("#e2").val();
        window.location.href = 'index.php?r=Stats/info/index&school_id=<?=$school_id;?>&name=<?= $name; ?>&s2=' + s2 + '&e2=' + e2;
    });
</script>



