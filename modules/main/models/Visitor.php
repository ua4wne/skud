<?php

namespace app\modules\main\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "visitor".
 *
 * @property int $id
 * @property int $is_worker
 * @property string $fname
 * @property string $mname
 * @property string $lname
 * @property string $image
 * @property int $renter_id
 * @property int $status
 * @property int $card_id
 * @property int $car_id
 * @property string $car_num
 * @property string $doc_type
 * @property string $doc_series
 * @property string $doc_num
 * @property string $phone
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Card $card
 * @property Renter $renter
 */
class Visitor extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'visitor';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fname', 'lname', 'renter_id', 'card_id'], 'required'],
            [['renter_id', 'card_id', 'car_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['fname', 'mname', 'lname'], 'string', 'max' => 50],
            [['image', 'doc_type'], 'string', 'max' => 30],
            [['car_num', 'doc_num'], 'string', 'max' => 10],
            [['doc_series'], 'string', 'max' => 7],
            [['phone'], 'string', 'max' => 20],
            [['card_id'], 'exist', 'skipOnError' => true, 'targetClass' => Card::className(), 'targetAttribute' => ['card_id' => 'id']],
            [['renter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Renter::className(), 'targetAttribute' => ['renter_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fname' => 'Имя',
            'mname' => 'Отчество',
            'lname' => 'Фамилия',
            'image' => 'Фото',
            'renter_id' => 'Организация',
            'card_id' => 'Карта доступа',
            'car_id' => 'Транспортное средство',
            'car_num' => 'Регистрационный номер',
            'doc_type' => 'Документ',
            'doc_series' => 'Серия',
            'doc_num' => 'Номер',
            'phone' => 'Телефон',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCard()
    {
        return $this->hasOne(Card::className(), ['id' => 'card_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRenter()
    {
        return $this->hasOne(Renter::className(), ['id' => 'renter_id']);
    }
}
