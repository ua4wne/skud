<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Device */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Контроллеры СКУД', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-create">

    <h1 class="text-center">Новый контроллер СКУД</h1>

    <?= $this->render('_form', [
        'model' => $model,
        'upload' => $upload,
        'tzone' => $tzone,
    ]) ?>

</div>
