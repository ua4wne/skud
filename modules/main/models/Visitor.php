<?php

namespace app\modules\main\models;

use app\modules\admin\models\CarType;
use app\modules\admin\models\DocType;
use Yii;
use app\models\BaseModel;
use yii\helpers\ArrayHelper;

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
 * @property int $card
 * @property int $car_id
 * @property string $car_num
 * @property string $doc_id
 * @property string $doc_series
 * @property string $doc_num
 * @property string $phone
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Car $car
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
            [['fname', 'lname', 'renter_id', 'card', 'doc_id'], 'required'],
            [['renter_id', 'car_id', 'doc_id'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['fname', 'mname', 'lname'], 'string', 'max' => 50],
            [['image'], 'string', 'max' => 30],
            [['car_num', 'doc_num'], 'string', 'max' => 10],
            [['doc_series'], 'string', 'max' => 7],
            [['phone'], 'string', 'max' => 20],
            [['card'], 'string', 'max' => 20],
            [['card'], 'unique'],
            [['renter_id'], 'exist', 'skipOnError' => true, 'targetClass' => Renter::className(), 'targetAttribute' => ['renter_id' => 'id']],
            [['car_id'], 'exist', 'skipOnError' => true, 'targetClass' => CarType::className(), 'targetAttribute' => ['car_id' => 'id']],
            [['doc_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocType::className(), 'targetAttribute' => ['doc_id' => 'id']],
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
            'card' => 'Карта доступа',
            'car_id' => 'Транспортное средство',
            'car_num' => 'Регистрационный номер',
            'doc_id' => 'Документ',
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
    public function getRenter()
    {
        return $this->hasOne(Renter::className(), ['id' => 'renter_id']);
    }

    public function getRenterName()
    {
        $renter = $this->renter;

        return $renter ? $renter->title . ' (' . $renter->area . ')' : '';
    }

    public static function getRenterList()
    {
        // Выбираем организации для фильтра
        $car = Renter::find()
            ->select(['id', 'title'])
            ->all();

        return ArrayHelper::map($car, 'id', 'title');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDoc()
    {
        return $this->hasOne(DocType::className(), ['id' => 'doc_id']);
    }

    public function getDocName()
    {
        $doc = $this->doc;

        return $doc ? $doc->text : '';
    }

    public static function getDocList()
    {
        // Выбираем организации для фильтра
        $car = DocType::find()
            ->select(['id', 'text'])
            ->all();

        return ArrayHelper::map($car, 'id', 'text');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCar()
    {
        return $this->hasOne(CarType::className(), ['id' => 'car_id']);
    }

    public function getCarName()
    {
        $car = $this->car;

        return $car ? $car->text : '';
    }

    public static function getCarList()
    {
        // Выбираем ТС для фильтра
        $car = CarType::find()
            ->select(['id', 'text'])
            ->all();

        return ArrayHelper::map($car, 'id', 'text');
    }
}
