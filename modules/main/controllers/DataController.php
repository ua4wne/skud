<?php

namespace app\modules\main\controllers;

use app\modules\admin\models\Device;
use app\modules\admin\models\Task;
use app\modules\main\models\Card;
use app\modules\main\models\Event;
use app\modules\main\models\Visitor;
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
//        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
       // $session = Yii::$app->session;
        //Получить JSON как строку
        $json_str = file_get_contents('php://input');

//        $file = './download/data.txt';
        // The new person to add to the file
    //            $data = $json_str."\n";
        // Write the contents to the file,
        // using the FILE_APPEND flag to append the content to the end of the file
        // and the LOCK_EX flag to prevent anyone else writing to the file at the same time
  //      file_put_contents($file, $data, FILE_APPEND | LOCK_EX);

        //$json_str = '{"type":"Z5RWEB","sn":44374,"messages":[{ "id":112805732, "success":1},{"id":1120048829,"operation":"events","events":[{"flag": 0,"event": 4,"time": "2018-06-20 09:50:37","card": "00000029CF67"},{"flag": 0,"event": 16,"time": "2018-06-20 09:50:37","card": "00000029CF67"}]}]}';
        //$json_str = '{"type":"Z5RWEB","sn":44374,"messages":[{ "id":511702305, "success":1},{"id":2084420925,"operation":"events","events":[{"flag": 0,"event": 2,"time": "2018-06-18 16:24:49","card": "00000029780F"},{"flag": 0,"event": 2,"time": "2018-06-19 00:56:46","card": "000000297D13"},{"flag": 0,"event": 2,"time": "2018-06-19 00:56:47","card": "000000297D13"},{"flag": 0,"event": 2,"time": "2018-06-19 00:56:50","card": "000000297D13"},{"flag": 0,"event": 2,"time": "2018-06-19 08:15:11","card": "000000164A84"},{"flag": 0,"event": 2,"time": "2018-06-19 08:15:15","card": "000000164A84"},{"flag": 0,"event": 2,"time": "2018-06-19 08:25:54","card": "00000029697E"},{"flag": 0,"event": 2,"time": "2018-06-19 08:29:52","card": "00000029BD87"},{"flag": 0,"event": 4,"time": "2018-06-19 10:48:30","card": "00000014DEE7"},{"flag": 0,"event": 16,"time": "2018-06-19 10:48:30","card": "00000014DEE7"},{"flag": 0,"event": 5,"time": "2018-06-19 10:48:34","card": "00000014DEE7"},{"flag": 0,"event": 17,"time": "2018-06-19 10:48:34","card": "00000014DEE7"}]}]}';
        //$json_str = ' {"type":"Z5RWEB","sn":44374,"messages":[{"id":1869470124,"operation":"events","events":[{"flag": 0,"event": 4,"time": "2018-06-08 14:26:22","card": "00000067BDC3"}]}]}';
        //$json_str = '{"type":"Z5RWEB","sn":44374,"messages":[{"id":631704567,"operation":"power_on","fw":"3.23","conn_fw":"1.0.123","active":0,"mode":0,"controller_ip":"192.168.8.9"}]}';
        //$json_str = '{"type":"Z5RWEB","sn":44374,"messages":[{"id":1856669179,"operation":"ping","active":1,"mode":0}]}';
        //$json_str = '{"type":"Z5RWEB","sn":44374,"messages":[{"id":719885386,"operation":"events","events":[{"flag": 0,"event": 17,"time": "2018-04-15 20:25:34","card": "0000002982C6"}]}]}';
        //$json_str = '{"type":"Z5RWEB","sn":44374,"messages":[{ "id":358931379, "success":1},{"id":663594565,"operation":"events","events":[{"flag": 264,"event": 37,"time": "2018-04-10 10:49:12","card": "000000000000"}]}]}';
            //Получить объект
        $json = json_decode($json_str);

        //проверяем успешность выполнения последнего задания
        $pos = strpos($json_str, '"success":1');
        if($pos){
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
        }

        if(!empty($json)) {
            //запись в лог
//            $log = 'Сообщение от контроллера ' . $json_str;
//            LibraryModel::AddTraceLog('request', $log);

            $sn = $json->sn; //серийный номер контроллера Z5R-WEB
            $type = $json->type; //тип контроллера Z5R-WEB
            foreach($json->messages as $message){
//return print_r($message);
		if(isset($message->operation)){
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
                    //проверяем нет ли сформированных команд контроллеру
                    $task = Task::findOne(['status'=>1]);
                    if(empty($task)){
                        //$msg=null;
                    }
                    else{
                        $msg = json_decode($task->json);
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
                        //смотрим привязку карты к посетителю
                        $visitor = Visitor::findOne(['card'=>$model->card]);
                        if(!empty($visitor)){
                            $model->visitor_id = $visitor->id;
                        }

                        if(isset($item->flag))
                            $model->flag = $item->flag;
                        $model->event_time = $item->time;
                        $model->created_at = date('Y-m-d H:i:s');
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
            }
        }
            //преобразование и отправка сообщения контроллеру
            $send = new stdClass();
            $send->date = date('Y-m-d H:i:s');
            $send->interval = 10;
            if(isset($msg))
                $send->messages[0] = $msg;
            else
                $send->messages = array();
            $data = json_encode($send);
            //запись в лог
//file_put_contents($file,$data."\n",FILE_APPEND | LOCK_EX);
//            $log = 'Ответ от сервера ' . $data;
//            LibraryModel::AddTraceLog('response', $log);
            return $data;
        }
    }

    private function power_on($type,$sn,$msg){
        //$ip = LibraryModel::GetRealIp();
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
            $model->address = $msg->controller_ip;
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
            $device->address = $msg->controller_ip;
            $device->save(false);
            //запись в лог
            //$log = 'Данные контроллера <strong>'. $device->type .' (sn '. $device->snum .')</strong> были обновлены '.date('d-m-Y H:i:s');
            //LibraryModel::AddEventLog('power_on',$log);
        }
        return true;
    }

}
