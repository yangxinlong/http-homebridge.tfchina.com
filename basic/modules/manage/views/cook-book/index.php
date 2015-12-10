<?php

use yii\helpers\Html;



$this->title = '食谱管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<script language="javascript">
var edit_url = 'index.php?r=manage/cook-book/edit-cook';
</script>
<?= Html::cssFile('@web/css/js_tree/default/style.min.css') ?>
<?= Html::jsFile('@web/js/jquery.js') ?>
<?= Html::jsFile('@web/js/listtable.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>


<h1>食谱管理</h1>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="table">
  <tr>
    <th>&nbsp;</th>
    <th>早餐</th>
    <th>加餐</th>
    <th>中餐</th>
    <th>加餐</th>
    <th>晚餐</th>

  </tr>
  <tr>
    <th>星期一</th>
    <td><span onclick="listTable.edit(this, 'name', 96)"><?= $cook_info['96']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 97)"><?= $cook_info['97']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 98)"><?= $cook_info['98']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 99)"><?= $cook_info['99']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 100)"><?= $cook_info['100']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
  </tr>
  <tr>
    <th>星期二</th>
    <td><span onclick="listTable.edit(this, 'name', 116)"><?= $cook_info['116']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 117)"><?= $cook_info['117']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 118)"><?= $cook_info['118']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 119)"><?= $cook_info['119']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 120)"><?= $cook_info['120']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
  </tr>
  <tr>
    <th>星期三</th>
    <td><span onclick="listTable.edit(this, 'name', 126)"><?= $cook_info['126']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 127)"><?= $cook_info['127']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 128)"><?= $cook_info['128']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 129)"><?= $cook_info['129']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 130)"><?= $cook_info['130']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
  </tr>
  <tr>
    <th>星期四</th>
    <td><span onclick="listTable.edit(this, 'name', 136)"><?= $cook_info['136']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 137)"><?= $cook_info['137']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 138)"><?= $cook_info['138']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 139)"><?= $cook_info['139']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 140)"><?= $cook_info['140']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
  </tr>
  <tr>
    <th>星期五</th>
    <td><span onclick="listTable.edit(this, 'name', 147)"><?= $cook_info['147']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 148)"><?= $cook_info['148']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 149)"><?= $cook_info['149']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 150)"><?= $cook_info['150']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 160)"><?= $cook_info['160']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
  </tr>
    <tr>
    <th>星期六</th>
    <td><span onclick="listTable.edit(this, 'name', 166)"><?= $cook_info['166']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 167)"><?= $cook_info['167']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 168)"><?= $cook_info['168']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 169)"><?= $cook_info['169']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 170)"><?= $cook_info['170']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
  </tr>
    <tr>
    <th>星期日</th>
    <td><span onclick="listTable.edit(this, 'name', 176)"><?= $cook_info['176']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 177)"><?= $cook_info['177']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 178)"><?= $cook_info['178']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 179)"><?= $cook_info['179']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
    <td><span onclick="listTable.edit(this, 'name', 180)"><?= $cook_info['180']?> <span class="glyphicon glyphicon-pencil"></span></span></td>
  </tr>
</table>








