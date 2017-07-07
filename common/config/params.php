<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,

    'page_size'=>15, // Android 列表

    // 违约金每天5块
    // update:2017-05-10 这个配置没用了
    'overdue_money_everyday'=>5,

    // 提交订单时发送短信的模板
    'submit_orders'=>'【深圳天牛金融服务有限公司】您好，您的验证码是:',
    'sms_timeout'=>120, // 短信验证码有效期 秒

    // 安装订单初始数据
    'goods_type'=>[
        ['t_id'=>1, 't_name'=>'手机'],
        ['t_id'=>2, 't_name'=>'家电'],
        ['t_id'=>3, 't_name'=>'电脑'],
        ['t_id'=>4, 't_name'=>'其他'],
//        ['t_id'=>5, 't_name'=>'测试'],
        ['t_id'=>6, 't_name'=>'摩托车'],
        ['t_id'=>7, 't_name'=>'电动车'],
        ['t_id'=>8, 't_name'=>'有钱花'],
        ['t_id'=>9, 't_name'=>'驾校贷'],

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
        ['house_info_id'=>2, 'house_info_str'=>'出租房'],
        ['house_info_id'=>3, 'house_info_str'=>'父母房'],
        ['house_info_id'=>4, 'house_info_str'=>'宿舍'],
        ['house_info_id'=>5, 'house_info_str'=>'公租房/廉租房'],
    ], // 住房情况
    'kinship'=>[
        ['kinship_id'=>1, 'kinship_str'=>'父亲'],
        ['kinship_id'=>2, 'kinship_str'=>'母亲'],
        ['kinship_id'=>3, 'kinship_str'=>'兄弟姐妹'],
        ['kinship_id'=>4, 'kinship_str'=>'朋友'],
        ['kinship_id'=>5, 'kinship_str'=>'同事'],
        ['kinship_id'=>6, 'kinship_str'=>'其他'],
        ['kinship_id'=>7, 'kinship_str'=>'子女'],
        //        ['kinship_id'=>6, 'kinship_str'=>'表兄弟'],
//        ['kinship_id'=>7, 'kinship_str'=>'表姐妹'],
    ], // 亲属关系

    'bank_list'=>[
        ['bank_id'=>1, 'bank_name'=>'中国银行股份有限公司 '],
        ['bank_id'=>2, 'bank_name'=>'中国工商银行股份有限公司'],
        ['bank_id'=>3, 'bank_name'=>'中国农业银行股份有限公司 '],
        ['bank_id'=>4, 'bank_name'=>'中国建设银行股份有限公司 '],
    ],

    // 公司性质
    'company_type'=>[
        ['company_type_id'=>1, 'company_type_name'=>'股份有限制公司'],
        ['company_type_id'=>2, 'company_type_name'=>'私营企业'],
        ['company_type_id'=>3, 'company_type_name'=>'国有企业'],
        ['company_type_id'=>4, 'company_type_name'=>'个体工商户'],
    ],

    // 所属行业
    'company_kind'=>[
        ['company_kind_id'=>1, 'company_kind_name'=>'餐饮、酒店、旅游、美容美发保健'],
        ['company_kind_id'=>2, 'company_kind_name'=>'农业、林业、畜牧业和渔业'],
        ['company_kind_id'=>3, 'company_kind_name'=>'建筑、装修'],
        ['company_kind_id'=>4, 'company_kind_name'=>'文化、运动、娱乐、传媒、广告设计'],
        ['company_kind_id'=>5, 'company_kind_name'=>'教育'],
        ['company_kind_id'=>6, 'company_kind_name'=>'金融机构、专业性事务机构'],
        ['company_kind_id'=>7, 'company_kind_name'=>'政府机构、社会团队'],
        ['company_kind_id'=>8, 'company_kind_name'=>'计算机、通信、通讯'],
        ['company_kind_id'=>9, 'company_kind_name'=>'制造、快速消费品、耐用消费品'],
        ['company_kind_id'=>10, 'company_kind_name'=>'能源、化工、矿产'],
        ['company_kind_id'=>11, 'company_kind_name'=>'军队'],
        ['company_kind_id'=>12, 'company_kind_name'=>'电力、煤气、水的生成和供应'],
        ['company_kind_id'=>13, 'company_kind_name'=>'房地产'],
        ['company_kind_id'=>14, 'company_kind_name'=>'个体、自营、退休、居住、家政和其他服务'],
        ['company_kind_id'=>15, 'company_kind_name'=>'科研、地质勘探和技术服务'],
        ['company_kind_id'=>16, 'company_kind_name'=>'事业单位、公共设施、医疗卫生、社会保障'],
        ['company_kind_id'=>17, 'company_kind_name'=>'租赁和商业服务'],
        ['company_kind_id'=>18, 'company_kind_name'=>'批发和零售贸易'],
        ['company_kind_id'=>19, 'company_kind_name'=>'其他'],
    ],





    "yijifu"=>[
        "api"=>"http://merchantapi.yijifu.net/gateway.html",
        "partnerId"=>"20160831020000752643", // 签约的服务平台账号对应的合作方ID
        "privateKey"=>"b04fbc6afc77b131c355dd1788215dbb" , // 私钥
    ],


    'ws'=>'ws://119.23.15.90:8081'


];
