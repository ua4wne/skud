<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Visitor */

$this->title = $model->lname.' '.$model->fname;
$this->params['breadcrumbs'][] = ['label' => 'Visitors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->lname.' '.$model->fname, 'url' => ['view', 'id' => $model->id]];
?>
<div class="visitor-update">

    <h1 class="text-center"><?= $model->lname.' '.$model->fname ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'rentsel' => $rentsel,
        'docs' => $docs,
        'upload' => $upload,
        'cars' => $cars,
    ]) ?>

</div>
