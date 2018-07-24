<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Visitor */

$this->title = $model->fname . ' ' . $model->lname;
$this->params['breadcrumbs'][] = ['label' => 'Посетители', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visitor-view">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Создать', ['create'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('Обновить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены, что хотите удалить этот элемент?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            [
                'label' => 'Фото',
                'format' => 'raw',
                'value' => function($data){
                    return Html::img(Url::toRoute($data->image),[
                        'alt'=>'image',
                        'style' => 'width:300px;',
                        //'class'=>'img-circle'
                    ]);
                },
            ],
            'id',
            'fname',
            'mname',
            'lname',
            //'image',
            'renter_id',
            'card',
            'car_id',
            'car_num',
            'doc_id',
            'doc_series',
            'doc_num',
            'phone',
            //'created_at',
            //'updated_at',
        ],
    ]) ?>

</div>
