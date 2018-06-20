<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\SearchCard */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Карты доступа';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Новая карта', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::Button('Загрузить все карты', ['class' => 'btn btn-info', 'id'=>'add_cards']) ?>
        <?= Html::Button('Удалить все карты', ['class' => 'btn btn-danger', 'id'=>'clear_cards']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'code',
            [
                /**
                 * Название поля модели
                 */
                'attribute' => 'share',
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
                    0 => 'Индивидуальная',
                    1 => 'Гостевая',
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
                        $active ? 'Гостевая' : 'Индивидуальная',
                        [
                            'class' => 'label label-' . ($active ? 'info' : 'success'),
                        ]
                    );
                },
            ],
            //'granted',
            [
                /**
                 * Название поля модели
                 */
                'attribute' => 'granted',
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
                    0 => 'Заблокирована',
                    1 => 'Действующая',
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
                        $active ? 'Действующая' : 'Заблокирована',
                        [
                            'class' => 'label label-' . ($active ? 'success' : 'danger'),
                        ]
                    );
                },
            ],
            'flags',
            'zone',
            //'share',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php
$js = <<<JS
    $('#add_cards').click(function(e){
        e.preventDefault();
        $.ajax({
            url: '/admin/card/add-cards',
            type: 'POST',
            data: {'data':'add'},
            success: function(res){
                //alert("Сервер вернул вот что: " + res);
                if(res=='OK'){
                    alert('Задачи по загрузке карт в контроллеры созданы!');                    
                }
                else{
                    alert('Задачи по загрузке карт в контроллеры не созданы! Возможно есть не активные контроллеры или нет авторизованных карт для них.');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status+' '+thrownError);
            }
        });         
    });

    $('#clear_cards').click(function(e){
        e.preventDefault();
        var b;
         b=confirm("Из контроллеров будут удалены все карты. Продолжить?");
        
         if(b==true){        
            $.ajax({
                url: '/admin/card/clear-cards',
                type: 'POST',
                data: {'data':'clear'},
                success: function(res){
                    //alert("Сервер вернул вот что: " + res);
                    if(res=='OK'){
                        alert('Задачи по удалению карт из контроллеров созданы!');                    
                    }
                    else{
                        alert('Задачи по удалению карт из контроллеров не созданы! Возможно есть не активные контроллеры.');
                    }
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status+' '+thrownError);
                }
            });         
         }
         else{
            return false;
         }        
    });
JS;
$this->registerJs($js);
?>

