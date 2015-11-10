<?php

namespace app\modules\Admin\Notes\controllers;
use app\modules\Admin\Notes\models\Notes;
use app\modules\Admin\Notes\models\NotesReplies;
use app\modules\Admin\Notes\models\NotesSearch;
use app\modules\admin\Notes\models\NotesView;
use app\modules\AppBase\base\appbase\BaseController;
use app\modules\AppBase\base\CommonFun;
use app\modules\AppBase\base\HintConst;
use Yii;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;
/**
 * NotesController implements the CRUD actions for Notes model.
 */
class NotesController extends BaseController
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }
    /**
     * Lists all Notes models.
     * @return mixed
     */
    public function actionIndex()
    {
        if ($this->checkAdminSession()) {
            $searchModel = new NotesSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        }
    }
    /**
     * Displays a single Notes model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        if ($this->checkAdminSession()) {
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }
    /**
     * Creates a new Notes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if ($this->checkAdminSession()) {
            $model = new Notes();
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
    }
    /**
     * Updates an existing Notes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        if ($this->checkAdminSession()) {
            $model = $this->findModel($id);
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }
    /**
     * Deletes an existing Notes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        if ($this->checkAdminSession()) {
            $this->findModel($id)->delete();
            return $this->redirect(['index']);
        }
    }
    /**
     * Finds the Notes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Notes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Notes::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function  actionAddnote()
    {
        $ErrCode = HintConst::$Zero;
        $note_type_id = !empty($_REQUEST['note_type_id']) ? $_REQUEST['note_type_id'] : '0';
        $a_p_id = !empty($_REQUEST['a_p_id']) ? $_REQUEST['a_p_id'] : '0';
        $obj_id = !empty($_REQUEST['obj_id']) ? $_REQUEST['obj_id'] : '0';
        $for_someone_type = !empty($_REQUEST['for_someone_type']) ? $_REQUEST['for_someone_type'] : '0';
        $for_someone_id = !empty($_REQUEST['for_someone_id']) ? $_REQUEST['for_someone_id'] : '0';
        $title = !empty($_REQUEST['title']) ? $_REQUEST['title'] : '';
        $contents = !empty($_REQUEST['contents']) ? $_REQUEST['contents'] : '';
        $starttime = !empty($_REQUEST['starttime']) ? $_REQUEST['starttime'] : '';
        $endtime = !empty($_REQUEST['endtime']) ? $_REQUEST['endtime'] : '';
        if ($obj_id == '' || !is_numeric($obj_id)) {
            $ErrCode = HintConst::$No_obj_id;
        } elseif ($for_someone_type == '' || !is_numeric($for_someone_type)) {
            $ErrCode = HintConst::$No_note_type_type;
        } elseif ($note_type_id == '' || !is_numeric($note_type_id)) {
            $ErrCode = HintConst::$No_note_type_id;
        } elseif ($a_p_id == '' || !is_numeric($a_p_id)) {
            $ErrCode = HintConst::$No_a_p_id;
        } else {
            if ($for_someone_id == HintConst::$Zero && $a_p_id != 0) {
                $ErrCode = HintConst::$No_for_someone_id;
            } elseif ($contents == '') {
                $ErrCode = HintConst::$NoContents;
            } elseif ($starttime == '' || !CommonFun::IsDate($starttime)) {
                $ErrCode = HintConst::$No_starttime;
            } elseif ($endtime == '' || !CommonFun::IsDate($endtime)) {
                $ErrCode = HintConst::$No_endtime;
            } else {
                $notes = new Notes();
                $result = $notes->AddNote($title, $contents, $note_type_id, $a_p_id, $obj_id, $for_someone_type, $for_someone_id, $starttime, $endtime);
                parent::myjsonencode($result);
            }
        }
        if ($ErrCode != HintConst::$Zero) {
            $Message = HintConst::$NULL;
            $Content = HintConst::$NULLARRAY;
            echo(CommonFun::json($ErrCode, $Message, $Content));
        }
    }
    public function  actionGetnote()
    {
        parent::myjsonencode((new Notes())->Getnote());
    }
    public function  actionReply()
    {
        parent::myjsonencode((new NotesReplies())->Reply());
    }
    public function  actionGetreply()
    {
        return (new NotesReplies())->Getreply();
    }
    public function actionDel()
    {
        return (new Notes())->mydel();
    } public function actionNotedetail()
    {
        return json_encode((new Notes())->NoteDetail());
    }
    public function actionDelreply()
    {
        return (new NotesReplies())->Delreply();
    }
    public function actionDelrr()
    {
        return (new NotesReplies())->Delrr();
    }
    public function actionPass()
    {
        return (new Notes())->Pass();
    }
    public function actionGetnopass()
    {
        return json_encode((new Notes())->Getnopass());
    }
    public function actionGetuserforview()
    {
        return (new NotesView())->Getuserforview();
    }
}
