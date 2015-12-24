<?php

namespace app\modules\manage\controllers;
use app\modules\AppBase\base\appbase\ManageBC;
use Yii;
use yii\filters\VerbFilter;
/**
 * SchoolsController implements the CRUD actions for Schools model.
 */
class TestController extends ManageBC
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    public function actionIndex()
    {
        $this->module->set_layout('main2');
        return $this->render('index');
    }
}
