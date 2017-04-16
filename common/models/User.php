<?php

namespace common\models;

use backend\models\AuthAssignment;
use yii;
use common\core\CoreCommonActiveRecord;
/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $realname
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property string $id_card_num
 * @property string $address
 * @property string $county
 * @property string $city
 * @property string $province
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends CoreCommonActiveRecord implements \yii\web\IdentityInterface
{
    public $password_hash_1; // 重复密码
    public $old_password; // 原始密码


    // 10正常 0删除 1禁用 2离职
    const STATUS_ACTIVE = 10;
    const STATUS_DELETE = 0;
    const STATUS_STOP = 1;
    const STATUS_LEAVE = 2;

    /**
     * 返回员工所有状态
     * @return array
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function getAllStatus()
    {
        return [
            self::STATUS_ACTIVE => '正常',
            self::STATUS_STOP => '锁定',
            self::STATUS_LEAVE => '离职',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at'], 'safe'],
            ['password_hash', 'required', 'on'=>'create', 'message'=>'{attribute}必须填写'],
            ['email', 'email', 'message'=>'{attribute}错误'],
            [['username', 'realname', 'email', 'county', 'city', 'province', 'password_hash_1', 'password_hash', 'old_password', 'id_card_num', 'address'], 'required',
                'message'=>'{attribute}必须填写'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key'], 'string', 'max' => 32],
            [['realname'], 'string', 'max' => 10],
            [['password_hash', 'password_reset_token', 'email', 'password_hash_1'], 'string', 'max' => 256, 'min' => 6, 'tooShort'=>'{attribute}至少为6位'],
            [['county', 'city', 'province'], 'string', 'max' => 20],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => '{attribute}已存在', 'on'=>'create'],
//            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => '邮箱已存在', 'on'=>'create'],
            [['cellphone'], 'match', 'pattern' => '/^1[3|5|7|8]\d{9}$/', 'message'=>'错误的手机号码'],

            [['password_hash_1'], 'compare', 'compareAttribute'=>'password_hash', 'message'=>'两次密码不一致'],
            [['department_id', 'job_id'], 'safe'],

            ['address', 'string', 'min'=>10, 'max'=>'150', 'tooShort'=>'地址太短了', 'tooLong'=>'地址太长了'],
            ['id_card_num', 'match', 'pattern'=>'/^(\d{15}$|^\d{18}$|^\d{17}(\d|X|x))$/', 'message'=>'身份证号码错误']
        ];
    }
    public function scenarios()
    {
        $scen = parent::scenarios();
        $scen['create'] = ['id_card_num', 'address', 'username', 'realname', 'password_hash', 'password_hash_1', 'county', 'city', 'province', 'email', 'status', 'cellphone', 'department_id', 'job_id'];
        $scen['update'] = [/*'username', 'realname', 'password_hash',*/ 'email', 'county', 'city', 'province', 'status', 'cellphone', 'department_id', 'job_id', 'id_card_num', 'address'];
        $scen['modpwd'] = ['password_hash', 'password_hash_1'];
//        $scen['modselfpwd'] = ['password_hash', 'password_hash_1', 'old_password'];
        return $scen;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'realname' => '真实姓名',
            'auth_key' => 'Auth Key', // cookie登录用
            'password_hash' => '密码',
            'address'=>'联系地址',
            'id_card_num'=>'身份证号码',
            'password_hash_1' => '密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'county' => '县/区',
            'city' => '市',
            'province' => '省',
            'status' => '状态', // 10正常 0删除 1禁用 2离职
            'created_at' => '创建时间',
            'updated_at' => 'Updated At',
        ];
    }



    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            return $this->updated_at = $_SERVER['REQUEST_TIME'];
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::find()->where(['id'=>$id, 'status'=>self::STATUS_ACTIVE])->one();
    }

    /**
     * api接口用
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::find()->where(['access_token' => $token])->one();
        // 暂时不用
        /*foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }
        return null;*/
    }

    public function getUsergroup()
    {
        /**
         * 第一个参数为要关联的字表模型类名称，
         *第二个参数指定 通过子表的 customer_id 去关联主表的 id 字段
         */
        return $this->hasOne(AuthAssignment::className(), ['user_id' => 'id']);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username'=>$username, 'status'=>self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * 获取基于cookie登录时的验证密钥
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
     * Validates passwordf1
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * 加密密码
     * @param $password
     * @throws \yii\base\Exception
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function setPassword($password)
    {
        $this->password_hash = \yii::$app->security->generatePasswordHash($password);
    }
    /**
     * 生成验证串
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function generateAuthkey()
    {
        $this->auth_key = \yii::$app->security->generateRandomString();
    }

    /**
     * 添加用户
     * @param $param
     * @return bool
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function createUser($param)
    {
        $this->scenario = 'create';
        if(!$this->load($param) || !$this->validate()){
//            p($this->errors);
            return false;
        }
        $this->setPassword($this->password_hash);
        $this->access_token = \yii::$app->security->generatePasswordHash($this->password_hash);
        $this->generateAuthkey();
        $this->created_at = $_SERVER['REQUEST_TIME'];
        return $this->save(false) ? $this: null;
    }

    /**
     * 编辑用户
     * @param $param
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function updateUser($param)
    {
        $this->setScenario('update');
        if(!$this->load($param) || !$this->validate()){
            return false;
        }
        return $this->save(false) ? $this : null;
    }

    /**
     * 修改密码
     * @param $userid
     * @param $newpwd
     * @return bool
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function modpwd($param, $userid)
    {
        $this->scenario = 'modpwd';
        $this->load($param);
        if(!$this->validate()){
            return false;
        }

        $this->password_hash = Yii::$app->security->generatePasswordHash($this->password_hash);
        $this->access_token = Yii::$app->security->generatePasswordHash($this->password_hash);
        if(!$this->update(false)){
            return false;
        }
        return true;
    }

    /**
     * 修改本账号的密码
     * @param $param
     * @return bool
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function modselfpwd($param)
    {
        if(!$this->validatePassword($param['User']['old_password'])){
            return $this->addError('old_password', '原始密码错误');
        }
        if($param['User']['password_hash'] !== $param['User']['password_hash_1']){
            $this->addError('password_hash', '两次密码不一致');
            return $this->addError('password_hash_1', '两次密码不一致');
        }
        $this->password_hash = Yii::$app->security->generatePasswordHash($param['User']['password_hash']);
        $this->access_token = Yii::$app->security->generatePasswordHash($param['User']['password_hash']);
        $this->updated_at = $_SERVER['REQUEST_TIME'];
        if(!$this->update(false)){
            return false;
        }
        return true;
    }


    /**
     * 更新access_token ，保证安全
     * @param User $user
     * @return null
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public static function updateAccessToken(\common\models\User $user)
    {
        $user->access_token = Yii::$app->security->generatePasswordHash($user->password_hash);
        return $user->save(false) ? : null;
    }
}
