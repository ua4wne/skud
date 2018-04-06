<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Task */

$this->title = 'Просмотр';
$this->params['breadcrumbs'][] = ['label' => 'Задания', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Просмотр';
?>
<div class="task-view">

    <h1 class="text-center">Задание № <?= $model->id ?></h1>

    <p>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'type',
            'snum',
            'json:ntext',
            'status',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
