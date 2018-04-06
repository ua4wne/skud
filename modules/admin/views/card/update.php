<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Card */

$this->title = 'Обновление записи';
$this->params['breadcrumbs'][] = ['label' => 'Карты доступа', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->code, 'url' => ['view', 'id' => $model->id]];
?>
<div class="card-update col-md-8 col-md-offset-2">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <?= $this->render('_form', [
        'model' => $model,
        'data' => $data,
    ]) ?>

</div>
