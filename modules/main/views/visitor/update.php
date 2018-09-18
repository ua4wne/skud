<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Visitor */

$this->title = $model->lname.' '.$model->fname;
$this->params['breadcrumbs'][] = ['label' => 'Visitors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->lname.' '.$model->fname, 'url' => ['view', 'id' => $model->id]];
?>
<div class="visitor-update">

    <h1 class="text-center"><?= $model->lname.' '.$model->fname ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'rentsel' => $rentsel,
        'docs' => $docs,
        'upload' => $upload,
        'cars' => $cars,
    ]) ?>

</div>

<?php
$js = <<<JS
    $('form').on('beforeSubmit', function(){
         var data = $(this).serialize();
         var result = false;
         $.ajax({
             url: '/main/visitor/check',
             type: 'POST',
             data: data,
             async: false,
             success: function(res){
                //alert("Сервер вернул вот что: " + res);
                if(res=='OK'){
                    alert('Карта свободна!');
                    result = true;
                }
                if(res=='OLD'){
                    //alert('Карта свободна!');
                    result = true;
                }
                if(res=='SHARE'){
                    alert('Вы выбрали гостевую карту. Гостевая карта назначается только временным посетителям!');
                    $('#card').val('');
                    $('#card').focus();
                }
                if(res=='BUSY')
                    alert('Карта уже назначена другому сотруднику!');
                if(res=='NO_CARD'){
                    alert('Карта еще не активирована. Обратитесь к администратору СКУД!');
                    $('#card').val('');
                    result=true;
                }
             },
             error: function (xhr, ajaxOptions, thrownError) {
       	        alert(xhr.status+' '+thrownError);
             }
         });
         //alert('result='+result);
         return result;
    });

JS;

$this->registerJs($js);
?>

