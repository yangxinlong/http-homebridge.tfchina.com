<?php

namespace app\modules\Admin\Articles\models;
use app\modules\AppBase\base\appbase\base\BaseReply;
use app\modules\AppBase\base\appbase\TransAct;
use app\modules\AppBase\base\cat_def\CatDef;
use app\modules\AppBase\base\CommonFun;
use app\modules\AppBase\base\HintConst;
use app\modules\AppBase\base\score\Score;
use Yii;
use yii\db\Query;
/**
 * This is the model class for table "article_replies".
 * @property integer $id
 * @property integer $article_id
 * @property integer $repliers_id
 * @property string $title
 * @property string $contents
 * @property string $createtime
 * @property string $updatetime
 * @property integer $ispassed
 * @property integer $isdelete
 * @property integer $isview
 * @property integer $reply_id
 */
class ArticleReplies extends BaseReply
{
    private $sel_reply = 'r.id,r.article_id as m_id,r.createtime, r.contents,r.repliers_id as sender_id,c.name_zh as sender_name,r.reply_id as receiver_id,cc.name_zh as receiver_name,a.author_id,ccc.name_zh as author_name';
    private $reply_list = 'ar.*,customs.name_zh as repliers_name,customs.cat_default_id as repliers_role_id,c.name_zh as reply_name,c.cat_default_id as reply_role_id';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article_replies';
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['article_id', 'repliers_id', 'ispassed', 'isdelete', 'isview', 'reply_id', 'cus_p', 'sys_p'], 'integer'],
            [['createtime', 'updatetime'], 'safe'],
            [['title'], 'string', 'max' => 45],
            [['contents'], 'string', 'max' => 500]
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'article_id' => 'Article ID',
            'repliers_id' => 'Repliers ID',
            'title' => 'Title',
            'contents' => 'Contents',
            'createtime' => 'Createtime',
            'updatetime' => 'Updatetime',
            'ispassed' => 'Ispassed',
            'isdelete' => 'Isdelete',
            'isview' => 'Isview',
            'reply_id' => 'Reply ID',
        ];
    }
    public function Delreply()
    {
        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        if (empty($id) || !is_numeric($id)) {
            $ErrCode = HintConst::$NoId;
        } else {
            $sql = "DELETE FROM article_replies WHERE id=$id OR link_id=$id";
            $ErrCode = (new TransAct())->trans($sql);
        }
        $result = ['ErrCode' => $ErrCode, 'Message' => HintConst::$Success, 'Content' => HintConst::$NULLARRAY];
        return json_encode($result);
    }
    public function Delrr()
    {
        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : '';
        if (empty($id) || !is_numeric($id)) {
            $ErrCode = HintConst::$NoId;
        } else {
            $sql = "DELETE FROM article_replies WHERE id=$id ";
            $ErrCode = (new TransAct())->trans($sql);
        }
        $result = ['ErrCode' => $ErrCode, 'Message' => HintConst::$Success, 'Content' => HintConst::$NULLARRAY];
        return json_encode($result);
    }
    public function Get_replybyid($id)
    {
        $mc_name = $this->getMcName() . 'Get_replybyid' . $id;
        if ($val = $this->mc->get($mc_name)) {
            $Content = $val;
        } else {
            $query = new Query();
            $Content = $query->select($this->sel_reply)
                ->distinct()
                ->from('article_replies as r')
                ->leftjoin('customs as c', 'c.id = r.repliers_id')
                ->leftjoin('customs as cc', 'cc.id = r.reply_id')
                ->leftJoin('articles as a', 'a.id=r.article_id')
                ->leftjoin('customs as ccc', 'ccc.id = a.author_id')
                ->where("r.id in ($id)")
                ->orderby(['r.id' => SORT_DESC])
                ->groupBy('r.id')
                ->all();
            $this->mc->add($mc_name, $Content);
        }
        return $Content;
    }
    public function Reply()
    {
        $ErrCode = HintConst::$Zero;
        $Message = HintConst::$Success;
        $Content = HintConst::$NULLARRAY;
        $d['article_id'] = isset($_REQUEST['article_id']) && is_numeric($_REQUEST['article_id']) ? $_REQUEST['article_id'] : 0;
        $d['reply_id'] = isset($_REQUEST['reply_id']) && is_numeric($_REQUEST['reply_id']) ? $_REQUEST['reply_id'] : 0;
        $d['link_id'] = isset($_REQUEST['id']) && is_numeric($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        $d['contents'] = isset($_REQUEST['content']) ? $_REQUEST['content'] : 0;
        if (!$d['article_id']) {
            $ErrCode = HintConst::$No_ar_id;
            $Message = '缺少ID';
        } elseif (!$d['contents']) {
            $ErrCode = HintConst::$NoContents;
            $Message = '缺少Content';
        } elseif ($d['article_id'] && $d['contents']) {
            $flag = $this->checkReply($d['article_id']);
            if ($flag) {
                $ErrCode = HintConst::$Not_addscore;
            }
            $d['sys_p'] = Score::getSysP('reply', '');
            $newid = self::addNew($d);
            $Message = $newid;
            $Content = $d['contents'];
            if (!$flag) {
                $score = new Score();
                $data['related_id'] = $d['article_id'];
                $data['pri_type_id'] = CatDef::$act['reply'];
                $data['sub_type_id'] = (new Articles())->getTypeAndTitle($d['article_id'])['article_type_id'];
                $data['contents'] = $d['contents'];
                $score->ReplyPoint($data);
            }
            $m = (new Articles())->getFeild('id', $d['article_id']);
            $receiver = (new ArticleSendRevieve())->getFeild('article_id', $d['article_id']);
            if ($m !== null) {
                if ($m->article_type_id == CatDef::$mod['moneva'] || $m->article_type_id == CatDef::$mod['termeva']) {
                    (new Articles())->pushReplyForEva($d['article_id'], $receiver->reciever_id, $d['contents']);
                } else {
                    if ($d['reply_id'] > 0 || $d['link_id'] > 0) {
                        (new Articles())->pushReplyReplyByArid($d['article_id'], $newid, $d['reply_id'], $d['contents']);
                    } else {
                        (new Articles())->pushReplyByArid($d['article_id'], $newid, $d['contents']);
                    }
                }
            }
        } else {
            $ErrCode = HintConst::$ReplyNoData;
        }
        $result = ['ErrCode' => $ErrCode, 'Message' => $Message, 'Content' => $Content];
        return json_encode($result);
    }
    protected function addNew($d)
    {
        $d['repliers_id'] = $this->getCustomId();
        $d['ispassed'] = HintConst::$YesOrNo_YES;
        $d['isview'] = $d['isdelete'] = HintConst::$YesOrNo_NO;
        $d['createtime'] = CommonFun::getCurrentDateTime();
        $vote = new self();
        foreach ($d as $k => $v) {
            $vote->$k = $v;
        }
        $vote->save(false);
        return $vote->attributes['id'];
    }
    public function ReplyList()
    {
        $id = isset($_REQUEST['id']) ? $_REQUEST['id'] : 0;
        $page = Yii::$app->request->get('page') ? Yii::$app->request->get('page') : 1;
        $page_size = Yii::$app->request->get('size') ? Yii::$app->request->get('size') : 10;
        $start_line = $page_size * ($page - 1);
        if ($id == 0) {
            $result = ['ErrCode' => 1, 'Message' => '文章ID不存在', 'Content' => []];
            return json_encode($result);
        }
        //得到文章 判断用户角色 根据角色取不同的回复
        $article = Articles::findOne($id);
        if (isset($article->id)) {
            Yii::$app->session['article_id'] = $article->id;
            $user_id = $this->getCustomId();
            $user_role_id = $this->getCustomRole();
            $mc_name = $this->getMcName() . 'ReplyList' . $user_id . $id . $page . $page_size;
            if ($val = $this->mc->get($mc_name)) {
                $result = $val;
            } else {
                $query = new Query();
                $reply_list = $query->select($this->reply_list)
                    ->distinct()
                    ->from('article_replies as ar')
                    ->leftjoin('customs', 'customs.id = ar.repliers_id')
                    ->leftjoin('customs as c', 'c.id = ar.reply_id')
                    ->leftJoin('article_send_revieve as sr', 'sr.article_id=ar.article_id');
                if ($user_role_id == HintConst::$ROLE_HEADMASTER || ($article->author_id == $user_id && $user_role_id == HintConst::$ROLE_TEACHER) || (($article->article_type_id == HintConst::$YUEPINGJIA_PATH || $article->article_type_id == HintConst::$NIANPINGJIA_PATH) && $this->getCustomRole() == HintConst::$ROLE_PARENT)) {  //可以查看全部回复及回复的回复:园长;老师是作者; 发给家长的评价
                    $reply_list = $query->where(['ar.article_id' => $id, 'ar.link_id' => 0, 'ar.reply_id' => 0]);
                } else {
                    $reply_list = $query->where(['ar.article_id' => $id, 'ar.repliers_id' => $user_id, 'ar.link_id' => 0, 'ar.reply_id' => 0])
                        ->orWhere(['ar.article_id' => $id, 'sr.role' => CatDef::$obj_cat['parent'], 'sr.reciever_id' => $user_id, 'ar.link_id' => 0, 'ar.reply_id' => 0]);
                }
                $reply_list = $query->orderby(['ar.id' => SORT_ASC])
                    ->offset($start_line)
                    ->limit($page_size)
                    ->all();
                if ($reply_list) {
                    foreach ($reply_list as $key => $value) {
                        $query = new Query();
                        $reply_list[$key]['reply_list'] = $query->select($this->reply_list)
                            ->from('article_replies as ar')
                            ->leftjoin('customs', 'customs.id = ar.repliers_id')
                            ->leftjoin('customs as c', 'c.id = ar.reply_id')
                            ->where(['ar.article_id' => $id, 'ar.link_id' => $value['id']]);
                        $reply_list[$key]['reply_list'] = $reply_list[$key]['reply_list']->orderby(['ar.id' => SORT_ASC])
                            ->all();
                    }
                }
                $result = ['ErrCode' => 0, 'Message' => HintConst::$WEB_JYQ, 'Content' => $reply_list];
                $this->mc->add($mc_name, $result);
            }
            return json_encode($result);
        } else {
            Yii::$app->session['article_id'] = 0;
            $result = ['ErrCode' => 1, 'Message' => '文章不属于该用户下', 'Content' => []];
            return json_encode($result);
        }
    }
    protected function  checkReply($article_id)
    {
        $mo = self::find()
            ->where(['article_id' => $article_id, 'repliers_id' => $this->getCustomId()])
            ->one();
        if ($mo !== null) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }
}
