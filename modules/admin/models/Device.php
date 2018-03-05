<?php

namespace app\modules\admin\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "device".
 *
 * @property integer $id
 * @property string $type
 * @property string $snum
 * @property string $fware
 * @property string $conn_fw
 * @property string $image
 * @property string $text
 * @property integer $is_active
 * @property integer $mode
 * @property integer $zone_id
 * @property string $created_at
 * @property string $updated_at
 *
 * @property EventSkud[] $eventSkuds
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
            [['type', 'snum', 'fware', 'conn_fw'], 'required'],
            [['is_active', 'mode', 'zone_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['type', 'snum', 'fware', 'conn_fw'], 'string', 'max' => 10],
            [['image'], 'string', 'max' => 50],
            [['text'], 'string', 'max' => 255],
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
            'fware' => 'Firmware',
            'conn_fw' => 'Conn Fw',
            'image' => 'Фото',
            'text' => 'Описание',
            'is_active' => 'Статус',
            'mode' => 'Режим работы',
            'zone_id' => 'Временная зона',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventSkuds()
    {
        return $this->hasMany(EventSkud::className(), ['device_id' => 'id']);
    }
}
