<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Смена аватара';
$this->params['breadcrumbs'][] = ['label' => 'Профиль', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>
    <div class="form-group">
        <?= $form->field($model, 'image')->fileInput() ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Загрузить', ['class' => 'btn btn-success']) ?>
    </div>
<?php ActiveForm::end() ?>