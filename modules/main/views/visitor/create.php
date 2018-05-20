<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Visitor */

$this->title = 'Новый сотрудник';
$this->params['breadcrumbs'][] = ['label' => 'Журнал регистрации', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visitor-create">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

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
                if(res=='SHARE')
                    alert('Вы выбрали гостевую карту. Гостевая карта назначается только временным посетителям!');
                    $('#card').focus();
                if(res=='BUSY'){
                    $('#card').val('');
                    $('#card').focus();
                    alert('Карта уже назначена другому сотруднику!');
                }
                if(res=='NO_CARD')
                    alert('Карта еще не активирована. Обратитесь к администратору СКУД!');
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
