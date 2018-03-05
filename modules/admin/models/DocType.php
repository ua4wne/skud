<?php

namespace app\modules\admin\models;

use Yii;
use app\models\BaseModel;

/**
 * This is the model class for table "doc_type".
 *
 * @property integer $id
 * @property string $text
 * @property string $created_at
 * @property string $updated_at
 */
class DocType extends BaseModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'doc_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'required'],
            [['text'], 'unique'],
            [['created_at', 'updated_at'], 'safe'],
            [['text'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Тип документа',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }
}
