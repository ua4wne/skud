<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Card */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="card-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'device')->dropDownList($data) ?>

    <?= $form->field($model, 'code')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'granted')->dropDownList(['0' => 'Заблокирована','1' => 'Действующая']) ?>

    <?= $form->field($model, 'flags')->textInput() ?>

    <?= $form->field($model, 'zone')->textInput() ?>

    <?= $form->field($model, 'share')->dropDownList(['0' => 'Индивидуальная','1' => 'Гостевая']) ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
