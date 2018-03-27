<?php

namespace app\modules\admin\models;

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
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
                $this->updated_at = date('Y-m-d H:i:s');
            }
            return true;
        } else {
            return false;
        }
    }

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
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => 'Type',
            'snum' => 'Snum',
            'json' => 'Json',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
