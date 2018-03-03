<?php
namespace app\modules\user\models;

use yii\base\Model;
use yii\db\ActiveQuery;
use Yii;

class ProfileUpdateForm extends Model
{
    public $email;
    public $fname;
    public $lname;
    public $phone;

    /**
     * @var User
     */
    private $_user;

    public function __construct(User $user, $config = [])
    {
        $this->_user = $user;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->fname = $user->fname;
        $this->lname = $user->lname;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            ['fname', 'required'],
            ['fname', 'string', 'max' => 50],

            ['lname', 'required'],
            ['lname', 'string', 'max' => 50],

            ['email', 'required'],
            ['email', 'email'],
            //['email', 'unique', 'targetClass' => self::className(), 'message' => 'Пользователь с таким e-mail уже существует.'],
            [
                'email',
                'unique',
                'targetClass' => User::className(),
                'message' => 'Пользователь с таким e-mail уже существует.',
                'filter' => ['<>', 'id', $this->_user->id],
            ],
            ['email', 'string', 'max' => 40],
            ['phone', 'required'],
            ['phone', 'string', 'max' => 12],
            [
                'phone',
                'unique',
                'targetClass' => User::className(),
                'message' => 'Такой телефон уже существует.',
                'filter' => ['<>', 'id', $this->_user->id],
            ],
        ];
    }

    public function update()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->email = $this->email;
            $user->fname = $this->fname;
            $user->lname = $this->lname;
            $user->phone = $this->phone;
            return $user->save();
        } else {
            return false;
        }
    }

    public function attributeLabels()
    {
        return [
            'email' => 'Email',
            'fname' => 'Имя',
            'lname' => 'Фамилия',
            'phone' => 'Телефон',
        ];
    }
}