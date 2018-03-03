<?php

namespace app\modules\main\controllers;

use app\modules\main\models\Config;
use app\modules\main\models\Device;
use app\modules\main\models\Option;
use app\modules\main\models\Outbox;
use app\modules\main\models\Rule;
use app\modules\main\models\Sms;
use app\modules\main\models\Syslog;
use Yii;

class ControlController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'controllers' => ['main/control'],
                    ],
                ]
            ],
        ];
    }
//http://smart/control?device=70ed8b0cc057d9dd&celsio[123erf456hrthhh]=10.10&celsio[143erf456hrthhf]=50.10
    public function actionIndex() //http://smart/control?device=70ed8b0cc057d9dd&celsio[one]=14.50&celsio[two]=51.00$addr[one]=402552162517723541$addr[two]=4025522324177235247
    {
        $uid = $_GET['device']; //выделяем UID устройства из запроса и смотрим, есть ли такой в базе
        $device = Device::findOne(['uid'=>$uid]);
        if(!empty($device)){ //есть такое устройство, парсим дальше, выбираем остальные параметры
            //return print_r($_GET);
            foreach($_GET as $key=>$value){
                if($key!='device'){
                    if(is_array($value)){ //если массив параметров
                        //return print_r($value);
                        foreach($value as $k=>$param){
                            $this->CheckParam($device,$key,$param,$k);
                        }
                    }
                    else {
                        $this->CheckParam($device,$key,$value);
                    }

                }
            }
        }
        return true;
        //return $this->render('index');
    }

    private function CheckParam(Device $device,$key,$value,$address=null){
        if(empty($address))
            $option = Option::findOne(['device_id'=>$device->id,'alias'=>$key]);
        else
            $option = Option::findOne(['device_id'=>$device->id,'address'=>$address,'alias'=>$key]);
        if(empty($option)) return; //если не найден объект - выход
        $oldval = $option->val;
        if($oldval != $value){
            $option->val = $value;
            $option->save();
            //проверяем на вхождение в диапазон min - max
            if($value < $option->min_val){
                //запись в лог
                $syslog = new Syslog();
                $syslog->created_at = date('Y-m-d H:i:s');
                $syslog->type = 'error';
                $syslog->msg = 'Значение параметра <strong>'. $option->name . ' (' . $option->device->name . ')</strong>  меньше минимально возможного! <span class="red">value=' . $value . ' min_value=' . $option->min_val . '</span>';
                $syslog->save();
            }
            if($value > $option->max_val){
                //запись в лог
                $syslog = new Syslog();
                $syslog->created_at = date('Y-m-d H:i:s');
                $syslog->type = 'error';
                $syslog->msg = 'Значение параметра <strong>'. $option->name . ' (' . $option->device->name . ')</strong>  больше максимально возможного! <span class="red">value=' . $value . ' max_value=' . $option->max_val . '</span>';
                $syslog->save();
            }
            //ищем связанные правила
            $rcount = Rule::find()->where(['option_id'=>$option->id])->count();
            if($rcount) {
                $location = $device->location->name;
                $this->CheckRules($option,$location); //проверяем связанные правила
            }
        }
    }

    private function CheckRules(Option $model, $location){
        $time_stamp = strtotime(date('Y-m-d H:i:s')); //получаем текущую метку времени
        //определяем получателей почты
        $resp = Config::findOne(['param'=>'CONTROL_E_MAIL'])->val;
        $resipients = explode(",",$resp);
        $ph = Config::findOne(['param'=>'CONTROL_PHONE'])->val;
        $phones = explode(",",$ph);
        //ищем связанные правила
        $rules = Rule::find()->where(['option_id'=>$model->id])->all();
        foreach ($rules as $rule){
            if(!empty($rule->runtime))
                $runtime = strtotime($rule->runtime); //получаем метку времени старта правила
            if($time_stamp < $runtime) continue; //время еще не вышло, пропуск правила
            if(($model->val > $rule->val) && $rule->condition == 'more'){
                if($rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        $this->SendMail($resipient, $msg);
                    }
                }
                if($rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        $this->SendSms($phone,$msg);
                    }
                }
                if($rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    $this->RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
            elseif(($model->val < $rule->val) && $rule->condition == 'less'){
                if($rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        $this->SendMail($resipient, $msg);
                    }
                }
                if($rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        $this->SendSms($phone,$msg);
                    }
                }
                if($rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    $this->RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
            elseif(($rule->val == $model->val) && $rule->condition == 'equ'){
                if($rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        $this->SendMail($resipient, $msg);
                    }
                }
                if($rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        $this->SendSms($phone,$msg);
                    }
                }
                if($rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    $this->RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
            elseif(($rule->val != $model->val) && $rule->condition == 'not'){
                if($rule->action == 'mail'){
                    foreach ($resipients as $resipient){
                        $msg = str_replace("#LOCATION#",$location,$rule->text);
                        $msg = str_replace("#VAL#",$model->val . $model->unit,$msg);
                        $this->SendMail($resipient, $msg);
                    }
                }
                if($rule->action == 'sms'){
                    foreach ($phones as $phone){
                        $msg = str_replace('#LOCATION#',$location,$rule->text);
                        $msg = str_replace('#VAL#',$model->val . $model->unit,$msg);
                        $this->SendSms($phone,$msg);
                    }
                }
                if($rule->action == 'cmd'){
                    $cmd = $rule->text;
                    $id_rule = $rule->id;
                    $this->RunCmd($cmd,$id_rule);
                }
                $runtime = $time_stamp + $rule->step;
                $rule->runtime = date( 'Y-m-d H:i:s' , $runtime);
                $rule->save();
            }
        }
    }

    private function SendMail($to,$msg){
        $result = Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($to)
            ->setSubject(Yii::$app->name)
            ->setTextBody($msg)
            ->setHtmlBody($msg)
            ->send();
        //запись в лог
        $syslog = new Syslog();
        if($result){
            $syslog->msg = $msg;
            $syslog->type = 'email';
        }
        else{
            $syslog->type = 'error';
            $syslog->msg = 'Возникла ошибка при отправке системного сообщения адресату <strong>'. $to .'</strong>';
        }
        $syslog->from = Yii::$app->params['adminEmail'];
        $syslog->to = $to;
        $syslog->is_new = 1;
        $syslog->created_at = date('Y-m-d H:i:s');
        $syslog->save();
    }

    private function SendSms($to,$msg){
        $model = new Sms();
        $model->phone = $to;
        $model->message = $msg;
        $cost = $model->GetCost();
        if($cost>0){
            $model->SendViaMail();
        }
        $model->SendSms();
        //запись в лог
        $syslog = new Syslog();
        $syslog->from = 'Система';
        $syslog->to = $to;
        $syslog->is_new = 1;
        $syslog->created_at = date('Y-m-d H:i:s');
        $syslog->type = 'sms';
        $syslog->msg = 'Адресату <strong>'. $to .'</strong> было отправлено СМС. Стоимость отправки - ' . $cost . ' руб.';
        $syslog->save();
    }

    private function RunCmd($cmd,$rule){
        //запись в лог
        $syslog = new Syslog();
        $syslog->from = 'Система';
        $syslog->to = 'shell';
        $syslog->is_new = 1;
        $syslog->created_at = date('Y-m-d H:i:s');
        $syslog->type = 'exec';
        $syslog->msg = 'Запуск команды <strong>'. $cmd . '</strong> <a href="/main/rule/'.$rule.'">по правилу</a>';
        $syslog->save();
    }
}
