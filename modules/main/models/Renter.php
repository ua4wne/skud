<?php

namespace app\modules\main\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "renter".
 *
 * @property int $id
 * @property string $title
 * @property string $area
 * @property string $agent
 * @property string $phone
 * @property int $status
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Visitor[] $visitors
 */
class Renter extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'renter';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'area', 'agent'], 'required'],
            [['status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 100],
            [['area', 'agent'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 20],
            [['email'], 'email'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Наименование',
            'area' => 'Локация',
            'agent' => 'Директор',
            'phone' => 'Телефон',
            'email' => 'E-Mail',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitors()
    {
        return $this->hasMany(Visitor::className(), ['renter_id' => 'id']);
    }
}
