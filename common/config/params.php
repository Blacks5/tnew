<?php
return [
	'adminEmail' => 'admin@example.com',
	'supportEmail' => 'support@example.com',
	'user.passwordResetTokenExpire' => 3600,

	'page_size' => 15, // Android 列表

	// 违约金每天5块
	// update:2017-05-10 这个配置没用了
	'overdue_money_everyday' => 5,

	// 提交订单时发送短信的模板
	'submit_orders' => '【深圳天牛金融服务有限公司】您好，您的验证码是:',
	'sms_timeout' => 120, // 短信验证码有效期 秒

	// 安装订单初始数据
	'goods_type' => [
		['t_id' => 1, 't_name' => '手机'],
		['t_id' => 2, 't_name' => '家电'],
		['t_id' => 3, 't_name' => '电脑'],
		['t_id' => 4, 't_name' => '其他'],
		['t_id' => 5, 't_name' => '驾校贷'],
		['t_id' => 6, 't_name' => '摩托车'],
		['t_id' => 7, 't_name' => '电动车'],
		['t_id' => 8, 't_name' => '有钱花'],
//        ['t_id'=>9, 't_name'=>'驾校贷'],

	],
	// 客户等级暗号
	'secret_code' => [
		['secret_code' => 1],
		['secret_code' => 2],
		['secret_code' => 3],
	],

	'marital_status' => [
		['marital_id' => 1, 'marital_str' => '未婚'],
		['marital_id' => 2, 'marital_str' => '已婚'],
		['marital_id' => 3, 'marital_str' => '离异'],
		['marital_id' => 4, 'marital_str' => '丧偶'],
	],

	'house_info' => [
		['house_info_id' => 1, 'house_info_str' => '自有房'],
		['house_info_id' => 2, 'house_info_str' => '出租房'],
		['house_info_id' => 3, 'house_info_str' => '父母房'],
		['house_info_id' => 4, 'house_info_str' => '宿舍'],
		['house_info_id' => 5, 'house_info_str' => '公租房/廉租房'],
	], // 住房情况
	'kinship' => [
		['kinship_id' => 1, 'kinship_str' => '父亲'],
		['kinship_id' => 2, 'kinship_str' => '母亲'],
		['kinship_id' => 3, 'kinship_str' => '兄弟姐妹'],
		['kinship_id' => 4, 'kinship_str' => '朋友'],
		['kinship_id' => 5, 'kinship_str' => '同事'],
		['kinship_id' => 6, 'kinship_str' => '其他'],
		['kinship_id' => 7, 'kinship_str' => '子女'],
		//        ['kinship_id'=>6, 'kinship_str'=>'表兄弟'],
		['kinship_id' => 7, 'kinship_str' => '表姐妹'],
	], // 亲属关系

	// 其他关系
	'other_kinship' => [
		['kinship_id' => 1, 'kinship_str' => '同事'],
		['kinship_id' => 2, 'kinship_str' => '朋友'],
		['kinship_id' => 3, 'kinship_str' => '同学'],
	],

	// app银行列表
	'bank_list' => [
		['bank_id' => 1, 'bank_name' => '中国银行股份有限公司'],
		['bank_id' => 2, 'bank_name' => '中国工商银行股份有限公司'],
		['bank_id' => 3, 'bank_name' => '中国农业银行股份有限公司'],
		['bank_id' => 4, 'bank_name' => '中国建设银行股份有限公司'],
		['bank_id' => 5, 'bank_name' => '中国光大银行'],
		['bank_id' => 6, 'bank_name' => '兴业银行'],
		['bank_id' => 7, 'bank_name' => '民生银行'],
		['bank_id' => 8, 'bank_name' => '中信银行'],
		['bank_id' => 9, 'bank_name' => '重庆农村商业银行'],
		['bank_id' => 10, 'bank_name' => '中国邮政储蓄银行'],
		['bank_id' => 11, 'bank_name' => '平安银行'],
		['bank_id' => 12, 'bank_name' => '交通银行'],
		['bank_id' => 13, 'bank_name' => '广东发展银行'],
	],

	// 公司性质
	'company_type' => [
		['company_type_id' => 1, 'company_type_name' => '股份有限制公司'],
		['company_type_id' => 2, 'company_type_name' => '私营企业'],
		['company_type_id' => 3, 'company_type_name' => '国有企业'],
		['company_type_id' => 4, 'company_type_name' => '个体工商户'],
	],

	// 所属行业
	'company_kind' => [
		['company_kind_id' => 1, 'company_kind_name' => '餐饮、酒店、旅游、美容美发保健'],
		['company_kind_id' => 2, 'company_kind_name' => '农业、林业、畜牧业和渔业'],
		['company_kind_id' => 3, 'company_kind_name' => '建筑、装修'],
		['company_kind_id' => 4, 'company_kind_name' => '文化、运动、娱乐、传媒、广告设计'],
		['company_kind_id' => 5, 'company_kind_name' => '教育'],
		['company_kind_id' => 6, 'company_kind_name' => '金融机构、专业性事务机构'],
		['company_kind_id' => 7, 'company_kind_name' => '政府机构、社会团队'],
		['company_kind_id' => 8, 'company_kind_name' => '计算机、通信、通讯'],
		['company_kind_id' => 9, 'company_kind_name' => '制造、快速消费品、耐用消费品'],
		['company_kind_id' => 10, 'company_kind_name' => '能源、化工、矿产'],
		['company_kind_id' => 11, 'company_kind_name' => '军队'],
		['company_kind_id' => 12, 'company_kind_name' => '电力、煤气、水的生成和供应'],
		['company_kind_id' => 13, 'company_kind_name' => '房地产'],
		['company_kind_id' => 14, 'company_kind_name' => '个体、自营、退休、居住、家政和其他服务'],
		['company_kind_id' => 15, 'company_kind_name' => '科研、地质勘探和技术服务'],
		['company_kind_id' => 16, 'company_kind_name' => '事业单位、公共设施、医疗卫生、社会保障'],
		['company_kind_id' => 17, 'company_kind_name' => '租赁和商业服务'],
		['company_kind_id' => 18, 'company_kind_name' => '批发和零售贸易'],
		['company_kind_id' => 19, 'company_kind_name' => '其他'],
	],
	//商户银行(对私)
	'stores_banklist' => [
		'BOC' => '中国银行',
		'ABC' => '中国农业银行',
		'ICBC' => '中国工商银行',
		'CCB' => '中国建设银行',
		'CEB' => '中国光大银行',
		'CIB' => '兴业银行',
		'CMBC' => '民生银行',
		'CITIC' => '中信银行',
		'CQRCB' => '重庆农村商业银行',
		'PSBC' => '中国邮政储蓄银行',
		'PINGANBK' => '平安银行',
		'COMM' => '交通银行',
		'CGB' => '广东发展银行',
	],

	//查询费用
	'inquiryFee' => 15,

	// 易极付回调需要使用
	'domain' => 'http://119.23.15.90',

	"yijifu-test" => [
		"api" => "http://merchantapi.yijifu.net/gateway.html",
		"partnerId" => "20160831020000752643", // 签约的服务平台账号对应的合作方ID
		"privateKey" => "b04fbc6afc77b131c355dd1788215dbb", // 私钥
	],

	"yijifu" => [
		"api" => "https://api.yxtweb.com/gateway.html",
		"partnerId" => "20170725020014672546", // 签约的服务平台账号对应的合作方ID
		"privateKey" => "3eb7482931b0f9b99c032e49cd4546fe", // 私钥
	],

	"junziqian-test" => [
		'appkey' => 'b24da5e8ccc283cb',
		'secret' => '8e369967b24da5e8ccc283cb34fc6efc',
		'service_url' => 'http://sandbox.api.junziqian.com/services',
	],
	"junziqian" => [
		'appkey' => 'bdbb597509c94cd2',
		'secret' => '5bd94203bdbb597509c94cd294f69e8a',
		'service_url' => 'http://api.junziqian.com/services',
	],
	'ws' => 'ws://119.23.15.90:8081',
	'customernew_date' => '2017-08-02 00:00:00', //新用户创建时间,用于筛选
	// 商家服务费率
	'seller_serverfee_rate' => 0.02,

	// 商家服务费率【促销商品】
	'promotions_seller_serverfee_rate' => 0.02,
	// 商家服务费率【普通商品】
	'common_seller_serverfee_rate' => 0.035,
	//cash api url
	'cashBaseUrl' => 'http://cash.api.tnew.cn/v1/orders/',
	// cash dev api url
	'cashDevBaseUrl' => 'http://cash.devapi.tnew.cn/v1/orders',
	// cash api token
	'CASH_API_TOKEN' => 'e4f193277b664c4695f36b00a0b8bbe45a02ae98097ae151798135',

	// 还款周期
	'installmentCycle' => [
		[
			'title' => '按周还款',
			'value' => 'week',
			'periods' => [
				[
					'title' => '4周',
					'value' => '4',
				],
				[
					'title' => '8周',
					'value' => '8',
				],
				[
					'title' => '12周',
					'value' => '12',
				],
				[
					'title' => '24周',
					'value' => '24',
				],
				[
					'title' => '48周',
					'value' => '48',
				],
			],
		],
		[
			'title' => '按月还款',
			'value' => 'month',
			'periods' => [
				[
					'title' => '6月',
					'value' => '6',
				],
				[
					'title' => '9月',
					'value' => '9',
				],
				[
					'title' => '12月',
					'value' => '12',
				],
				[
					'title' => '18月',
					'value' => '18',
				],
			],
		],
	],

	// 贵宾服务包费用 xx元/月
	'vipServiceFee' => 15,
	// 个人保障服务费用  xx元/月
	'protectionFee' => 16.89,

	// 婚姻状况
	'maritalSituation' => [
		[
			'title' => '已婚',
			'value' => 'married',
		],
		[
			'title' => '未婚',
			'value' => 'unmarried',
		],
		[
			'title' => '离异',
			'value' => 'divorced',
		],
		[
			'title' => '丧偶',
			'value' => 'widowhood',
		],
	],

	// 联系人关系
	'contactRelationship' => [
		[
			'title' => '家人-其他',
			'value' => 'family',
		],
		[
			'title' => '父母',
			'value' => 'parent',
		],
		[
			'title' => '配偶',
			'value' => 'spouse',
		],
		[
			'title' => '兄弟姐妹',
			'value' => 'brothers',
		],
		[
			'title' => '同事',
			'value' => 'workmate',
		],
		[
			'title' => '朋友',
			'value' => 'friend',
		],
		[
			'title' => '其它',
			'value' => 'other',
		],
	],

	// 现金贷借款用途
	'casePurpose' => [
		[
			'title' => '家用电器',
			'value' => '家用电器',
		],
		[
			'title' => '数码产品',
			'value' => '数码产品',
		],
		[
			'title' => '国内教育',
			'value' => '国内教育',
		],
		[
			'title' => '出境留学',
			'value' => '出境留学',
		],
		[
			'title' => '装修',
			'value' => '装修',
		],
		[
			'title' => '婚庆',
			'value' => '婚庆',
		],
		[
			'title' => '旅游',
			'value' => '旅游',
		],
		[
			'title' => '租赁',
			'value' => '租赁',
		],
		[
			'title' => '医疗',
			'value' => '医疗',
		],
		[
			'title' => '美容',
			'value' => '美容',
		],
		[
			'title' => '家具',
			'value' => '家具',
		],
		[
			'title' => '生活用品',
			'value' => '生活用品',
		],
		[
			'title' => '购物',
			'value' => '购物',
		],
		[
			'title' => '个人消费',
			'value' => '个人消费',
		],
	],

	// 现金贷产品类型
	'cashProductType' => [
		[
			'title' => '常规',
			'value' => 1,
		],
		[
			'title' => '促销',
			'value' => 2,
		],
	],

	// 现金贷产品类型
	'houseProperty' => [
		[
			'title' => '租用',
			'value' => 'rented',
		],
		[
			'title' => '自有',
			'value' => 'owned',
		],
	],

	// 服务之间通信TOKEN
	'server_communicate_token' => 'e4f193277b664c4695f36b00a0b8bbe45a02ae98097ae151798135',
	// 服务运行环境 master develop
	'server_running_env' => 'master',
];
