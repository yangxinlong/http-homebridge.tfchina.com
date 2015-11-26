<?php

namespace app\modules\Admin\Articles\models;
use app\modules\Admin\Custom\models\Customs;
use app\modules\Admin\Notes\models\NotesReplies;
use app\modules\Admin\Vote\models\Vote;
use app\modules\Admin\Vote\models\VoteAtt;
use app\modules\Admin\Vote\models\VoteReplies;
use app\modules\AppBase\base\appbase\base\BaseMain;
use app\modules\AppBase\base\appbase\BaseAnalyze;
use app\modules\AppBase\base\appbase\TransAct;
use app\modules\AppBase\base\BaseConst;
use app\modules\AppBase\base\cat_def\CatDef;
use app\modules\AppBase\base\CommonFun;
use app\modules\AppBase\base\HintConst;
use app\modules\AppBase\base\score\Score;
use app\modules\AppBase\base\xgpush\XgEvent;
use Exception;
use Yii;
use yii\db\Query;
/**
 * This is the model class for table "articles".
 * @property integer $id
 * @property integer $school_id
 * @property integer $class_id
 * @property integer $author_id
 * @property integer $for_someone_id
 * @property integer $article_type_id
 * @property integer $sub_type_id
 * @property string $title
 * @property string $subtitle
 * @property string $contents
 * @property string $thumb
 * @property string $date
 * @property string $createtime
 * @property string $updatetime
 * @property integer $praise_times
 * @property integer $share_times
 * @property integer $view_times
 * @property integer $ispassed
 * @property integer $isdelete
 * @property integer $isview
 * @property integer $month
 * @property integer $term
 */
class Articles extends BaseMain
{
    private $arlist_se = 'a.id,a.o_link_id,a.title,a.contents,a.thumb,a.createtime,a.author_id,c.name_zh as stu_name,a.view_times,a.praise_times,a.share_times,a.cus_p,a.sys_p';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'articles';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_id', 'class_id', 'author_id', 'for_someone_id', 'article_type_id', 'sub_type_id', 'praise_times', 'share_times', 'view_times', 'ispassed', 'isdelete', 'isview', 'month', 'term', 'cus_p', 'sys_p'], 'integer'],
            [['contents'], 'string'],
            [['date', 'createtime', 'updatetime'], 'safe'],
            [['title', 'subtitle'], 'string', 'max' => 100],
            [['thumb'], 'string', 'max' => 500]
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'school_id' => 'School ID',
            'class_id' => 'Class ID',
            'author_id' => 'Author ID',
            'for_someone_id' => 'For Someone ID',
            'article_type_id' => 'Article Type ID',
            'sub_type_id' => 'Sub Type ID',
            'title' => 'Title',
            'subtitle' => 'Subtitle',
            'contents' => 'Contents',
            'thumb' => 'Thumb',
            'date' => 'Date',
            'createtime' => 'Createtime',
            'updatetime' => 'Updatetime',
            'praise_times' => 'Praise Times',
            'share_times' => 'Share Times',
            'view_times' => 'View Times',
            'ispassed' => 'Ispassed',
            'isdelete' => 'Isdelete',
            'isview' => 'Isview',
            'month' => 'Month',
            'term' => 'Term',
        ];
    }
    public function mydel()
    {
        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        if (empty($id) || !is_numeric($id)) {
            $ErrCode = HintConst::$NoId;
        } else {
            $sql = "DELETE FROM articles WHERE id=$id";
            $sql2 = "DELETE FROM article_attachment WHERE article_id=$id";
            $sql3 = "DELETE FROM article_replies WHERE article_id=$id";
            $sql4 = "DELETE FROM article_send_revieve WHERE article_id=$id";
            $ErrCode = (new TransAct())->trans($sql, $sql2, $sql3, $sql4);
        }
        $result = ['ErrCode' => $ErrCode, 'Message' => HintConst::$Success, 'Content' => HintConst::$NULLARRAY];
        return json_encode($result);
    }
    public function updateArticleIsView()//update all isview=no to yes ,on get 院所圈 articles list
    {
        $mode = Articles::find()
            ->where(['school_id' => Yii::$app->session['custominfo']->custom->school_id, 'isview' => HintConst::$YesOrNo_NO])
            ->one();
        if ($mode) {
            Articles::updateAll(['isview' => HintConst::$YesOrNo_YES], 'school_id = ' . Yii::$app->session['custominfo']->custom->school_id . ' and article_type_id = ' . HintConst::$ARTICLE_PATH . ' and articles.ispassed = ' . HintConst::$YesOrNo_YES . ' and isview=' . HintConst::$YesOrNo_NO);
        }
    }
    public function PendingEva()
    {
        $school_id = $school_id = Yii::$app->session['custominfo']->custom->school_id;
        $query = new Query();
        $pending_eva_num = $query->select('count(*) as number')
            ->from(BaseConst::$articles_T)
            ->where('(article_type_id=' . HintConst::$NIANPINGJIA_PATH . ' or ' . 'article_type_id=' . HintConst::$YUEPINGJIA_PATH . ') and school_id=' . $school_id . ' and ispassed=' . HintConst::$YesOrNo_NO . ' and isdelete=' . HintConst::$YesOrNo_NO)
            ->one();
        return $pending_eva_num['number'];
    }
    public function DaySummary()
    {
        $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $page_size = isset($_REQUEST['size']) && is_numeric($_REQUEST['size']) ? $_REQUEST['size'] : 10;
        $custom_id = Yii::$app->session['custominfo']->custom->id; //得到session登录用户的session信息的id
        $start_time = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
        $type_id = HintConst::$HIGHLIGHT_PATH_NEW;
        $custom_id = isset($_REQUEST['custom_id']) && is_numeric($_REQUEST['custom_id']) ? $_REQUEST['custom_id'] : $custom_id;
        $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : date('Y-m-d');
        $date = CommonFun::getDateFitFormat($date);
        $mc_name = $this->getMcName() . 'DaySummary' . $custom_id . $date . $page . $page_size;
        if ($val = $this->mc->get($mc_name)) {
            $result = $val;
        } else {
            //学生 daylable
            $customsDaily = new CustomsDaily();
            $summary = $customsDaily->getDayLable($custom_id, $date);
            //检查 $summary是否有空的选项 试图到班级daily中寻找
            //通过custom_id得到school_id  继而得到当前日的食谱。
            $classesDaily = new ClassesDaily();
            $class_summary = $classesDaily->getDayLable($custom_id, $date);
            //如果得到的用户日常值为空  则用班级日常代替  班级日常也是空  就没哟办法了
            foreach ($summary as $key => $value) {
                if ($value == '') {
                    $summary[$key] = $class_summary[$key];
                }
            }
            //cook_book
            $cook_book = new CookbookInfo();
            $summary['cook_book'] = $cook_book->getCookbook($date);
            //hightlight
            $summary['att_list'] = array();
            $query = new \yii\db\Query();
            $start_line = $page_size * ($page - 1);
            $att_list = $query->select('aa.*,articles.contents as img_des,cat_default.name_zh as cat_default_name')
                ->from('article_attachment as aa')
                ->leftjoin('articles', 'articles.id = aa.article_id')
                ->leftjoin('cat_default', 'cat_default.id = aa.sub_type_id')
                ->leftjoin('article_send_revieve as asr', 'asr.article_id = aa.article_id')
                ->where(['aa.isdelete' => HintConst::$YesOrNo_NO, 'aa.ispassed' => HintConst::$YesOrNo_YES, 'asr.reciever_id' => $custom_id, 'articles.article_type_id' => HintConst::$HIGHLIGHT_PATH_NEW, 'articles.date' => $date])
                ->offset($start_line)
                ->limit($page_size)
                ->orderby(['aa.id' => SORT_DESC])
                ->all();
            if (is_array($att_list)) {
                $server_host = $_SERVER['HTTP_HOST'];
                foreach ($att_list as $key => $value) {
                    $summary['att_list'][$key] = $value;
                    $summary['att_list'][$key]['article_att_id'] = $att_list[$key]['id'];
                    $summary['att_list'][$key]['url'] = $server_host . '/' . $att_list[$key]['url'];
                    $summary['att_list'][$key]['url_thumb'] = $server_host . '/' . $att_list[$key]['url_thumb'];
                }
            }
            $result = ['ErrCode' => '0', 'Message' => HintConst::$WEB_JYQ, 'Content' => array($summary)];
            $this->mc->add($mc_name, $result);
        }
        return json_encode($result);
    }
    public function Newdaysummary()
    {
        $custom_id = isset($_REQUEST['custom_id']) && is_numeric($_REQUEST['custom_id']) ? $_REQUEST['custom_id'] : $this->getCustomId();
        $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : date('Y-m-d');
        $mc_name = $this->getMcName() . 'Newdaysummary' . $custom_id . $date;
        if ($val = $this->mc->get($mc_name)) {
            $result = $val;
        } else {
            //学生 daylable
            $customsDaily = new CustomsDaily();
            $summary = $customsDaily->getDayLable($custom_id, $date);
            //检查 $summary是否有空的选项 试图到班级daily中寻找
            //通过custom_id得到school_id  继而得到当前日的食谱。
            $classesDaily = new ClassesDaily();
            $class_summary = $classesDaily->getDayLable($custom_id, $date);
            //如果得到的用户日常值为空  则用班级日常代替  班级日常也是空  就没哟办法了
            foreach ($summary as $key => $value) {
                if ($value == '') {
                    $summary[$key] = $class_summary[$key];
                }
            }
            //通过custom_id得到school_id  继而得到当前日的食谱。
            $cook_book = new CookbookInfo();
            $summary['cook_book'] = $cook_book->getCookbook($date);
            $result = ['ErrCode' => '0', 'Message' => HintConst::$WEB_JYQ, 'Content' => array($summary)];
            $this->mc->add($mc_name, $result);
        }
        return json_encode($result);
    }
    public function DaySummary2()//班级动态
    {
        $class_id = isset($_REQUEST['class_id']) && is_numeric($_REQUEST['class_id']) ? $_REQUEST['class_id'] : 0;
        if (!$class_id) {
            $result = ['ErrCode' => '1', 'Message' => '缺少class_id', 'Content' => []];
            return (json_encode($result));
        }
        $school_id = Yii::$app->session['custominfo']->custom->school_id;
        $date = isset($_REQUEST['date']) && strlen($_REQUEST['date']) > 9 ? $_REQUEST['date'] : date('Y-m-d');
        $mc_name = $this->getMcName() . 'DaySummary2' . $school_id . $class_id . $date;
        if ($val = $this->mc->get($mc_name)) {
            $result = $val;
        } else {
            $query = new Query();
            $p_list = $query->select('*')
                ->from('classes_daily')
                ->where(['class_id' => $class_id, 'classes_daily.date' => $date])
                ->orderby(['classes_daily.daily_type_id' => SORT_ASC])
                ->all();
            $summary = ['eat' => '', 'sleep' => '', 'course' => '', 'outdoor' => '', 'lessons' => '', 'homework' => ''];
            if (count($p_list) > 0 && is_array($p_list)) {
                foreach ($p_list as $key => $value) {
                    switch ($value['daily_type_id']) {
                        case HintConst::$LABLE_LIFE_EAT_PATH:
                            $summary['eat'] = $value['daily_contents'];
                            break;
                        case HintConst::$LABLE_LIFE_SLEEP_PATH:
                            $summary['sleep'] = $value['daily_contents'];
                            break;
                        case HintConst::$LABLE_LIFE_COURSE_PATH:
                            $summary['course'] = $value['daily_contents'];
                            break;
                        case HintConst::$LABLE_LIFE_OUTDOOR_PATH:
                            $summary['outdoor'] = $value['daily_contents'];
                            break;
                        case HintConst::$LABLE_LESSONS_PATH:
                            $summary['lessons'] = $value['daily_contents'];
                            break;
                        case HintConst::$DAILY_HOMEWORK_PATH:
                            $summary['homework'] = $value['daily_contents'];
                            break;
                        default:
                            break;
                    }
                }
            }
            //cook_book
            $cook_book = new CookbookInfo();
            $summary['cook_book'] = $cook_book->getCookbook($date);
            $summary['att_list'] = array();
            $query = new Query();
            $att_list = $query->select('aa.id,aa.article_id,aa.cat_default_id,aa.sub_type_id,aa.url,aa.url_thumb,aa.createtime,aa.sys_p,aa.cus_p,articles.author_id,articles.contents as img_des,cat_default.name_zh as cat_default_name')
                ->from('article_attachment as aa')
                ->distinct()
                ->leftjoin('articles', 'articles.id = aa.article_id')
                ->leftjoin('article_send_revieve as asr', 'asr.article_id = aa.article_id')
                ->leftjoin('cat_default', 'cat_default.id = aa.sub_type_id')
                ->where(['aa.ispassed' => HintConst::$YesOrNo_YES, 'asr.class_id' => $class_id, 'articles.article_type_id' => HintConst::$HIGHLIGHT_PATH_NEW, 'articles.date' => $date])
                ->orderby(['aa.id' => SORT_DESC])
                ->all();
            if (is_array($att_list)) {
                $server_host = $_SERVER['HTTP_HOST'];
                foreach ($att_list as $key => $value) {
                    $summary['att_list'][$key] = $value;
                    $summary['att_list'][$key]['article_att_id'] = $att_list[$key]['id'];
                    $summary['att_list'][$key]['url'] = $server_host . '/' . $att_list[$key]['url'];
                    $summary['att_list'][$key]['url_thumb'] = $server_host . '/' . $att_list[$key]['url_thumb'];
                }
            }
            $result = ['ErrCode' => '0', 'Message' => HintConst::$WEB_JYQ, 'Content' => $summary];
            $this->mc->add($mc_name, $result);
        }
        return (json_encode($result));
    }
    public function JarticleList()
    {
        $user_id = $this->getCustomId(); //得到session登录用户的session信息的id
        $school_id = $this->getCustomSchool_id(); //得到session登录用户的session信息的id
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $page_size = isset($_REQUEST['size']) ? $_REQUEST['size'] : 10;
        $mc_name = $this->getMcName() . 'JarticleList' . $school_id . $user_id . $page . $page_size;
        if ($val = $this->mc->get($mc_name)) {
            $result = $val;
        } else {
            $start_line = $page_size * ($page - 1);
            $query = new Query();
            $p_list = $query->select('articles.*,customs.name_zh as author_name,customs.cat_default_id as author_role_id,c.name_zh as author_role_name')
                ->from('articles')
                ->leftjoin('article_send_revieve', 'article_send_revieve.article_id = articles.id')
                ->leftjoin('customs', 'article_send_revieve.sender_id = customs.id')
                ->leftjoin('cat_default as c', 'c.id = customs.cat_default_id')
                ->where(['article_send_revieve.reciever_id' => $user_id, 'articles.article_type_id' => HintConst::$ARTICLE_PATH, 'articles.isdelete' => HintConst::$YesOrNo_NO, 'articles.ispassed' => HintConst::$YesOrNo_YES])
                ->orderby(['articles.id' => SORT_DESC])
                ->offset($start_line)
                ->limit($page_size)
                ->all();
            if ($p_list) {
                $server_host = $_SERVER['HTTP_HOST'];
                foreach ($p_list as $key => $value) {
                    $p_list[$key]['thumb'] = !empty($p_list[$key]['thumb']) ? $server_host . '/' . $p_list[$key]['thumb'] : '';
                    $p_list[$key]['att_list'] = $this->get_att_list($value['id']);
                    $p_list[$key]['reply_list'] = $this->get_reply_list($value['id']);
                }
            }
            $result = ['ErrCode' => 0, 'Message' => HintConst::$WEB_JYQ, 'Content' => $p_list];
            $this->mc->add($mc_name, $result);
        }
        return json_encode($result);
    }
    public function RelateMe()
    {
        $user_id = $this->getCustomId(); //得到session登录用户的session信息的id
        $school_id = $this->getCustomSchool_id(); //得到session登录用户的session信息的id
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $page_size = isset($_REQUEST['size']) ? $_REQUEST['size'] : 10;
        $start_line = $page_size * ($page - 1);
        $mc_name = $this->getMcName() . 'RelateMe' . $school_id . $user_id . $page . $page_size;
        if ($val = $this->mc->get($mc_name)) {
            $result = $val;
        } else {
            $query = new Query();
            //$list = $query->select('ar.contents as reply_content,ar.createtime as reply_time,ar.repliers_id,articles.*')
            $list = $query->select('ar.*,c.name_zh as replier_name,c.cat_default_id as replier_role_id,ar.reply_id,c2.name_zh as reply_name,c2.cat_default_id as reply_role_id,ar.createtime as reply_time,articles.id as article_id,articles.contents as article_contents,articles.title,customs.name_zh as author_name,customs.cat_default_id as author_role_id')
                //->distinct()
                ->from('article_replies as ar')
                ->leftjoin('articles', 'articles.id = ar.article_id')
                ->leftjoin('customs as c', 'c.id = ar.repliers_id')
                ->leftjoin('customs as c2', 'c2.id = ar.reply_id')
                ->leftjoin('customs', 'customs.id = articles.author_id')
                ->where(['ar.reply_id' => $user_id])
                ->orderby(['ar.id' => SORT_DESC])
                ->offset($start_line)
                ->limit($page_size)
                ->all();
            //设置已读
            foreach ($list as $key => $value) {
                ArticleReplies::updateAll(['isview' => HintConst::$YesOrNo_YES], 'id = ' . $value['id']);
            }
            $result = ['ErrCode' => 0, 'Message' => HintConst::$WEB_JYQ, 'Content' => $list];
            $this->mc->add($mc_name, $result);
        }
        return json_encode($result);
    }
    //得到单条文章ID的att_list
    protected function get_att_list($article_id, $type = 1)
    {
        $att_list = array();
        if ($article_id > 0) {
            $mc_name = $this->getMcName() . 'get_att_list' . $article_id . $type;
            if ($val = $this->mc->get($mc_name)) {
                $att_list = $val;
            } else {
                $query = new Query();
                if ($type == 1) {
                    $att_list = $query->select('article_attachment.*,cat_default.name as cat_default_name')
                        ->from('article_attachment')
                        ->leftjoin('cat_default', 'article_attachment.cat_default_id = cat_default.id')
                        ->where(['article_attachment.article_id' => $article_id])
                        ->orderby(['article_attachment.id' => SORT_DESC])
                        ->all();
                } else {
                    $arat = new ArticleAttachment();
                    $att_list = $arat->ArAt($article_id);
                }
                if (is_array($att_list) && count($att_list) > 0) {
                    $server_host = $_SERVER['HTTP_HOST'];
                    foreach ($att_list as $key => $value) {
                        $att_list[$key]['url'] = $server_host . '/' . $att_list[$key]['url'];
                        $att_list[$key]['url_thumb'] = $server_host . '/' . $att_list[$key]['url_thumb'];
                    }
                }
                $this->mc->add($mc_name, $att_list);
            }
            return $att_list;
        } else {
            return $att_list;
        }
    }
    //得到单条文章记录的reply_list
    //1.家长和老师  只获取自己发表和别人回复自己的 回复
    //2.园长        获取所有的回复
    protected function get_reply_list($article_id)
    {
        $type = $this->getCustomRole();
        $reply_list = array();
        $user_id = $this->getCustomId();
        if ($article_id > 0) {
            $mc_name = $this->getMcName() . 'get_reply_list' . $user_id . $article_id . $type;
            if ($val = $this->mc->get($mc_name)) {
                $reply_list = $val;
            } else {
                $query = new Query();
                if ($type == HintConst::$ROLE_HEADMASTER) {
                    $reply_list = $query->select('article_replies.*,customs.name_zh as repliers_name,customs.cat_default_id as repliers_role_id,c.name_zh as reply_name,c.cat_default_id as reply_role_id')
                        ->from('article_replies')
                        ->leftjoin('customs', 'customs.id = article_replies.repliers_id')
                        ->leftjoin('customs as c', 'c.id = article_replies.reply_id')
                        ->where(['article_replies.article_id' => $article_id])
                        ->orderby(['article_replies.id' => SORT_ASC])
                        ->all();
                } else {
                    $reply_list = $query->select('article_replies.*,customs.name_zh as repliers_name,customs.cat_default_id as repliers_role_id,c.name_zh as reply_name,c.cat_default_id as reply_role_id')
                        ->from('article_replies')
                        ->leftjoin('customs', 'customs.id = article_replies.repliers_id')
                        ->leftjoin('customs as c', 'c.id = article_replies.reply_id')
                        ->where(['article_replies.article_id' => $article_id, 'article_replies.repliers_id' => $user_id])
                        ->orwhere(['article_replies.article_id' => $article_id, 'article_replies.reply_id' => $user_id])
                        ->orderby(['article_replies.id' => SORT_ASC])
                        ->all();
                }
                $this->mc->add($mc_name, $reply_list);
            }
            return $reply_list;
        } else {
            return $reply_list;
        }
    }
    public function ArticleDetail()
    {
        return $this->Artidetail();
    }
    public function Artidetail($id = 0)
    {
        $ErrCode = HintConst::$Zero;
        $Message = HintConst::$WEB_JYQ;
        $Content = HintConst::$NULLARRAY;
        $id = isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : $id;
        if ($id > 0) {
            $role = $this->getCustomRole();
            $mc_name = $this->getMcName() . 'Artidetail' . $id . $role;
            if ($val = $this->mc->get($mc_name)) {
                $Content = $val;
            } else {
                $query = new Query();
                $model = $query->select('a.id,a.article_type_id,a.o_link_id,a.title,a.contents,a.createtime,a.author_id,c.name_zh as author_name,c.cat_default_id as author_role_id,a.cus_p,a.sys_p')
                    ->from('articles as a')
                    ->leftjoin('customs as c', 'c.id = a.author_id');
                if ($role == 207) {
                    $model = $query->where(['a.id' => $id, 'a.isdelete' => HintConst::$YesOrNo_NO]);
                } else {
                    $model = $query->where(['a.id' => $id, 'a.ispassed' => HintConst::$YesOrNo_YES, 'a.isdelete' => HintConst::$YesOrNo_NO]);
                }
                $model = $query->one();
                $model['att_list'] = array();
                if (isset($model['id'])) {
                    if ($model['o_link_id'] > 0) {
                        (new Vote())->getClubBySharedId($model);
                        $model['att_list'] = (new VoteAtt())->getAtt($model['o_link_id']);
                    } else {
                        $model['att_list'] = $this->get_att_list($model['id']);
                    }
                    //add stats
                    $ar_stats = new ArStats();
                    $ar_stats->addStats($id);
                    self::increaseF($id, 'view_times');
                    $Content = $model;
                } else {
                    $ErrCode = HintConst::$NoRecord;
                    $Message = '没有这篇文章';
                }
                $this->mc->add($mc_name, $Content);
            }
        } else {
            $ErrCode = HintConst::$NoId;
            $Message = '参数错误';
        }
        return CommonFun::json($ErrCode, $Message, $Content);
    }
    public function PingjiaList()//成长历程 grow
    {
        //测试可用  过后再说
        $user_id = !empty($_REQUEST['user_id']) && is_numeric($_REQUEST['user_id']) ? $_REQUEST['user_id'] : $this->getCustomId();
        //$user_id = Yii::$app->session['custominfo']->custom->id; //得到session登录用户的session信息的id
        $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $page_size = isset($_REQUEST['size']) && is_numeric($_REQUEST['size']) ? $_REQUEST['size'] : 10;
        $start_line = $page_size * ($page - 1);
        $mc_name = $this->getMcName() . 'PingjiaList' . $user_id . $page . $page_size;
        if ($val = $this->mc->get($mc_name)) {
            $result = $val;
        } else {
            $query = new Query();
            $p_list = $query->select('articles.*,customs.name_zh as author_name,customs.cat_default_id as author_role_id,cat_default.name_zh as article_type_name,ad.name_zh as sub_type_name')
                ->from('articles')
                ->leftjoin('article_send_revieve', 'article_send_revieve.article_id = articles.id')
                ->leftjoin('customs', 'article_send_revieve.sender_id = customs.id')
                ->leftjoin('cat_default', 'articles.article_type_id = cat_default.id')
                ->leftjoin('cat_default as ad', 'articles.sub_type_id = ad.id')
                ->where(['article_send_revieve.reciever_id' => $user_id, 'articles.article_type_id' => HintConst::$HIGHLIGHT_PATH_NEW, 'articles.isdelete' => HintConst::$YesOrNo_NO, 'articles.ispassed' => HintConst::$YesOrNo_YES])//精彩瞬间
                ->orwhere(['article_send_revieve.reciever_id' => $user_id, 'articles.article_type_id' => HintConst::$NIANPINGJIA_PATH, 'articles.isdelete' => HintConst::$YesOrNo_NO, 'articles.ispassed' => HintConst::$YesOrNo_YES])//学期总结
                ->orwhere(['article_send_revieve.reciever_id' => $user_id, 'articles.article_type_id' => HintConst::$YUEPINGJIA_PATH, 'articles.isdelete' => HintConst::$YesOrNo_NO, 'articles.ispassed' => HintConst::$YesOrNo_YES])//月评价/月总结
                ->orderby(['articles.id' => SORT_DESC])
                ->offset($start_line)
                ->limit($page_size)
                ->all();
            $server_host = $_SERVER['HTTP_HOST'];
            if (is_array($p_list) && count($p_list) > 0) {
                foreach ($p_list as $key => $value) {
                    $p_list[$key]['id'] = (int)$p_list[$key]['id'];
                    $p_list[$key]['author_role_name'] = CatDef::$role[$value['author_role_id']];
                    //todo:在精彩瞬间的分类里不会有181,181是属于生活标签的
                    $p_list[$key]['sub_type_name'] = isset($value['sub_type_id']) ? CatDef::$highlight[$value['sub_type_id']] : '';
                    //转化图片的url
                    $p_list[$key]['thumb'] = $server_host . '/' . $p_list[$key]['thumb'];
                    $query = new Query();
                    $att_list = $query->select('article_attachment.*,cat_default.name as cat_default_name')
                        ->from('article_attachment')
                        ->leftjoin('cat_default', 'article_attachment.cat_default_id = cat_default.id')
                        ->where(['article_attachment.article_id' => $value['id'], 'article_attachment.ispassed' => HintConst::$YesOrNo_YES, 'article_attachment.isdelete' => HintConst::$YesOrNo_NO])
                        ->all();
                    if (is_array($att_list) && count($att_list) > 0) {
                        //如果得到图片的数据  就循环转化成 指定的图片路径
                        foreach ($att_list as $k => $v) {
                            $att_list[$k]['url'] = $server_host . '/' . $v['url'];
                            $att_list[$k]['url_thumb'] = $server_host . '/' . $v['url_thumb'];
                        }
                    }
                    $p_list[$key]['att_list'] = $att_list;
                    //更新该文章的的阅读状态
                    ArticleSendRevieve::updateall(['isread' => HintConst::$YesOrNo_YES], 'article_id=' . $p_list[$key]['id'] . ' and reciever_id = ' . $user_id);
                }
                $result = ['ErrCode' => 0, 'Message' => HintConst::$WEB_JYQ, 'Content' => $p_list];
            } else {
                $result = ['ErrCode' => 0, 'Message' => HintConst::$WEB_JYQ, 'Content' => []];
            }
            $this->mc->add($mc_name, $result);
        }
        return json_encode($result);
    }
    public function Newgrow()
    {
        $ErrCode = HintConst::$Zero;
        $Message = HintConst::$WEB_JYQ;
        $Content = HintConst::$NULLARRAY;
        $school_id = isset($_REQUEST['school_id']) ? $_REQUEST['school_id'] : Yii::$app->session['custominfo']->custom->school_id;
        $class_id = isset($_REQUEST['class_id']) ? $_REQUEST['class_id'] : Yii::$app->session['custominfo']->custom->class_id;
        $user_id = !empty($_REQUEST['user_id']) && is_numeric($_REQUEST['user_id']) ? $_REQUEST['user_id'] : Yii::$app->session['custominfo']->custom->id;
        $page = isset($_REQUEST['page']) && is_numeric($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        $page_size = isset($_REQUEST['size']) && is_numeric($_REQUEST['size']) ? $_REQUEST['size'] : 10;
        $start_line = $page_size * ($page - 1);
        $mc_name = $this->getMcName() . 'Nesgrow' . $school_id . $class_id . $user_id . $page . $page_size;
        if ($val = $this->mc->get($mc_name)) {
            $Content = $val;
        } else {
            $grow_ss = "((asr.role=" . CatDef::$obj_cat['parent'] . " and asr.school_id=$school_id and asr.class_id=0 and asr.reciever_id=0)or(asr.role=" . CatDef::$obj_cat['parent'] . " and asr.school_id=$school_id and asr.class_id=$class_id and asr.reciever_id=0)or(asr.role=" . CatDef::$obj_cat['parent'] . " and asr.school_id=$school_id and asr.class_id=$class_id and asr.reciever_id=$user_id)or(asr.role=0 and asr.school_id=$school_id and asr.class_id=0 and asr.reciever_id=0)or(asr.role=0 and asr.school_id=$school_id  and asr.class_id=$class_id and asr.reciever_id=0) or (asr.reciever_id =$user_id)) and a.article_type_id=";
            $query = new Query();
            $Content = $query->select('a.id,a.author_id,a.article_type_id,a.sub_type_id,a.title,a.thumb,a.date,c.name_zh as author_name,c.cat_default_id as author_role_id')
                ->distinct()
                ->from('articles as a')
                ->leftjoin('article_send_revieve as asr', 'asr.article_id = a.id')
                ->leftjoin('customs as c', 'asr.sender_id = c.id')
                ->where($grow_ss . HintConst::$HIGHLIGHT_PATH_NEW)//精彩瞬间
//                ->orwhere($grow_ss . HintConst::$NIANPINGJIA_PATH)//学期总结
//                ->orwhere($grow_ss . HintConst::$YUEPINGJIA_PATH)//月评价/月总结
                ->andWhere(['a.isdelete' => HintConst::$YesOrNo_NO, 'a.ispassed' => HintConst::$YesOrNo_YES])
                ->orderby(['a.id' => SORT_DESC])
                ->offset($start_line)
                ->limit($page_size)
                ->all();
            $server_host = $_SERVER['HTTP_HOST'];
            if (is_array($Content) && count($Content) > 0) {
                foreach ($Content as $key => $value) {
                    $Content[$key]['id'] = (int)$Content[$key]['id'];
                    $Content[$key]['thumb'] = $server_host . '/' . $Content[$key]['thumb'];
                    $Content[$key]['att_list'] = $this->get_att_list($value['id'], 2);
                    //更新该文章的的阅读状态 isread no using
//                    ArticleSendRevieve::updateall(['isread' => HintConst::$YesOrNo_YES], 'article_id = ' . $Content[$key]['id'] . ' and reciever_id = ' . $user_id);
                }
            }
            $this->mc->add($mc_name, $Content);
        }
        return CommonFun::json($ErrCode, $Message, $Content);
    }
    public function PingjiaDetail2()
    {
        $user_id = isset($_REQUEST['student_id']) ? $_REQUEST['student_id'] : 0;
        $month = isset($_REQUEST['month']) ? $_REQUEST['month'] : date('Ym');
        $user_id = $user_id > 0 ? $user_id : $this->getCustomId();  //可以通过user_id参数 和  session的uer_id 进行查询 可能会出现问题  检查无误后可以清除
        if ($user_id > 0 && $month) {
            $mc_name = $this->getMcName() . 'PingjiaDetail2' . $user_id . $month;
            if ($val = $this->mc->get($mc_name)) {
                $result = $val;
            } else {
                $query = new Query();
                $model = $query->select('articles .*,customs.name_zh as author_name,customs.cat_default_id as author_role_id,c.name_zh as stu_name,c.cat_default_id as stu_role_id')
                    ->from('articles')
                    ->leftjoin('customs', 'customs.id = articles.author_id')
                    ->leftjoin('article_send_revieve', 'article_send_revieve.article_id = articles.id')
                    ->leftjoin('customs as c', 'c.id = article_send_revieve.reciever_id')
                    ->where(['articles.month' => $month, 'article_send_revieve.reciever_id' => $user_id, 'articles.article_type_id' => HintConst::$YUEPINGJIA_PATH, 'articles.ispassed' => HintConst::$YesOrNo_YES])
                    ->orderby(['articles.id' => SORT_DESC])
                    ->all();
                if (!$model || !is_array($model) || count($model) == 0) {
                    $result = json_encode(['ErrCode' => 0, 'Message' => '没有相应的数据', 'Content' => []]);
                } else {
                    foreach ($model as $key => $value) {
                        $model[$key]['reply_list'] = $this->get_reply_list($value['id']);
                    }
                    $result = json_encode(['ErrCode' => '0', 'Message' => HintConst::$WEB_JYQ, 'Content' => $model]);
                }
                $this->mc->add($mc_name, $result);
            }
            return $result;
        } else {
            $result = json_encode(['ErrCode' => '7055', 'Message' => '参数错误', 'Content' => '']);
            return $result;
        }
    }
    public function PingjiaDetail3()
    {
        $user_id = isset($_REQUEST['student_id']) ? $_REQUEST['student_id'] : 0;
        $term = isset($_REQUEST['term']) ? $_REQUEST['term'] : date('Y');
        $user_id = $user_id > 0 ? $user_id : $this->getCustomId(); //可以通过user_id参数 和  session的uer_id 进行查询 可能会出现问题  检查无误后可以清除
        if ($user_id > 0 && $term) {
            $mc_name = $this->getMcName() . 'PingjiaDetail3' . $user_id . $term;
            if ($val = $this->mc->get($mc_name)) {
                $result = $val;
            } else {
                $query = new Query();
                $model = $query->select('articles .*,customs.name_zh as author_name,customs.cat_default_id as author_role_id,c.name_zh as stu_name,c.cat_default_id as stu_role_id')
                    ->from('articles')
                    ->leftjoin('customs', 'customs.id = articles.author_id')
                    ->leftjoin('article_send_revieve', 'article_send_revieve.article_id = articles.id')
                    ->leftjoin('customs as c', 'c.id = ' . $user_id)
                    ->where(['article_send_revieve.reciever_id' => $user_id, 'articles.article_type_id' => HintConst::$NIANPINGJIA_PATH, 'articles.ispassed' => HintConst::$YesOrNo_YES])
                    ->andwhere(['like', 'articles.term', $term])
                    ->orderby(['articles.id' => SORT_DESC])
                    ->all();
                if (!$model || $model == false) {
                    $result = json_encode(['ErrCode' => 0, 'Message' => '没有相应的数据', 'Content' => []]);
                } else {
                    foreach ($model as $key => $value) {
                        $model[$key]['reply_list'] = $this->get_reply_list($value['id']);
                    }
                    $result = json_encode(['ErrCode' => 0, 'Message' => HintConst::$WEB_JYQ, 'Content' => $model]);
                }
                $this->mc->add($mc_name, $result);
            }
            return $result;
        } else {
            $result = json_encode(['ErrCode' => 7055, 'Message' => '参数错误', 'Content' => []]);
            return $result;
        }
    }
    public function AEList($type)
        //分页显示
    {
        $school_id = isset($_REQUEST['school_id']) ? trim($_REQUEST['school_id']) : Yii::$app->session['custominfo']->custom->school_id;
        $class_id = isset($_REQUEST['class_id']) ? trim($_REQUEST['class_id']) : Yii::$app->session['custominfo']->custom->class_id;
        $user_id = isset($_REQUEST['user_id']) ? trim($_REQUEST['user_id']) : Yii::$app->session['custominfo']->custom->id;
        $role = isset($_REQUEST['role']) ? trim($_REQUEST['role']) : Yii::$app->session['custominfo']->custom->cat_default_id;
        $page = isset($_REQUEST['page']) ? trim($_REQUEST['page']) : 1;
        $size = isset($_REQUEST['size']) ? trim($_REQUEST['size']) : 10;
        if ($user_id > 0) {
            $model = $this->AE_List($school_id, $class_id, $user_id, $role, $page, $size, $type);
            $result = json_encode(['ErrCode' => 0, 'Message' => HintConst::$WEB_JYQ, 'Content' => $model]);
            return $result;
        } else {
            $result = json_encode(['ErrCode' => 7055, 'Message' => '参数错误', 'Content' => []]);
            return $result;
        }
    }
    public function AE_List($school_id, $class_id, $user_id, $role, $page, $size, $ar_type)
    {
        $mc_name = $this->getMcName() . 'AE_List' . $user_id . $page . $size . $ar_type;
        if ($val = $this->mc->get($mc_name)) {
            $model = $val;
        } else {
            $start_line = $size * ($page - 1);
            if ($role == HintConst::$ROLE_HEADMASTER) {
                if ($user_id != Yii::$app->session['custominfo']->custom->id) {//not headmast self
                    $model = $this->getParentRec($ar_type, $school_id, $class_id, $user_id, $start_line, $size);
                } else {
                    $model = $this->getHeadRec($ar_type, $school_id, $user_id, $start_line, $size);
                }
            } elseif ($role == HintConst::$ROLE_TEACHER) {
                if ($user_id != Yii::$app->session['custominfo']->custom->id) {//not teacher self
                    $model = $this->getParentRec($ar_type, $school_id, $class_id, $user_id, $start_line, $size);
                } else {
                    $model = $this->getTeachRec($ar_type, $school_id, $class_id, $user_id, $start_line, $size);
                }
            } elseif ($role == HintConst::$ROLE_PARENT) {
                $model = $this->getParentRec($ar_type, $school_id, $class_id, $user_id, $start_line, $size);
            }
            foreach ($model as $key => $value) {
                if (Yii::$app->session['custominfo']->custom->cat_default_id == HintConst::$ROLE_PARENT) {
                    ArticleSendRevieve::updateall(['isread' => HintConst::$YesOrNo_YES], 'article_id = ' . $value['id'] . ' and reciever_id = ' . $user_id);
                }
            }
            $this->mc->add($mc_name, $model);
        }
        return $model;
    }
    public function getHeadRec($ar_type, $school_id, $user_id, $start_line, $size)
    {
        $mc_name = $this->getMcName() . 'getHeadRec' . $school_id . $user_id . $start_line . $size;
        if ($val = $this->mc->get($mc_name)) {
            $Content = $val;
        } else {
            $query = new Query();
            $Content = $query->select($this->arlist_se)
                ->distinct()
                ->from('articles as a')
                ->leftjoin('article_send_revieve as asr', 'asr.article_id = a.id')
                ->leftjoin('customs as c', 'c.id = a.author_id')
                ->where(['asr.school_id' => $school_id])
                ->andWhere(['a.article_type_id' => $ar_type, 'a.ispassed' => HintConst::$YesOrNo_YES, 'a.isdelete' => HintConst::$YesOrNo_NO])
                ->orderby(['a.id' => SORT_DESC])
                ->groupBy('a.id')
                ->offset($start_line)
                ->limit($size)
                ->all();
            $club = new Vote();
            foreach ($Content as &$v) {
                if ((int)$v['o_link_id'] != 0) {
                    $club->getClubBySharedId($v);
                }
            }
            $this->mc->add($mc_name, $Content);
        }
        return $Content;
    }
    public function getTeachRec($ar_type, $school_id, $class_id, $user_id, $start_line, $size)
    {
        $mc_name = $this->getMcName() . 'getTeachRec' . $school_id . $class_id . $user_id . $start_line . $size;
        if ($val = $this->mc->get($mc_name)) {
            $Content = $val;
        } else {
            $query = new Query();
            $Content = $query->select($this->arlist_se)
                ->distinct()
                ->from('articles as a')
                ->leftjoin('article_send_revieve as asr', 'asr.article_id = a.id')
                ->leftjoin('customs as c', 'c.id = ' . $user_id)
                ->where(['asr.role' => CatDef::$obj_cat['teacher'], 'asr.school_id' => $school_id, 'asr.class_id' => 0, 'asr.reciever_id' => 0])
                ->orWhere(['asr.role' => CatDef::$obj_cat['all'], 'asr.school_id' => $school_id, 'asr.class_id' => 0, 'asr.reciever_id' => 0])
                ->orWhere(['asr.role' => CatDef::$obj_cat['all'], 'asr.school_id' => $school_id, 'asr.class_id' => $class_id, 'asr.reciever_id' => 0])
                ->orWhere(['asr.reciever_id' => $user_id])
                ->orWhere(['asr.sender_id' => $user_id])//author
                ->andWhere(['a.article_type_id' => $ar_type, 'a.ispassed' => HintConst::$YesOrNo_YES, 'a.isdelete' => HintConst::$YesOrNo_NO])
                ->orderby(['a.id' => SORT_DESC])
                ->offset($start_line)
                ->limit($size)
                ->all();
            $club = new Vote();
            foreach ($Content as &$v) {
                if ((int)$v['o_link_id'] != 0) {
                    $club->getClubBySharedId($v);
                }
            }
            $this->mc->add($mc_name, $Content);
        }
        return $Content;
    }
    public function getParentRec($ar_type, $school_id, $class_id, $user_id, $start_line, $size)
    {
        $mc_name = $this->getMcName() . 'getParentRec' . $school_id . $class_id . $user_id . $start_line . $size;
        if ($val = $this->mc->get($mc_name)) {
            $Content = $val;
        } else {
            $query = new Query();
            $Content = $query->select($this->arlist_se)
                ->distinct()
                ->from('articles as a')
                ->leftjoin('article_send_revieve as asr', 'asr.article_id = a.id')
                ->leftjoin('customs as c', 'c.id = ' . $user_id)
                ->where(['asr.role' => CatDef::$obj_cat['parent'], 'asr.school_id' => $school_id, 'asr.class_id' => 0, 'asr.reciever_id' => 0])
                ->orWhere(['asr.role' => CatDef::$obj_cat['all'], 'asr.school_id' => $school_id, 'asr.class_id' => 0, 'asr.reciever_id' => 0])
                ->orWhere(['asr.role' => CatDef::$obj_cat['all'], 'asr.school_id' => $school_id, 'asr.class_id' => $class_id, 'asr.reciever_id' => 0])
                ->orWhere(['asr.reciever_id' => $user_id])
                ->andWhere(['a.article_type_id' => $ar_type, 'a.ispassed' => HintConst::$YesOrNo_YES, 'a.isdelete' => HintConst::$YesOrNo_NO])
                ->orderby(['a.id' => SORT_DESC])
                ->offset($start_line)
                ->limit($size)
                ->all();
            $club = new Vote();
            foreach ($Content as &$v) {
                if ((int)$v['o_link_id'] != 0) {
                    $club->getClubBySharedId($v);
                }
            }
            $this->mc->add($mc_name, $Content);
        }
        return $Content;
    }
    public function Addarshare()
    {
        $ErrCode = HintConst::$Zero;
        $Message = HintConst::$WEB_JYQ;
        $Content = HintConst::$NULLARRAY;
        $d['id'] = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        if (empty($d['id']) || !is_numeric($d['id'])) {
            $ErrCode = HintConst::$NoId;
        } else {
            $ar_stats = new ArStats();
            $ar_stats->addStats($d['id'], HintConst::$YesOrNo_YES);
            $ar = new Articles();
            $ar->increaseF($d['id'], 'share_times');
        }
        $result = ['ErrCode' => $ErrCode, 'Message' => $Message, 'Content' => $Content];
        return json_encode($result);
    }
    public function AddAHE($type)
    {
        try {
            $ErrCode = HintConst::$Zero;
            $Message = HintConst::$WEB_JYQ;
            $Content = HintConst::$NULLARRAY;
            $d['article_type_id'] = $type;
            $d['o_link_id'] = isset($_REQUEST['o_link_id']) ? $_REQUEST['o_link_id'] : 0;
            $d['sub_type_id'] = isset($_REQUEST['sub_type_id']) ? $_REQUEST['sub_type_id'] : 0;
            $d['title'] = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';
            $d['contents'] = isset($_REQUEST['contents']) ? $_REQUEST['contents'] : '';
            $role = isset($_REQUEST['role']) ? $_REQUEST['role'] : 0;
            $school = isset($_REQUEST['school']) ? $_REQUEST['school'] : 0;
            $class = isset($_REQUEST['class']) ? $_REQUEST['class'] : 0;
            $user = isset($_REQUEST['user']) ? $_REQUEST['user'] : 0;
            if (empty($d['title'])) {
                $ErrCode = HintConst::$No_title;
            } elseif (empty($d['contents'])) {
                $ErrCode = HintConst::$NoContents;
            } else {
                if ($d['o_link_id'] != 0) {
                    (new Vote())->increaseShareTimes($d['o_link_id']);
                    (new Score())->ClubShare($d['o_link_id']);
                }
                $Content = $this->addArticle($d, $role, $school, $class, $user);
                $this->push($role, $school, $class, $user, $type, $Content, $d['title']);
            }
            return json_encode(['ErrCode' => $ErrCode, 'Message' => $Message, 'Content' => $Content]);
        } catch (Exception $e) {
            $ba = new BaseAnalyze();
            $ba->writeToAnal('err: ' . $e->getMessage());
            return json_encode(['ErrCode' => HintConst::$No_success, 'Message' => HintConst::$NULL, 'Content' => HintConst::$NULLARRAY]);
        }
    }
    public function  addArticle($d, $role, $school, $class, $user)
    {
        $ar = new Articles();
        $d['sys_p'] = Score::getSysP('create', $d['article_type_id']);
        $d['school_id'] = $this->getCustomSchool_id();
        $d['class_id'] = $this->getCustomClass_id();
        $d['author_id'] = $this->getCustomId();
        $d['createtime'] = CommonFun::getCurrentDateTime();
        $d['date'] = CommonFun::getCurrentDate();
        $d['term'] = CommonFun::getCurrentTerm();
        $d['month'] = CommonFun::getCurrentYm();
        $d['ispassed'] = $this->getIsCanSend();
        $d['isdelete'] = HintConst::$YesOrNo_NO;
        $d['isview'] = HintConst::$YesOrNo_NO;
        $file_name = $this->create_img($d['school_id'], $d['class_id'], 'images');  //上传图片 并记录文件名
        $d['thumb'] = $file_name <> '' ? $file_name . '.thumb.jpg' : '';
        $id = $ar->addNew($d);
        $newid = 0;
        if ($file_name <> '' && $file_name) {
            //插入图片到attament
            $at = new ArticleAttachment();
            $dd['article_id'] = $id;
            $dd['cat_default_id'] = $d['article_type_id'];
            $dd['sub_type_id'] = $d['sub_type_id'];
            $dd['url'] = $file_name . '.jpg';
            $dd['url_thumb'] = $file_name . '.thumb.jpg';
            $dd['createtime'] = CommonFun::getCurrentDateTime();
            $dd['ispassed'] = $this->getIsCanSend();
            $dd['isdelete'] = HintConst::$YesOrNo_NO;
            $dd['isview'] = HintConst::$YesOrNo_NO;
            $dd['sys_p'] = Score::IMG_CREATE_N;
            $newid = $at->add_At($dd);
        }
        if ($this->getCustomRole() == HintConst::$ROLE_TEACHER && $this->getIsCanSend() == HintConst::$YesOrNo_YES) {
            $score = new Score();
            $data['contents'] = $d['title'];
            if ($d['article_type_id'] == CatDef::$mod['pic']) {
                //high score
                $data['related_id'] = $newid;
                $score->ImgCreate($data);
            } else {
                //not high
                $data['sub_type_id'] = $d['article_type_id'];
                $data['related_id'] = $id;
                $score->ArtiCreate($data);
            }
        }
        $arsr = new ArticleSendRevieve();
        $dsr['article_id'] = $id;
        $arsr->addArsr($dsr, $role, $school, $class, $user);
        return $id;
    }
    public function addNew($d)
    {
        $ar = new Articles();
        foreach ($d as $k => $v) {
            $ar->$k = $v;
        }
        $ar->save(false);
        return $ar->attributes['id'];
    }
    public function Getpushlist()
        //分页显示
    {
        $ErrCode = HintConst::$Zero;
        $Message = HintConst::$WEB_JYQ;
        $Content = HintConst::$NULLARRAY;
        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        if (empty($id)) {
            $ErrCode = HintConst::$NoId;
        } else {
            $Content = $this->Get_pushlist($id);
        }
        $result = json_encode(['ErrCode' => $ErrCode, 'Message' => $Message, 'Content' => $Content]);
        return $result;
    }
    public function Get_pushlist($id)
    {
        $mc_name = $this->getMcName() . 'Get_pushlist' . $id;
        if ($val = $this->mc->get($mc_name)) {
            $Content = $val;
        } else {
            $query = new Query();
            $Content = $query->select($this->arlist_se)
                ->distinct()
                ->from('articles as a')
                ->leftjoin('article_send_revieve as asr', 'asr.article_id = a.id')
                ->leftjoin('customs as c', 'c.id = a.author_id')
                ->where("a.id in ($id)")
                ->orderby(['a.id' => SORT_DESC])
                ->groupBy('a.id')
                ->all();
            $this->mc->add($mc_name, $Content);
        }
        return $Content;
    }
    public function Getreplybyid()
    {
        $ErrCode = HintConst::$Zero;
        $Message = HintConst::$WEB_JYQ;
        $Content = HintConst::$NULLARRAY;
        $pri_type_id = isset($_REQUEST['pri_type_id']) ? trim($_REQUEST['pri_type_id']) : '';
        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        if (empty($id)) {
            $ErrCode = HintConst::$NoId;
        } elseif (empty($pri_type_id)) {
            $ErrCode = HintConst::$No_pri_type_id;
        } else {
            if ($pri_type_id == HintConst::$ARTICLE_PATH || $pri_type_id == HintConst::$YUEPINGJIA_PATH || $pri_type_id == HintConst::$NIANPINGJIA_PATH) {
                $Content = (new ArticleReplies())->Get_replybyid($id);
            } elseif ($pri_type_id == HintConst::$NOTE_PATH) {
                $Content = (new NotesReplies())->Get_replybyid($id);
            } else {
                $Content = (new VoteReplies())->Get_replybyid($id);
            }
            foreach ($Content as &$key) {
                if ($key['receiver_id'] == 0) {
                    $key['receiver_id'] = $key['author_id'];
                    $key['receiver_name'] = $key['author_name'];
                }
                $key = array_slice($key, 0, 8);
            }
        }
        $result = json_encode(['ErrCode' => $ErrCode, 'Message' => $Message, 'Content' => $Content]);
        return $result;
    }
    public function push($role, $school, $class, $user, $type, $id, $title)
    {
        $d['role'] = $role;
        $d['school'] = $school;
        $d['class'] = $class;
        $d['user'] = $user;
        $school = [];
        $class = [];
        $user = [];
        $this->getSchoolAndClassAndUserForArti($school, $class, $user, $d);
        $custom = new Customs();
        $token = $custom->getToken($school, $class, $user, $role);
        (new XgEvent)->push_ar($token, $type, $id, $title);
    }
    public function pushAuditByArid($id, $title)//used for audit
    {
        $school = [];
        $class = [];
        $user = [];
        $this->getSchoolAndClassAndUserForArtiByID($school, $class, $user, $id);
        $custom = new Customs();
        $token = $custom->getToken($school, $class, $user);
        $ar = new Articles();
        $ar_type = $ar->getTypeAndTitle($id);
        (new XgEvent)->push_ar($token, $ar_type['article_type_id'], $id, $title);
    }
    public function pushReplyByArid($id, $reply_id, $con)//used for audit and reply
    {
        $ar = new Articles();
        $user = $ar->getAuthor($id);
        $ar_type = $ar->getTypeAndTitle($id);
        $custom = new Customs();
        $token = $custom->getToken([], [], $user);
        (new XgEvent())->push_reply($token, $ar_type['article_type_id'], $reply_id, $con);
    }
    public function pushReplyReplyByArid($article_id, $reply_id, $reciever_id, $con)//used for audit and reply
    {
        $ar = new Articles();
        $ar_type = $ar->getTypeAndTitle($article_id);
        $custom = new Customs();
        $token = $custom->getToken([], [], [$reciever_id]);
        (new XgEvent())->push_reply($token, $ar_type['article_type_id'], $reply_id, $con);
    }
    public function pushReplyForEva($id, $reply_id, $con)//used for audit and reply
    {
        $ar = new Articles();
        $ar_type = $ar->getTypeAndTitle($id);
        $custom = new Customs();
        $user = $custom->getRelativeUserId($reply_id);
        if ($reply_id) {
            $user[] = $reply_id;
        }
        $user = array_unique($user);
        $token = $custom->getToken([], [], $user);
        (new XgEvent())->push_reply($token, $ar_type['article_type_id'], $id, $con);
    }
    public function pushReplyByRecieverid($id, $reciever_id, $reply_id, $con)//used for audit and reply
    {
        $ar = new Articles();
        $ar_type = $ar->getTypeAndTitle($id);
        $user[] = $reciever_id;
        $custom = new Customs();
        $token = $custom->getToken([], [], $user);
        (new XgEvent())->push_reply($token, $ar_type['article_type_id'], $reply_id, $con);
    }
    public function getTypeAndTitle($id)
    {
        $mc_name = $this->getMcName() . 'getTypeAndTitle' . $id;
        if ($val = $this->mc->get($mc_name)) {
            $d = $val;
        } else {
            $d = Articles::find()->asArray()
                ->select('article_type_id,sub_type_id,title')
                ->where("id=$id")
                ->one();
            $this->mc->add($mc_name, $d);
        }
        return $d;
    }
//    public function getAuthor($id)
//    {
//        $mc_name = $this->getMcName() . 'artigetAuthor' . $id;
//        if ($val = $this->mc->get($mc_name)) {
//            $d = $val;
//        } else {
//            $d = Articles::find()->asArray()
//                ->select('author_id')
//                ->where("id=$id")
//                ->one();
//            $this->mc->add($mc_name, $d);
//        }
//        return $d;
//    }
    public function getNum($school_id, $type, $startdate, $enddate)
    {
        $mc_name = $this->getMcName() . 'artigetNum' . $school_id . $type . $startdate . $enddate;
        if ($val = $this->mc->get($mc_name)) {
            $d = $val;
        } else {
            $d = $this->find()
                ->asArray()
                ->select('count(id) as num')
                ->where("article_type_id=$type");
            if ($school_id) {
                $d = $d->andWhere("school_id=$school_id");
            }
            $d = $d->andWhere("createtime between '$startdate' and '$enddate'")
                ->one();
            $this->mc->add($mc_name, $d);
        }
        return $d;
    }
    //审核文章
    public function Review()
    {
        $ar_id = $id = isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : Yii::$app->request->get('id');
        //拆分send_to
        $id = explode(',', $id);
        $id = array_filter($id);//过滤为空的值
        $id = array_unique($id);//过滤重复的值
        if (count($id) > 0) {
            foreach ($id as $key => $value) {
                $model = $this->findId($value);
                $model->ispassed = HintConst::$YesOrNo_YES;
                $author_id = $model->author_id;
                $type = $model->article_type_id;
                $reward = $model->sys_p;
                $model->save();
                $score = new Score();
                $tt = self::getTypeAndTitle($value);
                $data['sub_type_id'] = $tt['article_type_id'];
                $data['contents'] = $tt['title'];
                $data['related_id'] = $value;
                $score->ArtiCreate($data);
                self::push_pass($author_id, $type, $value, $reward, $data['contents']);
                //附件审核通过
                ArticleAttachment::updateAll(['ispassed' => HintConst::$YesOrNo_YES], 'article_id = ' . $value);
            }
        }
        $ar = new Articles();
        $ar->pushAuditByArid($ar_id, $data['contents']);
        $result = ['ErrCode' => '0', 'Message' => '审核成功', 'Content' => ''];
        return (json_encode($result));
    }
    public function push_pass($user_id, $type, $id, $reward, $title)
    {
        $user = explode('-', $user_id);
        (new Customs())->increaseF($user[0], 'points', $reward);
        $custom = new Customs();
        $token = $custom->getToken([], [], $user);
        (new XgEvent())->push_pass($token, $type, $id, $reward, $title);
    }
}
