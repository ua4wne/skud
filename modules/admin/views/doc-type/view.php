<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\DocType */

$this->title = $model->text;
$this->params['breadcrumbs'][] = ['label' => 'Виды документов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doc-type-view">

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
            'text',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
