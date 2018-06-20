<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\main\models\Device */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Оборудование', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php if($model->type->name == 'Счетчик') :?>
        <?= Html::a('Тариф', ['tarif', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
        <?php endif; ?>
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
            'id',
            [
                'label' => 'Картинка',
                'format' => 'raw',
                'value' => function($data){
                    return Html::img(Url::toRoute($data->image),[
                        'alt'=>'image',
                        'style' => 'width:300px;',
                        //'class'=>'img-circle'
                    ]);
                },
            ],
            'uid',
            'name',
            [
                'attribute'=>'type.name',
                'label'=>'Тип устройства',
            ],
            [
                'attribute'=>'tarif.koeff',
                'label'=>'Тариф',
            ],
            'descr:ntext',
            'address',
            'verify',
            'protocol_id',
            //'location.name',
            [
                'attribute'=>'location.name',
                'label'=>'Локация',
            ],
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
