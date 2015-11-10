<?php
namespace app\modules\AppBase\base\appbase;
use app\modules\AppBase\base\HintConst;
use Yii;
use yii\db\Exception;
/**
 * User: gjc
 *  2015/7/27 20:08
 */
class TransAct
{
    public function trans()
    {
        $con = Yii::$app->db;
        $t = $con->beginTransaction();
        try {
            $args = func_get_args();
            foreach ($args as $k) {
                $con->createCommand($k)->execute();
            }
            $t->commit();
            return HintConst::$Zero;
        } catch (Exception $e) {
            $t->rollBack();
            $this->execpt_nosuccess($e->getMessage());
        }
    }
    protected function execpt_nosuccess($err = '')
    {
        LogToFile::Log($err);
        die(json_encode(array("ErrCode" => HintConst::$Operate_fail, "Message" => HintConst::$NULL, "Content" => HintConst::$NULLARRAY)));
    }
}