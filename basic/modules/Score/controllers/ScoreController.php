<?php
/**
 * User: gjc
 *  2015/7/15 11:29
 */
namespace app\modules\Score\controllers;
use app\modules\Admin\Custom\models\CustomScore;
use app\modules\AppBase\base\appbase\ScoreBC;
use app\modules\AppBase\base\HintConst;
use app\modules\AppBase\base\score\Score;
use Yii;
class ScoreController extends ScoreBC
{
    public function actionEditscorebyhead()
    {
        $ErrCode = HintConst::$Zero;
        $Message = HintConst::$Success;
        $Content = HintConst::$NULLARRAY;
        $data['pri_type_id'] = isset($_REQUEST['pri_type_id']) ? trim($_REQUEST['pri_type_id']) : 0;
        $data['sub_type_id'] = isset($_REQUEST['sub_type_id']) ? trim($_REQUEST['sub_type_id']) : 0;
        $data['custom_id'] = isset($_REQUEST['custom_id']) ? trim($_REQUEST['custom_id']) : $this->getCustomId();
        $data['related_id'] = isset($_REQUEST['related_id']) ? trim($_REQUEST['related_id']) : 0;
        $data['contents'] = isset($_REQUEST['contents']) ? trim($_REQUEST['contents']) : '';
        $num = isset($_REQUEST['num']) ? trim($_REQUEST['num']) : 0;
        if (empty($data['custom_id']) || !is_numeric($data['custom_id'])) {
            $ErrCode = HintConst::$NoCustomId;
        } elseif (!is_numeric($num) || $num == 0) {
            $ErrCode = HintConst::$No_num;
        } elseif (!is_numeric($data['related_id'])) {
            $ErrCode = HintConst::$No_related_id;
        } elseif (!is_numeric($data['pri_type_id']) || $data['pri_type_id'] == 0) {
            $ErrCode = HintConst::$No_pri_type_id;
        } elseif (!is_numeric($data['sub_type_id']) || $data['sub_type_id'] == 0) {
            $ErrCode = HintConst::$No_sub_type_id;
        } else {
            $score = new Score();
            $ErrCode = $score->EditScoreByHead($data, $num);
        }
        $result = ['ErrCode' => $ErrCode, 'Message' => $Message, 'Content' => $Content];
        return json_encode($result);
    }
    public function actionRankofhead()
    {
        $result = ['ErrCode' => HintConst::$Zero, 'Message' => HintConst::$Success, 'Content' => (new Score())->Rankofhead()];
        return json_encode($result);
    }
    public function actionRankofhl()
    {
        $result = ['ErrCode' => HintConst::$Zero, 'Message' => HintConst::$Success, 'Content' => (new Score())->Rankofhl()];
        return json_encode($result);
    }
    public function actionRankofteacher()
    {
        $result = ['ErrCode' => HintConst::$Zero, 'Message' => HintConst::$Success, 'Content' => (new Score())->Rankofteacher()];
        return json_encode($result);
    }
    public function actionRankoftl()
    {
        $result = ['ErrCode' => HintConst::$Zero, 'Message' => HintConst::$Success, 'Content' => (new Score())->Rankoftl()];
        return json_encode($result);
    }
    public function actionRankofparent()
    {
        $id = isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : Yii::$app->session['custominfo']->custom->id;
        $result = ['ErrCode' => HintConst::$Zero, 'Message' => HintConst::$Success, 'Content' => (new Score())->Rankofparent($id)];
        return json_encode($result);
    }
    public function actionRankofpl()
    {
        $result = ['ErrCode' => HintConst::$Zero, 'Message' => HintConst::$Success, 'Content' => (new Score())->Rankofpl()];
        return json_encode($result);
    }
    public function actionRankoftlforsum()
    {
        $result = ['ErrCode' => HintConst::$Zero, 'Message' => HintConst::$Success, 'Content' => (new Score())->Rankoftlforsum()];
        return json_encode($result);
    }
    public function actionRankofplforsum()
    {
        $result = ['ErrCode' => HintConst::$Zero, 'Message' => HintConst::$Success, 'Content' => (new Score())->Rankofplforsum()];
        return json_encode($result);
    }
    public function actionScoredetail()
    {
        return (new Score())->Scoredetail();
    }
    public function actionGetdd()//方便明细的点击,但没有写入API,没有把所有的情况都罗列
    {
        return (new CustomScore())->getdd();
    }
}