<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
use yii\widgets\ActiveForm;


/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\SearchDevice */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Контроллеры СКУД';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <div>
        <?= Html::a('Новая запись', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::Button('Удалить все карты', ['class' => 'btn btn-danger','id'=>'clear_card']) ?>

        <?php
        Modal::begin([
            'header' => '<h3>Установка режима работы</h3>',
            'toggleButton' => ['label' => '<i class="fa fa-gears" aria-hidden="true"></i> Режим работы','class'=>'btn btn-primary'],
            //'footer' => 'Низ окна',
        ]);

        $form = ActiveForm::begin([
            'id' => 'set-mode-form',
            //'enableAjaxValidation' => true,
            'action' => ['index']
        ]); ?>

        <?= $form->field($command, 'device')->hiddenInput(['class' => 'id_device'])->label(false) ?>
        <?= $form->field($command, 'mode')->dropDownList(['0'=>'Нормальный', '1'=>'Блокировка', '2'=>'Свободный проход','3'=>'Ожидание свободного прохода']) ?>

        <div class="form-group">
            <?= Html::submitButton('Установить', ['class' => 'btn btn-success','id'=>'set-mode']) ?>
        </div>

        <?php ActiveForm::end();

        Modal::end();

        Modal::begin([
            'header' => '<h3>Установка тайм-зоны</h3>',
            'toggleButton' => ['label' => '<i class="fa fa-clock-o" aria-hidden="true"></i> Тайм-зона','class'=>'btn btn-primary'],
            //'footer' => 'Низ окна',
        ]);

        $form = ActiveForm::begin([
            'id' => 'set-timezone-form',
            //'enableAjaxValidation' => true,
            'action' => ['index']
        ]); ?>

        <?= $form->field($command, 'device')->hiddenInput(['class' => 'id_device'])->label(false) ?>
        <?= $form->field($command, 'zone')->dropDownList($zone) ?>

        <div class="form-group">
            <?= Html::submitButton('Установить', ['class' => 'btn btn-success','id'=>'set-timezone']) ?>
        </div>

        <?php ActiveForm::end();

        Modal::end();
        ?>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'class' => 'yii\grid\RadioButtonColumn',
                'radioOptions' => function ($model) {
                    return [
                        'value' => $model['id'],
                        'checked' => $model['id'] == 1
                    ];
                },
                'contentOptions' =>['class' => 'seldev'],
            ],
            [
                'label' => 'Фото',
                'format' => 'raw',
                'value' => function($data){
                    return Html::img(Url::toRoute($data->image),[
                            'alt'=>'image',
                            'style' => 'width:50px;',
                            //'class'=>'img-circle'
                    ]);
                },
            ],
            'type',
            'snum',
            'fware',
            'conn_fw',
            //'image',
            'text',
            'address',
            //'is_active',
            [
                /**
                 * Название поля модели
                 */
                'attribute' => 'is_active',
                /**
                 * Формат вывода.
                 * В этом случае мы отображает данные, как передали.
                 * По умолчанию все данные прогоняются через Html::encode()
                 */
                'format' => 'raw',
                /**
                 * Переопределяем отображение фильтра.
                 * Задаем выпадающий список с заданными значениями вместо поля для ввода
                 */
                'filter' => [
                    0 => 'Не активный',
                    1 => 'Активный',
                ],
                /**
                 * Переопределяем отображение самих данных.
                 * Вместо 1 или 0 выводим Yes или No соответственно.
                 * Попутно оборачиваем результат в span с нужным классом
                 */
                'value' => function ($model, $key, $index, $column) {
                    $active = $model->{$column->attribute} === 1;
                    return \yii\helpers\Html::tag(
                        'span',
                        $active ? 'Активный' : 'Не активный',
                        [
                            'class' => 'label label-' . ($active ? 'success' : 'danger'),
                        ]
                    );
                },
            ],
            //'mode',
            [
                'label' => 'Режим работы',
                'attribute' => 'mode',
                'filter' => ['0' => 'Нормальный', '1' => 'Блокирующий', '2' => 'Свободный проход', '3' => 'Ожидание свободного прохода', '12' => 'Не установлен'],
                //'filterInputOptions' => ['prompt' => 'All educations', 'class' => 'form-control', 'id' => null]
                'value' => function ($model, $key, $index, $column) {
                    $mode = $model->{$column->attribute};
                    switch ($mode) {
                        case 0:
                            $val = 'Нормальный';
                            break;
                        case 1:
                            $val = 'Блокирующий';
                            break;
                        case 2:
                            $val = 'Свободный проход';
                            break;
                        case 3:
                            $val = 'Ожидание свободного прохода';
                            break;
                        default:
                            $val = 'Не установлен';
                            break;
                    }
                    return $val;
                },
            ],
            //'zone.text',
            /*[
                'label' => 'Временная зона',
                'attribute' => 'zone_id',
                'value' => function ($model, $key, $index, $column) {
                    return 'Зона №' . $model->zone->zone . ' (' .$model->zone->text.')';
                },
            ],*/
            [
                'attribute'=>'zone_id',
                'label'=>'Временная зона',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){
                    return $data->getZoneName();
                },
                'filter' => \app\modules\admin\models\Device::getZoneList()
            ],
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php
$js = <<<JS
    $('.btn-primary').prop('disabled', true);
    $('#clear_card').prop('disabled', true);
    var dev_id;
    $('.seldev').click (function(){
        var id = $(this).children().val();
        dev_id = id;
        if(id){
            $('.btn-primary').prop('disabled', false);
            $('.id_device').val(id);
            $('#clear_card').prop('disabled', false);
        }
        else
            $('.btn-primary').prop('disabled', true);
    });
    
    $('#set-mode-form').on('beforeSubmit', function(){
         var data = $(this).serialize();
         $.ajax({
             url: '/admin/device/set-mode',
             type: 'POST',
             data: data,
             success: function(res){
                //alert("Сервер вернул вот что: " + res);
                if(res=='OK')
                    window.location.replace("/admin/device");
             },
             error: function (xhr, ajaxOptions, thrownError) {
       	        alert(xhr.status+' '+thrownError);
             }
         });
         return false;
    });
    $('#set-timezone-form').on('beforeSubmit', function(){
         var data = $(this).serialize();
         if($('#command-begin').val()==''){
             $('#command-begin').focus();
             alert('Не указано время начала действия!');
             return false;
         }
         if($('#command-end').val()==''){
             $('#command-end').focus();
             alert('Не указано время окончания действия!');
             return false;
         }
                  
         $.ajax({
             url: '/admin/device/set-zone',
             type: 'POST',
             data: data,
             success: function(res){
                //alert("Сервер вернул вот что: " + res);
                if(res=='OK')
                    window.location.replace("/admin/device");
             },
             error: function (xhr, ajaxOptions, thrownError) {
       	        alert(xhr.status+' '+thrownError);
             }
         });
         return false;
    });
    
    $('#clear_card').click(function(e){
        e.preventDefault();
        $.ajax({
             url: '/admin/device/clear-card',
             type: 'POST',
             data: {'id':dev_id},
             success: function(res){
                //alert("Сервер вернул вот что: " + res);
                if(res=='OK')
                   alert('Задание на очистку карт из памяти контроллера создано!');
             },
             error: function (xhr, ajaxOptions, thrownError) {
       	        alert(xhr.status+' '+thrownError);
             }
         });
    });
JS;

$this->registerJs($js);
?>
