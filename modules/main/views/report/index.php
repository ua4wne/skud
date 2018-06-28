<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\main\models\SearchEvent */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <?= $content ?>

</div>
