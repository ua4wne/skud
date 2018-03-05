<?php

namespace app\modules\user\models;
use app\models\ArrayHelper;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use app\models\BaseModel;
use app\modules\admin\models\Role;

/**
 * User model
 *
 * @property integer $id
 * @property string $username
 * @property string $fname
 * @property string $lname
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 */
class User extends BaseModel implements IdentityInterface
{
    const STATUS_ACTIVE = 1; //активный пользователь
    const STATUS_BLOCKED = 0; //заблокированный пользователь

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user'; //return '{{%user}}';
    }

    public function getStatusName()
    {
        return ArrayHelper::getValue(self::getStatusesArray(), $this->status);
    }

    public static function getStatusesArray()
    {
        return [
            self::STATUS_BLOCKED => 'Заблокирован',
            self::STATUS_ACTIVE => 'Активен',
        ];
    }

    /**
     * @inheritdoc
     */
    /*public function behaviors()
    {
        return [
            TimestampBehavior::className(), //чтобы записывать дату в поля created_at и updated_at
        ];
    }*/

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'required'],
            ['username', 'match', 'pattern' => '#^[\w_-]+$#is'],
            ['username', 'unique', 'targetClass' => self::className(), 'message' => 'Пользователь с таким логином уже существует.'],
            ['username', 'string', 'min' => 3, 'max' => 30],
            ['image', 'string', 'max' => 30],

            ['fname', 'required'],
            ['fname', 'string', 'max' => 50],

            ['lname', 'required'],
            ['lname', 'string', 'max' => 50],

            ['role_id', 'required'],

            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => self::className(), 'message' => 'Пользователь с таким e-mail уже существует.'],
            ['email', 'string', 'max' => 40],

            ['status', 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => array_keys(self::getStatusesArray())],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'created_at' => 'Создан',
            'updated_at' => 'Обновлён',
            'username' => 'Логин',
            'email' => 'Email',
            'status' => 'Статус',
            'fname' => 'Имя',
            'lname' => 'Фамилия',
            'role_id' => 'Роль',
            'image' => 'Аватар'
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->generateAuthKey();
            }
            return true;
        }
        return false;
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }
        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        $parts = explode('_', $token);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    public function AddRole($role_name, $id){

        $auth = Yii::$app->authManager;
        $role = $auth->getRole($role_name); // Получаем роль
        $auth->assign($role, $id); // Назначаем пользователю
        Yii::$app->session->setFlash('success', 'Разрешения добавлены!');
    }

    public function UpdateRole($role_name,$id){
        if($id!=1){ //права первого админа менять нельзя
            $query="select * from auth_assignment where user_id=$id"; //ищем предыдущие разрешения
            // подключение к базе данных
            $connection = \Yii::$app->db;
            // Составляем SQL запрос
            $conn = $connection->createCommand($query);
            //Осуществляем запрос к базе данных, переменная $model содержит ассоциативный массив с данными
            $rows = $conn->queryAll();
            //return print_r($rows);
            if(!empty($rows)){
                $query="delete from auth_assignment where user_id=$id"; //удаляем предыдущие разрешения
                // подключение к базе данных
                $connection = \Yii::$app->db;
                // Составляем SQL запрос
                $conn = $connection->createCommand($query);
                $conn->execute();
            }
            $auth = Yii::$app->authManager;
            $role = $auth->getRole($role_name); // Получаем роль
            $auth->assign($role, $this->id); // Назначаем пользователю, которому принадлежит модель User
            Yii::$app->session->setFlash('success', 'Разрешения обновлены!');

        }
    }
}
