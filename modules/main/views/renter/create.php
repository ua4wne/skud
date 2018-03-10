<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Renter */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Организации', 'url' => ['index']];

?>
<div class="renter-create">

    <h1 class="text-center">Новая организация</h1>

    <?= $this->render('_form', [
        'model' => $model,
        'statsel' => $statsel,
    ]) ?>

</div>
