<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\user\models\User;
use app\modules\user\models\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\admin\models\Role;
use app\models\LibraryModel;

/**
 * UserController implements the CRUD actions for User model.
 */
class UserController extends Controller
{
    public $layout = '@app/views/layouts/main.php';
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['adminTask']
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new User model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            //генерим новый пароль
            $pass = $this->makepass(8);
            $model->setPassword($pass);
            if(empty($model->image))
                $model->image = '/images/male.png';
            //$model->save();
            if ($model->save()) {
                $site = Yii::$app->urlManager->createAbsoluteUrl(['/']);
                $msg = '<html><head><title>Новый пользователь</title></head>
                    <body><h3>Доступ к сайту</h3>
                    <p>Здравствуйте!<br>Ваши учетные данные для доступа к сайту '.$site.'<br>Логин: '.$model->username.'<br>Пароль: '.$pass.'</p>
                    <em style="color:red;">Письмо отправлено автоматически. Отвечать на него не нужно.</em><br>
                    <p style="color:darkblue;">С уважением,<br> Ваш почтовый робот.</p>
                    </body></html>';
                $sent = Yii::$app->mailer->compose()
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
                    ->setTo($model->email)
                    ->setSubject('Данные для ' . $model->fname.' '.$model->lname)
                    ->setHtmlBody($msg)
                    ->send();
            }
            $msg = 'Пользователем <strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong> добавлена новая учетная запись <strong>'. $model->username .'</strong>.';
            LibraryModel::AddEventLog('info',$msg);
            $model->AddRole($model->role->name,$model->id); //добавляем права пользователю
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $statsel = ['0'=>'Не активный', '1'=>'Активный'];
            $rolesel = array();
            $roles = Role::find()->select(['id','alias'])->asArray()->all();
            $def = Role::find()->where(['name'=>'operator'])->asArray()->all();
            foreach($roles as $role) {
                $rolesel[$role['id']] = $role['alias']; //массив для заполнения данных в select формы
            }
            return $this->render('create', [
                'model' => $model,
                'statsel' => $statsel,
                'rolesel' => $rolesel,
                'def' => $def[0]['id']
            ]);
        }
    }

    /**
     * Updates an existing User model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $msg = 'Пользователь <strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong> обновил учетную запись <strong>'. $model->username .'</strong>.';
            LibraryModel::AddEventLog('info',$msg);
            $model->UpdateRole($model->role->name,$model->id); //обновляем права пользователю
            if(empty($model->image))
                $model->image = '/images/male.png';
            $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            $statsel = ['0'=>'Не активный', '1'=>'Активный'];
            $rolesel = array();
            $roles = Role::find()->select(['id','alias'])->asArray()->all();
            $def = User::findOne($id);
            foreach($roles as $role) {
                $rolesel[$role['id']] = $role['alias']; //массив для заполнения данных в select формы
            }
            return $this->render('update', [
                'model' => $model,
                'statsel' => $statsel,
                'rolesel' => $rolesel,
                'def' => $def->role_id
            ]);
        }
    }

    /**
     * Deletes an existing User model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        //пользователь админ?
        if($model->role->name=='admin'){
            //смотрим сколько админов есть в системе
            $admins = User::find()->where(['=','role_id',$model->role_id])->count();
            if($admins>1){
                $msg = 'Пользователь <strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong> удалил учетную запись <strong>'. $model->username .'</strong>.';
                $model->delete();
                LibraryModel::AddEventLog('info',$msg);
            }
            else{
                Yii::$app->session->setFlash('error', 'Единственного администратора удалить нельзя!');
            }
        }
        else{
            $msg = 'Пользователь <strong>'.Yii::$app->user->identity->fname .' '.Yii::$app->user->identity->lname.'</strong> удалил учетную запись <strong>'. $model->username .'</strong>.';
            $model->delete();
            LibraryModel::AddEventLog('info',$msg);
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the User model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    //функция генерации случайного пароля
    protected function makepass($num_chars)
    {
        $pass='';
        if((is_numeric($num_chars))&&($num_chars>0)&&(!is_null($num_chars)))
        {
            $accepted_chars='abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRSTUVWXYZ1234567890';
            srand(((int)((double)microtime()*1000003)));
            for($i=0;$i<=$num_chars;$i++)
            {
                $random_number=rand(0,(strlen($accepted_chars)-1));
                $pass.=$accepted_chars[$random_number];
            }
        }
        return $pass;
    }
}
