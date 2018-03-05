<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\modules\user\models\User */

$this->title = 'Новая запись';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h2>Новый пользователь</h2>

    <?= $this->render('_form', [
        'model' => $model,
        'statsel' => $statsel,
        'rolesel' => $rolesel,
        'def' => $def
    ]) ?>

</div>
