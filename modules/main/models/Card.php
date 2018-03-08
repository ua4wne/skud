<?php

namespace app\modules\main\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "card".
 *
 * @property int $id
 * @property string $code
 * @property int $granted
 * @property int $flags
 * @property int $zone
 * @property int $share
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Event[] $events
 * @property Visitor[] $visitors
 */
class Card extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'zone'], 'required'],
            [['flags', 'zone'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['code'], 'string', 'max' => 20],
            [['granted', 'share'], 'string', 'max' => 1],
            [['code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Идентификатор',
            'granted' => 'Активность',
            'flags' => 'Флаг',
            'zone' => 'Временная зона',
            'share' => 'Гостевая',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['card_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitors()
    {
        return $this->hasMany(Visitor::className(), ['card_id' => 'id']);
    }
}
