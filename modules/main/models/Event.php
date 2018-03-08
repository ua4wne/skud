<?php

namespace app\modules\main\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property int $device_id
 * @property string $event_type
 * @property int $card_id
 * @property string $flag
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Device $device
 * @property Idcard $card
 */
class Event extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', 'event_type', 'card_id'], 'required'],
            [['device_id', 'card_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['event_type'], 'string', 'max' => 2],
            [['flag'], 'string', 'max' => 3],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Device::className(), 'targetAttribute' => ['device_id' => 'id']],
            [['card_id'], 'exist', 'skipOnError' => true, 'targetClass' => Idcard::className(), 'targetAttribute' => ['card_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_id' => 'Контроллер СКУД',
            'event_type' => 'Событие',
            'card_id' => 'Карта доступа',
            'flag' => 'Флаг',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Device::className(), ['id' => 'device_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCard()
    {
        return $this->hasOne(Idcard::className(), ['id' => 'card_id']);
    }
}
