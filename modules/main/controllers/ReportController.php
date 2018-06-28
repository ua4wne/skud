<?php

namespace app\modules\main\controllers;

use app\modules\main\models\Event;
use app\modules\main\models\Filter;
use app\modules\main\models\Renter;

class ReportController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionEmployeeCard(){
        $filter = new Filter();

        if (\Yii::$app->request->isAjax) {
            if ($filter->load(\Yii::$app->request->post())){
                //выборка по арендаторам
                $query="select v.image, v.fname, v.mname, v.lname, c.code, t.text, v.car_num, c.granted from visitor v
                        join card c on v.card = c.code
                        join car t on v.car_id = t.id
                        where c.share = 0 and v.renter_id = $filter->id order by v.lname";
                // подключение к базе данных
                $connection = \Yii::$app->db;
                // Составляем SQL запрос
                $model = $connection->createCommand($query);
                //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
                $rows = $model->queryAll();
                //    return print_r($rows);
                $content='<div class="hr hr-18 dotted hr-double"></div>
                            <div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-xs-12">
										<table id="simple-table" class="table  table-bordered table-hover">
											<thead>
											<tr>
													<th>Фото</th>
													<th>Фамилия</th>
													<th>Имя</th>
													<th>Отчество</th>
													<th>Автомобиль</th>
													<th>Рег. номер</th>
													<th>Карта</th>
													<th>Статус карты</th>
												</tr>
											</thead>
											<tbody>';
                foreach($rows as $row){
                    if($row['granted']){
                        $granted = '<span class="label label-sm label-success">Активна</span>';
                    }
                    else{
                        $granted = '<span class="label label-sm label-danger">Блокировка</span>';
                    }
                    $content.='<tr><td><img src="'.$row['image'].'" width="100" height="80"></td><td>'.$row['lname'].'</td><td>'.$row['fname'].'</td><td>'.$row['mname'].'</td><td>'.$row['text'].'</td>
                                <td>'.$row['car_num'].'</td><td>'.$row['code'].'</td><td>'.$granted.'</td></tr>';
                }


                $content.='</tbody>
										</table>
									</div><!-- /.span -->
								</div><!-- /.row -->
							</div>
						</div>';

                return $content;
            }
        }
        else {
            //$renter = new Renter();
            $renters = Renter::find()->select(['id', 'title'])->where(['status'=>'1'])->orderBy(['title'=>SORT_ASC])->asArray()->all();
            $optsel = array();
            foreach ($renters as $val) {
                $optsel[$val['id']] = $val['title']; //массив для заполнения данных в select формы
            }
            return $this->render('employee-filter',[
                'optsel'=>$optsel,
                'renter'=>$filter,
            ]);
        }
    }

    public function actionWhoWhere(){
        $query="select v.id, v.fname,v.mname,v.lname,v.card,cr.text as auto, v.car_num, dt.text as doc, v.doc_series, v.doc_num, r.title from visitor v
                join renter r on v.renter_id = r.id
                join car cr on v.car_id = cr.id
                join doc_type dt on v.doc_id = dt.id
                where v.card is not null
                order by r.title";
        // подключение к базе данных
        $connection = \Yii::$app->db;
        // Составляем SQL запрос
        $model = $connection->createCommand($query);
        //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
        $rows = $model->queryAll();
        //    return print_r($rows);
        $content='<div class="hr hr-18 dotted hr-double"></div>
                            <div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="row">
									<div class="col-xs-12">
										<table id="simple-table" class="table  table-bordered table-hover">
											<thead>
											<tr>
													<th>ФИО</th>
													<th>Карта</th>
													<th>Автомобиль</th>
													<th>Рег №</th>
													<th>Документ</th>
													<th>Серия</th>
													<th>Номер</th>
													<th>Организация</th>
													<th>Дата входа</th>
													<th>Дата выхода</th>
												</tr>
											</thead>
											<tbody>';
        foreach($rows as $row){
            $in = Event::find()->select(['event_time'])->where(['event_type'=>[4,16]])->andWhere(['card'=>$row['card']])->andWhere(['visitor_id'=>$row['id']])->orderBy(['id'=>SORT_DESC])->limit(1)->all();
            $out = Event::find()->select(['event_time'])->where(['event_type'=>[5,17]])->andWhere(['card'=>$row['card']])->andWhere(['visitor_id'=>$row['id']])->orderBy(['id'=>SORT_DESC])->limit(1)->all();
            if(!empty($in) || !empty($out)){
                $content.='<tr><td>'.$row['lname'].' '.$row['fname'].' '.$row['mname'].'</td><td>'.$row['card'].'</td><td>'.$row['auto'].'</td>
                           <td>'.$row['car_num'].'</td><td>'.$row['doc'].'</td><td>'.$row['doc_series'].'</td><td>'.$row['doc_num'].'</td>
                           <td>'.$row['title'].'</td><td>'.$in[0]['event_time'].'</td><td>'.$out[0]['event_time'].'</td></tr>';
            }
        }

        $content.='</tbody>
										</table>
									</div><!-- /.span -->
								</div><!-- /.row -->
							</div>
						</div>';

        return $this->render('index',[
            'title'=>'Карта прохода',
            'content'=>$content,
        ]);
    }

    public function actionShareCard(){
        return 'ShareCard';
    }

}
