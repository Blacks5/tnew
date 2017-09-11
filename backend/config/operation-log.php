<?php
return [
    'operation-log' => [
        'customer/view' => ['tag' => 'customer.view', 'memo' => '{OPERATOR_REALNAME}[{OPERATOR_ID}]查看了客户'],
        'customer/index' => ['tag' => 'customer.index', 'active' => 'test'],
        'borrownew/deduct-callback' => ['tag' => 'api.callback.deduct', 'memo' => 'API回调:易极付-扣款回调'],
    ],
];