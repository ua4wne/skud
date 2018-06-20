<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Оборудование';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-index">

    <h1 class="center"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Новая запись', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Сканер устройств', ['ping'], ['class' => 'btn btn-danger', 'name' => 'scan', 'id' => 'scan']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
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
            'uid',
            //'name',
            [
                'attribute' => 'name',
                'format' => 'raw',
                'value' => function ($model, $key, $index, $column) {
                    /** @var User $model */
                    if($model->getOptionCount())
                        return Html::a(Html::encode($model->name), ['/main/option/index', 'id' => $model->id]).' <span class="badge">'.$model->getOptionCount().'</span>';
                    else
                        return Html::a(Html::encode($model->name), ['/main/option/index', 'id' => $model->id]);
                }
            ],
            [
                'attribute'=>'type_id',
                'label'=>'Тип устройства',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){
                    return $data->getTypeName();
                },
            ],
            'descr:ntext',
            'address',
            //'verify',
            [
                /**
                 * Название поля модели
                 */
                'attribute' => 'verify',
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
                //'filter' => [
                //    0 => 'Ручной',
                //    1 => 'Автоматический',
                //],
                /**
                 * Переопределяем отображение самих данных.
                 * Вместо 1 или 0 выводим Yes или No соответственно.
                 * Попутно оборачиваем результат в span с нужным классом
                 */
                'value' => function ($model, $key, $index, $column) {
                    $active = $model->{$column->attribute} === 1;
                    return \yii\helpers\Html::tag(
                        'span',
                        $active ? 'Автоматический' : 'Ручной',
                        [
                            'class' => 'label label-' . ($active ? 'success' : 'danger'),
                        ]
                    );
                },
            ],
            'protocol_id',
            //'location.id',
            [
                'attribute'=>'location.name',
                'label'=>'Локация',
            ],
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>

<?php
$js = <<<JS
    $('#scan').click(function(e) {
		e.preventDefault();
		// отправляем AJAX запрос
		$.ajax({
			type: "POST",
		    url: "/main/device/ping",
		    //dataType: "json",
	        data: {action:'ping'},
		    // success - это обработчик удачного выполнения событий
		    success: function(res) {
			    alert("Сервер вернул вот что: " + res);
			    //$(".events").text(res);
			}     		 
     	});
	});
JS;

$this->registerJs($js);
?>