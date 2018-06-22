<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Device */

$this->title = 'Обновление: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Оборудование', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="device-update">

    <h1 class="center"><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <?= Html::img(Url::toRoute($model->image),[
                'alt'=>'image',
                'style' => 'width:300px;',
                //'class'=>'img-circle'
            ]); ?>
        </div>
    </div>
    <div class="device-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'uid')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'type_id')->dropDownList($seltype) ?>

        <?= $form->field($model, 'descr')->textarea(['rows' => 6]) ?>

        <?= $form->field($model, 'address')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'verify')->dropDownList($selvrf) ?>

        <?= $form->field($model, 'protocol_id')->textInput() ?>

        <?= $form->field($model, 'location_id')->dropDownList($selloc) ?>

        <?= $form->field($upload, 'new_image')->fileInput() ?>

        <div class="form-group">
            <?= Html::submitButton($model->isNewRecord ? 'Создать' : 'Обновить', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
