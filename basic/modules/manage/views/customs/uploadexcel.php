<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

$this->title = '添加用户';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <?php if (empty($result)) { ?>
        <h1>所有用都添加成功!</h1>
    <?php } else { ?>
        <h1>下面表中的用户已经存在,请核查!</h1>
        <div class="container-fluid">
            <div class="row-fluid">
                <div class="span12">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>
                                编号
                            </th>
                            <th>
                                姓名
                            </th>
                            <th>
                                手机
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($result as $key => $value) { ?>
                            <tr class="warning">
                                <td>
                                    <?= $key ?>
                                </td>
                                <td>
                                    <?= $value['姓名'] ?>
                                </td>
                                <td>
                                    <?= $value['手机'] ?>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
