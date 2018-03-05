<?php

namespace app\modules\admin\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "time_zone".
 *
 * @property integer $id
 * @property integer $zone
 * @property string $begin
 * @property string $end
 * @property string $days
 * @property string $text
 * @property string $created_at
 * @property string $updated_at
 */
class TimeZone extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'time_zone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['zone', 'begin', 'end', 'days'], 'required'],
            [['zone'], 'integer'],
            [['zone'], 'unique'],
            [['begin', 'end', 'created_at', 'updated_at'], 'safe'],
            [['days'], 'string', 'max' => 8],
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
            'zone' => 'Зона',
            'begin' => 'Начало',
            'end' => 'Окончание',
            'days' => 'Маска дней',
            'text' => 'Описание',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
}
