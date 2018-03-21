<?php

namespace app\modules\main\controllers;

use app\models\LibraryModel;
use app\modules\admin\models\Device;
use app\modules\main\models\Card;
use app\modules\main\models\Event;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use Yii;
use stdClass;
use yii\web\Response;

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
  //      $json_str = file_get_contents('php://input');
        //$json_str = '{"type":"Z5RWEB","sn":44374,"messages":[{"id":1856669179,"operation":"power_on","fw":"a.a","conn_fw":"1.0.120","active":1,"mode":0}]}';
        $json_str = '{"type":"Z5RWEB","sn":44374,"messages":[{"id":1856669179,"operation":"ping","active":1,"mode":0}]}';
        //Получить объект
        $json = json_decode($json_str);

        //$session['z5j'] = json_decode(file_get_contents("php://input"));
        if(!empty($json)) {
            //запись в лог
            $log = 'Сообщение от контроллера <strong>' . $json_str . '</strong>';
            LibraryModel::AddTraceLog('request', $log);

            $sn = $json->sn; //серийный номер контроллера Z5R-WEB
            $type = $json->type; //тип контроллера Z5R-WEB
            //активация контроллера
            if ($json->messages[0]->operation == "power_on") {
                $this->power_on($type, $sn, $json->messages[0]);
                $msg = new stdClass();
                $msg->id = $json->messages[0]->id;
                $msg->operation = "set_active";
                $msg->active = 1;
                $msg->online = 1;
            }

            //пинг
            if ($json->messages[0]->operation == "ping") {
                //если запросов нет то готовим пустое сообщение
                $msg = null;
            }

            //преобразование и отправка сообщения контроллеру
            $send = new stdClass();
            $send->date = date('Y-m-d H:i:s');
            $send->interval = 8;
            if(isset($msg))
                $send->messages[0] = $msg;
            else
                $send->messages = $msg;
            //$send = ['date'=>date('Y-m-d H:i:s'),'interval'=>10,'messages' => ['id'=>10,'operation'=>'set_active','active'=>1,'online'=>1]];
            $data = json_encode($send);
            //запись в лог
            $log = 'Ответ от сервера <strong>' . $data . '</strong>';
            LibraryModel::AddTraceLog('response', $log);

            //echo json_encode($session['z3']->$sn);
            //header('Access-Control-Allow-Origin: *');
            //header('Content-Type: application/json');
            return $data;
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

    private function check_access($type,$sn,$msg){
        //проверяем наличие контроллера в базе
        $device = Device::findOne(['type'=>$type,'snum'=>$sn]);
        if(empty($device)){
            //нет такого контроллера
            $send = ['date'=>date('Y-m-d H:i:s'),'interval'=>10,'messages' => ['id'=>1,'operation'=>'check_access','granted'=>0]];
        }
        else{ //есть такой контроллер
            $card = Card::findOne(['code'=>$msg->card]);
            if(empty($card)){
                //нет такой карты в системе
                $send = ['date'=>date('Y-m-d H:i:s'),'interval'=>10,'messages' => ['id'=>1,'operation'=>'check_access','granted'=>0]];
            }
            else{
                //TODO проверить таймзону
                if($card->granted){
                    $send = ['date'=>date('Y-m-d H:i:s'),'interval'=>10,'messages' => ['id'=>1,'operation'=>'check_access','granted'=>1]];
                }
                else{
                    $send = ['date'=>date('Y-m-d H:i:s'),'interval'=>10,'messages' => ['id'=>1,'operation'=>'check_access','granted'=>0]];
                }
            }
        }
        return $send;
    }

    private function events($type,$sn,$msg){
        //проверяем наличие контроллера в базе
        $device = Device::findOne(['type'=>$type,'snum'=>$sn]);
        $events = $msg->events;
        $count = 0;
        if(empty($device)){
            //нет такого контроллера
            //имитируем успешное прочтение, чтобы не авторизованный контроллер не долбал своими пакетами
            foreach ($events as $val){
                $count++;
            }
        }
        else{
            //обрабатываем события контроллера
            foreach ($events as $val){
                //есть такая карта?
                $card = Card::findOne(['code'=>$val->card])->id;
                if(empty($card)){ //нет такой карты - игнорируем ее
                    $count++;
                }
                else{
                    $event = Event::findOne(['event_type'=>$val->event, 'card_id'=>$card, 'event_time'=>$val->time]);
                    if(empty($event)){
                        //нет такого события в журнале
                        $model = new Event();
                        $model->device_id = $device->id;
                        $model->event_type = $val->event;
                        $model->card_id = $card;
                        $model->flag = $val->flag;
                        $model->event_time = $val->time;
                        $model->save();
                    }
                    $count++;
                }
            }
        }
        $send = ['date'=>date('Y-m-d H:i:s'),'interval'=>10,'messages' => ['id'=>100,'operation'=>'events','events_success'=>$count]];
        return $send;
    }
}
