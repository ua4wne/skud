<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Renter */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Организации', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['view', 'id' => $model->id]];

?>
<div class="renter-update">

    <h1 class="text-center">Обновление записи</h1>

    <?= $this->render('_form', [
        'model' => $model,
        'statsel' => $statsel,
    ]) ?>

</div>
