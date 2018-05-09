<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\SearchVisitor */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Сотрудники';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="visitor-index">

    <h1 class="text-center">Журнал регистрации</h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Новый сотрудник', ['create'], ['class' => 'btn btn-success']) ?>
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
            //'renter_id',
            [
                'attribute'=>'renter_id',
                'label'=>'Организация',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){
                    return $data->getRenterName();
                },
                'filter' => \app\modules\main\models\Visitor::getRenterList(),
            ],
            'card',
            //'car_id',
            [
                'attribute'=>'car_id',
                'label'=>'Транспортное средство',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){
                    return $data->getCarName();
                },
                'filter' => \app\modules\main\models\Visitor::getCarList(),
            ],
            'car_num',
            //'doc_id',
            [
                'attribute'=>'doc_id',
                'label'=>'Документ',
                'format'=>'text', // Возможные варианты: raw, html
                'content'=>function($data){
                    return $data->getDocName();
                },
                'filter' => \app\modules\main\models\Visitor::getDocList(),
            ],
            'doc_series',
            'doc_num',
            //'phone',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
