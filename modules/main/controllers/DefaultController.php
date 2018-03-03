<?php

namespace app\modules\main\controllers;

use app\models\Events;
use app\models\Weather;
use app\modules\main\models\Config;
use app\modules\main\models\Device;
use app\modules\main\models\Syslog;
use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use \yii\web\HttpException;
use app\modules\user\models\User;
use app\modules\main\models\Location;
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

        return $this->render('index');
        /*else{
            throw new HttpException(404 ,'Доступ запрещен');
        }*/
    }

    public function actionAddAdmin() {
    //    if(Yii::$app->user->can('admin')) {
            $model = User::find()->where(['username' => 'ircut'])->one();
        if (empty($model)) {
            $user = new User();
            $user->username = 'ircut';
            $user->email = 'admin@mail.com';
            $user->phone = '1234567890';
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
    //    }
    //    else{
    //        throw new HttpException(404 ,'Действие запрещено');
    //    }
    }
}
