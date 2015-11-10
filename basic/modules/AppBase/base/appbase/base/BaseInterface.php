<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/10/12
 * Time: 10:20
 */

namespace app\modules\AppBase\base\appbase\base;


interface BaseInterface {
public function execpt_nosuccess($err);
public function execpt_noteacherinfo($err);
public function execpt_notimage($err);

}