<?php
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Табель пропусков';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="page-employee">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <?php
    Modal::begin([
        'header' => '<h3>Выбор организации</h3>',
        'toggleButton' => ['label' => '<i class="fa fa-users" aria-hidden="true"></i> Организации','class'=>'btn btn-primary'],
        //'footer' => 'Низ окна',
    ]);

    $form = ActiveForm::begin([
        'id' => 'set-renter-form',
        //'enableAjaxValidation' => true,
        'action' => ['employee-card']
    ]); ?>

    <?= $form->field($renter, 'id')->dropDownList($optsel)->label('Организация') ?>

    <div class="form-group">
        <?= Html::submitButton('Установить', ['class' => 'btn btn-success','id'=>'set-renter']) ?>
    </div>

    <?php ActiveForm::end();

    Modal::end(); ?>
    <div id="content"></div>
</div><!-- /.page-header -->

<?php
$js = <<<JS

    $('#set-renter-form').on('beforeSubmit', function(){
         var data = $(this).serialize();
         $.ajax({
             url: '/main/report/employee-card',
             type: 'POST',
             data: data,
             success: function(res){
                //alert("Сервер вернул вот что: " + res);
                $('#content').html(res);
             },
             error: function (xhr, ajaxOptions, thrownError) {
       	        alert(xhr.status+' '+thrownError);
             }
         });
         return false;
    });

JS;

$this->registerJs($js);
?>

