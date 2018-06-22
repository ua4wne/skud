<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Device */

$this->title = 'Новое устройство';
$this->params['breadcrumbs'][] = ['label' => 'Оборудование', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Новая запись';
?>
<div class="device-create">

    <h1 class="center"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'selvrf' => $selvrf,
        'selloc' => $selloc,
        'seltype' => $seltype,
        'upload' => $upload,
    ]) ?>

</div>
