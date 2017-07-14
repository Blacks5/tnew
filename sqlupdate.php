ALTER TABLE stores ADD `s_idcard_num` char(20) DEFAULT '' COMMENT '结算账户所有人身份证号';

CREATE TABLE `yijifu_loan` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`order_id` int(10) unsigned NOT NULL COMMENT '核心系统客户订单号',
`amount` double(10,3) NOT NULL DEFAULT '0.000' COMMENT '代发金额',
`realRemittanceAmount` double(10,3) NOT NULL DEFAULT '0.000' COMMENT '实际代发金额',
`contractNo` varchar(40) NOT NULL COMMENT '代发流水号',
`chargeAmount` double(10,3) NOT NULL DEFAULT '0.000' COMMENT '代发手续费',
`status` tinyint(3) DEFAULT '0' COMMENT '1未放款2已放款',
`created_at` int(10) unsigned NOT NULL COMMENT '记录创建时间',
`operator_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作人id',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='易极付放款记录表';


CREATE TABLE `yijifu_sign_returnmoney` (
`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
`order_id` int(10) unsigned NOT NULL COMMENT '核心系统客户订单号',
`merchOrderNo` varchar(40) NOT NULL COMMENT '商户签约订单号，接口查询要用',
`merchContractNo` varchar(64) CHARACTER SET latin1 NOT NULL COMMENT '商户签约合同号，暂时没用',
`deductAmount` double(10,3) NOT NULL DEFAULT '0.000' COMMENT '代扣金额，类型是代扣时才有效',
`operateType` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '操作类型：1签约，2代扣',
`created_at` int(10) unsigned NOT NULL COMMENT '记录创建时间',
`updated_at` int(10) unsigned NOT NULL COMMENT '记录更新时间',
`operator_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '操作人id',
`status` tinyint(2) DEFAULT '2' COMMENT '1成功2等待回调',
PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='易极付签约和回款记录表';