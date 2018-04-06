<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Card */

$this->title = 'Новая карта';
$this->params['breadcrumbs'][] = ['label' => 'Карты доступа', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card-create col-md-8 col-md-offset-2">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'data' => $data,
    ]) ?>

</div>
