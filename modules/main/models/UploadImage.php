<?php
namespace app\modules\main\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadImage extends Model{

    public $image;
    public $new_image;

    public function rules(){
        return[
            [['image'], 'required', 'message' => 'Не выбран файл!'],
            [['image'], 'file', 'extensions' => 'png, jpg', 'maxSize' => 1048576], //не более 1Мб!!!
            [['new_image'], 'file', 'extensions' => 'png, jpg', 'maxSize' => 1048576], //не более 1Мб!!!
        ];
    }

    public function upload(){
        if($this->validate()){
            //создаем произвольное имя файла во избежание проблем с кодировками!
            $fname = substr_replace(sha1(microtime(true)), '', 12);
            $this->image->saveAs("images/gallery/{$fname}.{$this->image->extension}");
            return '/images/gallery/'.$fname.'.'.$this->image->extension;
        }
        else{
            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'image' => 'Файл изображения',
            'new_image' => 'Новый файл изображения'
        ];
    }
}