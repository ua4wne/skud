<?php

namespace app\modules\admin\models;


use yii\base\Model;
use stdClass;

class Command extends Model
{
    public $device;
    public $active;
    public $online;
    public $direction;
    public $address;
    public $mode;
    public $zone;

    public function rules()
    {
        return [
            [['device'], 'required'],
            [['active', 'online', 'direction', 'mode', 'zone', 'device'], 'integer'],
            [['address'], 'string', 'max' => 15],
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
}
