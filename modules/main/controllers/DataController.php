<?php

namespace app\modules\main\controllers;

use app\models\LibraryModel;
use app\modules\admin\models\Device;
use app\modules\main\models\Card;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

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

    public function actionIndex()
    {
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if(Yii::$app->request->isPost){
            # Получить JSON как строку
            $json_str = file_get_contents('php://input');
            # Получить объект
            $json = json_decode($json_str);
            $type = $json->type;
            $sn = $json->sn;
            $messages = $json->messages;
            foreach ($messages as $msg){
                if($msg->operation == 'power_on'){ //POWER_ON
                    return $this->power_on($type,$sn,$msg);
                }
                if($msg->operation == 'check_access'){ //CHECK_ACCESS
                    return $this->check_access($type,$sn,$msg);
                }
            }

            /*$file = './download/data.txt';

            if ( !file_exists( $file ) ) { // если файл НЕ существует
                $fp = fopen ($file, "w");
                // Добавляем новую запись в файл
                $current = $json_str."\n";
                fwrite($fp,$current);
                fclose($fp);
            } else {
                $current = file_get_contents($file);
                // Добавляем новую запись в файл
                $current .= $json_str."\n";
                // Пишем содержимое обратно в файл
                file_put_contents($file, $current);
            }*/
        }
        return true;
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
            $model->image = '/images/noimage.jpeg';
            $model->save();
            //запись в лог
            $msg = 'Новый контроллер <strong>'. $model->type .' (sn '. $model->snum .')</strong> добавлен в систему '.date('d-m-Y H:i:s');
            LibraryModel::AddEventLog('power_on',$msg);
        }
        else{
            $device->fware = $msg->fw;
            $device->conn_fw = $msg->conn_fw;
            $device->is_active = $msg->active;
            $device->mode = $msg->mode;
            $device->address = $ip;
            $device->save();
            //запись в лог
            $msg = 'Данные контроллера <strong>'. $device->type .' (sn '. $device->snum .')</strong> были обновлены '.date('d-m-Y H:i:s');
            LibraryModel::AddEventLog('power_on',$msg);
        }
        //SET_ACTIVE
        $send = ['date'=>date('Y-m-d H:i:s'),'interval'=>10,'messages' => ['id'=>10,'operation'=>'set_active','active'=>1,'online'=>1]];
        return $send;
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

}
