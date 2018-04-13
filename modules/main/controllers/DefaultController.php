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
        ]);
        /*else{
            throw new HttpException(404 ,'Доступ запрещен');
        }*/
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
            $event_type = $event[0]['event_type'];
            $code = $event[0]['card'];
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
									<span class="editable" id="username">Нет данных</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Организация </div>
								<div class="profile-info-value">
									<span class="editable" id="username">Нет данных</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Точка прохода </div>
								<div class="profile-info-value">
									<span class="editable" id="username">'.$device.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Карта доступа </div>
								<div class="profile-info-value">
									<span class="editable" id="username">'.$code.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Событие </div>
								<div class="profile-info-value">
									<span class="editable" id="username">'.$text.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Время события </div>
								<div class="profile-info-value">
									<span class="editable" id="username">'.$time.'</span>
								</div>
							</div>';
            }
            else{
                //определяем текущую привязку карты к сотруднику
                $visitor = Visitor::findOne(['card'=>$card]);
                if(empty($visitor)){
                    $image = '<img src="images/noimage.jpg" alt="photo">';
                    $persona .= '<div class="profile-info-row">
								<div class="profile-info-name"> ФИО </div>
								<div class="profile-info-value">
									<span class="editable" id="username">Нет привязки</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Организация </div>
								<div class="profile-info-value">
									<span class="editable" id="username">Нет привязки</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Точка прохода </div>
								<div class="profile-info-value">
									<span class="editable" id="username">'.$device.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Карта доступа </div>
								<div class="profile-info-value">
									<span class="editable" id="username">'.$card->code.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Событие </div>
								<div class="profile-info-value">
									<span class="editable" id="username">'.$text.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Время события </div>
								<div class="profile-info-value">
									<span class="editable" id="username">'.$time.'</span>
								</div>
							</div>';
                }
                else{
                    $image = '<img src="'.$visitor->image.'" alt="photo">';
                    $persona .= '<div class="profile-info-row">
								<div class="profile-info-name"> ФИО </div>
								<div class="profile-info-value">
									<span class="editable" id="username">'.$visitor->lname.' '.$visitor->fname.' '.$visitor->mname.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Организация </div>
								<div class="profile-info-value">
									<span class="editable" id="username">'.$visitor->renter->title.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Точка прохода </div>
								<div class="profile-info-value">
									<span class="editable" id="username">'.$device.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Карта доступа </div>
								<div class="profile-info-value">
									<span class="editable" id="username">'.$card->code.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Событие </div>
								<div class="profile-info-value">
									<span class="editable" id="username">'.$text.'</span>
								</div>
							</div>
							<div class="profile-info-row">
								<div class="profile-info-name"> Время события </div>
								<div class="profile-info-value">
									<span class="editable" id="username">'.$time.'</span>
								</div>
							</div>';
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
