<?php

namespace app\modules\main\models;

use Yii;

/**
 * This is the model class for table "guest_card".
 *
 * @property int $id
 * @property int $visitor_id
 * @property string $card
 * @property string $issued
 * @property string $passed
 *
 * @property Visitor $visitor
 */
class GuestCard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'guest_card';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['visitor_id'], 'integer'],
            [['visitor_id','issued','card'], 'required'],
            [['issued', 'passed'], 'safe'],
            [['card'], 'string', 'max' => 20],
            [['visitor_id'], 'exist', 'skipOnError' => true, 'targetClass' => Visitor::className(), 'targetAttribute' => ['visitor_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'visitor_id' => 'Visitor ID',
            'card' => 'Card',
            'issued' => 'Issued',
            'passed' => 'Passed',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVisitor()
    {
        return $this->hasOne(Visitor::className(), ['id' => 'visitor_id']);
    }
}
