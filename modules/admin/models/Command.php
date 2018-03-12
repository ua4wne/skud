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

    public function rules()
    {
        return [
            [['device'], 'required'],
            [['active', 'online', 'direction', 'mode', 'zone', 'device'], 'integer'],
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
        $device = Device::findOne($id);
        $device->zone_id = $this->zone;
        $device->save(false);
        $tzone = TimeZone::findOne($this->zone);
        $host = $device->address;

        if(!empty($host)){
            $host = 'http://'.$host;
            $id = rand();
            $data = $this->SetHeader();
            $data['messages'] = ['id'=>$id,'operation'=>'set_timezone','zone'=>$tzone->zone,'begin'=>$tzone->begin,'end'=>$tzone->end,'days'=>$tzone->days];
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