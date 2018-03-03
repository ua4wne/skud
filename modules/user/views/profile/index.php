<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = 'Профиль пользователя '.$model->username;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-profile">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Сменить аватар', ['avatar'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Сменить пароль', ['password', 'id' => $model->id], ['class' => 'btn btn-warning']) ?>
        <?= Html::a('Редактировать', ['update', 'id' => $model->id], ['class' => 'btn btn-primary',]) ?>
    </p>
    <div class="row">
        <div class="col-md-1">
            <?= Html::img('@web'.$model->image, ['alt' => 'аватар']) ?>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-8">
            <?= DetailView::widget([
                'model' => $model,
                'attributes' => [
                    'username',
                    'fname',
                    'lname',
                    'email',
                    'phone',
                ],
            ]) ?>
        </div>
    </div>

</div>