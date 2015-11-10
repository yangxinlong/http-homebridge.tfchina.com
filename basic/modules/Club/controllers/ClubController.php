<?php

namespace app\modules\Club\controllers;
use app\modules\Admin\Articles\models\ArticlesFav;
use app\modules\Admin\Vote\models\Vote;
use app\modules\Admin\Vote\models\VoteReplies;
use app\modules\Admin\Vote\models\VoteView;
use app\modules\AppBase\base\appbase\base\BaseAtt;
use app\modules\AppBase\base\appbase\ClubBC;
use app\modules\AppBase\base\cat_def\CatDef;
use app\modules\AppBase\base\HintConst;
class ClubController extends ClubBC
{
    public function actionAddclub()
    {
        $vote = new Vote();
        $pri_type_id = isset($_REQUEST['pri_type_id']) ? trim($_REQUEST['pri_type_id']) : CatDef::$mod['club_arti'];
        $result = $vote->Addvote($pri_type_id);
        return ($result);
    }
    public function actionUploadforclubarti()
    {
        $vote = new Vote();
        $result = $vote->FileUpload('club_arti');
        return ($result);
    }
    public function actionUploadforclubreply()
    {
        $arr = new BaseAtt();
        $result = $arr->FileUpload('club_reply');
        return ($result);
    }
    public function actionClubfilt()
    {
        $vote = new Vote();
        $result = $vote->Clublist();
        return ($result);
    }
    public function  actionReply()
    {
        return json_encode((new VoteReplies())->Reply());
    }
    public function  actionGetreply()
    {
        $votereply = new VoteReplies();
        $result = $votereply->Getreply();
        parent::myjsonencode($result);
    }
    public function actionDetailforclub()
    {
        $vote = new Vote();
        $result = $vote->Getdetail();
        return ($result);
    }
    public function actionPraise()
    {
        $vote = new Vote();
        $result = $vote->Praise();
        return ($result);
    }
    public function actionAddFav()
    {
        $fav = new ArticlesFav();
        $result = $fav->addFav();
        return ($result);
    }
    public function actionDelfav()
    {
        $ErrCode = HintConst::$Zero;
        $id = isset($_REQUEST['id']) ? trim($_REQUEST['id']) : 0;
        if ($id == 0 || !is_numeric($id)) {
            $ErrCode = HintConst::$NoId;
        } else {
            $fav = new ArticlesFav();
            $fav->del($id);
        }
        $result = ['ErrCode' => $ErrCode, 'Message' => '', 'Content' => HintConst::$NULLARRAY];
        return (json_encode($result));
    }
    public function actionFav()
    {
        $fav = new ArticlesFav();
        $result = $fav->Fav();
        return ($result);
    }
    public function actionViewuser()
    {
        $viewuser = new VoteView();
        $result = $viewuser->getViewUser();
        return ($result);
    }
    public function actionAdopt()
    {
        $reply = new VoteReplies();
        $result = $reply->Adopt();
        return ($result);
    }
    public function actionShare()
    {
        $vote = new Vote();
        $result = $vote->Share();
        return ($result);
    }
    public function actionDel()
    {
        return (new Vote())->mydel();
    }
    public function actionDelreply()
    {
        return (new VoteReplies())->Delreply();
    }
    public function actionDelrr()
    {
        return (new VoteReplies())->Delrr();
    }
}
