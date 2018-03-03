<?php

namespace app\modules\user\controllers;
use yii\filters\AccessControl;
use yii\web\Controller;
use Yii;
use app\modules\user\models\User;
use app\modules\user\models\UploadImage;
use yii\web\UploadedFile;
use app\modules\user\models\ProfileUpdateForm;
use app\models\LibraryModel;
use app\modules\user\models\PasswordChangeForm;

class ProfileController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index', [
            'model' => $this->findModel(),
        ]);
    }

    public function actionAvatar(){
        $model = new UploadImage();
        if(Yii::$app->request->isPost){
            $model->image = UploadedFile::getInstance($model, 'image');
            $model->upload();
            //обновляем данные аватара
            $user = User::findIdentity(Yii::$app->user->identity->getId());
            $user->image = '/images/'.$model->image->name;
            $user->save();
            $msg = 'Пользователь <strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong> сменил свой аватар.';
            LibraryModel::AddEventLog('info',$msg);
        }
        return $this->render('upload', [
            'model' => $model]
        );
    }

    public function actionUpdate()
    {
        $user = $this->findModel();
        $model = new ProfileUpdateForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->update()) {
            $msg = 'Пользователь <strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong> обновил свой профиль.';
            LibraryModel::AddEventLog('info',$msg);
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionPassword()
    {
        $user = $this->findModel();
        $model = new PasswordChangeForm($user);

        if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
            $msg = 'Пользователь <strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong> сменил свой пароль.';
            LibraryModel::AddEventLog('info',$msg);
            return $this->redirect(['index']);
        } else {
            return $this->render('password', [
                'model' => $model,
            ]);
        }
    }

    /**
     * @return User the loaded model
     */
    private function findModel()
    {
        return User::findOne(Yii::$app->user->identity->getId());
    }

}
