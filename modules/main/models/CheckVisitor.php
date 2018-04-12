<?php
namespace app\modules\main\models;

use yii\base\Model;


class CheckVisitor extends Model
{

    public $photo;
    public $person;
    public $organization;
    public $event;
    public $card;
    public $card_type;
    public $time;

    public function rules()
    {
        return [
            [['person', 'organization', 'event'], 'required'],
            [['photo', 'person', 'organization', 'event'], 'string', 'max' => 255],
            [['time'], 'safe'],
            [['card', 'card_type'], 'string', 'max' => 20],
        ];
    }


    public function attributeLabels()
    {
        return [
            'photo' => 'Фото',
            'person' => 'Персона',
            'organization' => 'Организация',
            'card' => 'Карта доступа',
            'card_type' => 'Тип карты',
            'event' => 'Событие',
            'time' => 'Дата события',
        ];
    }
}