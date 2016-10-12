<?php
namespace common\models;

/**
 * 注册模型, 放在前台, 只能通过前台注册.
 */
use common\helper\GetPcc;
use common\models\User;
use yii\base\Exception;
use yii\db\Query;
use yii;

/**
 * Signup form
 */
class SignupForm extends \yii\base\Model
{
    public $username;
    public $realname;
    public $email;
    public $password;
    public $password_2;
    public $status;
    public $province;
    public $city;
    public $county;


    public function scenarios()
    {
        $scena = parent::scenarios();
        $scena['create'] = ['username', 'realname', 'status', 'email', 'province', 'city', 'county', 'password', 'password_2'];
        $scena['update'] = ['username', 'realname', 'status', 'email', 'province', 'city', 'county', 'password', 'password_2'];
        return $scena;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username', 'realname'], 'trim'],
            [['username', 'status', 'email', 'province', 'city', 'county', 'realname'], 'required', 'message' => '{attribute}必须填写'],
            [['password', 'password_2'], 'required', 'message' => '{attribute}必须填写', 'on' => 'create'],
            ['username', 'unique', 'targetClass' => '\app\models\User', 'message' => '{attribute}已存在', 'on' => 'create'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => 'app\models\User', 'message' => '邮箱已存在', 'on' => 'create'],
            [['password', 'password_2'], 'string', 'min' => 6, 'tooShort' => '{attribute}至少6位长度'],
            ['password_2', 'compare', 'compareAttribute' => 'password', 'operator' => '===', 'message' => '{attribute}和{compareValueOrAttribute}不一致']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'username' => '用户名',
            'realname' => '真实姓名',
            'email' => '邮箱',
            'password' => '密码',
            'password_2' => '重复密码',
            'status' => '状态',
            'province' => '省',
            'city' => '市',
            'county' => '县/区'
        ];
    }

    /**
     * 添加员工
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup($param)
    {
        $this->scenario = 'create';
        $this->load($param);
        p($param, $this->getAttributes());
        if (!$this->validate()) {
//            p($this->errors);
            return null;
        }
//        p(GetPcc::getAddName($this->province));
        $user = new User();
        $user->username = $this->username;
        $user->realname = $this->realname;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->province = GetPcc::getAddName($this->province);
        $user->city = GetPcc::getAddName($this->city);
        $user->county = GetPcc::getAddName($this->county);
        $res = $user->save();
        return $res ? $user : null;
    }

    /**
     * 修改员工
     * @param $param
     * @return null
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function update($param, $id)
    {
        p($param);
        $this->scenario = 'update';
        $data['data'] = $param;
        $this->load($param, 'User');
//        p($param, $this->getAttributes());
        if (!$this->validate()) {
            p($this->errors);
            return null;
        }
        $columns = $this->getAttributes();
        p($columns);
        $user = new User();
        $columns['password_hash'] = $user->setPassword2($columns['password']);
        unset($columns['password_2'], $columns['password']);
        $columns['province'] = GetPcc::getAddName($columns['province']);
        $columns['city'] = GetPcc::getAddName($columns['city']);
        $columns['county'] = GetPcc::getAddName($columns['county']);
        p($user->updateAll($columns, ['id' => $id]));
//        yii::$app->db->createCommand()->update(User::tableName(), $columns, ['id'=>$id])->execute();
//        $user = (new Query())->from(User::tableName())->where(['id'=>$param['id']]);
    }
}
