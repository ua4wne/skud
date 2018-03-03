<?php

namespace app\models;

use Yii;
use app\modules\user\models\User;

/**
 * This is the model class for table "events".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $type
 * @property string $msg
 * @property integer $is_read
 * @property string $created_at
 * @property string $updated_at
 *
 * @property User $user
 */
class Events extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'events';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_ip', 'type', 'msg'], 'required'],
            [['user_id', 'is_read'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['user_ip'], 'string', 'max' => 15],
            [['type'], 'string', 'max' => 50],
            [['msg'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'Пользователь',
            'user_ip' => 'IP адрес',
            'type' => 'Тип события',
            'msg' => 'Сообщение',
            'is_read' => 'Прочтено',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
