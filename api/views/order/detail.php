<!DOCTYPE html>
<html lang="en" style="background-color: white">
<head>
    <meta charset="UTF-8">
    <title>订单详情</title>
</head>
<body>
<h2 style="color:red;text-align:center;margin-bottom:5px;">客户信息</h2>
<table style="padding:10px;width:100%;background-color:#efefef;border-radius:8px;" >
    <tbody>
    <tr style="">
        <th align="right"  style="width:32%">姓名：</th>
        <td style="padding-left:5px;"><?=$data['c_customer_name']?></td>
    </tr>
    <tr>
        <th align="right">性别：</th>
        <td style="padding-left:5px;"><?=$data['c_customer_gender']?></td>
    </tr>
    <tr>
        <th align="right">电话：</th>
        <td style="padding-left:5px;"><?=$data['c_customer_cellphone']?></td>
    </tr>
    <tr>
        <th align="right">身份证号码：</th>
        <td style="padding-left:5px;"><?=$data['c_customer_id_card']?></td>
    </tr>
    <tr>
        <th align="right">身份证地址：</th>
        <td style="padding-left:5px;"><?=$id_address?></td>
    </tr>
    <tr>
        <th align="right">现居地址：</th>
        <td style="padding-left:5px;"><?=$now_address?></td>
    </tr>
    <tr>
        <th align="right">客户银行：</th>
        <td style="padding-left:5px;"><?=$data['c_bank']?></td>
    </tr>
    <tr>
        <th align="right">客户银行卡号：</th>
        <td style="padding-left:5px;"><?=$data['c_banknum']?></td>
    </tr>
    <tr>
        <th align="right">婚姻状况：</th>
        <td style="padding-left:5px;"><?=$data['c_family_marital_status']?></td>
    </tr>

    <?php if($data['c_family_marital_status']=='已婚'){ ?>
        <tr>
            <th align="right">配偶姓名：</th>
            <td style="padding-left:5px;"><?=$data['c_family_marital_partner_name']?></td>
        </tr>
        <tr>
            <th align="right">配偶手机号：</th>
            <td style="padding-left:5px;"><?=$data['c_family_marital_partner_cellphone']?></td>
        </tr>
    <?php } ?>

    <tr>
        <th align="right">亲属关系：</th>
        <td style="padding-left:5px;"><?=$data['c_kinship_relation']?></td>
    </tr>
    <tr>
        <th align="right">亲属姓名：</th>
        <td style="padding-left:5px;"><?=$data['c_kinship_name']?></td>
    </tr>
    <tr>
        <th align="right">亲属手机号：</th>
        <td style="padding-left:5px;"><?=$data['c_kinship_cellphone']?></td>
    </tr>
    <tr>
        <th align="right">工作单位：</th>
        <td style="padding-left:5px;"><?=$data['c_customer_jobs_company']?></td>
    </tr>
    <tr>
        <th align="right">工作行业：</th>
        <td style="padding-left:5px;"><?=$data['c_customer_jobs_type']?></td>
    </tr>
    <tr>
        <th align="right">单位性质：</th>
        <td style="padding-left:5px;"><?=$data['c_customer_jobs_industry']?></td>
    </tr>
    <tr>
        <th align="right">单位电话：</th>
        <td style="padding-left:5px;"><?=$data['c_customer_jobs_phone']?></td>
    </tr>
    <tr>
        <th align="right">单位地址：</th>
        <td style="padding-left:5px;"><?=$job_address?></td>
    </tr>
    <tr>
        <th align="right">是否购买社保：</th>
        <td style="padding-left:5px;"><?=$data['c_customer_jobs_is_shebao']?></td>
    </tr>
    <tr>
        <th align="right">其他联系人关系：</th>
        <td style="padding-left:5px;"><?=$data['c_kinship_relation']?></td>
    </tr>
    <tr>
        <th align="right">其他联系人姓名：</th>
        <td style="padding-left:5px;"><?=$data['c_kinship_relation']?></td>
    </tr>
    <tr>
        <th align="right">其他联系人电话：</th>
        <td style="padding-left:5px;"><?=$data['c_other_people_cellphone']?></td>
    </tr>
    <tr>
        <th align="right">住房情况：</th>
        <td style="padding-left:5px;"><?=$data['c_family_house_info']?></td>
    </tr>
    <tr>
        <th align="right">住房情况：</th>
        <td style="padding-left:5px;"><?=$data['c_customer_name']?></td>
    </tr>
    </tbody>
</table>


<h2 style="color:red;text-align:center;margin-bottom:5px;">订单信息</h2>
<table style="padding:10px;width:100%;background-color:#efefef;border-radius:8px;" >
    <tbody>
    <tr style="">
        <th align="right" style="width:32%">订单号：</th>
        <td style="padding-left:5px;"><?=$data['o_serial_id']?></td>
    </tr>
    <tr>
        <th align="right">总金额：</th>
        <td style="padding-left:5px;"><?=$data['o_total_price']?></td>
    </tr>
    <tr>
        <th align="right">贷款金额：</th>
        <td style="padding-left:5px;"><?=$total_borrow_money?></td>
    </tr>
    <tr>
        <th align="right">首付金额：</th>
        <td style="padding-left:5px;"><?=$data['o_total_deposit']?></td>
    </tr>
    <tr>
        <th align="right">是否使用个人保障计划：</th>
        <td style="padding-left:5px;"><?=$data['o_is_add_service_fee']?></td>
    </tr>
    <tr>
        <th align="right">是否使用贵宾服务包：</th>
        <td style="padding-left:5px;"><?=$data['o_is_free_pack_fee']?></td>
    </tr>
    <tr>
        <th align="right">是否使用银行代扣：</th>
        <td style="padding-left:5px;"><?=$data['o_is_auto_pay']?></td>
    </tr>
    <tr>
        <th align="right">客户银行卡号：</th>
        <td style="padding-left:5px;"><?=$data['c_banknum']?></td>
    </tr>
    </tbody>
</table>


<h2 style="color:red;text-align:center;margin-bottom:5px;">产品信息</h2>
<table style="padding:10px;width:100%;background-color:#efefef;border-radius:0 0 10px 10px;" >
    <tbody>
    <tr style="">
        <th align="right" style="width:32%">产品名：</th>
        <td style="padding-left:5px;"><?=$data['p_name']?></td>
    </tr>
    <tr>
        <th align="right">期数：</th>
        <td style="padding-left:5px;"><?=$data['p_period']?></td>
    </tr>
    <tr>
        <th align="right">月利率：</th>
        <td style="padding-left:5px;"><?=$data['p_month_rate']?>%</td>
    </tr>
    <tr>
        <th align="right">财务管理费利率：</th>
        <td style="padding-left:5px;"><?=$data['p_finance_mangemant_fee']?>%</td>
    </tr>
    <tr>
        <th align="right">客户管理费利率：</th>
        <td style="padding-left:5px;"><?=$data['p_customer_management']?>%</td>
    </tr>
    </tbody>
</table>


<h2 style="color:red;text-align:center;margin-bottom:5px;">商户信息</h2>
<table style="padding:10px;width:100%;background-color:#efefef;border-radius:0 0 10px 10px;" >
    <tbody>
    <tr style="">
        <th align="right" style="width:32%">商户名：</th>
        <td style="padding-left:5px;"><?=$data['s_name']?></td>
    </tr>
    <tr>
        <th align="right">负责人姓名：</th>
        <td style="padding-left:5px;"><?=$data['s_owner_name']?></td>
    </tr>
    <tr>
        <th align="right">负责人电话：</th>
        <td style="padding-left:5px;"><?=$data['s_owner_phone']?></td>
    </tr>
    <tr>
        <th align="right">负责人邮箱：</th>
        <td style="padding-left:5px;"><?=$data['s_owner_email']?></td>
    </tr>
    </tbody>
</table>

</body>
</html>