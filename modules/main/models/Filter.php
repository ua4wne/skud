<?php


namespace app\modules\main\models;
use yii\base\Model;

class Filter extends Model
{
    public $id;

    public function rules(){
        return[
            [['id'], 'integer'],
        ];
    }
}