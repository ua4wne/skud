<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Device */

$this->title = 'Тарифы';
$this->params['breadcrumbs'][] = ['label' => 'Оборудование', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $device, 'url' => ['view', 'id' => $id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="device-update">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <?= Html::img(Url::toRoute($image),[
                'alt'=>'image',
                'style' => 'width:300px;',
                //'class'=>'img-circle'
            ]); ?>
        </div>
    </div>
    <div class="device-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'device_id')->hiddenInput()->label(false) ?>

        <?= $form->field($model, 'koeff')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'unit')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Создать', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
