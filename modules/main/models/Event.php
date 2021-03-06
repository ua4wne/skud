<?php

namespace app\modules\main\models;

use app\modules\admin\models\EventType;
use Yii;
//use app\models\BaseModel;
use app\modules\admin\models\Device;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "event".
 *
 * @property int $id
 * @property int $device_id
 * @property string $event_type
 * @property int $card
 * @property string $flag
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Device $device
 */
class Event extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'event';
    }

    /*public function beforeSave($insert)
    {
        //смотрим привязку карты к посетителю
        $visitor_id = Visitor::findOne(['card'=>$this->card])->id;
        $this->visitor_id = $visitor_id;
        parent::beforeSave($insert);
    }*/

    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            if($this->event_type == 17){ //проход состоялся
                //это гостевая карта?
                $share = Card::findOne(['code'=>$this->card])->share;
                if($share){
                    $visitor = Visitor::findOne(['card'=>$this->card]);
                    if(!empty($visitor)){
                        $visitor->card = null;
                        $visitor->save(false);
                        $gcard = GuestCard::findOne(['visitor_id'=>$this->visitor_id,'passed'=>null]);
                        if(!empty($gcard)){
                            $gcard->passed = date('Y-m-d H:i:s');
                            $gcard->save();
                        }
                    }
                }
            }
        }
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['device_id', 'event_type', 'card', 'event_time'], 'required'],
            [['device_id'], 'integer'],
            [['created_at', 'updated_at', 'event_time', 'visitor_id'], 'safe'],
            [['event_type'], 'string', 'max' => 2],
            [['card'], 'string', 'max' => 20],
            [['flag'], 'string', 'max' => 3],
            [['device_id'], 'exist', 'skipOnError' => true, 'targetClass' => Device::className(), 'targetAttribute' => ['device_id' => 'id']],
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
            'card' => 'Карта доступа',
            'flag' => 'Флаг',
            'visitor_id' => 'ID посетителя',
            'event_time' => 'Время события',
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

    public static function getEventList()
    {
        // Выбираем тайм-зоны для фильтра
        $type = EventType::find()
            ->select(['code', 'text'])
            ->all();

        return ArrayHelper::map($type, 'code', 'text');
    }

    public static function getDeviceList(){
        // Выбираем тайм-зоны для фильтра
        $type = Device::find()
            ->select(['id', 'text'])
            ->all();

        return ArrayHelper::map($type, 'id', 'text');
    }
}
