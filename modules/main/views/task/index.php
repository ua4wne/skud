<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Очередь задач';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="task-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?= Html::a('Удалить все задания', ['all-delete'], ['class' => 'btn btn-danger']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'type',
            'snum',
            'json:ntext',
            //'status',
            [
                /**
                 * Название поля модели
                 */
                'attribute' => 'status',
                /**
                 * Формат вывода.
                 * В этом случае мы отображает данные, как передали.
                 * По умолчанию все данные прогоняются через Html::encode()
                 */
                'format' => 'raw',
                /**
                 * Переопределяем отображение самих данных.
                 * Вместо 1 или 0 выводим Yes или No соответственно.
                 * Попутно оборачиваем результат в span с нужным классом
                 */
                'value' => function ($model, $key, $index, $column) {
                    $active = $model->{$column->attribute} === 1;
                    return \yii\helpers\Html::tag(
                        'span',
                        $active ? 'Активная' : 'Ожидает',
                        [
                            'class' => 'label label-' . ($active ? 'success' : 'danger'),
                        ]
                    );
                },
            ],
            'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn','template' => '{view} {delete}'],
        ],
    ]); ?>
</div>

<?php
$js = <<<JS
setTimeout(function() {window.location.reload();}, 10000);
JS;
$this->registerJs($js);
?>

