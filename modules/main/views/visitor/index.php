<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\SearchVisitor */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Посетители';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visitor-index">

    <h1 class="text-center">Журнал регистрации</h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Новый посетитель', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'label' => 'Фото',
                'format' => 'raw',
                'value' => function($data){
                    return Html::img(Url::toRoute($data->image),[
                        'alt'=>'image',
                        'style' => 'width:50px;',
                        //'class'=>'img-circle'
                    ]);
                },
            ],
            'fname',
            'mname',
            'lname',
            //'image',
            'renter_id',
            'card_id',
            'car_id',
            'car_num',
            'doc_type',
            'doc_series',
            'doc_num',
            'phone',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
