ALTER TABLE stores ADD `s_idcard_num` char(20) DEFAULT '' COMMENT '结算账户所有人身份证号';

CREATE TABLE `yijifu_loan` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`y_serial_id` char(20) DEFAULT '' COMMENT '订单号',
`outOrderNo` char(40) DEFAULT '' COMMENT '外部订单号',
`amount` double(10,3) NOT NULL DEFAULT '0.000' COMMENT '代发金额',
`realRemittanceAmount` double(10,3) NOT NULL DEFAULT '0.000' COMMENT '实际代发金额',
`contractNo` varchar(40) NOT NULL COMMENT '代发流水号',
`chargeAmount` double(10,3) NOT NULL DEFAULT '0.000' COMMENT '代发手续费',
`status` tinyint(3) DEFAULT '0' COMMENT '1接口调用失败  2接口调用成功处理中 3放款处理失败  4放款处理成功',
`created_at` int(10) unsigned NOT NULL COMMENT '记录创建时间',
`updated_at` int(10) NOT NULL DEFAULT '0' COMMENT '记录更新时间',
`operator_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作人id',
`y_operator_realname` char(10) DEFAULT '' COMMENT '操作人真实姓名',
`resultmsg` varchar(30) DEFAULT '' COMMENT '描述信息',
PRIMARY KEY (`id`),
UNIQUE KEY `y_serial_id` (`y_serial_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COMMENT='易极付放款记录表';



CREATE TABLE `yijifu_sign` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`o_serial_id` char(30) NOT NULL COMMENT '核心系统客户订单号',
`merchOrderNo` varchar(40) NOT NULL COMMENT '商户签约订单号，接口查询要用',
`merchContractNo` varchar(64) CHARACTER SET latin1 NOT NULL COMMENT '商户签约合同号，暂时没用',
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
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COMMENT='易极付签约记录表';




CREATE TABLE `yijifu_deduct` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`o_serial_id` char(20) NOT NULL DEFAULT '' COMMENT '系统核心订单号',
`merchOrderNo` varchar(40) NOT NULL DEFAULT '' COMMENT '唯一订单号，貌似没用处',
`merchSignOrderNo` varchar(40) NOT NULL DEFAULT '' COMMENT '签约时的merchOrderNo，用于匹配签约信息',
`deductAmount` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT '代扣金额',
`realName` varchar(16) NOT NULL DEFAULT '' COMMENT '借款人姓名',
`bankCardNo` varchar(40) NOT NULL DEFAULT '' COMMENT '代扣卡号',
`bankCode` varchar(16) NOT NULL DEFAULT '' COMMENT '银行编码',
`realRepayTime` int(11) NOT NULL DEFAULT '0' COMMENT '实际还款时间',
`status` tinyint(4) NOT NULL COMMENT '代扣状态：0等待异步回调 1待处理 2代扣处理中 3待审核 4审核驳回 5 代扣失败 6代扣成功 7结算成功 8接口调用失败',
`errorCode` varchar(10) NOT NULL DEFAULT '' COMMENT '出错时的错误编码',
`description` varchar(128) NOT NULL DEFAULT '' COMMENT '错误描述',
`repayment_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '待还款id',
`operator_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作者id',
`created_at` int(10) unsigned NOT NULL DEFAULT '0',
PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COMMENT='易极付代扣记录表';




