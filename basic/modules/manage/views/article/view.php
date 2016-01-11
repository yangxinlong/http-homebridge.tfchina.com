<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\Articles\models\Articles */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '文章列表'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::jsFile('@web/js/jquery.js') ?>
<?= Html::jsFile('@web/js/jquery.tokeninput.js') ?>
<?= Html::jsFile('@web/js/jstree.min.js') ?>
<?= Html::jsFile('@web/js/listtable.js') ?>
<?= Html::jsFile('@web/js/bootstrap.min.js') ?>

<div class="wrapper">
  <div class="col-sm-12">
    <section class="panel panel-info">
      <header class="panel-heading">
        <span><?= Html::encode($this->title) ?></span>
      </header>
      <div class="panel-body">
        <div class="adv-table editable-table">
          <table class="table table-striped table-hover table-bordered" id="editable-sample" style="margin-top:20px;">
            <tr style="background:#f0ad4e;color:#fff;">
              <th class="text-center">标题</th>
              <th class="text-center">内容</th>
              <th class="text-center">文章配图</th>
            </tr>

            <tr class="text-center">
              <td><?= $model->title?></td>
              <td><?php echo $model->contents; ?></td>
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
          </table>
        </div><!-- adv-table结束 -->
      </div><!-- panel-body结束 -->
    </section>
  </div><!-- col-*结束 -->
</div><!-- wrapper结束 -->