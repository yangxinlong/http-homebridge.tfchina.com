<?php
use yii\helpers\Html;

$this->title = '标签管理';
$this->params['breadcrumbs'][] = $this->title;


?>
<script language="javascript">
    var edit_url = 'index.php?r=manage/school/edit-school';
    var edit_url2 = 'index.php?r=manage/school/edit-custom';
</script>
<?= Html::jsFile('@web/js/jquery.js') ?>
<?= Html::jsFile('@web/js/listtable.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>
<h1>标签管理</h1>

<?php foreach ($tag_arr as $kk => $vv) { ?>
    <div class="" style="border-bottom:1px #bbb solid;"><h3><?= $vv['tag_name'] ?></h3></div>
    <div class="" style="border-top:1px #ddd solid;"></div>
    <div class="form_padding">
        <?php foreach ($vv['arr'] as $k => $v) { ?>
            <span class="tag_value"><?= $v['name_zh'] ?> <span class="glyphicon glyphicon-remove delete_tag"
                                                               style="visibility: hidden;" title="删除"
                                                               date="<?= $v['id'] ?>"></span></span>
        <?php } ?>
    </div>
    <div class="form_padding">
        <form action="" method="post">
            <input type="text" class="" name="tag_value" size="15" maxlength="15" style="width:15em;"/><input
                type="submit" class="button" value="添加"/>
            <input type="hidden" name="parent_id" value="<?= $kk ?>"/>
        </form>
    </div>
<?php } ?>

<script language="javascript">
    $(".tag_value").mousemove(function () {
        $(this).attr('class', 'edit_tag');
        $(this).children(".glyphicon").css({visibility: 'visible'});
    });
    $(".tag_value").mouseout(function () {
        $(this).attr('class', 'tag_value');
        $(this).children(".glyphicon").css({visibility: 'hidden'});
    });
    $(".delete_tag").click(function () {
        if (confirm('确定删除该标签')) {
            window.location.href = 'index.php?r=manage/tag/delete-tag&id=' + $(this).attr('date');
        }
    });
</script>