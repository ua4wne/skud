<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Visitor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visitor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'is_worker')->textInput() ?>

    <?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'mname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'lname')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'renter_id')->textInput() ?>

    <?= $form->field($model, 'status')->textInput() ?>

    <?= $form->field($model, 'card_id')->textInput() ?>

    <?= $form->field($model, 'car_id')->textInput() ?>

    <?= $form->field($model, 'car_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_series')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'doc_num')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
