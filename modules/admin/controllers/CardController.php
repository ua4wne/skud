<?php

namespace app\modules\admin\controllers;

use app\modules\admin\models\Device;
use app\modules\main\models\Visitor;
use Yii;
use app\modules\main\models\Card;
use app\modules\admin\models\Task;
use app\modules\main\models\SearchCard;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CardController implements the CRUD actions for Card model.
 */
class CardController extends Controller
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
     * Lists all Card models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchCard();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Card model.
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
     * Creates a new Card model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Card();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $device = Device::findOne($model->device);
            //формируем команду для записи карты в контроллер
            $msg = new \stdClass();
            $msg->id = rand();
            $msg->operation = 'add_cards';
            $msg->cards = new \stdClass();
            $msg->cards->card = strtoupper(dechex($model->code));
            if(empty($model->flags))
                $msg->cards->flags = 0;
            else
                $msg->cards->flags = $model->flags;
            $msg->cards->tz = $model->zone;
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
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $devices = Device::find()->select(['id','type','text'])->asArray()->all();
        $data = array();
        foreach ($devices as $val) {
            $data[$val['id']] = $val['type'].' ('.$val['text'].')'; //массив для заполнения данных в select формы
        }
        return $this->render('create', [
            'model' => $model,
            'data' => $data,
        ]);
    }

    /**
     * Updates an existing Card model.
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
        $devices = Device::find()->select(['id','type','text'])->asArray()->all();
        $data = array();
        foreach ($devices as $val) {
            $data[$val['id']] = $val['type'].' ('.$val['text'].')'; //массив для заполнения данных в select формы
        }
        return $this->render('update', [
            'model' => $model,
            'data' => $data,
        ]);
    }

    /**
     * Deletes an existing Card model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        //проверяем наличие связанных записей
        $visitor = Visitor::findOne(['card_id'=>$id]);
        if(empty($visitor)){
            //формируем команду на удаление карты из контроллеров
            $device = Device::find()->select(['type','snum'])->all();
            foreach ($device as $val){
                //формируем команду для удаления карты из контроллеров
                $msg = new \stdClass();
                $msg->id = rand();
                $msg->operation = 'del_cards';
                $msg->cards = array();
                $msg->cards[0] = strtoupper(dechex($this->findModel($id)->code)); //символы в верхний регистр
                $data = json_encode($msg);
                $task = new Task();
                $task->type = $val->type;
                $task->snum = $val->snum;
                $task->json = $data;
                $count = Task::find(['status'=>1])->count(); //есть уже активные задания?
                if($count)
                    $task->status = 0;
                else
                    $task->status = 1;
                $task->created_at = date('Y-m-d H:m:s');
                $task->save();
            }
            $this->findModel($id)->delete();
        }
        else{
            Yii::$app->session->setFlash('error', 'Удаление карты '.$this->findModel($id)->code.' не возможно! Карта привязана к пользователю с ID = '.$visitor->id);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Card model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Card the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Card::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
