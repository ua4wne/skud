<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Device */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="device-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'snum')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fware')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'conn_fw')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'is_active')->dropDownList($active) ?>

    <?= $form->field($model, 'mode')->dropDownList($mode) ?>

    <?= $form->field($model, 'zone_id')->dropDownList($zone) ?>

    <?= $model->isNewRecord ? $form->field($upload, 'image')->fileInput():$form->field($upload, 'new_image')->fileInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
