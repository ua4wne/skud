<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\TimeZone */

$this->title = 'Обновление зоны: ' . $model->zone;
$this->params['breadcrumbs'][] = ['label' => 'Временные зоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->zone, 'url' => ['view', 'id' => $model->id]];
?>
<div class="time-zone-update">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
