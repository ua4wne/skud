<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\TimeZone */

$this->title = 'Временная зона:'.$model->zone;
$this->params['breadcrumbs'][] = ['label' => 'Временные зоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-zone-view">

    <h1 class="text-center">Просмотр записи</h1>

    <p>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
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
            'zone',
            'begin',
            'end',
            'days',
            'text',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
