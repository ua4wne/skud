<?php

namespace app\modules\main\models;

use Yii;

/**
 * This is the model class for table "task".
 *
 * @property int $id
 * @property string $type
 * @property string $snum
 * @property string $json
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 */
class Task extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'task';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'snum', 'json', 'created_at'], 'required'],
            [['json'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['type', 'snum'], 'string', 'max' => 10],
            [['status'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Тип контроллера',
            'snum' => 'Серийный №',
            'json' => 'Json-пакет',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
}
