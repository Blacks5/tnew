<?php
namespace common\models;

use Yii;
use yii\base\Model;
use backend\models\Log;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username and password are both required
            [['username', 'password'], 'required', 'message' => '{attribute}不能为空'],
            // rememberMe must be a boolean value
            ['rememberMe', 'boolean'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'password' => '密码',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $_user = $this->getUser();
                return Yii::$app->getUser()->login($_user); // , $this->rememberMe ? 3600 * 24 * 30 : 0
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {

        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }

    /**
     * 登录 记录
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function loginLog()
    {
        $userIP = Yii::$app->getRequest()->getUserIP();
        if(Yii::$app->getUser()->getIdentity()->username !== 'admin') {
            Yii::$app->getDb()->createCommand()->insert(
                Log::tableName(), [
                'username' => $this->username,
                'create_time' => $_SERVER['REQUEST_TIME'],
                'ip' => $userIP,
                'data' => '',
            ])->execute();
        }
    }
}
