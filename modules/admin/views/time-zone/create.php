<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\TimeZone */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Временные зоны', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="time-zone-create">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
