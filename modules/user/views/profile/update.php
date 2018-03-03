<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование профиля';
$this->params['breadcrumbs'][] = ['label' => 'Профиль', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>
    <div class="form-group">
        <?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'lname')->textInput(['maxlength' => true]) ?>
    </div>
    <div class="form-group">
        <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    </div>

    <div class="form-group">
        <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
            'mask' => '999-999-9999',
        ]) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Обновить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end() ?>