<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\models\SearchDevice */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Контроллеры СКУД';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Новая запись', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'class' => 'yii\grid\CheckboxColumn',
                // вы можете настроить дополнительные свойства здесь.
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
                'filter' => ['0' => 'Нормальный', '1' => 'Блокирующий', '2' => 'Свободный проход', '3' => 'Ожидание свободного прохода'],
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
                    }
                    return $val;
                },
            ],
            'zone_id',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
