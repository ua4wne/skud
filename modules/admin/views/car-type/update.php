<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\CarType */

$this->title = 'Обновление записи: ' . $model->text;
$this->params['breadcrumbs'][] = ['label' => 'Виды автотранспорта', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->text, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Обновление';
?>
<div class="car-type-update">

    <h1 class="text-center">Обновление записи</h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
