<?php
return [
    'operation-log' => [
        // 'customer/index' => ['tag' => 'customer.index', 'active' => 'test'], // 自定义函数方式处理
        'customer/view' => ['tag' => 'customer.view', 'memo' => '{OPERATOR_REALNAME}[{OPERATOR_ID}]查看了客户'],
        'borrownew/deduct-callback' => ['tag' => 'api.callback.yijifu.deduct-new', 'memo' => 'API回调:易极付-扣款回调-NEW'],
        'borrow/deduct-callback' => ['tag' => 'api.callback.yijifu.deduct', 'memo' => 'API回调:易极付-扣款回调'],
        // 'borrow/update-bank-call-back' => ['tag' => 'api.callback.bankcard-update', 'memo' => 'API回调:易极付-修改银行卡'],
        'borrow/verify-pass-callback' => ['tag' => 'api.callback.yijifu.sign', 'memo' => 'API回调:易极付-签约回调'],
        'borrownew/deduct-callback' => ['tag' => 'api.callback.yijifu.borrownew-deduct', 'memo' => 'API回调:易极付-提前还款扣款'],
        'loan/async' => ['tag' => 'api.callback.yijifu.loan-pay', 'memo' => 'API回调:易极付-给商家放款'],
        'jun/callback' => ['tag' => 'api.callback.junziqian.sign', 'memo' => 'API回调:君子签-签约'],
    ],
];