<?php
return [
    'operation-log' => [
        // 'customer/index' => ['tag' => 'customer.index', 'active' => 'test'], // 自定义函数方式处理
        
        // 回调相关:
        'borrownew/deduct-callback' => ['tag' => 'api.callback.yijifu.deduct-new', 'memo' => 'API回调:易极付-扣款回调-NEW'],
        'borrow/deduct-callback' => ['tag' => 'api.callback.yijifu.deduct', 'memo' => 'API回调:易极付-扣款回调（旧）'],
        // 'borrow/update-bank-call-back' => ['tag' => 'api.callback.bankcard-update', 'memo' => 'API回调:易极付-修改银行卡'],
        'borrow/verify-pass-callback' => ['tag' => 'api.callback.yijifu.sign', 'memo' => 'API回调:易极付-签约回调'],
        // 'borrownew/deduct-callback' => ['tag' => 'api.callback.yijifu.borrownew-deduct', 'memo' => 'API回调:易极付-提前还款扣款'],
        'repaymentnew/deduct-callback' => ['tag' => 'api.callback.yijifu.repaymentnew-deduct', 'memo' => 'API回调:易极付-扣款扣款（repaymentnew.actionDeductCallback）'],
        
        'loan/async' => ['tag' => 'api.callback.yijifu.loan-pay', 'memo' => 'API回调:易极付-给商家放款'],
        'jun/callback' => ['tag' => 'api.callback.junziqian.sign', 'memo' => 'API回调:君子签-签约'],

        // 订单、审核相关:
        'borrow/view' => ['tag' => 'borrow.view', 'memo' => '{OPERATOR_REALNAME}[{OPERATOR_ID}]查看了订单详情'],
        'borrownew/view' => ['tag' => 'borrow.view', 'memo' => '{OPERATOR_REALNAME}[{OPERATOR_ID}]查看了订单详情(NEW)'],
        'borrow/verify-pass-first' => ['tag' => 'auditing.verify-pass-first', 'memo' => '{OPERATOR_REALNAME}[{OPERATOR_ID}] 初审通过'],
        'borrownew/verify-pass' => ['tag' => 'auditing.verify-pass', 'memo' => '{OPERATOR_REALNAME}[{OPERATOR_ID}] 终审放款(NEW)'],
        'borrow/verify-cancel' => ['tag' => 'auditing.verify-cancel', 'memo' => '{OPERATOR_REALNAME}[{OPERATOR_ID}] 取消订单'],
        'borrow/verify-refuse' => ['tag' => 'auditing.refuse', 'memo' => '{OPERATOR_REALNAME}[{OPERATOR_ID}]拒绝订单'],
        'borrow/verify-failpic' => ['tag' => 'auditing.verify-failpic', 'memo' => '{OPERATOR_REALNAME}[{OPERATOR_ID}]审核-照片不合格'],

        // 提前还款、取消贵宾服务包、取消个人保障计划:
        'borrownew/prepayment' => ['tag' => 'borrownew.prepayment', 'memo' => '{OPERATOR_REALNAME}[{OPERATOR_ID}]- 提前还款'],
        'borrownew/cancel-vip-pack' => ['tag' => 'borrownew.cancel-vip-pack', 'memo' => '{OPERATOR_REALNAME}[{OPERATOR_ID}]取消贵宾服务包'],
        'borrownew/cancel-personal-protection' => ['tag' => 'borrownew.cancel-personal-protection', 'memo' => '{OPERATOR_REALNAME}[{OPERATOR_ID}]取消个人保障计划'],

        // ..
        'loan/loan' => ['tag' => 'loan.loan', 'memo' => '{OPERATOR_REALNAME}[{OPERATOR_ID}] 发起向商家放款请求'],
        'repaymentnew/repay' => ['tag' => 'repaymentnew.repay', 'memo' => '{OPERATOR_REALNAME}[{OPERATOR_ID}] 发起还款扣款请求'],

    ],
];