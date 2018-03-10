<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Device */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Контроллеры СКУД', 'url' => ['index']];
?>
<div class="device-create">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'active' => $active,
        'mode' => $mode,
        'zone' => $zone,
        'upload' => $upload,
    ]) ?>

</div>
