<?php
return [
	'enable_csrf_validation' => [
		'Borrow/ListWaitVerify',
        'borrow/verify-pass-callback',  //易极付签约回调
        'repayment/deduct-callback',    //易极付代扣回调
        'repaymentnew/deduct-callback', //易极付单期代扣回调
        'borrownew/deduct-callback',    //提前还款回调
        'loan/async',                   //放款回调
        'jun/callback',                 //君子签回调
        'user/get-leader',              //获取上级ajax
        'borrownew/calculation-residual-loan',  //提前还款计算金额
        'borrow/prepayment',            //提前还款
        'borrownew/prepayment',         //提前还款(新)
        'borrownew/cancel-personal-protection', //取消个人保障计划
        'borrownew/cancel-vip-pack',    //取消贵宾包
        'borrownew/update-product-code', //修改商品代码

    ],
    'v2_jzq' => 'http://third-party.devapi.tnew.cn/sign/sign_call',
    'v2_jzq_token' => '',
    'v2_user' => 'http://users.devapi.tnew.cn/v1/',
    // 'v2_user' => 'http://users.api.tnew.loc/v1/',
    'v2_user_token' => 'e4f193277b664c4695f36b00a0b8bbe45a02ae98097ae151798135',
    // v2_cash
    'v2_cash' => 'http://cash.api.tnew.cn/v1/orders/',
    'v2_cash_token' => 'e4f193277b664c4695f36b00a0b8bbe45a02ae98097ae151798135',
];
