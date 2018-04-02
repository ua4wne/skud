<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
$this->title = 'Панель управления';
$this->params['breadcrumbs'][] = $this->title;
?>

    <div class="page-header">
        <h1>Главная панель</h1>
    </div><!-- /.page-header -->

<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>
                    150
                </h3>
                <p>
                    New Orders
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    53<sup style="font-size: 20px">%</sup>
                </h3>
                <p>
                    Bounce Rate
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    44
                </h3>
                <p>
                    User Registrations
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>
                    65
                </h3>
                <p>
                    Unique Visitors
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="#" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div><!-- ./col -->
</div><!-- /.row -->

<div class="row">
    <div class="col-md-10">
        <div class="tabbable">
            <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
                <li class="active">
                    <a data-toggle="tab" href="#home">Текущие события</a>
                </li>

                <li>
                    <a data-toggle="tab" href="#registry">Регистрация</a>
                </li>

                <li>
                    <a data-toggle="tab" href="#device">Оборудование</a>
                </li>

            </ul>

            <div class="tab-content">
                <div id="home" class="tab-pane in active">
                    <?= GridView::widget([
                        'dataProvider' => $dataEventProvider,
                        'filterModel' => $searchEventModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            //'id',
                            //'device_id',
                            [
                                'attribute'=>'device_id',
                                'label'=>'Контроллер',
                                'format'=>'text', // Возможные варианты: raw, html
                                //    'content'=>function($data){
                                //        return $data->getZoneName();
                                //    },
                                'filter' => \app\modules\main\models\Event::getDeviceList()
                            ],
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

                            //['class' => 'yii\grid\ActionColumn'],
                        ],
                    ]); ?>
                </div>

                <div id="registry" class="tab-pane">
                    <div class="visitor-form">

                        <?php $form = ActiveForm::begin(); ?>

                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'lname')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'mname')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'renter_id')->dropDownList($rentsel) ?>

                                <?= $form->field($model, 'card_id')->textInput() ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'doc_type')->dropDownList($docs) ?>

                                <?= $form->field($model, 'doc_series')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'doc_num')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'car_id')->dropDownList($cars) ?>

                                <?= $form->field($model, 'car_num')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'phone')->widget(\yii\widgets\MaskedInput::className(), [
                                    'mask' => '(999) 999-99-99',
                                ]) ?>
                            </div>
                            <div class="form-group">
                                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>

                    </div>

                </div>

                <div id="device" class="tab-pane">
                    <?= GridView::widget([
                        'dataProvider' => $dataDeviceProvider,
                        //'filterModel' => $searchDeviceModel,
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
                            'type',
                            'snum',
                            'text',
                            'address',
                            //'is_active',
                            [
                                /**
                                 * Название поля модели
                                 */
                                'attribute' => 'is_active',
                                /**
                                 * Формат вывода.
                                 * В этом случае мы отображает данные, как передали.
                                 * По умолчанию все данные прогоняются через Html::encode()
                                 */
                                'format' => 'raw',
                                /**
                                 * Переопределяем отображение самих данных.
                                 * Вместо 1 или 0 выводим Yes или No соответственно.
                                 * Попутно оборачиваем результат в span с нужным классом
                                 */
                                'value' => function ($model, $key, $index, $column) {
                                    $active = $model->{$column->attribute} === 1;
                                    return \yii\helpers\Html::tag(
                                        'span',
                                        $active ? 'Активный' : 'Не активный',
                                        [
                                            'class' => 'label label-' . ($active ? 'success' : 'danger'),
                                        ]
                                    );
                                },
                            ],
                            //'mode',
                            [
                                'label' => 'Режим работы',
                                'attribute' => 'mode',
                                'value' => function ($model, $key, $index, $column) {
                                    $mode = $model->{$column->attribute};
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
                                        case 12:
                                            $val = 'Не установлен';
                                            break;
                                    }
                                    return $val;
                                },
                            ],
                            //'zone.text',
                            /*[
                                'label' => 'Временная зона',
                                'attribute' => 'zone_id',
                                'value' => function ($model, $key, $index, $column) {
                                    return 'Зона №' . $model->zone->zone . ' (' .$model->zone->text.')';
                                },
                            ],*/
                            [
                                'attribute'=>'zone_id',
                                'label'=>'Временная зона',
                                'format'=>'text', // Возможные варианты: raw, html
                                'content'=>function($data){
                                    return $data->getZoneName();
                                },
                            ],
                        ],
                    ]); ?>
                </div>

            </div>
        </div>
    </div><!-- /.col -->
    <div class="col-md-2">
        <div class="row">
            <div class="col-sm-12">
                <span class="bigger-110">Выбор устройства</span>
            </div><!-- /.span -->
        <div>
            <div class="space-2"></div>

            <select multiple="" class="chosen-select form-control" id="form-field-select-4" data-placeholder="Выбор устройства...">
                <?= $devopt ?>
            </select>
        </div>
        <p></p>

        <p>
            <button class="btn btn-danger btn-lg btn-block" id="btn_block">Блокировка</button>
        </p>
        <p>
            <button class="btn btn-success btn-lg btn-block" id="btn_free">Свободный проход</button>
        </p>
        <p>
            <button class="btn btn-info btn-lg btn-block" id="btn_normal">Нормальный режим</button>
        </p>
    </div>
</div>

<?php
$js = <<<JS
        
    $('#btn_block').click(function(e){
        e.preventDefault();
        var device=$('#form-field-select-4 :selected').val();
        var text = $('#form-field-select-4 :selected').text();
         if(device){
             $.ajax({
                 url: '/main/default/set-mode',
                 type: 'POST',
                 data: {'data':'block','device':device},
                 success: function(res){
                    //alert("Сервер вернул вот что: " + res);
                    if(res=='OK'){
                        alert('Для контроллера '+ text +' установлен режим блокировки!');
                        location.reload();
                    }
                 },
                 error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status+' '+thrownError);
                 }
            });
         }
         else
             alert('Не выбран контроллер из списка!');
         return false;
    });
    $('#btn_free').click(function(e){
        e.preventDefault();
        var device=$('#form-field-select-4 :selected').val();
         if(device){
             $.ajax({
                 url: '/main/default/set-mode',
                 type: 'POST',
                 data: {'data':'free','device':device},
                 success: function(res){
                    //alert("Сервер вернул вот что: " + res);
                    if(res=='OK'){
                        alert('Для контроллера '+ $('#form-field-select-4 :selected').text()+' установлен режим свободного прохода!');
                        location.reload();
                    }
                 },
                 error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status+' '+thrownError);
                 }
            });
         }
         else
             alert('Не выбран контроллер из списка!');
         return false;
    });
    
    $('#btn_normal').click(function(e){
        e.preventDefault();
        var device=$('#form-field-select-4 :selected').val();
         if(device){
             $.ajax({
                 url: '/main/default/set-mode',
                 type: 'POST',
                 data: {'data':'normal','device':device},
                 success: function(res){
                    //alert("Сервер вернул вот что: " + res);
                    if(res=='OK'){
                         alert('Для контроллера '+ $('#form-field-select-4 :selected').text()+' установлен нормальный режим!');
                        location.reload();
                    }
                 },
                 error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status+' '+thrownError);
                 }
            });
         }
         else
             alert('Не выбран контроллер из списка!');
         return false;
    });
    
    if(!ace.vars['touch']) {
					$('.chosen-select').chosen({allow_single_deselect:true}); 
					//resize the chosen on window resize
			
					$(window)
					.off('resize.chosen')
					.on('resize.chosen', function() {
						$('.chosen-select').each(function() {
							 $(this).next().css({'width': $(this).parent().width()});
						})
					}).trigger('resize.chosen');
					//resize chosen on sidebar collapse/expand
					$(document).on('settings.ace.chosen', function(e, event_name, event_val) {
						if(event_name != 'sidebar_collapsed') return;
						$('.chosen-select').each(function() {
							 $(this).next().css({'width': $(this).parent().width()});
						})
					});
			
			
					$('#chosen-multiple-style .btn').on('click', function(e){
						var target = $(this).find('input[type=radio]');
						var which = parseInt(target.val());
						if(which == 2) $('#form-field-select-4').addClass('tag-input-style');
						 else $('#form-field-select-4').removeClass('tag-input-style');
					});
				}
JS;

$this->registerJs($js);
?>