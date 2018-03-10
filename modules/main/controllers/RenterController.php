<?php

namespace app\modules\main\controllers;

use app\models\LibraryModel;
use app\modules\main\models\Visitor;
use Yii;
use app\modules\main\models\Renter;
use app\modules\main\models\SearchRenter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RenterController implements the CRUD actions for Renter model.
 */
class RenterController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Renter models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchRenter();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Renter model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Renter model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Renter();

        if ($model->load(Yii::$app->request->post())) {
            //проверяем наличие дублей
            $renter = Renter::findOne(['title'=>$model->title]);
            if(empty($renter)){
                $model->save();
                $msg = 'Пользователем <strong>' . Yii::$app->user->identity->fname . ' ' . Yii::$app->user->identity->lname . '</strong> добавлен новый арендатор <strong>' . $model->title . '</strong>.';
                LibraryModel::AddEventLog('info', $msg);
                return $this->redirect(['view', 'id' => $model->id]);
            }
            else{
                Yii::$app->session->setFlash('error', 'Такая организация уже есть!');
                return $this->redirect(['view', 'id' => $renter->id]);
            }

        }
        else{
            $statsel = array('1' => 'Действующий', '0' => 'Не действующий');
            return $this->render('create', [
                'model' => $model,
                'statsel' => $statsel,
            ]);
        }
    }

    /**
     * Updates an existing Renter model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $statsel = array('1' => 'Действующий', '0' => 'Не действующий');
        return $this->render('update', [
            'model' => $model,
            'statsel' => $statsel,
        ]);
    }

    /**
     * Deletes an existing Renter model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //определяем связанные записи
        $count = Visitor::find()->where(['renter_id'=>$id])->count();
        if($count){
            Yii::$app->session->setFlash('error', 'Удаление организации '.$this->findModel($id)->title.' не возможно! Имеются связанные записи посетителей ('.$count.')');
        }
        else{
            $this->findModel($id)->delete();
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Renter model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Renter the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Renter::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
