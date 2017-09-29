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

	]
];
