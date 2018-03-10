<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\models\Device */

$this->title = $model->type;
$this->params['breadcrumbs'][] = ['label' => 'Контроллеры СКУД', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="device-view">

    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

    <p>
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
            //'id',
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
            'type',
            'snum',
            'fware',
            'conn_fw',
            //'image',
            'text',
            'address',
            //'is_active',
            [
                'label' => 'Статус',
                'format' => 'raw',
                'value' => function($data){
                    $mode = $data->is_active;
                    switch ($mode) {
                        case 0:
                            $val = 'Не активный';
                            break;
                        case 1:
                            $val = 'Активный';
                            break;
                    }
                    return $val;
                },
            ],
            //'mode',
            [
                'label' => 'Режим работы',
                'format' => 'raw',
                'value' => function($data){
                    $mode = $data->is_active;
                    switch ($mode) {
                       case 0:
                          $val = 'Нормальный';
                          break;
                       case 1:
                          $val = 'Блокирующий';
                          break;
                       case 2:
                          $val = 'Свободный проход';
                          break;
                       case 3:
                          $val = 'Ожидание свободного прохода';
                          break;
                    }
                    return $val;
                },
            ],
            'zone_id',
            'created_at',
            'updated_at',
        ],
    ]) ?>

</div>
