哟哟切克闹

CREATE TABLE `jzq_sign` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `o_serial_id` char(30) DEFAULT NULL COMMENT '核心系统客户订单号',
  `applyNo` char(30) DEFAULT NULL COMMENT '签约编号',
  `IdentityType` varchar(100) DEFAULT NULL COMMENT '证件类型',
  `fullName` varchar(100) DEFAULT NULL COMMENT '企业名称/个人姓名',
  `optTime` char(20) DEFAULT NULL COMMENT '操作时间',
  `signStatus` tinyint(3) DEFAULT '0' COMMENT '0 未签、 1 已签、 2 拒签 3签约+保全都完成',
  `Timestamp` char(20) DEFAULT NULL COMMENT '回传信息发送时间',
  `operator_id` int(10) DEFAULT NULL COMMENT '操作人ID',
  `operator_realname` varchar(20) DEFAULT NULL COMMENT '操作人姓名',
  `created_at` int(10) unsigned DEFAULT NULL COMMENT '记录创建时间',
  `updated_at` int(10) unsigned DEFAULT NULL COMMENT '记录更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='君子签-签约记录表';



