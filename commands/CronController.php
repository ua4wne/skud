<?php

namespace app\commands;
use app\models\Weather;
use app\modules\main\models\Logger;
use app\modules\main\models\Option;
use yii\console\Controller;
use app\modules\main\models\Config;
use app\modules\main\models\Syslog;
use Yii;

class CronController extends Controller
{
    //получение XML-файла с данными погоды
    public function actionGetWeather()
    {
        $api_key = Config::findOne(['param'=>'API_KEY_FORECAST'])->val;
        $city_id = Config::findOne(['param'=>'CITY_ID'])->val;
        $geoloc = Config::findOne(['param'=>'USE_GEOLOCATION'])->val;
        if(isset($api_key)&&isset($city_id)&&isset($geoloc)){
            $model = new Weather($api_key,$city_id,$geoloc);
            $model->Forecast();
        }
        else
            echo 'Error config';
    }

    //запись переменных в лог
    public function actionLogger(){
        //находим переменные, значения которых необходимо писать в лог
        $options = Option::find()->select(['id','val'])->where(['to_log'=>1])->all();
        foreach ($options as $option){
            $date = date('Y-m-d H:i:s');
            $new = $option->val;
            //находим последнее значение переменной в логе
            $logger = Logger::find()->where(['option_id'=>$option->id])->orderBy(['created_at' => SORT_DESC])->limit(1)->all();
            foreach ($logger as $log){
                $old = $log->val;
            }
            if(empty($logger)){
                //еще нет записей в логе, это первая
                $newlog = new Logger();
                $newlog->option_id = $option->id;
                $newlog->val = $option->val;
                $newlog->created_at = $date;
                $newlog->updated_at = $date;
                $newlog->save();
            }
            else{
                if($old != $new){
                    //значение параметра отличается от последнего сохраненного
                    $newlog = new Logger();
                    $newlog->option_id = $option->id;
                    $newlog->val = $option->val;
                    $newlog->created_at = $date;
                    $newlog->updated_at = $date;
                    $newlog->save();
                }
            }
        }
    }

    //запускать при старте системы
    public function actionStart(){
        $msg = 'Система была запущена ' . date("Y-m-d H:i:s");
        $to = Yii::$app->params['adminEmail'];
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

    //ежедневный отчет о работе
    public function actionSysState(){
        //memory stat
        //$stat['mem_percent'] = shell_exec("cat /proc/meminfo | grep MemFree");
        $name = strtolower(php_uname('s'));
        if (strpos($name, 'windows') !== FALSE) {
            //hdd stat
            $stat['hdd_free'] = round(disk_free_space("/") / 1024 / 1024 / 1024, 2);
            $stat['hdd_total'] = round(disk_total_space("/") / 1024 / 1024/ 1024, 2);
            $stat['hdd_used'] = $stat['hdd_total'] - $stat['hdd_free'];
            $stat['hdd_percent'] = round(sprintf('%.2f',($stat['hdd_used'] / $stat['hdd_total']) * 100), 0);
            $content ='<tr><td>RAM</td><td>'.$stat['mem_used'].'</td><td>'.$stat['mem_total'].'</td><td>'.$stat['mem_percent'].'</td></tr>';
            $content .='<tr><td>HDD</td><td>'.$stat['hdd_used'].'</td><td>'.$stat['hdd_free'].'</td><td>'.$stat['hdd_percent'].'</td></tr>';
        } elseif (strpos($name, 'linux') !== FALSE) {
            $mem_result = shell_exec("cat /proc/meminfo | grep MemTotal");
            $stat['mem_total'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
            $mem_result = shell_exec("cat /proc/meminfo | grep MemFree");
            $stat['mem_free'] = round(preg_replace("#[^0-9]+(?:\.[0-9]*)?#", "", $mem_result) / 1024 / 1024, 3);
            $stat['mem_used'] = $stat['mem_total'] - $stat['mem_free'];
            $stat['mem_percent'] = round(sprintf('%.2f',($stat['mem_used']/$stat['mem_total']) * 100), 0);
            $content ='<tr><td>RAM</td><td>'.$stat['mem_used'].'</td><td>'.$stat['mem_total'].'</td><td>'.$stat['mem_percent'].'</td></tr>';
            //hdd stat
            $stat['hdd_free'] = round(disk_free_space("/") / 1024 / 1024 / 1024, 2);
            $stat['hdd_total'] = round(disk_total_space("/") / 1024 / 1024/ 1024, 2);
            $stat['hdd_used'] = $stat['hdd_total'] - $stat['hdd_free'];
            $stat['hdd_percent'] = round(sprintf('%.2f',($stat['hdd_used'] / $stat['hdd_total']) * 100), 0);
            $content .='<tr><td>HDD</td><td>'.$stat['hdd_used'].'</td><td>'.$stat['hdd_total'].'</td><td>'.$stat['hdd_percent'].'</td></tr>';
            $load = round(array_sum(sys_getloadavg()) / count(sys_getloadavg()), 2);
        }

        $uptime = shell_exec('uptime -p');
        $uptime = str_replace('up','',$uptime);
        $uptime = str_replace('days','d',$uptime);
        $uptime = str_replace('hours','h',$uptime);
        $uptime = str_replace('minutes','m',$uptime);

        $to = Yii::$app->params['adminEmail'];
        $params = [];
        \Yii::$app->mailer->getView()->params['uptime'] = $uptime;
        \Yii::$app->mailer->getView()->params['upload'] = $load;
        \Yii::$app->mailer->getView()->params['table'] = $content;
        $subject = 'Состояние системы на  ' . date('Y-m-d H:i:s');
        $result = \Yii::$app->mailer->compose([
            'html' => 'views/html_state',
            //'text' => 'views/text_mail',
        ], $params)
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($to)
            ->setSubject($subject)
            ->send();
        if(!$result){
            //запись в лог
            $syslog = new Syslog();
            $syslog->type = 'error';
            $syslog->msg = 'Возникла ошибка при отправке ежедневного сообщения о состоянии системы адресату <strong>'. $to .'</strong>';
            $syslog->from = Yii::$app->params['adminEmail'];
            $syslog->to = $to;
            $syslog->is_new = 1;
            $syslog->created_at = date('Y-m-d H:i:s');
            $syslog->save();
        }
    }

}
