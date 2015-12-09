<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2014/11/25
 * Time: 10:32
 */
namespace app\modules\AppBase\base\appbase;
use app\modules\Admin\Apkversion\models\Apkversion;
use app\modules\Admin\Catalogue\models\Catalogue;
use app\modules\Admin\Classes\models\Classes;
use app\modules\Admin\Custom\models\Customs;
use app\modules\Admin\Logs\models\LogsClasses;
use app\modules\Admin\Message\models\Messages;
use app\modules\Admin\Message\models\Msgsendrecieve;
use app\modules\AppBase\base\appbase\base\BaseInterface;
use app\modules\AppBase\base\BaseConst;
use app\modules\AppBase\base\CommonFun;
use app\modules\AppBase\base\HintConst;
use Exception;
use Yii;
use yii\web\Controller;
class BaseController extends Controller implements BaseInterface
{
    const LONG = 6;
    private $pagestartime;
    private $pageendtime;
    private $log;
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
    public function checkAdminSession()//检测session
    {
        if (isset(Yii::$app->session['admin_user'])) {
            return true;
        } else {
            echo "Fly Up To Heaven ...";
            return false;
        }
    }
    public function checkUserSession()//检测session
    {
        if (isset(Yii::$app->session['custominfo'])) {
            return true;
        } else {
            return false;
        }
    }
    public function checkUserSession2()//检测session
    {
//        $ana = new BaseAnalyze();
//        $ana->writeToAnal('2session isActive' . Yii::$app->session->isActive);
//        $ana->writeToAnal('2session test' . isset(Yii::$app->session['custominfo']));
//        $ana->writeToAnal('2session has' . Yii::$app->session->has('custominfo'));
//        if (isset($_COOKIE['PHPSESSID'])) {
//            $ana->writeToAnal('2PHPSESSID' . $_COOKIE['PHPSESSID']);
//        }
//        if (isset($_COOKIE['sfkey'])) {
//            $ana->writeToAnal('2sfkey' . $_COOKIE['sfkey']);
//            $ana->writeToAnal('2role' . $_COOKIE['role']);
//        }
        if (isset(Yii::$app->session['custominfo'])) {
            return true;
        } elseif (isset($_COOKIE['sfkey'])) {
            $custom = new Customs();
            $r = $custom->Check_sfkeyCheck($_COOKIE['sfkey'], $_COOKIE['role']);
            if ($r['ErrCode'] == 0) {
//                $ana->writeToAnal("checkUserSession ErrCode=0");
                return true;
            } else {
//                $ana->writeToAnal("checkUserSession ErrCode=1");
                return false;
            }
        } else {
            return false;
        }
    }
    public function beforeAction($action)
    {
//        $this->mc->flush();
        $this->pagestartime = microtime();
        $allow_arr = ['index', 'dl_xyh', 'dl_bbname', 'dl_ma',//phone
            'getconf', 'login', 'apkinfo', 'checkcode-a', 'login-a-h', 'user-share', 'addparent',
            'provinceslist', 'citieslist', 'districtslist', 'apply', 'apply-a', 'audit', 'daily', 'updatelogo-a', 'uploadlog', 'initschool',
            'pushpass', 'pushauditbyarid', 'pushaddnote', 'pushaddahe', 'pushaddclub'
        ];
        $this->mc_name = Yii::$app->request->getUrl();
        $this->mc_name_common = $this->mc_name;
        $this->mc_name_act = $this->mc_name;
        $this->log = '--start' . CommonFun::getCurrentDateTime() . ' :  ' . $this->getCustomId() . ' :  ' . Yii::$app->request->getUrl();
        if (in_array($action->id, $allow_arr) || isset(Yii::$app->session['admin_user']) || isset(Yii::$app->session['manage_user']) || $this->checkUserSession2()) {
            return true;
        } else {
            $result = ['ErrCode' => '7057', 'Message' => '没有登录', 'Content' => 'homebridge'];
            die(json_encode($result));
            return false;
        }
    }
    public function afterAction($action, $result)
    {
        $this->closesys($action);
        try {
            $content = $this->mc_name;
            $this->pageendtime = microtime();
            $starttime = explode(" ", $this->pagestartime);
            $endtime = explode(" ", $this->pageendtime);
            $totaltime = $endtime[0] - $starttime[0] + $endtime[1] - $starttime[1];
            $timecost = sprintf("%s", $totaltime);
            $content .= "\t" . $timecost;
            $content .= " \r\n";
//            $ba->writeToAnal($this->log . "  " . $timecost);
            $content = CommonFun::getCurrentDateTime() . '|' . $this->getCustomSchool_id() . '|' . $this->getCustomId() . '| ' . $content;
            if (count($_POST) > 0) {
                $content .= '|params:' . json_encode($_POST);
            }
            if ($totaltime > 3) {
                $ba = new BaseAnalyze();
                $ba->writeToWasteTime('++waste time: ' . $content);
            } elseif ($totaltime > 10) {
                $mail = Yii::$app->mailer->compose(); //加载模板这样写：$mail= Yii::$app->mailer->compose('moban',['key'=>'value']);
                $mail->setTo('1031534918@qq.com');
                $mail->setSubject("longwastertime");
                $mail->setTextBody($content);
                $mail->setHtmlBody($content);
                $mail->send();
            }
            return parent::afterAction($action, $result); // TODO: Change the autogenerated stub
        } catch (Exception $e) {
            $ba = new BaseAnalyze();
            $ba->writeToAnal('++error: ' . $e->getMessage());
            return parent::afterAction($action, $result); // TODO: Change the autogenerated stub
        }
    }
    public
    function closesys($action)
    {
        if ($this->isFitBatchDel($action->id)) {
            $this->mc->close(1);
        } else {
            $this->mc->close(2);
        }
    }
    public
    function batDelMC()//manual
    {
        $this->mc->close(1);
    }
    public
    function isFitBatchDel($str)
    {
        //todo:在园长web端添加老师,用到index,用$this->batDelMC()来清除缓存
        $relative = ['article-reply',
            'add', 'adopt',
            'catalogue-a-h', 'checkandsetpd-a',
            'create',
            'del',
            'edit',
            'graduate-a',
            'mul_upload',
            'pass',
            'review',
            'reset',
            'send',
            'update', 'upload'];
        $absolte = ['reply', 'article-reply', 'reply-reply'];
        if (in_array($str, $absolte)) {
            return true;
        }
        foreach ($relative as $key) {
            $pos = strpos($str, $key);
            if ($pos !== false) {
                return true;
                break;
            }
        }
        return false;
    }
    public
    function myjsonencode($result)
    {
        echo json_encode($result);
    }
    public
    function  CheckUpdateParamaA($id, $flag, $v)
    {
        $ErrCode = HintConst::$Zero;
        if ($id == '' || !is_numeric($id)) {
            $ErrCode = HintConst::$NoId;
        } elseif ($v != HintConst::$DONOTCHECK) {
            if ($v == '') {
                $ErrCode = HintConst::$NoVlaue;
            } else {
                if (CommonFun::must_int($flag)) {
                    if (!is_numeric($v)) {
                        $ErrCode = HintConst::$NotInteger;
                    } else {
                        $this->callBaseARUpdateFieldA($id, $flag, $v);
                    }
                } else {
                    $this->callBaseARUpdateFieldA($id, $flag, $v);
                }
            }
        } else {//不需要检测$v
            $this->callBaseARUpdateFieldA($id, $flag, $v);
        }
        if ($ErrCode != HintConst::$Zero) {
            $Message = HintConst::$NULL;
            $Content = HintConst::$NULLARRAY;
            echo(CommonFun::json($ErrCode, $Message, $Content));
        }
    }
    private
    function callBaseARUpdateFieldA($id, $flag, $v)
    {
        $mo = $this->returnModel();
        echo $mo->UpdateF($id, $flag, $v);
    }
//根据route返回model
    public
    function returnModel()
    {
        $r = CommonFun::explodeString('/', $this->getRoute());
        if ($r[0] == HintConst::$Module_Customs) {
            Yii::$app->session[BaseConst::$Table_Name] = BaseConst::$customs_T;
            return new Customs();
        } elseif ($r[0] == HintConst::$Module_Apkversion) {
            Yii::$app->session[BaseConst::$Table_Name] = BaseConst::$apkversion_T;
            return new Apkversion();
        } elseif ($r[0] == HintConst::$Module_Classes) {
            Yii::$app->session[BaseConst::$Table_Name] = BaseConst::$classes_T;
            return new Classes();
        } elseif ($r[0] == HintConst::$Module_Logs) {
            if ($r[1] == HintConst::$C_logs_classes) {
                Yii::$app->session[BaseConst::$Table_Name] = BaseConst::$logs_classes_T;
                return new LogsClasses();
            }
        } elseif ($r[0] == HintConst::$Module_manage) {
            if ($r[1] == HintConst::$C_customs) {
                Yii::$app->session[BaseConst::$Table_Name] = BaseConst::$customs_T;
                return new Customs();
            }
        } elseif ($r[0] == HintConst::$Module_Catalogue) {
            if ($r[1] == HintConst::$C_catalogue) {
                Yii::$app->session[BaseConst::$Table_Name] = BaseConst::$catalogue_T;
                return new Catalogue();
            }
        } elseif ($r[0] == HintConst::$Module_Message) {
            if ($r[1] == HintConst::$C_messages) {
                Yii::$app->session[BaseConst::$Table_Name] = BaseConst::$messages_T;
                return new Messages();
            } elseif ($r[1] == HintConst::$C_msgsendrecieve) {
                if ($r[2] == HintConst::$F_getmsgsrlist) {
                    Yii::$app->session[BaseConst::$Table_Name] = BaseConst::$msg_send_recieve_T;
                    return new Msgsendrecieve();
                }
            }
        }
    }
    /*
    * 修改是否具有发送权限
    */
    public
    function  actionUpdateiscansendA()
    {
        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $v = !empty($_REQUEST['iscansend']) ? $_REQUEST['iscansend'] : '';
        $this->CheckUpdateParamaA($id, HintConst::$Field_iscansend, $v);
    }
//手机端重置custom密码,园长和老师都可以重置,给定custom_id就可以了
    public
    function  actionResetpdA()
    {
        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $v = !empty($_REQUEST[HintConst::$Field_password]) ? $_REQUEST[HintConst::$Field_password] : HintConst::$DefPD;
        $this->CheckUpdateParamaA($id, HintConst::$Field_password, CommonFun::encrypt($v));
    }
    /*
    * 修改password
    */
    public
    function  actionUpdatepasswordA()
    {
        $id = !empty($_REQUEST[HintConst::$Field_id]) ? $_REQUEST[HintConst::$Field_id] : '';
        $v = !empty($_REQUEST[HintConst::$Field_password]) ? $_REQUEST[HintConst::$Field_password] : '';
        $this->CheckUpdateParamaA($id, HintConst::$Field_password, $v);
    }
    /*
     * 通过
     */
    public
    function  actionPassA()
    {
        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $v = !empty($_REQUEST['ispassed']) ? $_REQUEST['ispassed'] : '';
        $this->CheckUpdateParamaA($id, HintConst::$Field_ispassed, $v);
    }
    /*
     * 删除
     */
    public function  actionDeleteA()
    {
        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $v = !empty($_REQUEST['isdeleted']) ? $_REQUEST['isdeleted'] : '';
        $this->CheckUpdateParamaA($id, HintConst::$Field_isdeleted, $v);
    }
    /*
     * 修改name
     */
    public function  actionUpdatenameA()
    {
        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $v = !empty($_REQUEST['name']) ? $_REQUEST['name'] : '';
        $this->CheckUpdateParamaA($id, HintConst::$Field_name, $v);
    }
    /*
    * 修改name_zh
    */
    public
    function  actionUpdatenamezhA()
    {
        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $v = !empty($_REQUEST['name_zh']) ? $_REQUEST['name_zh'] : '';
        $this->CheckUpdateParamaA($id, HintConst::$Field_name_zh, $v);
    }
    /*
    * 修改path
    */
    public
    function  actionUpdatepathA()
    {
        $id = !empty($_REQUEST[HintConst::$Field_id]) ? $_REQUEST[HintConst::$Field_id] : '';
        $v = !empty($_REQUEST[HintConst::$Field_path]) ? $_REQUEST[HintConst::$Field_path] : '';
        $this->CheckUpdateParamaA($id, HintConst::$Field_path, $v);
    }
    /*
     * 修改描述
     */
    public
    function  actionUpdatedescriptionA()
    {
        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $v = !empty($_REQUEST['description']) ? $_REQUEST['description'] : '';
        $this->CheckUpdateParamaA($id, HintConst::$Field_description, $v);
    }

//    /*
//    * 修改手机信息
//    */
//    public function  actionUpdatephoneA()
//    {
//        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';
//        $v = !empty($_REQUEST['phone']) ? $_REQUEST['phone'] : '';
//        $this->CheckUpdateParamaA($id, HintConst::$Field_phone, trim($v));
//    }
    /*
    * 修改是否具有发送权限
    */
    public
    function  actionUpdateisstarA()
    {
        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $v = !empty($_REQUEST['isstar']) ? $_REQUEST['isstar'] : '';
        $this->CheckUpdateParamaA($id, HintConst::$Field_isstar, $v);
    }
    /*
    * 修改是否具有使用权限
    */
    public
    function  actionUpdateisoutA()
    {
        $id = !empty($_REQUEST[HintConst::$Field_id]) ? $_REQUEST[HintConst::$Field_id] : '';
        $v = !empty($_REQUEST[HintConst::$Field_isout]) ? $_REQUEST[HintConst::$Field_isout] : '';
        $this->CheckUpdateParamaA($id, HintConst::$Field_isout, $v);
    }
    /*
    * 修改isgraduated信息
    */
    public
    function  actionGraduateA()
    {
        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $v = !empty($_REQUEST['isgraduated']) ? $_REQUEST['isgraduated'] : '';
        $this->CheckUpdateParamaA($id, HintConst::$Field_isgraduated, $v);
        if ($v == HintConst::$YesOrNo_YES) {
            $custom = new Customs();
            $custom->freeTeacher($id);
        }
    }
    /*
    * 修改Catalogue表中的标签
    */
    public
    function  actionCatalogueAH()
    {
        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';
        $v = !empty($_REQUEST['name_zh']) ? $_REQUEST['name_zh'] : '';
        $this->CheckUpdateParamaA($id, HintConst::$Field_name_zh, $v);
    }
    public
    function  actionGetrecordbyid()
    {
        $ErrCode = HintConst::$Zero;
        $id = !empty($_REQUEST['id']) ? $_REQUEST['id'] : '';
        if ($id == '' || !is_numeric($id)) {
            $ErrCode = HintConst::$NoId;
        } else {
            $this->getRecordOne("id=$id");
        }
        if ($ErrCode != HintConst::$Zero) {
            $Message = HintConst::$NULL;
            $Content = HintConst::$NULLARRAY;
            echo(CommonFun::json($ErrCode, $Message, $Content));
        }
    }
    public
    function  actionGetrecordbycode()
    {
        $ErrCode = HintConst::$Zero;
        $code = !empty($_REQUEST['code']) ? $_REQUEST['code'] : '';
        if ($code == '' || !is_numeric($code)) {
            $ErrCode = HintConst::$NoCode;
        } else {
            $this->getRecordOne("code=$code");
        }
        if ($ErrCode != HintConst::$Zero) {
            $Message = HintConst::$NULL;
            $Content = HintConst::$NULLARRAY;
            echo(CommonFun::json($ErrCode, $Message, $Content));
        }
    }
    public
    function  actionGetrecordbyfield()
    {
        $ErrCode = HintConst::$Zero;
        $field = !empty($_REQUEST['field']) ? $_REQUEST['field'] : '';
        $value = !empty($_REQUEST['value']) ? $_REQUEST['value'] : '';
        try {
            $this->getRecordOne("$field=$value");
            if ($ErrCode != HintConst::$Zero) {
                $Message = HintConst::$NULL;
                $Content = HintConst::$NULLARRAY;
                echo(CommonFun::json($ErrCode, $Message, $Content));
            }
        } catch (Exception $e) {
            $this->execpt_nosuccess();
        }
    }
//根据id或code等field返回json:content中是一维数组,对应于actionGetrecordbyid,只有一个,因为条件只有一个
    public
    function getRecordOne($where)
    {
        $mo = $this->returnModel();
        return $mo->getRecordOne($where);
    }
//根据$where返回json:content中是二维数组,对应于多个action,因为条件有多个
    public
    function  getRecordList($where)
    {
        $mo = $this->returnModel();
        $result = $mo->getRecordList($where);
        $this->myjsonencode($result);
    }
    public
    function execpt_nosuccess($err = '')
    {
        LogToFile::Log($err);
        die(json_encode(array("ErrCode" => HintConst::$No_success, "Message" => HintConst::$NULL, "Content" => HintConst::$NULLARRAY)));
    }
    public
    function execpt_noteacherinfo($err = '')
    {
        LogToFile::Log($err);
        die(json_encode(array("ErrCode" => HintConst::$No_teacherinfo, "Message" => HintConst::$NULL, "Content" => HintConst::$NULLARRAY)));
    }
    public
    function execpt_notimage($err = '')
    {
        LogToFile::Log($err);
        die(json_encode(array("ErrCode" => HintConst::$No_notimage, "Message" => HintConst::$NULL, "Content" => HintConst::$NULLARRAY)));
    }
    public
    function getCustomId()
    {
        if (isset(Yii::$app->session['custominfo'])) {
            return Yii::$app->session['custominfo']->custom->id;
        }
        return 0;
    }
    public
    function getCustomSchool_id()
    {
        if (isset(Yii::$app->session['custominfo'])) {
            return Yii::$app->session['custominfo']->custom->school_id;
        }
        return 0;
    }
}