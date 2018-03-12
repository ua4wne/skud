<?php

namespace app\modules\admin\models;

use Yii;
use app\models\BaseModel;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "device".
 *
 * @property int $id
 * @property string $type
 * @property string $snum
 * @property string $fware
 * @property string $conn_fw
 * @property string $image
 * @property string $text
 * @property string $address
 * @property int $is_active
 * @property int $mode
 * @property int $zone_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property TimeZone $zone
 * @property Event[] $events
 */
class Device extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'device';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'snum', 'fware', 'conn_fw', 'zone_id'], 'required'],
            [['mode', 'zone_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['type', 'snum', 'fware', 'conn_fw'], 'string', 'max' => 10],
            [['image'], 'string', 'max' => 50],
            [['text'], 'string', 'max' => 255],
            [['address'], 'string', 'max' => 15],
            [['is_active'], 'string', 'max' => 1],
            [['snum'], 'unique'],
            [['zone_id'], 'exist', 'skipOnError' => true, 'targetClass' => TimeZone::className(), 'targetAttribute' => ['zone_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип',
            'snum' => 'Серийный №',
            'fware' => 'Fware',
            'conn_fw' => 'Conn Fw',
            'image' => 'Изображение',
            'text' => 'Описание',
            'address' => 'Адрес',
            'is_active' => 'Статус',
            'mode' => 'Режим работы',
            'zone_id' => 'Временная зона',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZone()
    {
        return $this->hasOne(TimeZone::className(), ['id' => 'zone_id']);
    }

    public function getZoneName()
    {
        $zone = $this->zone;

        return $zone ? 'Зона №' . $zone->zone . ' (' .$zone->text.')' : '';
    }

    public static function getZoneList()
    {
        // Выбираем тайм-зоны для фильтра
        $zone = TimeZone::find()
            ->select(['id', 'zone'])
            ->all();

        return ArrayHelper::map($zone, 'id', 'zone');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEvents()
    {
        return $this->hasMany(Event::className(), ['device_id' => 'id']);
    }
}
