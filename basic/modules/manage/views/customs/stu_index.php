<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\Admin\Custom\models\CustomsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '学生管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<script language="javascript">
var edit_url = 'index.php?r=manage/customs/edit-custom';
</script>
<?= Html::cssFile('@web/css/token-input.css') ?>
<?= Html::cssFile('@web/css/js_tree/default/style.min.css') ?>
<?= Html::cssFile('@web/css/style.min.css') ?>

<?= Html::jsFile('@web/js/jquery.js') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.js') ?>
<?= Html::jsFile('@web/js/jstree.min.js') ?>
<?= Html::jsFile('@web/js/listtable.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close"
                        data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">
                    积分修改
                </h4>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <table border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td width="20%">当前积分: <span id="curpoints">1000</span></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td width="50%"><input id="pointssize" type="text" class="form-control" name="name_zh"
                                                   size="10" min="1" max="100"
                                                   placeholder="输入要变更的积分"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                            <td width="50%"><label class="radio">
                                    <input type="radio" name="optionsRadios" id="radios1" value="1"
                                        > 加分 </label></td>
                            <td width="50%"><label class="radio">
                                    <label class="radio">
                                        <input type="radio" name="optionsRadios" id="radios2" value="2" checked>
                                        减分 </label></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="20%">变更原因:</td>
                            <td width="50%"><input id="pointscontents" type="text" class="form-control" name="name_zh"
                                                   size="10"
                                                   placeholder="(必填)30汉字以内"></td>
                            <td>&nbsp;</td>
                        </tr>
                        <tr>
                            <td width="20%"></td>
                            <td width="50%"><input id="curcustom_id" type="text" name="curcustom_id" size="10" hidden>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button id="editpoints" type="button" class="btn btn-primary"> 确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal -->
</div>


<h1>学生管理</h1>
<!--<div class="">
  <?php if($message <> ''){?>
  <div class="alert alert-success" role="alert" id="add_alert">
    <button type="button" class="close" data-dismiss="alert" onclick="$('#add_alert').hide();"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <?= $message?>
  </div>
  <?php }?>
  <form action="" method="post">
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
      <tr>
        <td width="10%"><label for="class_name" class="control-label">学生名称</label></td>
        <td width="25%"><input type="text" class="form-control" name="name" size="10"></td>
        <td width="10%"><label for="class_name" class="control-label">密码</label></td>
        <td width="25%"><input type="text" class="form-control" name="password" size="15"></td>
		<td width="10%"><label for="class_name" class="control-label">手机号</label></td>
        <td width="25%"><input type="text" class="form-control" name="phone" size="15"></td>
        <td><input type="hidden" name="r" value="manage/class/index" />
          <input type="submit" class="btn btn-primary" value="添加班级"></td>
      </tr>
    </table>
  </form>
</div>
-->
<div>
    <form action="" method="post">
        <table width="40%" border="0" cellspacing="0" cellpadding="0" class="table">
            <tr>
                <td width="8%"><SELECT id=field_type
                                       name=field_type
                                       style="background:#fff; border:2px; color:#666666; font-size:16px; height:40px; line-height:40px;"
                                       type="text">
                    </SELECT></td>
                <td width="15%"><input id="field" type="text" class="form-control" name="field" size="10"></td>
                <td width="15%"><input type="hidden" name="r" value="manage/class/index"/>
                    <input type="submit" class="btn btn-primary" value="查找"></td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </form>
</div>
<table class="table table-striped">
  <tr>
    <th>学生名称</th>
    <th>修改密码</th>
    <th>电话号码</th>
    <th>所属班级</th>
	<th>是否有效</th>
    <th>创建时间</th>
    <th>积分</th>
    <th>操作</th>
  </tr>
  <?php foreach($models as $kk => $vv){?>
  <tr>
    <td><span onclick="listTable.edit(this, 'name', <?= $vv['id']?>)"><?= $vv['name_zh']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'password', <?= $vv['id']?>)">点击修改密码 <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'phone', <?= $vv['id']?>)"><?= $vv['phone']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><?= $vv['class_name']?></td>
	<td><?= Html::img('@web/images/'.$vv['ispassed'].'.png',['onclick'=>"listTable.toggle(this, 'ispassed',".$vv['id'].")"])?></td>
    <td><?= $vv['createtime']?></td>
    <td><?= $vv['points']?></td>
    <td><a href="javascript:if(confirm('确定删除')){window.location.href='index.php?r=manage/customs/delete&id=<?= $vv['id']?>';}">删除</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <a href="javascript:void(0)" onclick="update(this,<?= $vv['id'] ?>);">变更积分</a></td>
  </tr>
  <?php }?>
</table>
<?php
echo LinkPager::widget([
    'pagination' => $pages,
]);
?>
<script language="javascript">
    var field_type = 'field_type';
    function update(obj, custom_id) {
        var tds = $(obj).parent().parent().find('td');
        $('#curpoints').html(tds.eq(6).text());
        $('#curcustom_id').val(custom_id);
        $('#myModal').modal('show');
    }
    $(document).ready(function () {
        $("#" + field_type + " option").remove();
        $("#" + field_type + "").append('<option value=0>请选择</option>');
        $("#" + field_type + "").append('<option value=1>姓名</option>');
        $("#" + field_type + "").append('<option value=2>手机</option>');
        $("#" + field_type + "").val("<?=$params['field_type']?>");
        $("#field").val("<?=$params['field']?>");
        $('#editpoints').click(function () {
            var size = $('#pointssize').val();
            var contents = $('#pointscontents').val();
            if (!isNaN(size) && contents) {
                var num = $('#pointssize').val();
                var boolCheck = $('#radios2').is(":checked");
                if (boolCheck) {
                    num = 0 - num;
                }
                $.post('index.php?r=Score/score/editscorebyhead', {
                    pri_type_id: 7,
                    sub_type_id: 7,
                    num: num,
                    custom_id: $('#curcustom_id').val(),
                    contents: $('#pointscontents').val()
                }, function (data) {
                    $('#myModal').modal('hide');
                    history.go(0);
                }, 'json');
            } else {
                alert("请输入相关内容!");
            }
        });
    });
</script>
