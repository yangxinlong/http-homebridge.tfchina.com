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
    public function push_reply($token, $ar_type, $reply_id, $con)
    {
        (new XgEvent())->push_reply($token, $ar_type, $reply_id, $con);
    }
    public function push_pass($token, $type, $id, $reward, $title)
    {
        (new XgEvent())->push_pass($token, $type, $id, $reward, $title);
    }
    public function push_msg($token, $con)
    {
        (new XgEvent())->push_msg($token, $con);
    }
    public function push_adopt($token, $type, $id, $reward)
    {
        (new XgEvent())->push_adopt($token, $type, $id, $reward);
    }
    public function push_rf($token, $type, $id)
    {
        (new XgEvent())->push_rf($token, $type, $id);
    }
    public function push_vote($token, $id, $title)
    {
        (new XgEvent())->push_vote($token, $id, $title);
    }
    public function push_note($token, $id, $title)
    {
        (new XgEvent())->push_note($token, $id, $title);
    }
    public function push_club($pri_type_id, $dtitle)
    {
        (new XgEvent)->push_club($pri_type_id, $dtitle);
    }
}