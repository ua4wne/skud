<?php

namespace app\modules\main\controllers;

use app\modules\admin\models\Device;
use app\modules\admin\models\EventType;
use app\modules\admin\models\SearchDevice;
use app\modules\main\models\Card;
use app\modules\main\models\Event;
use app\modules\main\models\SearchEvent;
use app\modules\main\models\Renter;
use app\modules\admin\models\CarType;
use app\modules\admin\models\DocType;
use app\modules\main\models\Visitor;
use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use \yii\web\HttpException;
use app\modules\user\models\User;
use yii\data\SqlDataProvider;

/**
 * Default controller for the `main` module
 */
class DefaultController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
                'view' => '@app/modules/main/views/error/view.php',
            ],
        ];
    }

    public function actionIndex()
    {
        $car = new CarType();
        $model = new Visitor();
        $searchDeviceModel = new SearchDevice();
        $dataDeviceProvider = $searchDeviceModel->search(Yii::$app->request->queryParams);

        $devices = Device::find()->select(['id','type','text'])->all();
        $devopt = '';
        foreach ($devices as $device){
            $devopt.='<option value="'.$device->id.'">'.$device->type.' ('.$device->text.')</option>'."\n";
        }

        $renters = Renter::find()->select(['id', 'title'])->where('status=1')->asArray()->all();
        $rentsel = array();
        foreach ($renters as $val) {
            $rentsel[$val['id']] = $val['title']; //массив для заполнения данных в select формы
        }
        $doctype = DocType::find()->select(['id', 'text'])->asArray()->all();
        $docs = array();
        foreach ($doctype as $val) {
            $docs[$val['id']] = $val['text']; //массив для заполнения данных в select формы
        }
        $cartype = CarType::find()->select(['id', 'text'])->asArray()->all();
        $cars = array();
        foreach ($cartype as $val) {
            $cars[$val['id']] = $val['text']; //массив для заполнения данных в select формы
        }

        //определяем кол-во свободных гостевых карт
        $query = "select count(*) as cnt from card where share=1 and code not in (select card from visitor where card is not null)";
        // подключение к базе данных
        $connection = \Yii::$app->db;
        // Составляем SQL запрос
        $conn = $connection->createCommand($query);
        //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
        $row = $conn->queryAll();
        $free = $row[0]['cnt'];
        //определяем кол-во розданых гостевых карт
        $query = "select count(*) as cnt from card where share=1 and code in (select card from visitor where card is not null)";
        // Составляем SQL запрос
        $conn = $connection->createCommand($query);
        //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
        $row = $conn->queryAll();
        $busy = $row[0]['cnt'];

        //кол-во действующих организаций
        $rent_cnt = Renter::find()->where(['status'=>1])->count();
        //кол-во действующих сотрудников
        $query = "select count(*) as cnt from visitor where card in (select code from card where granted = 1 and share = 0)";
        // Составляем SQL запрос
        $conn = $connection->createCommand($query);
        //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
        $row = $conn->queryAll();
        $empl = $row[0]['cnt'];

        //кол-во посетителей на территории
        $query = "select count(*) as cnt from visitor where card in (select code from card where granted = 1 and share = 1)";
        // Составляем SQL запрос
        $conn = $connection->createCommand($query);
        //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
        $row = $conn->queryAll();
        $visit_cnt = $row[0]['cnt'];

        $data = date('Y-m-d');
        //кол-во зашедших сотрудников за день
        $query = "select count(*) as cnt from event where event_time like '$data%' and event_type=16 and card in (select code from card where share=0)";
        // Составляем SQL запрос
        $conn = $connection->createCommand($query);
        //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
        $row = $conn->queryAll();
        $in = $row[0]['cnt'];
        //кол-во вышедших сотрудников за день
        $query = "select count(*) as cnt from event where event_time like '$data%' and event_type=17 and card in (select code from card where share=0)";
        // Составляем SQL запрос
        $conn = $connection->createCommand($query);
        //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
        $row = $conn->queryAll();
        $out = $row[0]['cnt'];
        $empl_cnt = $in - $out;

        return $this->render('index',[
            'devopt' => $devopt,
            'dataDeviceProvider' => $dataDeviceProvider,
            //'searchDeviceModel' => $searchDeviceModel,
            'model' => $model,
            'rentsel' => $rentsel,
            'docs' => $docs,
            'cars' => $cars,
            'free' => $free,
            'busy' => $busy,
            'rent_cnt' => $rent_cnt,
            'empl' => $empl,
            'empl_cnt' => $empl_cnt,
            'visit_cnt' => $visit_cnt,
            'car' => $car,
        ]);
        /*else{
            throw new HttpException(404 ,'Доступ запрещен');
        }*/
    }

    public function actionAddTruck(){
        $model = new CarType();
        if (\Yii::$app->request->isAjax) {
            if ($model->load(\Yii::$app->request->post()) && $model->save()) {
                $id = $model->id;
                $html = '<label class="control-label" for="car_type">Транспортное средство</label>
                         <select id="car_type" class="select2" name="Visitor[car_id]">';
                $cars = CarType::find()->select(['id','text'])->all();
                foreach ($cars as $car){
                    if($car->id==$id){
                        $html .= '<option selected value="'.$car->id.'">'.$car->text.'</option>';
                    }
                    else{
                        $html .= '<option value="'.$car->id.'">'.$car->text.'</option>';
                    }
                }
                $html .= '</select>';
                return $html;
            }
        }
        return 'ERR';
    }

    public function actionAddVisitor(){
        $model = new Visitor();
        //if (\Yii::$app->request->isAjax) {
            if ($model->load(\Yii::$app->request->post())) {
                //проверка что карта есть и она гостевая и проход по ней разрешен
                $card = Card::findOne(['code'=>$model->card]);
                if(empty($card)){
                    //return 'Карта с номером '. $model->card . ' не обнаружена в системе! Для авторизации карты обратитесь к начальнику охраны.';
                    Yii::$app->session->setFlash('error', 'Карта с номером '. $model->card . ' не обнаружена в системе! Для авторизации карты обратитесь к начальнику охраны.');
                    return $this->redirect(['/']);
                }
                else{
                    if($card->share && $card->granted){
                        //проверяем что карта не привязана к другому человеку
                        $busy = Visitor::find()->where(['card'=>$model->card])->count();
                        if($busy){
                            //return 'Карта с номером '. $model->card . ' уже была выдана ранее! Выдача одной карты нескольким посетителям запрещена. Выберите другую карту, а эту сдайте администратору';
                            Yii::$app->session->setFlash('error', 'Карта с номером '. $model->card . ' уже была выдана ранее! Выдача одной карты нескольким посетителям запрещена. Выберите другую карту, а эту сдайте администратору!');
                            return $this->redirect(['/']);
                        }
                        else{
                            //Все нормально, можно выдавать. Проверяем не был ли ранее зарегистрирован данный чел
                            $visitor = Visitor::findOne(['doc_id'=>$model->doc_id,'doc_series'=>$model->doc_series,'doc_num'=>$model->doc_num]);
                            if(empty($visitor)){ //новый
                                if(empty($model->car_num)){
                                    $model->image = '/images/man.png';
                                }
                                else{
                                    $model->image = '/images/truck.png';
                                }
                                $model->save();
                            }
                            else{//был уже
                                $visitor->mname = $model->mname;
                                $visitor->card = $model->card;
                                $visitor->car_id = $model->car_id;
                                $visitor->car_num = $model->car_num;
                                $visitor->renter_id = $model->renter_id;
                                $visitor->save();
                            }
                            //проверяем, что выбрано ТС
                            $car = CarType::findOne($model->car_id)->text;
                            if($car != 'Без ТС'){
                                $device_id = Device::findOne(['type'=>'Z5RWEB'])->id;
                                //смотрим привязку карты к посетителю
                                $visitor_id = Visitor::findOne(['card'=>$model->card])->id;
                                // подключение к базе данных
                                $connection = \Yii::$app->db;
                                $connection->createCommand()->insert('event', [
                                    'device_id' => $device_id,
                                    'event_type' => '16',
                                    'card' => $model->card,
                                    'flag' => '0',
                                    'event_time' => date('Y-m-d H:i:s'),
                                    'visitor_id' => $visitor_id,
                                    'created_at' => date('Y-m-d H:i:s'),
                                    'updated_at' => date('Y-m-d H:i:s'),
                                ])->execute();
                            }
                            //return 'OK';
                            return $this->redirect(['/']);
                        }
                    }
                    elseif(!$card->share){
                        //return 'Карта не является гостевой! Выберите другую карту, а эту сдайте начальнику охраны.';
                        Yii::$app->session->setFlash('error', 'Карта '. $model->card . ' не является гостевой! Выберите другую карту, а эту сдайте начальнику охраны.');
                        return $this->redirect(['/']);
                    }
                    elseif(!$card->granted){
                        //return 'Проход по карте с номером '. $model->card . ' запрещен! Для авторизации карты обратитесь к начальнику охраны.';
                        Yii::$app->session->setFlash('error', 'Проход по карте с номером '. $model->card . ' запрещен! Для авторизации карты обратитесь к начальнику охраны.');
                        return $this->redirect(['/']);
                    }
                }

            }
        //}
        return $this->redirect(['/']);
    }

    public function actionFindVisitor(){
        if(\Yii::$app->request->isAjax){
            $doc_id = $_POST['doc_id'];
            $series = $_POST['series'];
            $doc_num = $_POST['doc_num'];

            $visitor = Visitor::findOne(['doc_id'=>$doc_id, 'doc_series'=>$series, 'doc_num'=>$doc_num]);
            if(empty($visitor)){
                return 'NOT';
            }
            else{
                return json_encode($visitor->toArray());
            }
        }
    }

    public function actionSetMode(){
        if(\Yii::$app->request->isAjax){
            $id = $_POST['device'];
            $mode = $_POST['data'];
            $device = Device::findOne($id);
            if (isset($device)) {
                switch ($mode) {
                    case 'block':
                        $device->mode = 1;
                        break;
                    case 'free':
                        $device->mode = 2;
                        break;
                    case 'normal':
                        $device->mode = 0;
                        break;
                    default:
                        $device->mode = 3;
                }
                $device->save(false);
                return 'OK';
            }
            else
                return 'ERR';
        }
    }

    public function actionCheckEvent(){
        if(\Yii::$app->request->isAjax){
            $persona = '<div class="profile-user-info profile-user-info-striped">';
            //определяем последнее событие в системе
            $event = Event::find()->orderBy(['id' => SORT_DESC,])->limit(1)->all();
            if(!empty($event)){
                $event_type = $event[0]['event_type'];
                $code = $event[0]['card'];
                $visitor_id = $event[0]['visitor_id'];
                $device = Device::findOne($event[0]['device_id'])->text;
                //определяем авторизована ли карта
                $card = Card::findOne(['code'=>$code]);
                $time = $event[0]['event_time'];
                $text = EventType::findOne(['code'=>$event_type])->text;
                if(empty($card)){
                    $image = '<img src="images/stop.png" alt="stop">';
                    $persona .= '<div class="profile-info-row">
								<div class="profile-info-name"> ФИО </div>
								<div class="profile-info-value">
									<span class="editable">Нет данных</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Организация </div>
								<div class="profile-info-value">
									<span class="editable">Нет данных</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Точка прохода </div>
								<div class="profile-info-value">
									<span class="editable">'.$device.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Карта доступа </div>
								<div class="profile-info-value">
									<span class="editable">'.$code.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Событие </div>
								<div class="profile-info-value">
									<span class="editable">'.$text.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Время события </div>
								<div class="profile-info-value">
									<span class="editable">'.$time.'</span>
								</div>
							</div>';
                }
                else{
                    //определяем текущую привязку карты к сотруднику
                    //$visitor = Visitor::findOne(['card'=>$card]);
                    $visitor = Visitor::findOne($visitor_id);
                    if(empty($visitor)){
                        $image = '<img src="images/noimage.jpg" alt="photo">';
                        $persona .= '<div class="profile-info-row">
								<div class="profile-info-name"> ФИО </div>
								<div class="profile-info-value">
									<span class="editable">Нет привязки</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Организация </div>
								<div class="profile-info-value">
									<span class="editable">Нет привязки</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Точка прохода </div>
								<div class="profile-info-value">
									<span class="editable">'.$device.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Карта доступа </div>
								<div class="profile-info-value">
									<span class="editable">'.$card->code.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Событие </div>
								<div class="profile-info-value">
									<span class="editable">'.$text.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Время события </div>
								<div class="profile-info-value">
									<span class="editable">'.$time.'</span>
								</div>
							</div>';
                    }
                    else{
                        $image = '<img src="'.$visitor->image.'" alt="photo">';
                        $persona .= '<div class="profile-info-row">
								<div class="profile-info-name"> ФИО </div>
								<div class="profile-info-value">
									<span class="editable">'.$visitor->lname.' '.$visitor->fname.' '.$visitor->mname.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Организация </div>
								<div class="profile-info-value">
									<span class="editable">'.$visitor->renter->title.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Точка прохода </div>
								<div class="profile-info-value">
									<span class="editable">'.$device.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Карта доступа </div>
								<div class="profile-info-value">
									<span class="editable">'.$card->code.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Событие </div>
								<div class="profile-info-value">
									<span class="editable">'.$text.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Время события </div>
								<div class="profile-info-value">
									<span class="editable">'.$time.'</span>
								</div>
							</div>';
                        if(!empty($visitor->car_id)){
                            $persona .= '<div class="profile-info-row">
								<div class="profile-info-name"> Марка ТС </div>
								<div class="profile-info-value">
									<span class="editable">'.$visitor->car->text.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Рег № </div>
								<div class="profile-info-value">
									<span class="editable">'.$visitor->car_num.'</span>
								</div>
							</div>';
                        }
                    }

                }

            }

            $persona .= '</div';

            $content = '<div class="row">';
            $content .= '<div class="col-xs-6 col-md-3 col-md-offset-1 thumbnail">'.$image.'</div>';
            $content .= '<div class="col-xs-6 col-md-7">'.$persona.'</div>';
            $content .= '</div>';
            return $content;
        }
    }

    public function actionAddAdmin() {
        $model = User::find()->where(['username' => 'ircut'])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = 'ircut';
            $user->email = 'admin@mail.com';
            $user->fname = 'Администратор';
            $user->lname = 'системы';
            $user->setPassword('12345678');
            $user->status = 1;
            $user->role_id = 1;
            $user->generateAuthKey();
            if ($user->save()) {
                return 'Администратор системы создан. Данные для входа: admin (pass 12345678). После первого входа необходимо сменить пароль и установить реальный адрес e-mail и телефон!';
            }
            else{
                throw new HttpException(500 ,'Ошибка выполнения');
            }
        }
    }
}
