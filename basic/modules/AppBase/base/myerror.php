<?php
/* @var $exception \Exception */
use app\modules\AppBase\base\appbase\LogToFile;
use app\modules\AppBase\base\HintConst;
if ($exception !== null) {
    LogToFile::Log($exception);
}
die(json_encode(array("ErrCode" => HintConst::$SERVER_ERR, "Message" => HintConst::$NULL, "Content" => HintConst::$NULLARRAY)));
?>