<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\SearchEvent */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Журнал системных событий';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'user_id',
            'user_ip',
            'type',
            'msg:html',
            'created_at',
            //'updated_at',

            ['class' => 'yii\grid\ActionColumn','template' => '{delete}'],
        ],
    ]); ?>
</div>
