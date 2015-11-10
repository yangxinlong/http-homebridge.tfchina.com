<?php
use yii\helpers\Html;

$this->title = '学校信息';
$this->params['breadcrumbs'][] = $this->title;
?>
<script language="javascript">
    var edit_url = 'index.php?r=manage/school/edit-school';
    var edit_url2 = 'index.php?r=manage/school/edit-custom';
</script>
<?= Html::jsFile('@web/js/jquery.js') ?>
<?= Html::jsFile('@web/js/listtable.js') ?>
<?= Html::jsFile('@web/js/bootstrap.js') ?>
<h1>学校信息</h1>
<!--<th><span onclick="listTable.edit(this, 'edit_goods_price', 12)" onmousemove="listTable.change_bg('edit_1')" id="edit_1">asdasdasdasdasda</span></th>-->
<table class="table">
    <tr>
        <td width="40%">学校名称</td>
        <td><span onclick="listTable.edit(this, 'name', 12,edit_url)"><?= $school['name'] ?> <span
                    class="glyphicon glyphicon-pencil"></span></span></td>
    </tr>
    <tr>
        <td>学校码</td>
        <td><?= $school['code'] ?></td>
    </tr>
    <tr>
        <td>联系电话</td>
        <td><span onclick="listTable.edit(this, 'tel', 12,edit_url)"><?= $school['tel'] ?> <span
                    class="glyphicon glyphicon-pencil"></span></span></td>
    </tr>
    <tr>
        <td>QQ</td>
        <td><span onclick="listTable.edit(this, 'qq', 12,edit_url)"><?= $school['qq'] ?> <span
                    class="glyphicon glyphicon-pencil"></span></span></td>
    </tr>
    <tr>
        <td>邮箱</td>
        <td><span onclick="listTable.edit(this, 'email', 12,edit_url)"><?= $school['email'] ?> <span
                    class="glyphicon glyphicon-pencil"></span></span></td>
    </tr>
    <tr>
        <td>地址</td>
        <td><span onclick="listTable.edit(this, 'address', 12,edit_url)"><?= $school['address'] ?> <span
                    class="glyphicon glyphicon-pencil"></span></span></td>
    </tr>
    <tr>
        <td>创建时间</td>
        <td><?= $school['createtime'] ?></td>
    </tr>
</table>
<h1>园长信息</h1>
<table class="table">
    <tr>
        <td width="40%">园长姓名</td>
        <td><span onclick="listTable.edit(this, 'name_zh', 12,edit_url2)"><?= $master['name_zh'] ?> <span
                    class="glyphicon glyphicon-pencil"></span></span></td>
    </tr>
    <tr>
        <td>园长电话</td>
        <td><span onclick="listTable.edit(this, 'phone', 12,edit_url2)"><?= $master['phone'] ?> <span
                    class="glyphicon glyphicon-pencil"></span></span></td>
    </tr>
    <tr>
        <td>修改密码</td>
        <td><span onclick="listTable.edit(this, 'password', 12,edit_url2)">点击修改密码 <span
                    class="glyphicon glyphicon-pencil"></span></span></td>
</table>