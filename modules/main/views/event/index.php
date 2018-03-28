<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\SearchEvent */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал событий';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'device_id',
            //'event_type',
            [
                'attribute'=>'event_type',
                'label'=>'Событие',
                'format'=>'text', // Возможные варианты: raw, html
            //    'content'=>function($data){
            //        return $data->getZoneName();
            //    },
                'filter' => \app\modules\main\models\Event::getEventList()
            ],
            'card',
            'flag',
            'event_time',
            //'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
