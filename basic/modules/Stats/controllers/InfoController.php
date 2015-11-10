<?php
/**
 * User: gjc
 *  2015/6/15 10:39
 */
namespace app\modules\Stats\controllers;
use app\modules\Admin\Custom\models\Customs;
use app\modules\Admin\School\models\Schools;
use app\modules\AppBase\base\appbase\StatsBC;
use app\modules\AppBase\base\CommonFun;
use Yii;
class InfoController extends StatsBC
{
    public function  actionIndex()
    {
        $dt = isset($_REQUEST['s2']) ? $_REQUEST['s2'] : CommonFun::getCurrentDate();
        $enddate = isset($_REQUEST['e2']) ? $_REQUEST['e2'] : CommonFun::getCurrentDate();
        if ($dt >= $enddate) {
            $enddate = CommonFun::getDateRight($dt);
        }
        $school_id = isset($_REQUEST['school_id']) ? $_REQUEST['school_id'] : 0;
        $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
        $sch = new Schools();
        $status = $sch->getStatusBySchid($school_id, $dt, $enddate);
        if (!$school_id) {
            $name = '全部';
        }
        return $this->render('index', ['status' => $status, 's2' => $dt, 'e2' => $enddate, 'school_id' => $school_id, 'name' => $name]);
    }
    public function actionMan()
    {
        $school_id = isset($_REQUEST['school_id']) ? $_REQUEST['school_id'] : 0;
        $custom = new Customs();
        Yii::$app->session['manage_user'] = $custom->getManagerSes($school_id);
        $url = Yii::$app->urlManager->createUrl(['manage/article/index']);
        return Yii::$app->getResponse()->redirect($url);
    }
}