<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

    'page_size'=>20, // Android 列表

    // 违约金每天5块
    'overdue_money_everyday'=>5,

    // 提交订单时发送短信的模板
    'submit_orders'=>'您好，您的验证码是:',
    'sms_timeout'=>300, // 短信验证码有效期 秒

    // 安装订单初始数据
    'goods_type'=>[
        ['t_id'=>1, 't_name'=>'手机'],
        ['t_id'=>2, 't_name'=>'家电'],
        ['t_id'=>3, 't_name'=>'电脑'],
        ['t_id'=>4, 't_name'=>'其他'],
        ['t_id'=>5, 't_name'=>'测试'],
    ],
    // 客户等级暗号
    'secret_code'=>[
        ['secret_code'=>1],
        ['secret_code'=>2],
        ['secret_code'=>3],
    ],

    'marital_status'=>[
        ['marital_id'=>1, 'marital_str'=>'未婚'],
        ['marital_id'=>2, 'marital_str'=>'已婚'],
        ['marital_id'=>3, 'marital_str'=>'离异'],
        ['marital_id'=>4, 'marital_str'=>'丧偶'],
    ],

    'house_info'=>[
        ['house_info_id'=>1, 'house_info_str'=>'自有房'],
        ['house_info_id'=>2, 'house_info_str'=>'租住房'],
        ['house_info_id'=>3, 'house_info_str'=>'父母房产'],
        ['house_info_id'=>4, 'house_info_str'=>'单位提供福利'],
    ], // 住房情况
    'kinship'=>[
        ['kinship_id'=>1, 'kinship_str'=>'父亲'],
        ['kinship_id'=>2, 'kinship_str'=>'母亲'],
        ['kinship_id'=>3, 'kinship_str'=>'兄弟姐妹'],
        ['kinship_id'=>4, 'kinship_str'=>'朋友'],
        ['kinship_id'=>5, 'kinship_str'=>'同事'],
        ['kinship_id'=>8, 'kinship_str'=>'其他'],
        //        ['kinship_id'=>6, 'kinship_str'=>'表兄弟'],
//        ['kinship_id'=>7, 'kinship_str'=>'表姐妹'],
    ], // 亲属关系

    'bank_list'=>[
        ['bank_id'=>1, 'bank_name'=>'中国银行'],
        ['bank_id'=>2, 'bank_name'=>'工商银行'],
        ['bank_id'=>3, 'bank_name'=>'成都银行'],
        ['bank_id'=>4, 'bank_name'=>'招商银行'],
    ],

    // 公司性质
    'company_type'=>[
        ['company_type_id'=>1, 'company_type_name'=>'个体'],
        ['company_type_id'=>2, 'company_type_name'=>'国企'],
        ['company_type_id'=>3, 'company_type_name'=>'上市公司'],
    ],

    // 所属行业
    'company_kind'=>[
        ['company_kind_id'=>1, 'company_kind_name'=>'美容美发'],
        ['company_kind_id'=>2, 'company_kind_name'=>'餐饮娱乐'],
        ['company_kind_id'=>3, 'company_kind_name'=>'游戏行业'],
        ['company_kind_id'=>4, 'company_kind_name'=>'金融行业'],
        ['company_kind_id'=>5, 'company_kind_name'=>'软件行业'],
    ]
];
