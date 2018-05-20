<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use yii\bootstrap\Modal;
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
        <div class="small-box bg-blue">
            <div class="inner">
                <h3>
                    Гостевые карты
                </h3>
                <p>
                    <span class="line-height-1 bigger-120"> Свободно - <?= $free ?>  </span>
                    <span class="line-height-1 bigger-120 pull-right"> Выдано - <?= $busy ?> </span>
                </p>

            </div>
            <div class="icon">
                <i class="ion ion-bag"></i>
            </div>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    На территории
                </h3>
                <p>
                    <span class="line-height-1 bigger-120"> Сотрудников - <?= $empl_cnt ?>  </span>
                    <span class="line-height-1 bigger-120 pull-right"> Посетителей - <?= $visit_cnt ?> </span>
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    Автотранспорт
                </h3>
                <p>
                    <span class="line-height-1 bigger-120"> Сотрудников - 0  </span>
                    <span class="line-height-1 bigger-120 pull-right"> Посетителей - 0 </span>
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
        </div>
    </div><!-- ./col -->
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-blue">
            <div class="inner">
                <h3>
                    Зарегистрировано
                </h3>
                <p>
                    <span class="line-height-1 bigger-120"> Организаций - <?= $rent_cnt ?>  </span>
                    <span class="line-height-1 bigger-120 pull-right"> Сотрудников - <?= $empl ?> </span>
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
        </div>
    </div><!-- ./col -->
</div><!-- /.row -->

<div class="row">
    <div class="col-md-10">
        <div class="tabbable">
            <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
                <li class="active">
                    <a data-toggle="tab" href="#home">Контроль</a>
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

                </div>

                <div id="registry" class="tab-pane">
                    <div class="row">
                        <div class="col-sm-12">
                            <?php
                            Modal::begin([
                                'header' => '<h3>Новое транспортное средство</h3>',
                                'toggleButton' => ['label' => '<i class="ace-icon fa fa-truck bigger-160" aria-hidden="true"></i>  Новое ТС','class'=>'btn btn-primary btn-xs pull-right'],
                                //'footer' => 'Низ окна',
                                'id'=>'car-modal',
                            ]);

                            $carform = ActiveForm::begin([
                                'id' => 'add-new-tc',
                                //'enableAjaxValidation' => true,
                                'action' => ['index']
                            ]); ?>
                            <?= $carform->field($car, 'text')->textInput(['maxlength' => true],['id'=>'car_type']) ?>

                            <div class="form-group">
                                <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success','id'=>'add-car']) ?>
                            </div>

                            <?php ActiveForm::end();

                            Modal::end(); ?>
                        </div>
                    </div>
                    <div class="visitor-form">
                        <?php $form = ActiveForm::begin(['id'=>'add_visitor','action'=>'/main/default/add-visitor']); ?>

                        <div class="row">
                            <div class="col-sm-6">
                                <?= $form->field($model, 'lname')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'fname')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'mname')->textInput(['maxlength' => true]) ?>

                                <?= $form->field($model, 'renter_id')->dropDownList($rentsel, ['class'=>'select2','id'=>'renter_id']) ?>

                                <?= $form->field($model, 'card')->textInput() ?>
                            </div>
                            <div class="col-sm-6">
                                <?= $form->field($model, 'doc_id')->dropDownList($docs,['id'=>'doc_id']) ?>

                                <?= $form->field($model, 'doc_series')->textInput(['maxlength' => true, 'id'=>'series']) ?>

                                <?= $form->field($model, 'doc_num')->textInput(['maxlength' => true, 'id'=>'doc_num']) ?>

                                <?= $form->field($model, 'car_id')->dropDownList($cars,['class'=>'select2','id'=>'car_type']) ?>

                                <?= $form->field($model, 'car_num')->textInput(['maxlength' => true, 'id'=>'car_num']) ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
                                </div>
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
                                        default:
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

    function show()  
    {  
        $.ajax({
            url: '/main/default/check-event',
            type: 'POST',
            data: {'data':'check'},
            cache: false,
            success: function(res){
                //alert("Сервер вернул вот что: " + res);
                $('#home').html(res);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr.status+' '+thrownError);
            }
        });  
    }

    window.setInterval(function () { show(); }, 3000);
    
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
    
    $('#add-new-tc').on('beforeSubmit', function(){
         var data = $(this).serialize();
         $.ajax({
             url: '/main/default/add-truck',
             type: 'POST',
             data: data,
             cache: false,
             success: function(res){
                //alert("Сервер вернул вот что: " + res);
                if(res=='ERR'){
                    alert('Попытка ввода дублирующего значения. Такое ТС уже есть в справочнике.');
                    $('#add-new-tc')[0].reset();
                }
                else{
                    $('.field-car_type').html(res);
                    $('.select2').css('width','100%').select2({allowClear:false});
                    $("#car-modal").modal('hide');
                }              
             },
             error: function (xhr, ajaxOptions, thrownError) {
       	        alert(xhr.status+' '+thrownError);
             }
         });
         return false;
    });
        
    $('#add_visitor').submit(function () {
        var err = false;
        
        if($('#visitor-card').val()==''){
            alert('Не указана карта доступа!');
            $('#visitor-card').focus();
            err = true;
        }
        if($('#visitor-doc_series').val()==''){
            alert('Не указана серия документа!');
            $('#visitor-doc_series').focus();
            err = true;
        }
        if($('#visitor-doc_num').val()==''){
            alert('Не указан номер документа!');
            $('#visitor-doc_num').focus();
            err = true;
        }
                
        if(err){
           return false;        
        }
        else
            return true;
    });
    
    $( "#doc_id" ).blur(function() {
        if(check_fields())
            findVisitor();
    });
    
    $( "#series" ).blur(function() {
        if(check_fields())
            findVisitor();
    });
    
    $( "#doc_num" ).blur(function() {
        if(check_fields())
            findVisitor();
    });
    
    function check_fields(){
        if($('#doc_id').val().length === 0 || $('#series').val().length === 0 || $('#doc_num').val().length === 0)
            return false;
        else
            return true;
    }
    
    function findVisitor(){
        var doc_id = $('#doc_id').val();
        var series = $('#series').val();
        var doc_num = $('#doc_num').val();
        $.ajax({
                 url: '/main/default/find-visitor',
                 type: 'POST',
                 data: {'doc_id':doc_id,'series':series,'doc_num':doc_num},
                 success: function(res){
                    //alert("Сервер вернул вот что: " + res);
                    if(res != 'NOT'){
                        var obj = JSON.parse(res);
                        $('#visitor-lname').val(obj.lname);
                        $('#visitor-fname').val(obj.fname);
                        $('#visitor-mname').val(obj.mname);
                        $('#renter_id').val(obj.renter_id);
                        //$("#renter_id option[value='"+obj.renter_id+"']").attr("selected", "selected");                        
                        $('#car_type').val(obj.car_id);
                        //$("#car_type option[value='"+obj.car_id+"']").attr("selected", "selected");
                        $('#car_num').val(obj.car_num);
                    }
                 },
                 error: function (xhr, ajaxOptions, thrownError) {
                    alert(xhr.status+' '+thrownError);
                 }
            });
    }
        
    //select2
	$('.select2').css('width','100%').select2({allowClear:false})
	/*$('#select2-multiple-style .btn').on('click', function(e){
		var target = $(this).find('input[type=radio]');
		var which = parseInt(target.val());
		if(which == 2) $('.select2').addClass('tag-input-style');
		else $('.select2').removeClass('tag-input-style');
	});*/
    
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