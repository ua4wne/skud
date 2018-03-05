<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\TimeZone */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="time-zone-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'zone')->textInput() ?>

    <?= $form->field($model, 'begin')->widget(\yii\widgets\MaskedInput::className(), [
        'clientOptions' => ['alias' =>  'hh:mm']
    ]) ?>

    <?= $form->field($model, 'end')->widget(\yii\widgets\MaskedInput::className(), [
        'clientOptions' => ['alias' =>  'hh:mm']
    ]) ?>

    <?= $form->field($model, 'days')->widget(\yii\widgets\MaskedInput::className(), [
        //'mask' => '9[9999999]'
        'mask' => 'j', // basic
        'definitions' => ['j' => [
            'validator' => '[0-1\(\)\.\+/ ]',
            'cardinality' => 8,
            'prevalidator' =>  [
                ['validator' => '[0|1]', 'cardinality' => 1],
            ]
        ]]
    ]) ?>

    <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
