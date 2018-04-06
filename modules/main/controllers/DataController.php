<?php

namespace app\modules\main\controllers;

use app\models\LibraryModel;
use app\modules\admin\models\Device;
use app\modules\admin\models\Task;
use app\modules\main\models\Card;
use app\modules\main\models\Event;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;
use stdClass;

class DataController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'controllers' => ['main/data'],
                    ],
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function beforeAction($action) //отключаем обязательную проверку csrf иначе не работает post от контроллера
    {
        if ($action->id == 'index') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }

    public function actionIndex()
    {
     //   \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$session = Yii::$app->session;
        //Получить JSON как строку
        //$json_str = file_get_contents('php://input');
        $json_str = '{"type":"Z5RWEB","sn":44374,"messages":[{"id":1138095455,"operation":"power_on","fw":"a.a","conn_fw":"1.0.121","active":0,"mode":12}]}';
        //$json_str = '{"type":"Z5RWEB","sn":44374,"messages":[{"id":1856669179,"operation":"ping","active":1,"mode":0}]}';
        //$json_str = '{"type":"Z5RWEB","sn":44374,"messages":[{"id":1856669179,"success":1}]}';
        //$json_str = '{"type":"Z5RWEB","sn":44374,"messages":[{"id":719885386,"operation":"events","events":[{"flag": 264,"event": 37,"time": "2018-03-27 17:25:34","card": "000000000000"}]}]}';
        //$json_str = '{"type":"Z5RWEB","sn":44374,"messages":[{ "id":846930886, "success":1},{"id":1681692777,"operation":"events","events":[{"flag": 264,"event": 37,"time": "2018-03-27 17:25:32","card": "000000000000"}]}]}';
            //Получить объект
        $json = json_decode($json_str);

        if(!empty($json)) {
            //запись в лог
    //        $log = 'Сообщение от контроллера ' . $json_str;
    //        LibraryModel::AddTraceLog('request', $log);

            $sn = $json->sn; //серийный номер контроллера Z5R-WEB
            $type = $json->type; //тип контроллера Z5R-WEB
            foreach($json->messages as $message){
                //активация контроллера
                if ($message->operation == "power_on") {
                    $this->power_on($type, $sn, $message);
                    $msg = new stdClass();
                    $msg->id = $message->id;
                    $msg->operation = "set_active";
                    $msg->active = 1;
                    $msg->online = 1;
                }

                //пинг
                if ($message->operation == "ping") {

                    //проверяем mode
                    $mode = Device::findOne(['type'=>$type,'snum'=>$sn])->mode;
                    if($message->mode != $mode) {
                        $msg = new \stdClass();
                        $msg->id = $message->id;
                        $msg->operation = 'set_mode';
                        $msg->mode = $mode;
                    }
                    else {
                        //проверяем нет ли сформированных команд контроллеру
                        $task = Task::findOne(['status'=>1]);
                        if(empty($task)){
                            $msg = null;
                        }
                        else{
                            $msg = json_decode($task->json);
                        }
                    }
                }

                //events
                if ($message->operation == "events") {
                    $event_success = 0;
                    $device_id = Device::findOne(['type'=>$type,'snum'=>$sn])->id;
                    //обрабатываем каждое событие
                    foreach($message->events as $item){
                        $model = new Event();
                        $model->device_id = $device_id;
                        $model->event_type = $item->event;
                        $model->card = hexdec($item->card);
                        if(isset($item->flag))
                            $model->flag = $item->flag;
                        $model->event_time = $item->time;
                        $model->created_at = date('Y-m-d H:m:s');
                        if($item->card != '000000000000')
                            $model->save(false);
                        $event_success++;
                    }
                    $msg = new \stdClass();
                    $msg->id = $message->id;
                    $msg->operation = 'events';
                    $msg->events_success = $event_success;
                }

                //проверка доступа
                if ($message->operation == "check_access") {
                    $card = hexdec($message->card);
                    //есть такая карта в базе?
                    $model = Card::findOne(['code'=>$card]);
                    $msg = new \stdClass();
                    $msg->id = $message->id;
                    $msg->operation = 'check_access';
                    if(empty($model))
                        $msg->granted = 1; //проход запрещен
                    else
                        $msg->granted = $model->granted;

                }

                //success = 1
                if(isset($message->success)){
                    if ($message->success == 1) {
                        //удаляем активную команду контроллеру и ставим следующую, если есть
                        $active = Task::findOne(['status'=>1]);
                        if(!empty($active))
                            $active->delete();
                        $count = Task::find()->where(['status'=>0])->count();
                        if($count){
                            $next = Task::findOne(['status'=>0]);
                            $next->status = 1;
                            $next->save(false);
                        }
                        $msg = null;
                    }
                }
            }

            //преобразование и отправка сообщения контроллеру
            $send = new stdClass();
            $send->date = date('Y-m-d H:i:s');
            $send->interval = 10;
            if(isset($msg))
                $send->messages[0] = $msg;
            else
                $send->messages = $msg;
            $data = json_encode($send);
            //запись в лог
        //    $log = 'Ответ от сервера ' . $data;
        //    LibraryModel::AddTraceLog('response', $log);
            header('Content-type: application/json');
            header("Connection: Keep-Alive");

            echo $data;
            die;
        }
    }

    private function power_on($type,$sn,$msg){
        $ip = LibraryModel::GetRealIp();
        //проверяем наличие контроллера в базе
        $device = Device::findOne(['type'=>$type,'snum'=>$sn]);
        if(empty($device)){ //новая запись
            $model = new Device();
            $model->type = $type;
            $model->snum = $sn;
            $model->fware = $msg->fw;
            $model->conn_fw = $msg->conn_fw;
            $model->is_active = $msg->active;
            $model->mode = $msg->mode;
            $model->address = $ip;
            $model->image = '/images/noimage.jpg';
            $model->zone_id = 1;
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');
            $model->save(false); //отключаем валидацию
            //запись в лог
            //$log = 'Новый контроллер <strong>'. $model->type .' (sn '. $model->snum .')</strong> добавлен в систему '.date('d-m-Y H:i:s');
            //LibraryModel::AddEventLog('power_on',$log);
        }
        else{
            $device->fware = $msg->fw;
            $device->conn_fw = $msg->conn_fw;
            $device->is_active = $msg->active;
            $device->mode = $msg->mode;
            $device->address = $ip;
            $device->save(false);
            //запись в лог
            //$log = 'Данные контроллера <strong>'. $device->type .' (sn '. $device->snum .')</strong> были обновлены '.date('d-m-Y H:i:s');
            //LibraryModel::AddEventLog('power_on',$log);
        }
        return true;
    }

}
