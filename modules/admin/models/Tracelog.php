<?php

namespace app\modules\admin\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "tracelog".
 *
 * @property int $id
 * @property string $type
 * @property string $msg
 * @property string $created_at
 * @property string $updated_at
 */
class Tracelog extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'tracelog';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'msg'], 'required'],
            [['created_at', 'updated_at'], 'safe'],
            [['type'], 'string', 'max' => 10],
            [['msg'], 'string', 'max' => 255],
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
            'msg' => 'Сообщение',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
}
