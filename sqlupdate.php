ALTER TABLE stores ADD `s_idcard_num` char(20) DEFAULT '' COMMENT '结算账户所有人身份证号';

CREATE TABLE `yijifu_loan` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`y_serial_id` char(20) DEFAULT '' COMMENT '订单号',
`amount` double(10,3) NOT NULL DEFAULT '0.000' COMMENT '代发金额',
`realRemittanceAmount` double(10,3) NOT NULL DEFAULT '0.000' COMMENT '实际代发金额',
`contractNo` varchar(40) NOT NULL COMMENT '代发流水号',
`chargeAmount` double(10,3) NOT NULL DEFAULT '0.000' COMMENT '代发手续费',
`status` tinyint(3) DEFAULT '0' COMMENT '1接口调用失败  2接口调用成功处理中 3放款处理失败  4放款处理成功',
`created_at` int(10) unsigned NOT NULL COMMENT '记录创建时间',
`updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录更新时间',
`operator_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作人id',
`y_operator_realname` char(10) DEFAULT '' COMMENT '操作人真实姓名',
PRIMARY KEY (`id`),
UNIQUE KEY `y_serial_id` (`y_serial_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COMMENT='易极付放款记录表';



CREATE TABLE `yijifu_sign_returnmoney` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`o_serial_id` char(30) NOT NULL COMMENT '核心系统客户订单号',
`merchOrderNo` varchar(40) NOT NULL COMMENT '商户签约订单号，接口查询要用',
`merchContractNo` varchar(64) CHARACTER SET latin1 NOT NULL COMMENT '商户签约合同号，暂时没用',
`deductAmount` double(10,3) NOT NULL DEFAULT '0.000' COMMENT '代扣金额，类型是代扣时才有效',
`operateType` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '操作类型：1签约，2代扣',
`created_at` int(10) unsigned NOT NULL COMMENT '记录创建时间',
`updated_at` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '记录更新时间',
`operator_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作人id',
`status` tinyint(2) NOT NULL DEFAULT '2' COMMENT '1回调签约成功 2等待回调 3接口调用失败 4回调处理失败 5回调审核驳回 6回调签约失败 7回调签约处理中 8回调待审核',
`orderNo` varchar(40) NOT NULL DEFAULT '' COMMENT '服务平台合作商户网站唯一流水号,同步和异步都会返回',
`sign` varchar(64) NOT NULL DEFAULT '' COMMENT '签名串，同步返回，不知作用，异步也返回了一个不同的，但没有保存',
`bankName` varchar(64) NOT NULL DEFAULT '' COMMENT '银行名；异步返回的',
`bankCardType` varchar(50) NOT NULL DEFAULT '' COMMENT '银行卡类型；异步返回',
`bankCode` varchar(40) NOT NULL DEFAULT '' COMMENT '签约银行卡银行编码；异步返回',
PRIMARY KEY (`id`),
KEY `orderNo` (`orderNo`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COMMENT='易极付签约和回款记录表';





