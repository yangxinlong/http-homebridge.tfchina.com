<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/11/25
 * Time: 10:32
 */
namespace app\modules\AppBase\base\appbase;
use Yii;
class StatsBC extends BaseController
{
    public $mc;
    public $mc_name;//id+url//有的用get有的用post
    public $mc_name_common;//url//有的用get有的用post
    public $mc_name_act;//action//get和post统一了
    public $myId;
    public function __construct($id, $module, $config = [])
    {
        parent::__construct($id, $module, $config); // TODO: Change the autogenerated stub
        $this->mc = new BaseMem();
    }
    public function beforeAction($action)
    {
        $this->setPagestartime(microtime());
        $allow_arr = ['regcustom'];
        $this->mc_name = Yii::$app->request->getUrl();
        $this->mc_name_common = $this->mc_name;
        $this->mc_name_act = $this->mc_name;
        if (isset(Yii::$app->session['admin_user']) || in_array($action->id, $allow_arr)) {
            return true;
        } else {
            $result = ['ErrCode' => '7057', 'Message' => '没有登录', 'Content' => 'homebridge.admin'];
            die(json_encode($result));
        }
    }
    public function afterAction($action, $result)
    {
        return parent::afterAction($action, $result); // TODO: Change the autogenerated stub
    }
}