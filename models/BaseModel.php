<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;

class BaseModel extends ActiveRecord
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

    public function afterSave($insert, $changedAttributes)
    {
        $modelName = $this->tableName();
        parent::afterSave($insert, $changedAttributes);
        if($modelName != 'options' && $modelName != 'eventlog' && $modelName != 'topic'){
            if ($insert) {
                Yii::$app->session->setFlash('success', 'Запись добавлена!');
            } else {
                if($modelName != 'rule')
                    Yii::$app->session->setFlash('success', 'Запись обновлена!');
            }
        }
    }

    public function afterDelete()
    {
        $modelName = $this->tableName();
        parent::afterDelete();
        if($modelName != 'options' && $modelName != 'eventlog' && $modelName != 'topic'){
            Yii::$app->session->setFlash('success', 'Запись c ID='. $this->id .' была удалена!');
        }
    }
}
