user表增加字段
wechat_openid varchar(64)

user表增加字段
leader init default(0) //销售人员上级领导ID    
level init default(0) //销售人员级别:1销售总监 2大区经理 3城市经理 4销售经理 5销售主管 6销售代表     

stores表增加新字段    
s_photo_nine varchar(50) not null default('') commit '授权书'    

orders 表增加字段    
`o_number_of_modify_date` tinyint(4) NOT NULL DEFAULT '0' COMMENT '还款时间选项被修改的次数'