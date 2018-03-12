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
        'mask' => '99:99',
    ]) ?>
    <?= $form->field($model, 'end')->widget(\yii\widgets\MaskedInput::className(), [
        'mask' => '99:99',
    ]) ?>

    <?=$form->field($model, 'days')
        ->checkboxList([
            '0' => 'ПН',
            '1' => 'ВТ',
            '2' => 'СР',
            '3' => 'ЧТ',
            '4' => 'ПТ',
            '5' => 'СБ',
            '6' => 'ВС',
        ]); ?>

    <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
