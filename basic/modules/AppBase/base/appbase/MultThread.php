<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/12/4
 * Time: 11:04
 */
namespace app\modules\AppBase\base\appbase;
use app\modules\AppBase\base\xgpush\XgEvent;
use Thread;
class MultThread extends Thread
{
    public function push_ar($token, $ar_type, $id, $title)
    {
        (new XgEvent)->push_ar($token, $ar_type, $id, $title);
    }
}