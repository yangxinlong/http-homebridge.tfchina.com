<?php

namespace app\modules\AppBase;
class AppBase extends \yii\base\Module
{
    public $controllerNamespace = 'app\modules\AppBase\controllers';
    public function init()
    {
        parent::init();
        // custom initialization code goes here
    }
}
