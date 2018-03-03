<?php

namespace app\modules\main\controllers;

use app\models\Ping;
use app\modules\main\models\DeviceType;
use app\modules\main\models\Location;
use app\modules\main\models\Tarif;
use Yii;
use app\modules\main\models\Device;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\main\models\UploadImage;
use yii\web\UploadedFile;

/**
 * DeviceController implements the CRUD actions for Device model.
 */
class DeviceController extends Controller
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
     * Lists all Device models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Device::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'pagination' => [
                'pageSize' => Yii::$app->params['page_size'],
            ],
        ]);
    }

    /**
     * Displays a single Device model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionTarif($id)
    {
        $model = new Tarif();
        $model->device_id = $id;
        if ($model->load(Yii::$app->request->post())) {
            //удаляем старую запись, если была
            $old = Tarif::findOne(['device_id'=>$id]);
            if(!empty($old))
                $old->delete();
            if($model->save())
                return $this->redirect(['view', 'id' => $id]);
        }
        else
            return $this->render('tarif', [
                'model' => $model,
                'image' => $this->findModel($id)->image,
                'device' => $this->findModel($id)->name,
                'id' => $id,
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
        $sid = md5(uniqid());
        $model->uid = substr($sid,0,16);
        if ($model->load(Yii::$app->request->post())) {
            $upload->image = UploadedFile::getInstance($upload, 'image');
            $fname = $upload->upload();
            //обновляем данные картинки
            $model->image = $fname;
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $locations = Location::find()->select(['id','name'])->asArray()->all();
            $selloc = array();
            foreach($locations as $location) {
                $selloc[$location['id']] = $location['name']; //массив для заполнения данных в select формы
            }
            $types = DeviceType::find()->select(['id','name'])->asArray()->all();
            $seltype = array();
            foreach($types as $type) {
                $seltype[$type['id']] = $type['name']; //массив для заполнения данных в select формы
            }
            $selvrf = array('0' => 'Ручной','1' => 'Автоматический');
            return $this->render('create', [
                'model' => $model,
                'selvrf' => $selvrf,
                'selloc' => $selloc,
                'seltype' => $seltype,
                'upload' => $upload,
            ]);
        }
    }

    /**
     * Updates an existing Device model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
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
        } else {
            $locations = Location::find()->select(['id','name'])->asArray()->all();
            $selloc = array();
            foreach($locations as $location) {
                $selloc[$location['id']] = $location['name']; //массив для заполнения данных в select формы
            }
            $types = DeviceType::find()->select(['id','name'])->asArray()->all();
            $seltype = array();
            foreach($types as $type) {
                $seltype[$type['id']] = $type['name']; //массив для заполнения данных в select формы
            }
            $selvrf = array('0' => 'Ручной','1' => 'Автоматический');
            return $this->render('update', [
                'model' => $model,
                'selvrf' => $selvrf,
                'selloc' => $selloc,
                'seltype' => $seltype,
                'upload' => $upload,
            ]);
        }
    }

    /**
     * Deletes an existing Device model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $fname = substr($this->findModel($id)->image,1);
        $this->findModel($id)->delete();
        //удаляем связанный файл изображения если это не общая картинка noimage.jpg
        $pos = strpos($fname, 'noimage.jpg');
        if($pos === false)
            unlink($fname);

        return $this->redirect(['index']);
    }

    public function actionPing(){
        $host="192.168.1.10";
        //return shell_exec("arping -n 2 " . long2ip($host));
        $scan = new Ping($host);
        $result = $scan->ping('fsockopen');
        if($result)
            return gethostbyaddr($host);
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
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
