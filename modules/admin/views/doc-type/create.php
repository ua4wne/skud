<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\main\models\DocType */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Виды документов', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="doc-type-create">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
