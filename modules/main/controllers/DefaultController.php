<?php

namespace app\modules\main\controllers;

use app\modules\admin\models\Device;
use app\modules\admin\models\SearchDevice;
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

        $searchEventModel = new SearchEvent();
        $dataEventProvider = $searchEventModel->search(Yii::$app->request->queryParams);

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

        return $this->render('index',[
            'devopt' => $devopt,
            'dataDeviceProvider' => $dataDeviceProvider,
            //'searchDeviceModel' => $searchDeviceModel,
            'dataEventProvider' => $dataEventProvider,
            'searchEventModel' => $searchEventModel,
            'model' => $model,
            'rentsel' => $rentsel,
            'docs' => $docs,
            'cars' => $cars,
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
