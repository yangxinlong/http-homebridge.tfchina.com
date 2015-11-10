<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\Articles\models\Articles */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '文章列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<form action="" method="post">
<table width="100%" class="table">
  <tr>
    <th>标题</th>
    <td><?= $model->title?></td>
  </tr>
  <tr>
    <th>内容</th>
    <td><?php echo $model->contents; ?></td>
  </tr>
  
   <tr>
    <th>文章配图</th>
    <td>
	<?php foreach($pic_list as $kk => $vv){?>
	<?php if($kk%3 == 0){?>
	<div class="row">
       <div class="col-md-3">
	 <?php }?>  
			<a href="http://homebrisge.tfchina.com/<?= $vv['url']?>" class="thumbnail" target="_blank">
			  <img src="http://homebrisge.tfchina.com/<?= $vv['url_thumb']?>" alt="" style="width:100%;">
			</a>
	<?php if($kk%3 == 0){?>		
		</div>
	</div>
	<?php }?>
	<?php }?>
	
	</td>
  </tr> 
  
  <tr>
    <td>&nbsp;</td>
    <td></td>
  </tr>
</table>
</form>
