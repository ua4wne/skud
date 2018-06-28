<?php

namespace app\modules\main\controllers;

use app\models\LibraryModel;
use app\modules\admin\models\CarType;
use app\modules\admin\models\DocType;
use app\modules\main\models\Renter;
use app\modules\main\models\Card;
use app\modules\main\models\UploadImage;
use yii\web\UploadedFile;
use Yii;
use app\modules\main\models\Visitor;
use app\modules\main\models\SearchVisitor;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VisitorController implements the CRUD actions for Visitor model.
 */
class VisitorController extends Controller
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
     * Lists all Visitor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchVisitor();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Visitor model.
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
     * Creates a new Visitor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Visitor();
        $upload = new UploadImage();

        if ($model->load(Yii::$app->request->post())) {
            $upload->image = UploadedFile::getInstance($upload, 'image');
            if(!empty($upload->image)) {
                $fname = $upload->upload();
                //обновляем данные картинки
                $model->image = $fname;
            }
            else
                $model->image = '/images/noimage.jpg';
            if($model->save()){
                $share = Card::findOne(['code'=>$model->card])->share;
                if($share){
                    $msg = 'Добавлен новый посетитель <strong>'. $model->fname .' '.$model->lname .'</strong>. Карта доступа '.$model->card.' арендатор '.$model->renter->title;
                }
                else{
                    $msg = 'Добавлен новый сотрудник <strong>'. $model->fname .' '.$model->lname .'</strong>. Карта доступа '.$model->card.' арендатор '.$model->renter->title;
                }
                LibraryModel::AddEventLog('info',$msg);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $renters = Renter::find()->select(['id', 'title'])->where('status=1')->asArray()->all();
        $rentsel = array();
        foreach ($renters as $val) {
            $rentsel[$val['id']] = $val['title']; //массив для заполнения данных в select формы
        }
        $doctype = DocType::find()->select(['id', 'text'])->asArray()->all();
        $docs = array();
        foreach ($doctype as $val) {
            $docs[$val['id']] = $val['text']; //массив для заполнения данных в select формы
        }
        $cartype = CarType::find()->select(['id', 'text'])->asArray()->all();
        $cars = array();
        foreach ($cartype as $val) {
            $cars[$val['id']] = $val['text']; //массив для заполнения данных в select формы
        }
        return $this->render('create', [
            'model' => $model,
            'rentsel' => $rentsel,
            'docs' => $docs,
            'upload' => $upload,
            'cars' => $cars,
        ]);
    }

    public function actionCheck(){
        $model = new Visitor();
        if(\Yii::$app->request->isAjax){
            if ($model->load(Yii::$app->request->post())) {
                //проверяем наличие карты
                $card = Card::findOne(['code'=>$model->card]);
                if($card->share){
                    return 'SHARE';
                }
                if(empty($card)){
                    return 'NO_CARD';
                }
                //проверяем что карта не занята
                $busy = Visitor::findOne(['card'=>$model->card]);
                if(empty($busy)){
                    return 'OK';
                }
                else{
                    //это обновление данных уже привязанного к этой карте посетителя?
                    $old = Visitor::findOne(['id'=>$model->id, 'card'=>$model->card]);
                    if(empty($old)){ //нет, это попытка привязки занятой карты к другому посетителю
                        return 'BUSY';
                    }
                    else{
                        return 'OLD';
                    }
                }
            }
        }
    }

    /**
     * Updates an existing Visitor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $old_image = substr($model->image,1); //старый файл изображения
        $upload = new UploadImage();

        if ($model->load(Yii::$app->request->post())) {
            $upload->image = UploadedFile::getInstance($upload, 'new_image');
            if(!empty($upload->image)){
                $fname = $upload->upload();
                //обновляем данные картинки
                $model->image = $fname;
                if(!empty($old_image)){
                    //удаляем связанный файл изображения если это не общая картинка noimage.jpg
                    $pos = strpos($old_image, 'noimage.jpg');
                    if($pos === false)
                        unlink($old_image);
                }
            }
            $share = Card::findOne(['code'=>$model->card])->share;
            if($share){
                $msg = 'Данные посетителя <strong>'. $model->fname .' '.$model->lname .'</strong> были обновлены ' . date('d-m-Y H:i:s');
            }
            else{
                $msg = 'Данные сотрудника <strong>'. $model->fname .' '.$model->lname .'</strong> были обновлены ' . date('d-m-Y H:i:s');
            }
            LibraryModel::AddEventLog('info',$msg);
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $renters = Renter::find()->select(['id', 'title'])->where('status=1')->asArray()->all();
        $rentsel = array();
        foreach ($renters as $val) {
            $rentsel[$val['id']] = $val['title']; //массив для заполнения данных в select формы
        }
        $doctype = DocType::find()->select(['id', 'text'])->asArray()->all();
        $docs = array();
        foreach ($doctype as $val) {
            $docs[$val['id']] = $val['text']; //массив для заполнения данных в select формы
        }
        $cartype = CarType::find()->select(['id', 'text'])->asArray()->all();
        $cars = array();
        foreach ($cartype as $val) {
            $cars[$val['id']] = $val['text']; //массив для заполнения данных в select формы
        }
        return $this->render('update', [
            'model' => $model,
            'rentsel' => $rentsel,
            'docs' => $docs,
            'upload' => $upload,
            'cars' => $cars,
        ]);
    }

    /**
     * Deletes an existing Visitor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $visitor = $this->findModel($id);
        $share = Card::findOne(['code'=>$visitor->card])->share;
        if($share){
            $msg = 'Данные посетителя <strong>'. $visitor->fname .' '.$visitor->lname .'</strong> были удалены ' . date('d-m-Y H:i:s');
        }
        else{
            $msg = 'Данные сотрудника <strong>'. $visitor->fname .' '.$visitor->lname .'</strong> были удалены ' . date('d-m-Y H:i:s');
        }
        LibraryModel::AddEventLog('info',$msg);
        $visitor->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Visitor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Visitor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Visitor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
