<?php
namespace app\modules\user\models;
use app\modules\user\models\User;
use yii\base\Model;
use Yii;
/**
 * Password reset request form
 */
class PasswordResetRequestForm extends Model
{
    public $email;
    private $_user = false;
    private $_timeout;
    /**
     * PasswordResetRequestForm constructor.
     * @param integer $timeout
     * @param array $config
     */
    public function __construct($timeout, $config = [])
    {
        $this->_timeout = $timeout;
        parent::__construct($config);
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => User::className(),
                'filter' => ['status' => User::STATUS_ACTIVE],
                'message' => 'Пользователь с таким адресом не найден.'
            ],
            ['email', 'validateIsSent'],
        ];
    }
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Email',
        ];
    }
    /**
     * @param string $attribute
     * @param array $params
     */
    public function validateIsSent($attribute, $params)
    {
        if (!$this->hasErrors() && $user = $this->getUser()) {
            if (User::isPasswordResetTokenValid($user->$attribute, $this->_timeout)) {
                $this->addError($attribute, 'Токен уже отправлен.');
            }
        }
    }
    /**
     * Sends an email with a link, for resetting the password.
     *
     * @return boolean whether the email was send
     */
    public function sendEmail()
    {
        if ($user = $this->getUser()) {
            $user->generatePasswordResetToken();
            if ($user->save()) {
                $resetLink = Yii::$app->urlManager->createAbsoluteUrl(['user/default/password-reset', 'token' => $user->password_reset_token]);
                $site = Yii::$app->urlManager->createAbsoluteUrl(['/']);
                $msg = '<html><head><title>Запрос на сброс пароля</title></head>
                    <body><h3>Сброс пароля для '.$user->username.'</h3>
                    <p>Здравствуйте!<br>С вашего e-mail поступил запрос на сброс пароля на сайте '.$site.'<br>Для сброса пароля перейдите по ссылке '.$resetLink.'</p>
                    <em style="color:red;">Письмо отправлено автоматически. Отвечать на него не нужно.</em><br>
                    <p style="color:darkblue;">С уважением,<br> Ваш '.Yii::$app->name.'.</p>
                    </body></html>';
                return Yii::$app->mailer->compose()
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Запрос на сброс пароля для ' . $user->username)
                    ->setHtmlBody($msg)
                    ->send();
            }
        }
        return false;
    }
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findOne([
                'email' => $this->email,
                'status' => User::STATUS_ACTIVE,
            ]);
        }
        return $this->_user;
    }
}