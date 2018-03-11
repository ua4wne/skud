<?php

namespace app\modules\admin\models;


use yii\base\Model;

class Command extends Model
{
    public $device;
    public $active;
    public $online;
    public $direction;
    public $mode;
    public $zone;
    public $begin;
    public $end;
    public $days;

    public function rules()
    {
        return [
            [['device'], 'required'],
            [['active', 'online', 'direction', 'mode', 'zone', 'device'], 'integer'],
            [['begin', 'end'], 'string', 'max' => 5],
            [['days'], 'string', 'max' => 8],
        ];
    }

    public function attributeLabels()
    {
        return [
            'device' => 'ID контроллера',
            'active' => 'Активация контроллера',
            'online' => 'Режим Online',
            'direction' => 'Направление',
            'mode' => 'Режим работы',
            'zone' => 'Временная зона',
            'begin' => 'Время начала действия',
            'end' => 'Время окончания действия',
            'days' => 'Дни недели'
        ];
    }

    public function SetActive($id){
        $host = Device::findOne($id)->address;
        if(!empty($host)){
            $host = 'http://'.$host;
            $id = rand();
            $data = $this->SetHeader();
            $data['messages'] = ['id'=>$id,'operation'=>'set_active','active'=>$this->active,'online'=>$this->online];
            return $this->SendCURL($data,$host);
        }
        else{
            return 'NOT';
        }
    }

    public function SetMode($id){
        $host = Device::findOne($id)->address;
        if(!empty($host)){
            $host = 'http://'.$host;
            $id = rand();
            $data = $this->SetHeader();
            $data['messages'] = ['id'=>$id,'operation'=>'set_mode','mode'=>$this->mode];
            return $this->SendCURL($data,$host);
        }
        else{
            return 'NOT';
        }
    }

    public function SetTimeZone($id){
        $host = Device::findOne($id)->address;
        $templ = array(0,0,0,0,0,0,0);
        $mask = '';
        foreach($this->days as $val){
            $templ[$val] = 1;
        }
        foreach ($templ as $tmp){
            $mask.=$tmp;
        }
        if(!empty($host)){
            $host = 'http://'.$host;
            $id = rand();
            $data = $this->SetHeader();
            $data['messages'] = ['id'=>$id,'operation'=>'set_timezone','zone'=>$this->zone,'begin'=>$this->begin,'end'=>$this->end,'days'=>$mask];
            return $this->SendCURL($data,$host);
        }
        else{
            return 'NOT';
        }
    }

    private function SetHeader(){
        $header = array('date'=>date('Y-m-d H:i:s'),'interval'=>10,'messages'=>' ');
        return $header;
    }

    private function SendCURL($data,$host){
        $data_string = json_encode($data);
        return $data_string;
        $ch = curl_init($host);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}