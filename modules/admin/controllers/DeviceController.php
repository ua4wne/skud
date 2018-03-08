<?php

namespace app\modules\admin\controllers;

use app\models\Events;
use app\modules\admin\models\TimeZone;
use app\modules\main\models\Event;
use app\modules\main\models\UploadImage;
use Yii;
use app\modules\admin\models\Device;
use app\modules\admin\models\SearchDevice;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DeviceController implements the CRUD actions for Device model.
 */
class DeviceController extends Controller
{
    public $layout = '@app/views/layouts/main.php';
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
     * Lists all Device models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchDevice();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Device model.
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
     * Creates a new Device model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Device();
        $upload = new UploadImage();
        $model->type = 'Z5R-Web';
        if ($model->load(Yii::$app->request->post())) {
            $upload->image = UploadedFile::getInstance($upload, 'image');
            $fname = $upload->upload();
            //обновляем данные картинки
            $model->image = $fname;
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }
        else{
            $zones = TimeZone::find()->select(['id','zone'])->asArray()->all();
            $tzone = array();
            foreach($zones as $zone) {
                $tzone[$zone['id']] = $zone['zone']; //массив для заполнения данных в select формы
            }
            return $this->render('create', [
                'model' => $model,
                'upload' => $upload,
                'tzone' => $tzone,
            ]);
        }
    }

    /**
     * Updates an existing Device model.
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
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        }
        else{
            $zones = TimeZone::find()->select(['id','zone'])->asArray()->all();
            $tzone = array();
            foreach($zones as $zone) {
                $tzone[$zone['id']] = $zone['zone']; //массив для заполнения данных в select формы
            }
            return $this->render('update', [
                'model' => $model,
                'upload' => $upload,
                'tzone' => $tzone,
            ]);
        }
    }

    /**
     * Deletes an existing Device model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //ищем связанные записи с таблицами time_zone и event
        $events = Event::findAll()->where(['device_id'=>$id])->count();
        if($events){
            Yii::$app->session->setFlash('error', 'Удаление не возможно! Имеются связанные записи ('.$events.')');
        }
        else{
            $this->findModel($id)->delete();
            Yii::$app->session->setFlash('success', 'Контроллер '.$this->findModel($id)->type.' удален из системы!');
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the Device model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Device the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Device::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
