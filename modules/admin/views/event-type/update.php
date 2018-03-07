<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\EventType */

$this->title = 'Обновление: ' . $model->text;
$this->params['breadcrumbs'][] = ['label' => 'Виды событий', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->text, 'url' => ['view', 'id' => $model->id]];
?>
<div class="event-type-update">

    <h1 class="text-center">Обновление записи</h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
