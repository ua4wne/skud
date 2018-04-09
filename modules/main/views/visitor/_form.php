<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Visitor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="visitor-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-sm-6 col-sm-offset-3">
            <div class="tabbable">
                <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
                    <li class="active">
                        <a data-toggle="tab" href="#home">Основное</a>
                    </li>

                    <li>
                        <a data-toggle="tab" href="#profile">Дополнительно</a>
                    </li>

                </ul>

                <div class="tab-content">
                    <div id="home" class="tab-pane in active">

                        <?= $form->field($model, 'lname')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'mname')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'renter_id')->dropDownList($rentsel) ?>

                        <?= $form->field($model, 'card')->textInput(['id'=>'card']) ?>

                        <?= $form->field($model, 'doc_id')->dropDownList($docs) ?>

                        <?= $form->field($model, 'doc_series')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'doc_num')->textInput(['maxlength' => true]) ?>

                        <div class="form-group">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>

                    <div id="profile" class="tab-pane">
                        <?= $form->field($model, 'car_id')->dropDownList($cars) ?>

                        <?= $form->field($model, 'car_num')->textInput(['maxlength' => true]) ?>

                        <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
                            'mask' => '(999) 999-99-99',
                        ]) ?>

                        <?= $model->isNewRecord ? $form->field($upload, 'image')->fileInput(['id' => 'id-input-file-2']):$form->field($upload, 'new_image')->fileInput(['id' => 'id-input-file-2']) ?>

                        <div class="form-group">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                        </div>

                    </div>

                </div>
            </div>
        </div><!-- /.col -->
    </div>

    <?php ActiveForm::end(); ?>

</div>
