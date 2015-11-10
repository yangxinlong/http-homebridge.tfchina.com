<?php

use app\modules\AppBase\base\HintConst;
use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\Admin\Admin\models\AdminsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Admin';

?>
<div class="admins-index">

    <div class="container" style="margin-top:15em;">
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-5">
                <?php if (isset($message)) { ?>
                    <div class="alert alert-warning"><?= $message ?></div>
                <?php } else { ?>

                <?php } ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><?= $this->title ?>  登录</h3>
                    </div>
                    <div class="panel-body">
                        <form action="index.php?r=Admin/admins/login" method="post">
                            <div class="row">
                                <div class="col-md-3" style="padding-top:0.2em;">
                                    <label for="user_name" class="control-label">用户名</label>
                                </div>
                                <div class="col-md-9" style="padding-top:0.2em;">
                                    <input type="text" name="user_name" class="form-control"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3" style="padding-top:0.2em;">
                                    <label for="user_name" class="control-label">密码</label>
                                </div>
                                <div class="col-md-9" style="padding-top:0.2em;">
                                    <input type="password" name="password" class="form-control"/>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3" style="padding-top:0.2em;"></div>
                                <div class="col-md-9" style="padding-top:0.2em;">
                                    <input type="hidden" name="r" value="Articles/articles/daily"/>
                                    <input type="submit" class="btn btn-default" value="登录"/>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-4"></div>
        </div>
    </div>

</div>
