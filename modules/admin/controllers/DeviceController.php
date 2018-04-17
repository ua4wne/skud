<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Command;
use app\modules\admin\models\Task;
use app\modules\admin\models\TimeZone;
use app\modules\main\models\Event;
use app\modules\main\models\UploadImage;
use yii\web\UploadedFile;
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
        $command = new Command();
        $zones = TimeZone::find()->select(['id', 'zone', 'text'])->asArray()->all();
        $zone = array();
        foreach ($zones as $val) {
            $zone[$val['id']] = 'Зона №' . $val['zone'].' ('.$val['text'].')'; //массив для заполнения данных в select формы
        }
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'command' => $command,
            'zone' => $zone,
        ]);
    }

    public function actionSetMode()
    {
        $model = new Command();
        if (\Yii::$app->request->isAjax) {
            if ($model->load(\Yii::$app->request->post())) {
                $device = Device::findOne($model->device);
                if (isset($device)) {
                    $msg = new \stdClass();
                    $msg->id = rand();
                    $msg->operation = 'set_mode';
                    switch ($model->mode) {
                        case "0":
                            $msg->mode = 0;
                            break;
                        case "1":
                            $msg->mode = 1;
                            break;
                        case "2":
                            $msg->mode = 2;
                            break;
                        case "3":
                            $msg->mode = 3;
                            break;
                        default:
                            $msg->mode = 0;
                            break;
                    }
                    $data = json_encode($msg);
                    $task = new Task();
                    $task->type = $device->type;
                    $task->snum = $device->snum;
                    $task->json = $data;
                    $count = Task::find(['status'=>1])->count(); //есть уже активные задания?
                    if($count)
                        $task->status = 0;
                    else
                        $task->status = 1;
                    $task->created_at = date('Y-m-d H:m:s');
                    $task->save();
                    $device->mode = $model->mode;
                    $device->save(false);
                    return 'OK';
                }
                else
                    return 'ERR';
            }
        }
    }

    public function actionSetZone(){
        $model = new Command();
        if(\Yii::$app->request->isAjax){
            if($model->load(\Yii::$app->request->post())){
                $device = Device::findOne($model->device);
                $tzone = TimeZone::findOne($model->zone);
                $msg = new \stdClass();
                $msg->id = rand();
                $msg->operation = 'set_timezone';
                $msg->zone = $tzone->zone;
                $msg->begin = $tzone->begin;
                $msg->end = $tzone->end;
                $msg->days = $tzone->days;
                $data = json_encode($msg);
                $task = new Task();
                $task->type = $device->type;
                $task->snum = $device->snum;
                $task->json = $data;
                $count = Task::find(['status'=>1])->count(); //есть уже активные задания?
                if($count)
                    $task->status = 0;
                else
                    $task->status = 1;
                $task->created_at = date('Y-m-d H:m:s');
                $task->save();
                $device->zone_id = $model->zone;
                $device->save(false);
                return 'OK';
            }
        }
    }

    public function actionClearCard(){
        if(\Yii::$app->request->isAjax){
            $id = $_POST['id'];
            $device = Device::findOne($id);
            $msg = new \stdClass();
            $msg->id = rand();
            $msg->operation = 'clear_cards';
            $data = json_encode($msg);
            $task = new Task();
            $task->type = $device->type;
            $task->snum = $device->snum;
            $task->json = $data;
            $count = Task::find(['status'=>1])->count(); //есть уже активные задания?
            if($count)
                $task->status = 0;
            else
                $task->status = 1;
            $task->created_at = date('Y-m-d H:m:s');
            $task->save();
            return 'OK';
        }
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

        if ($model->load(Yii::$app->request->post())) {
            $upload->image = UploadedFile::getInstance($upload, 'image');
            if(!empty($upload->image)) {
                $fname = $upload->upload();
                //обновляем данные картинки
                $model->image = $fname;
            }
            else
                $model->image = '/images/noimage.jpg';
            if($model->save())
                return $this->redirect(['view', 'id' => $model->id]);
        }
        else{
            $active = array('1' => 'Активный', '0' => 'Не активный');
            $mode = array('0' => 'Норма', '1' => 'Блокировка', '2' => 'Свободный проход', '3' => 'Ожидание свободного прохода', '12' => 'Не установлен');
            $zones = TimeZone::find()->select(['id', 'zone', 'text'])->asArray()->all();
            $zone = array();
            foreach ($zones as $val) {
                $zone[$val['id']] = $val['zone'].' ('.$val['text'].')'; //массив для заполнения данных в select формы
            }
            return $this->render('create', [
                'model' => $model,
                'active' => $active,
                'mode' => $mode,
                'zone' => $zone,
                'upload' => $upload,
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
            $active = array('1' => 'Активный', '0' => 'Не активный');
            $mode = array('0' => 'Норма', '1' => 'Блокировка', '2' => 'Свободный проход', '3' => 'Ожидание свободного прохода', '12' => 'Не установлен');
            $zones = TimeZone::find()->select(['id', 'zone', 'text'])->asArray()->all();
            $zone = array();
            foreach ($zones as $val) {
                $zone[$val['id']] = $val['zone'].' ('.$val['text'].')'; //массив для заполнения данных в select формы
            }
            return $this->render('update', [
                'model' => $model,
                'active' => $active,
                'mode' => $mode,
                'zone' => $zone,
                'upload' => $upload,
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
        //определяем связанные записи
        $count = Event::find()->where(['device_id'=>$id])->count();
        if($count){
            Yii::$app->session->setFlash('error', 'Удаление организации '.$this->findModel($id)->title.' не возможно! Имеются связанные записи посетителей ('.$count.')');
        }
        else{
            $this->findModel($id)->delete();
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
