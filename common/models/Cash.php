<?php

/**
 * 现金贷业务
 * @Author: MuMu
 * @Date:   2017-11-20 13:47:36
 * @Last Modified by:   MuMu
 * @Last Modified time: 2017-11-23 13:46:44
 */

namespace common\models;

use common\core\CoreCommonActiveRecord;

class Cash extends CoreCommonActiveRecord {
	public $loanAmount;
	public $installmentCycle;
	public $installmentPeriod;
	public $realName;
	public $certNo;
	public $bankCardNo;
	public $mobileNo;
	public $bankMobileNo;
	public $address;
	public $contactName;
	public $contactRelation;
	public $contactPhone;

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
			['realName', 'required', 'message' => '请输入客户姓名'],
			['realName', 'string', 'message' => '客户姓名不合法', 'length' => [2, 10]],
			['certNo', 'required', 'message' => '请输入身份证号'],
			['certNo', 'match', 'message' => '身份证号不合法', 'pattern' => '/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/'],
			['bankCardNo', 'required', 'message' => '请输入银行卡号'],
			['bankCardNo', 'string', 'message' => '银行卡号不合法', 'length' => [16, 19]],
			['bankMobileNo', 'required', 'message' => '请输入银行预留手机号'],
			['bankMobileNo', 'match', 'message' => '预留手机号不合法', 'pattern' => '/^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|17[0-9]|14[57])[0-9]{8}$/'],
			['mobileNo', 'required', 'message' => '请输入联系手机号'],
			['mobileNo', 'match', 'message' => '联系手机号不合法', 'pattern' => '/^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|17[0-9]|14[57])[0-9]{8}$/'],
			['address', 'required', 'message' => '请输入调查地址'],
			['address', 'string', 'message' => '调查地址长度在2~200之间', 'length' => [2, 200]],
			['contactName', 'required', 'message' => '请输入联系人姓名'],
			['contactName', 'string', 'message' => '联系人姓名长度在2~20之间', 'length' => [2, 20]],
			['contactRelation', 'required', 'message' => '请选择联系人关系'],
			['contactRelation', 'in', 'message' => '请选择联系人关系', 'range' => ['family' , 'workmate' , 'friend' , 'other']],
			['contactPhone', 'required', 'message' => '请输入联系人手机'],
			['contactPhone', 'match', 'message' => '联系人手机不合法', 'pattern' => '/^(0|86|17951)?(13[0-9]|15[012356789]|18[0-9]|17[0-9]|14[57])[0-9]{8}$/'],
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
			'realName' => '客户姓名',
			'certNo' => '身份证号',
			'bankCardNo' => '银行卡号',
			'bankMobileNo' => '预留手机号',
			'mobileNo' => '联系手机号',
			'address' => '调查地址',
			'contactName' => '联系人姓名',
			'contactRelation' => '联系人关系',
			'contactPhone' => '联系人手机',
		];
	}

	// 设置验证场景
	public function scenarios() {
		$scenarios = parent::scenarios();
		$scenarios['loanInfo'] = ['loanAmount', 'installmentCycle', 'installmentPeriod'];
		$scenarios['customerInfo'] = ['realName', 'certNo', 'bankCardNo', 'bankMobileNo', 'mobileNo', 'address'];
		$scenarios['orderInfo'] = ['loanAmount', 'installmentCycle', 'installmentPeriod', 'realName', 'certNo', 'bankCardNo', 'bankMobileNo', 'mobileNo', 'address'];
		$scenarios['contactInfo'] = ['contactName', 'contactRelation', 'contactPhone'];
		return $scenarios;
	}
}