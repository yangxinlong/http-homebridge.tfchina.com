<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/1/16
 * Time: 8:25
 */
namespace app\modules\AppBase\base\appbase\base;
use app\modules\AppBase\base\appbase\LogToFile;
use app\modules\AppBase\base\HintConst;
use Exception;
class BaseCommonDB
{
    private $db;
    function __construct($_db)
    {
        $this->setDb($_db);
        // TODO: Implement __construct() method.
    }
    public function trans()
    {
        $t = $this->getDb()->beginTransaction();
        try {
            $args = func_get_args();
            foreach ($args as $k) {
                $this->getDb()->createCommand($k)->execute();
            }
            $t->commit();
            return HintConst::$Zero;
        } catch (Exception $e) {
            $t->rollBack();
            $this->execpt_nosuccess($e->getMessage());
            die();
        }
    }
    public function  selcet($sql)
    {
        return $this->excute($sql);
    }
    public function  insert($sql)
    {
        return $this->excute($sql);
    }
    public function  delete($sql)
    {
        return $this->excute($sql);
    }
    public function  update($sql)
    {
        return $this->excute($sql);
    }
    private function  excute($sql)
    {
        try {
            echo "<br>";
//            $r = $this->getDb()->createCommand("select id,name_zh from customs WHERE phone=13837207027")->execute();
            $r = \Yii::$app->db->createCommand("select id,name_zh from customs WHERE id=2418");
            var_dump($r);
            exit;
        } catch (Exception $e) {
            $this->execpt_nosuccess($e->getMessage());
            die();
        }
    }
    protected function execpt_nosuccess($err = '')
    {
        LogToFile::Log($err);
        die(json_encode(array("ErrCode" => HintConst::$Operate_fail, "Message" => HintConst::$NULL, "Content" => HintConst::$NULLARRAY)));
    }
    /**
     * @return mixed
     */
    public function getDb()
    {
        return $this->db;
    }
    /**
     * @param mixed $db
     */
    public function setDb($db)
    {
        $this->db = $db;
    }
}