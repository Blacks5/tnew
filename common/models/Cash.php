<?php

/**
 * 现金贷业务
 * @Author: MuMu
 * @Date:   2017-11-20 13:47:36
 * @Last Modified by:   MuMu
 * @Last Modified time: 2018-04-04 17:47:19
 */

namespace common\models;

use common\core\CoreCommonActiveRecord;

class Cash extends CoreCommonActiveRecord {
	public $orderID;
	public $reason;
	public $loanAmount;
	public $installmentCycle;
	public $installmentPeriod;
	public $productType;
	public $purpose;
	public $realName;
	public $certNo;
	public $bankCardNo;
	public $mobileNo;
	public $bankMobileNo;
	public $address;
	public $gender;
	public $marital;
	public $monthlyIncome;
	public $houseProperty;
	public $cardAddress;
	public $currentAddress;
	public $jobName;
	public $jobAddress;
	public $jobPhone;
	public $wechat;
	public $qq;
	public $alipay;
	public $contactName;
	public $contactRelation;
	public $contactPhone;
	public $remark;

	/**
	 * @inheritdoc
	 */
	public static function tableName() {
		return 'orders';
	}

	/**
	 * @inheritdoc
	 */
	public function rules() {
		return [
			['loanAmount', 'required', 'message' => '贷款金额必需填写'],
			['loanAmount', 'number', 'message' => '贷款金额格式不正确'],
			['loanAmount', 'compare', 'compareValue' => 0, 'operator' => '>', 'message' => '贷款金额必需大于0'],
			['installmentCycle', 'required', 'message' => '请选择分期方式'],
			['installmentCycle', 'in', 'range' => ['week', 'month'], 'message' => '请选择分期方式'],
			['installmentPeriod', 'required', 'message' => '请选择分期时长'],
			['installmentPeriod', 'integer', 'message' => '请选择分期时长'],
			['productType', 'required', 'message' => '请选择产品类型'],
			['productType', 'integer', 'message' => '请选择产品类型'],
			['purpose', 'required', 'message' => '请选择借款用途'],
			['purpose', 'string', 'message' => '请选择借款用途', 'length' => [1, 20]],
			['realName', 'required', 'message' => '请输入客户姓名'],
			['realName', 'string', 'message' => '客户姓名不合法', 'length' => [2, 10]],
			['certNo', 'required', 'message' => '请输入身份证号'],
			['certNo', 'match', 'message' => '身份证号不合法', 'pattern' => '/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/'],
			['bankCardNo', 'required', 'message' => '请输入银行卡号'],
			['bankCardNo', 'string', 'message' => '银行卡号不合法', 'length' => [16, 19]],
			['bankMobileNo', 'required', 'message' => '请输入银行预留手机号'],
			['bankMobileNo', 'match', 'message' => '预留手机号不合法', 'pattern' => '/^(13[0-9]|14[579]|15[012356789]|16[6]|17[0-9]|18[0-9]|19[89])[0-9]{8}$/'],
			['mobileNo', 'required', 'message' => '请输入联系手机号'],
			['mobileNo', 'match', 'message' => '联系手机号不合法', 'pattern' => '/^(13[0-9]|14[579]|15[012356789]|16[6]|17[0-9]|18[0-9]|19[89])[0-9]{8}$/'],
			['address', 'required', 'message' => '请输入调查地址'],
			['address', 'string', 'message' => '调查地址长度在2~200之间', 'length' => [2, 200]],
			['contactName', 'required', 'message' => '请输入联系人姓名'],
			['contactName', 'string', 'message' => '联系人姓名长度在2~20之间', 'length' => [2, 20]],
			['contactRelation', 'required', 'message' => '请选择联系人关系'],
			['contactRelation', 'in', 'message' => '请选择联系人关系', 'range' => ['family', 'workmate', 'friend', 'other']],
			['contactPhone', 'required', 'message' => '请输入联系人手机'],
			['contactPhone', 'match', 'message' => '联系人手机不合法', 'pattern' => '/^(13[0-9]|14[579]|15[012356789]|16[6]|17[0-9]|18[0-9]|19[89])[0-9]{8}$/'],
			['orderID', 'required', 'message' => '参数异常'],
			['orderID', 'number', 'message' => '参数异常'],
			['reason', 'required', 'message' => '请输入取消原因'],
			['reason', 'string', 'length' => [2, 60], 'message' => '取消原因长度在2~60之间'],
			['gender', 'required', 'message' => '请选择客户性别'],
			['gender', 'in', 'range' => ['男', '女'], 'message' => '请选择客户性别'],
			['marital', 'required', 'message' => '请选择婚姻状况'],
			['marital', 'in', 'range' => ['married', 'unmarried', 'divorced', 'widowhood'], 'message' => '请选择婚姻状况'],
			['monthlyIncome', 'required', 'message' => '请输入月收入'],
			['monthlyIncome', 'number', 'message' => '月收入格式不正确'],
			['monthlyIncome', 'compare', 'compareValue' => 0, 'operator' => '>', 'message' => '月收入额必需大于0'],
			['houseProperty', 'required', 'message' => '请选择房屋权属'],
			['houseProperty', 'in', 'range' => ['rented', 'owned'], 'message' => '请选择房屋权属'],
			['cardAddress', 'required', 'message' => '请输入户籍地址'],
			['cardAddress', 'string', 'message' => '户籍地址长度在200以内', 'length' => [0, 200]],
			['currentAddress', 'required', 'message' => '请输入居住地址'],
			['currentAddress', 'string', 'message' => '居住地址长度在200以内', 'length' => [0, 200]],
			['jobName', 'required', 'message' => '请输入工作单位'],
			['jobName', 'string', 'message' => '工作单位长度在200以内', 'length' => [0, 200]],
			['jobAddress', 'required', 'message' => '请输入单位地址'],
			['jobAddress', 'string', 'message' => '单位地址长度在200以内', 'length' => [0, 200]],
			['jobPhone', 'required', 'message' => '请输入单位电话'],
			['jobPhone', 'match', 'message' => '单位电话格式不正确', 'pattern' => '/(^(\d{3,4}-?)?\d{7,9}$)|(^(13[0-9]|14[579]|15[012356789]|16[6]|17[0-9]|18[0-9]|19[89])[0-9]{8}$)/'],
			['wechat', 'required', 'message' => '请输入微信账号'],
			['wechat', 'string', 'message' => '微信账号长度在40以内', 'length' => [0, 40]],
			['qq', 'required', 'message' => '请输入QQ账号'],
			['qq', 'string', 'message' => 'QQ账号长度在20以内', 'length' => [0, 20]],
			['alipay', 'required', 'message' => '请输入支付宝账号'],
			['alipay', 'string', 'message' => '支付宝账号长度在40以内', 'length' => [0, 40]],
			['remark', 'required', 'message' => '请输入备注'],
			['remark', 'string', 'message' => '备注长度在200以内', 'length' => [0, 200]],
		];
	}

	/**
	 * @inheritdoc
	 */
	public function attributeLabels() {
		return [
			'loanAmount' => '贷款金额',
			'installmentCycle' => '分期方式',
			'installmentPeriod' => '分期时长',
			'productType' => '产品类型',
			'purpose' => '借款用途',
			'realName' => '客户姓名',
			'certNo' => '身份证号',
			'bankCardNo' => '银行卡号',
			'bankMobileNo' => '预留手机号',
			'mobileNo' => '联系手机号',
			'address' => '调查地址',
			'contactName' => '联系人姓名',
			'contactRelation' => '联系人关系',
			'contactPhone' => '联系人手机',
			'orderID' => '订单ID',
			'reason' => '取消原因',
			'gender' => '客户性别',
			'marital' => '婚姻状况',
			'monthlyIncome' => '月收入',
			'houseProperty' => '房屋权属',
			'cardAddress' => '户籍地址',
			'currentAddress' => '居住地址',
			'jobName' => '工作单位',
			'jobAddress' => '单位地址',
			'jobPhone' => '单位电话',
			'wechat' => '微信账号',
			'qq' => 'QQ账号',
			'alipay' => '支付宝账号',
			'remark' => '备注',
		];
	}

	// 设置验证场景
	public function scenarios() {
		$scenarios = parent::scenarios();
		$scenarios['loanInfo'] = ['loanAmount', 'purpose', 'productType', 'installmentCycle', 'installmentPeriod'];
		$scenarios['customerInfo'] = ['realName', 'certNo', 'bankCardNo', 'bankMobileNo', 'mobileNo', 'address', 'remark'];
		$scenarios['detailInfo'] = ['gender', 'marital', 'monthlyIncome', 'houseProperty', 'cardAddress', 'currentAddress', 'jobName', 'jobAddress', 'jobPhone', 'wechat', 'qq', 'alipay'];
		$scenarios['orderInfo'] = ['loanAmount', 'purpose', 'productType', 'installmentCycle', 'installmentPeriod', 'realName', 'certNo', 'bankCardNo', 'bankMobileNo', 'mobileNo', 'address', 'gender', 'marital', 'monthlyIncome', 'houseProperty', 'cardAddress', 'currentAddress', 'jobName', 'jobAddress', 'jobPhone', 'wechat', 'qq', 'alipay'];
		$scenarios['contactInfo'] = ['contactName', 'contactRelation', 'contactPhone'];
		$scenarios['cancelOrder'] = ['orderID', 'reason'];
		return $scenarios;
	}
}