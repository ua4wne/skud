<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Device */

$this->title = $model->type;
$this->params['breadcrumbs'][] = ['label' => 'Контроллеры СКУД', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->type, 'url' => ['view', 'id' => $model->id]];
?>
<div class="device-update">

    <h1 class="text-center">Обновление записи</h1>

    <?= $this->render('_form', [
        'model' => $model,
        'active' => $active,
        'mode' => $mode,
        'zone' => $zone,
        'upload' => $upload,
    ]) ?>

</div>
