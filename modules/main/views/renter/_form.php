<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Renter */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="renter-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'area')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'agent')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email') ?>

    <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
    'mask' => '(999) 999-99-99',
    ]) ?>

    <?= $form->field($model, 'status')->dropDownList($statsel) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
