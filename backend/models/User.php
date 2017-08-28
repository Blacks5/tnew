<?php

namespace backend\models;

use Yii;
use backend\models\AuthAssignment;
/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $email
 * @property integer $role
 * @property integer $status
 * @property
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends \yii\db\ActiveRecord
{
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
            [['username','email'], 'required'],
            //[['role', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'password_hash', 'password_reset_token', 'email'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => '密码',
            'password_hash' => 'Password Hash',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'role' => 'Role',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
    //获取所有用户
    public function get_all_user(){
        $user = Yii::$app->db->createCommand('select * from user')->queryAll();
        return $user;
    }
    public function getUsergrouplist()
    {
        /**
         * 第一个参数为要关联的字表模型类名称，
         *第二个参数指定 通过子表的 customer_id 去关联主表的 id 字段
         */
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
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

    /**
     * 根绝城市选择上级领导
     * @param $city
     * @param $leader
     * @return array
     */
    public static function getLeader($cityName,$cityId,$parentLeader)
    {


        if($parentLeader==1){
            $data = static::find()->select(['id','realname'])->where(['province'=>1,'department_id'=>26,'leader'=>1])->all();
        }else{
            $data = static::find()->select(['id','realname'])->where([$cityName=>$cityId,'department_id'=>26,'leader'=>$parentLeader])->all();
        }


        return $data;
    }

    /**
     * 根据job_id返回当前用户的级别
     * @param $leader
     * @return int
     */
    public function jobToleader($leader){
        switch ($leader){
            case $leader== 45:  //销售总监
                return 1;
                break;
            case $leader == 46 :    //大区经理
                return 2;
                break;
            case $leader==47 || $leader==48:    //城市经理
                return 3;
                break;
            case $leader ==49 || $leader==50 ||$leader==51: //销售经理
                return 4;
                break;
            case $leader == 52: //销售主管
                return 5;
                break;
            case $leader==53||$leader==54:  //销售人员
                return 6;
                break;
        }
    }

}
