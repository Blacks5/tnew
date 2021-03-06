<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="user-scalable=no, width=device-width, initial-scale=1.0"/>
</head>
<style>
    .container {
        border: 1px solid #9d9d9d;
        padding: 15px;
    }

    .w_div {
        display: block;
        float: left;
        width: 100%;
    }

    dl.info_box {
        display: block;
        float: left;
        width: 100%;
    }

    dl.info_box .info_box_dt {
        margin-bottom: 6px;
        font-size: 18px;
        font-weight: bold;
    }

    dl.info_box dd {
        margin-bottom: 10px;
    }

    .td_title {
        font-weight: bold;
        width: 20%;
    }
</style>
<body>
<div class="container">
    <div class="w_div">
        <dl class="info_box">
            <dt class="info_box_dt">商户信息：</dt>
            <dd>商户名称：<span class="info_box_span"> <?=$data['s_name'];?></span></dd>
            <dd>商户负责人姓名：<span class="info_box_span"> <?=$data['s_owner_name']?></span></dd>
            <dd>销售顾问：<span class="info_box_span"><?=$data['o_user_id']?> </span></dd>
        </dl>
    </div>
    <div class="w_div">
        <dl class="info_box">
            <dt class="info_box_dt">还款信息：</dt>
            <dd>贷款金额：<span class="info_box_span"> <?= $data['total_all']?> 元</span></dd>
            <dd>是否参与个人保障计划：<span class="info_box_span"> <?=$data['o_is_add_service_fee'] ?></span></dd>
            <dd>是否选择贵宾服务包：<span class="info_box_span"> <?=$data['o_is_free_pack_fee']?></span></dd>
            <dd>客户服务费：<span class="info_box_span"> <?=$data['o_service_fee']?>元</span></dd>
            <dd>查询费：<span class="info_box_span"> <?=$data['o_inquiry_fee']?>元</span></dd>
            <dd>分期月数：<span class="info_box_span"> <?=$data['p_period']?>月</span></dd>
            <dd>每月还款：<span class="info_box_span"><?=round($every_month_repay, 2)?> 元</span></dd>
            <dd>首次还款日：<span class="info_box_span"><?=date('Y-m-d', $data['first_repay_time'])?></span></dd>
            <dd>每月还款日期：<span class="info_box_span"> <?=date('d', $data['first_repay_time'])?>日</span></dd>
            <dd>还款说明：<span class="info_box_span"> 天牛金融将在还款日从借款人银行卡自动扣除应还金额</span> <a href="#flu">【见附录】</a> </dd>

        </dl>
    </div>
    <div class="w_div">
        <?php foreach ($data['data_goods'] as $k=>$v){ ?>
            <dl class="info_box">
                <dt class="info_box_dt">商品信息：</dt>
                <dd>商品类型：<span class="info_box_span"><?= $v['g_goods_type']?> </span></dd>
                <dd>商品品牌：<span class="info_box_span"> <?= $v['g_goods_name']?></span></dd>
                <dd>商品型号：<span class="info_box_span"> <?= $v['g_goods_models']?></span></dd>
                <dd>商品价格：<span class="info_box_span"> <?= $v['g_goods_price']?></span></dd>
        </dl>
        <?php } ?>
    </div>
    <div class="w_div">
        <dl class="info_box">
            <dt class="info_box_dt">申请人信息：</dt>
            <dd>合同编号：<span class="info_box_span"> <?=$data['o_serial_id']?></span></dd>
            <dd>申请姓名：<span class="info_box_span"> <?=$data['c_customer_name']?></span></dd>
            <dd>手机号码：<span class="info_box_span"> <?=$data['c_customer_cellphone']?></span></dd>
            <dd>身份证号：<span class="info_box_span"> <?=$data['c_customer_id_card']?></span></dd>
            <dd>居住地址：<span class="info_box_span"> <?=$now_address?></span></dd>
        </dl>
    </div>
    <div class="w_div">
        <dl class="info_box">
            <dt class="info_box_dt">借款人信息：</dt>
            <dd>借款人姓名：<span class="info_box_span"> <?=$data['c_customer_name']?></span></dd>
            <dd>开户银行：<span class="info_box_span"> <?=$data['c_bank'] ?></span></dd>
            <dd>银行卡号：<span class="info_box_span"> <?=$data['c_banknum'] ?></span></dd>
        </dl>
    </div>

    <div class="w_div" style="border-top: 2px solid #000;padding-top: 10px;">
        <dl class="info_box">
            <dd>(1)<span class="info_box_span"> 以上信息真实，准确；</span></dd>
            <dd>(2)：<span class="info_box_span"> 借款服务合同与商品购买合同是独立的法律关系，天牛金融不对商户所提供商品的质量承担任何责任；</span></dd>
            <dd>(3)：<span class="info_box_span"> 如购买的商品是货物，则借款人已取得该商品或取货凭证；并且商品与本合同中的描述一致，可以正常使用；</span></dd>
            <dd>(4)：<span class="info_box_span"> 如购买的商品是服务，无论借款人是否实际享受该服务，借款人必须按照借款服务合同的条款赔偿还借款。</span></dd>
            <dd>(5)：<span class="info_box_span"> 点击确认即视为认同该借款服务合同及其他相关合同等（如有）,同时授权相关CA机构为您制作具有法律效力的电子签章，并在前述合同上加盖电子签章。</span>
            </dd>
        </dl>
    </div>
    <div class="w_div">
        <h2><b>借款服务合同</b></h2>
        <dl class="info_box">
            <dt>
            <h4>提示条款</h4></dt>
            <dd>欢迎您与天牛金融共同签署本《借款服务合同》成为天牛金融的客户!各服务条款前所列索引关键词仅为帮助您理解该条款表达的主旨之用，不影响或限制本合同条款的含义或解释。为维护您自身权益，建议您仔细阅读各条款具体表述。
            <dd>
            <dd><b>【审慎阅读】</b>您在“天牛金融”APP中点击同意本协议之前，应当认真阅读本协议。<b>请您务必审慎阅读、充分理解各条款内容，特别是免除或者限制责任的条款、法律适用和争议解决条款。免除或者限制责任的条款将以粗体下划线条标识，您应重点阅读。</b>如您对本合同有任何疑问，可向天牛金融客服咨询。
                </b></dd>
            <dd><b>【签约动作】</b>当您在分期付款服务申请程序中填写申请信息、阅读并同意本合同且完成全部申请程序后，即表示您已充分阅读、理解并接受本合同的全部内容，并与有用分期达成一致，成为天牛金融的客户。<b>阅读本合同的过程中，如果您不同意本合同或其中任何条款约定，您应立即停止申请程序。</b>
            </dd>
        </dl>
    </div>
    <h3>第一部分 借款明细</h3>
    <dl class="info_box">
        <dt>
        <h4 style="width: 50%;float: left;">借款类型：商品贷</h4><h4 style="width: 50%;float: left;">合同编号：<?=$data['o_serial_id']?></h4></dt>
        <dd>
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td class="td_title">借款人姓名：<?=$data['c_customer_name']?></td>
                    <td></td>
                    <td class="td_title">身份证号码：<?=$data['c_customer_id_card']?></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="td_title">居住地址：<?=$now_address?></td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td class="td_title">联系电话：<?=$data['c_customer_cellphone']?></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="td_title">门店名称：<?=$data['s_name'];?></td>
                    <td colspan="3"></td>
                </tr>
                <tr>
                    <td class="td_title">商品类型：<?= $v['g_goods_type']?></td>
                    <td></td>
                    <td class="td_title">销售顾问：<?=$data['o_user_id']?></td>
                    <td></td>
                </tr>
                <tr>
                    <td class="td_title">产品系列代码：<?=$data['p_name']?></td>
                    <td></td>
                    <td class="td_title">客户服务费:<?= $data['o_service_fee'] ?>元</td>
                </tr>
                <tr>
                    <td class="td_title">商品总价：<?= $data['o_total_price'] ?>元</td>
                    <td></td>
                    <td class="td_title">查询服务费:<?= $data['o_inquiry_fee'] ?>元</td>
                </tr>
                <tr>
                    <td class="td_title">首付金额：<?=$data['o_total_deposit']?> 元</td>
                    <td></td>
                    <td class="td_title">借款金额：<?= $data['total_all']?> 元</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="td_title">财务管理费(%)：<?=$data['p_finance_mangemant_fee']?> </td>
                    <td></td>
                    <td class="td_title">客户管理费(%)：<?=$data['p_customer_management']?> </td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </dd>
        <dd>
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td class="td_title">每月还款日：<?=date('Y-m-d', $data['o_created_at'])?></td>
                    <td></td>
                    <td class="td_title">每月还款金额：<?=round($every_month_repay, 2)?> 元</td>
                    <td></td>
                </tr>
                <tr>
                    <td class="td_title">分期期数：<?=$data['p_period']?>期</td>
                    <td></td>
                    <td class="td_title">还款方式：</td>
                    <td>等额本息</td>
                </tr>
                <tr>
                    <td class="td_title">参与个人保障计划： <?=$data['o_is_add_service_fee'] ?></td>
                    <td>金额:<?= round($data['p_add_service_fee'] * ($data['o_total_price'] - $data['o_total_deposit'])/100, 2)?>元/每月</td>
                    <td class="td_title">购买贵宾服务包：<?=$data['o_is_free_pack_fee']?></td>
                    <td>金额:<?=$data['p_free_pack_fee']?>元/每月</td>
                </tr>
                </tbody>
            </table>
        </dd>
    </dl>
    <dl class="info_box">
        <dd>借款人应在约定的每月还款日的至少前一日在还款账户中存入足够的金额并保证还款账户处于正常的可以划扣的状态。借款人同意且授权天牛金融或天牛金融委托的第三方从下述账户进行扣款。</dd>
        <dd>银行名称：<span class="info_box_span"> <?=$data['c_bank'] ?></span></dd>
        <dd>银行账户：<span class="info_box_span"> <?=$data['c_banknum'] ?></span></dd>
        <dd>账户名：<span class="info_box_span"><?=$data['c_customer_name']?> </span></dd>
    </dl>
    <dl class="info_box">
        <dd>(1)<span class="info_box_span"> 以上信息真实，准确；</span></dd>
        <dd>(2)<span class="info_box_span"> 借款人已支付首付金额；</span></dd>
        <dd>(3)：<span class="info_box_span"> 借款服务合同与商品购买合同是独立的法律关系，天牛金融不对商户所提供商品的质量承担任何责任；</span></dd>
        <dd>(4)：<span class="info_box_span"> 如购买的商品是货物，则借款人已取得该商品或取货凭证；并且商品与本合同中的描述一致，可以正常使用；</span></dd>
        <dd>(5)：<span class="info_box_span"> 如购买的商品是服务，无论借款人是否实际享受该服务，借款人必须按照借款服务合同的条款赔偿还借款。</span></dd>
        <dd>(6)：<span class="info_box_span"> 本人已阅读并理解借款服务合同的通用条款并自愿遵守合同的规定。</span></dd>
    </dl>
    <div>
        <p style="width: 50%;float: left;"><b>客户签名：<?=$data['c_customer_name']?></b></p>
        <p style="width: 50%;float: left;"><b>天牛金融盖章：</b></p>
    </div>
    <dl class="info_box" style="margin-top: 50px;">
        <dd>
            <h3>借款服务详情</h3>
            <h4>第二部分通用条款</h4>
            鉴于：
            (1)深圳天牛金融服务有限公司（以下简称“天牛金融”）是一家依法成立的从事消费金融信息咨询服务的公司，为消费者提供分期付款信息服务;
            (2)借款人拟通过天牛金融申请一笔“借款明细”中所列明的借款，用于从“借款明细”上所列的商户（以下简称“商户”）处购买“借款明细”上所列的货物或服务（以下统称为“商品”）天牛金融为借款人寻找合适的出借人，并为借款人提供分期付款信息服务。天牛金融，借款人现就借款相关的服务事宜共同确定以下条款：

            <h5>一、基本借款条款与定义</h5>
            1.借款金额：以“借款明细”所列“尾款金额”以及用于向天牛金融支付其根据下述第二条约定一次性收取的“借款管理费”之和为准。但不含借款人自行支付的金额（即“借款明细”所列的“首付金额”）。
            2.每月还款金额：其中包含应支付予出借人的借款金额、利息及应支付予天牛金融及天牛金融合作客户的服务费及其他应付的款项。本借款应以按月等额本息还款的方式偿还。全额偿还本借款（包括借款人履行本合同项下其他的支付义务，违约金除外）所需的分期期数以及每期应支付的期款以“借款明细”所列“分期期数”和“每月还款金额”为准。
            3.借款管理费：天牛金融提供资格审核并为借款人寻找合适的出借人等撮合服务所收取的费用。
            4.客户服务费：天牛金融为借款人持续提供分期咨询，信息查询，资料保管和调阅等咨询服务所收取的费用。客户服务费自商品激活开始计算，按月结算，并计算在“借款明细”中列明的“每月还款额中”，不足一月的按照实际天数结算。
            5.利息：是指借款人应支付给出借人的借款利息，利息自商品激活日开始计算，按月结息，并计算中“借款明细”中列明的“每月还款金额”中。不足一月的按实际天数计算。
            6.借款期间从借款人签署本合同之日开始至最后一笔期款的到期日为止。为避免误解，借款期间结束后，如借款人仍拖欠本合同项下的任何款项，其还款义务并不因此而解除。
            7.每一期期款的到期日为借款期间内，借款人签署本合同之日止每个月度的对应日（如某月度无对应日，则到期日统一以每月28号为准。
            8.借款人：是指“借款明细”中列明的、向商户购买商品并向欧阳分期申请分期付款服务的自然人。
            9.出借人：是指经天牛金融的合作客户撮合，向借款人提供借款的、具有完全民事行为能力的自然人。法人或其他机构，包括但不限于商业银行、第三方融资平台上的投资人以及其他依法能够提供借款的机构或个人。出借人或天牛金融及其合作客户委托的第三方将“借款明细”中载明的“尾款金额”支付至商户或天牛金融与商户约定的账户，即视为借款发放成功。
            10.本合同：是指《借款服务合同》，具体由两部分组成：第一部分为“借款明细”，第二部分为通用条款“，两部分内容均作为本合同不可分割的一部分。
            <h5>二、客户服务</h5>
            1.就本合同，天牛金融向借款人提供以下服务：（1）分期服务咨询；（2）咨询、销售及服务点维护（如有）；（3）借款人咨询服务；（4）借款人咨格审核及推荐；（5）个人信息以及联系/通信信息更改管理（免费)(6)代为进行商品下单服务或与线下商户接洽并协助借款人购买商品（免费）；（7）借款人合同付清证明文件服务（免费）；（）借款人还款信息查询服务（免费）；（9）还款结算与错误还款后续跟进服务（免费）；以及（10）溢缴款处理（免费）。
            2.就上述服务，天牛金融向借款人收取“客户服务费”，和“借款管理费”，“客户服务费”的收取方式为期间内按月收取并包含中每一期期款中；“借款管理费”的收取方式为准借款发放是由天牛金融一次性扣除。（天牛金融有权对前述费用请时间进行调整，但不改变借款人每月还款金额、还款时间及年化综合费用成本）。
            <h5>三、借款人的同意与授权</h5>
            1借款人同意并授权天牛金融及其合作客户、出借人按照相关法律、法规和监管要求，向中国人民银行金融信用信息基础数据库、其他依法设立的征信机构、攻啊怒公民身份信息数据库查询和获取借款人 个人信息、个人征信相关报送上述信息。
            2.借款人同意并授权天牛金融出于提供个人借款业务之需，允许其关联公司、业务合作伙伴及其他合法的来源查询并获取借款人的个人相关信息（包括但不限于本合同中借款人的相关信息）。
            3.借款人理解并同意天牛金融将部分与个人借款业务有关的服务外包给其他业务合作伙伴，并为此目的将借款人个人信息提供给该业务伙伴，且该业务合作伙伴享有天牛金融在本合同项下的权利和授权。
            4.借款人同意并授权天牛金融在商业银行或第三方融资平台以借款人名义申请账号或注册用户，该账号或银行仅限用于本合同之目的，且商业银行或第三方融资平台有权限制借款人转移账户或用户的资金。借款人有权限根据协议及商业银行或第三方融资平台规则。
            5.借款人授权天牛金融将借款人的借款信息和个人信息（包括但不限于：性别，年龄，学历，家庭状况，户籍，资产状况，身份证件以及个人相关征信信息等）提交给商业银行，或通过第三方融资平台以网络公开的方式发布借款需求。如在前述借款信息发布之日起3个工作日后仍未能实现匹配借款，授权天牛金融独立判断是否取消已经发布的借款信息。过第三方融资平台以网络公开的方式发布借款需求。如在前述借款信息发布之日起3个工作日后仍未能实现匹配借款，授权天牛金融独立判断是否取消已经发布的借款信息。
            6.借款人同意并授权天牛金融以借款人的名义与出借人签订《借款合同》，《借款合同》的借款信息（包括借款金额、借款期限）等于本合同的约定一致。《借款合同》采用电子文本的形式，天牛金融经借款人的借款信息提供系统传输给商业银行或第三方融资平台后，该《借款合同》即对借款人发生效力。
            7.借款人确认并同意，出借人、第三方融资平台或天牛金融委托的第三方机构有权从借款人在“借款明细”中指定的银行账户内划扣借款人应付的客户服务费、违约金、提前还款费等相关费用，。并有权代出借人从借款人指定的账户中划扣《借款合同》项下的借款金额及利息并支付给出借人。
            8.借款人授权天牛金融中出借人向借款人发放借款是，代借款人直接将尾款金额支付至“借款明细‘中列明的商户。
            9.借款人同意，如借款人在履行《借款合同》及本合同的过程中存在违约情形，天牛金融可将借款人不良信息予以公开披露或向征信机构提供。
            10.借款人同意，对着申请借款服务过程中所获取的天牛金融、出借人信息、天牛金融与之签署的本合同及借款人与出借人签订的《借款合同》等相关法律文件的内容负有保密义务，未经天牛金融及出借人事先书面许可，不得向任何第三方披露，否则，应赔偿天牛金融及出借人因此而遭受的损失。
            11.借款人确认并承诺，本合同产生的法律关系与借款人跟谁之间的商品买卖合同关系是完全独立的。该买卖合同关系的无效或变更并不影响本合同的法律效力。故借款人不得以与商户之间的任何纠纷（包括涉及商品质量或者售后服务）为由拒绝履行本合同项下的任何义务。因密码合同导致的纠纷，包括但不限于退货、换货、售后服务等，均由借款人与商户自行解决，与出借人、天牛金融无关；且上述纠纷不得影响借款人在本合同项下之还款义务及借款管理费、客户服务费的支付义务。
            12.借款人在《借款合同》向下选择提前还款、《借款合同》项下借款被宣布提前到期或《借款合同》提前终止、各方解除、变更《借款合同》或未实际履行《借款合同》等情形的，天牛金融已收取的客户服务费和借款管理费、借款利息不予返还。自己人、借款人双方有违约情形发生的，不影响天牛金融费用的收取及收费金额。
            13.借款人同意并授权天牛金融使用借款人的个人信息向借款人推荐产品。推荐的方式包括但不限于向借款人通讯地址或电子邮箱地址发送商业广告或向借款人的移动电话、QQ/微信或其他形式发送商业广告短信。
            14.本合同终止或无效，不影响本条第9项、第13项继续生效。
            <h5>四、天牛金融的权利和义务</h5>
            1.天牛金融应为借款人提供信用审核、推荐、分期服务咨询等借款相关的服务，协助借款人及出借人办理借款相关手续，并代表借款人与出借人签署与本合同内容基本借款信息一致的《借款合同》。
            2.天牛金融有权向借款人收取借款管理费和客户服务费，并代为收取借款利息。
            3.如借款人未按《借款合同》约定足额支付任何一期应还款项，出借人或天牛金融有权自行或委托其他第三方采取催收措施。催收费用由借款人承担，并包含在借款人支付的逾期利息和客户服务违约金中。
            4.天牛金融有权随时对借款人进行贷后检查，借款人应按天牛金融要求提供资料并无条件配合天牛金融的检查工作。
            <h5>五、借款人的权利和义务</h5>
            1.借款人在申请及实现借款的全过程中，必须如实向天牛金融提供所要求提供的个人信息，并保证该休息真实、准确、完整、合法有效。
            2.借款人有权向天牛金融了解其中在天牛金融的信用评审进度及结果，以及借款相关手续的办理进度。
            3.借款人应按照本合同的规定向天牛金融支付借款管理费和客户服务费及借款利息。
            4.借款人同意，借款人成功借款后，天牛金融依据出借人的委托开展还款管理工作，督促借款人按照约定期限及金额进行还款，借款人有义务无条件及时配合天牛金融工作。
            5.如借款人需提前还款，应在还款日前向天牛金融提出申请，天牛金融根据出借人的委托，有权决定是否同意借款人的提前还款申请；天牛金融同意借款人的提前还款的，借款人应在约定的时间将应还款项及提前还款费足额存入指定委托和款账户内。
            6.借款申请过程中及借款存续期间，如借款人提交的证件或资料等即将到期或失效，借款人应在到期或失效之前向天牛金融提交新的有效证件或资料，否则，视为借款人违约。
            7.借款人确认并同意，出借人有权委托天牛金融将本合同项下的全部分债权转让给第三方，天牛金融或委托的第三方进行债权转让时，有权将债权信息以及借款人的个人信息进行公开披露，以寻找合适的债权受让人。出借人根据本合同转让债权的，借款人的还款金额不合还款方式不变。借款人在此不可撤销第同意，出借人和天牛金融中进行债权转让时无须通知借款人或征得借款人的同意。
            <h5>六、参保</h5>
            A 借款人意外险
            1.如借款人在申请表上选择参加保险，表明其同意成为天牛金融向中国平安财产保险股份有限公司投保的《平安借款人意外伤害保险》保单的“被保险人”，同意并认可保险金额为尾款金额的100%，并同意指定金融为第一受益人，天牛金融为第二受益人。保险责任范围为意外伤害身故或伤残责任。疾病身故或全残责任，如发生保险事故，保险金将由保险公司按照受益顺序直接给出借人偿还所欠款项的义务。如果保险金额数额超过借款人所欠两者的款项，则超过部分支付给借款人或其家人。
            2.借款人选择参保的，天牛金融有权自行决定是否将借款人以被保险人的身份加入《平安保险意外伤害保险》保单。
            3.若天牛金融同意借款人以被保险人身份加入《平安借款人意外伤害保险》保单，借款人应向天牛金融
            支付因此而产生的管理成本（以下简称“手续费”，具体以“借款明细”中的“参与个人保障计划”所列金额为准）该手续费包含在每一期期款中，具体金额以“借款明细”所列“每月还款金额”为准。
            4.借款人加入保单之后，天牛金融仍然有权根据借款人的申请或者任何其他原因，终止借款人的被保险人资格，如保险公司与天牛金融之间保单的终止或者天牛金融发现借款人就其健康情况作出不真实的陈述等。在上述情况下，天牛金融不再向借款人收取手续费。
            5.该保单下的任何保险金将直接付至天牛金融，用于偿还借款人因遭受意外伤害事故导致身故，伤残、及疾病身故导致无法偿还出借人的款项。
            6.借款人经过仔细审阅后确认：借款人已经了解关于投保抢被保险人患有以下疾病的不能参保“平安借款人意外伤害保险”的约定，并在此谁声明借款人参保前没有罹患以下疾病：肿瘤、心肌梗塞、白血病、肝硬化、中慢性肝功能衰竭、再生障碍性贫血、先天性疾病、帕金森氏病、精神病、癫痫病、法定传染病、艾滋病等，不如实告知的法律责任有借款人承担。
            7.借款人了解并确认，因下列原因造成借款人身故或伤残的，保险公司不承担给付保险金责任：（1）投标人的故意行为；（2）被保险人自致伤害或自杀，但被保险人自杀时为无民事行为能力的除外；（3）因被保险人挑衅或故意行为而导致的打斗、被袭击或被谋杀；（4）被保险人妊娠、流产、分娩、疾病、药物过敏、中暑、猝死；（5）被保险人接受整容手术及其他内、外科手术；（6）被保险人未尊医嘱，私自服用
            、涂用、注射药物；（7）核爆炸、核辐射或核污染；（8）恐怖袭击；（9）被保险人犯罪或拘捕；（10）被保险人从事高风险运动或参加职业或半职业体育运动。（12）被保险人醉酒或毒品；（13）被保险人酒后驾车、无有效驾驶证驾驶或无有效行驶证的机动车期间；（14）被保险人流产、分娩或由以上原因引起之并发症；（15）被保险人接受整容手术或其他内外科手术过程中发生的医疗事故；（16）既往症；（17）被保险人患艾滋病病毒（HIV呈阳性);(18)被保险人因意外伤害事故身故或全残的；（19）任何医疗事故；（20）保单中特别约定的除外疾病。
            <h5>七、贵宾服务包</h5>
            1.借款人可针对任一笔借款选择购买贵宾服务包（即贵宾服务包，下同），允许借款人优惠提前还款，即借款人通过天牛金融申请的任何一笔借款者足额偿还了三期期款后，借款人收取提前还款时，天牛金融将免除借款人的提前还款费。
            2.借款人购买贵宾服务包的价款（以下简称“贵宾服务包费”，具体以“借款明细”中的“购买贵宾服务包”所列金额为准）按月向天牛金融支付，并包含在“借款明细”中列明的“每月还款金额”中。如借款人收取任一笔借款后，天牛金融施了新的贵宾服务包收费标准，则已有借款的贵宾服务包仍按其原有标准交费。
            3.借款人申请取消一笔借款的贵宾服务包的，应在该笔借款的最后一期期款到期日前提出，已经支付的贵宾服务包费不予退还，即使借款人从未事业部过该服务。
            4.借款人收取优惠提前还款应在最近下一个还款到期日至少一日前通过致电客户提出。
            5.天牛金融保留基于借款人的情况和风险程度减少贵宾服务包的服务范围或全部取消贵宾服务包的权利。
            <h5>八、借款、费用的偿还与支付</h5>
            1.借款人确认，一旦签署本合同，其他应当按照“借款明细”所列每月还款日及还款金额履行很可能义务。
            2.借款人在此不可撤销授权出借人或天牛金融委托的其第三方从“借款明细”上列明的借款人优惠账户划扣到期的期款及所有本合同项下到期应付的其他款项，二无需进一步他自己客人或者得到其同意。代扣将不早于还款到期日
            3.如果任何本合同项下的代扣失败，无论何种原因所致，借款人在本合同项下的还款义务不得因此而减免，如有必要借款人应采用其他合理方式继续清偿债务，且为保证按时履行每月还款义务。借款人在此同意并授权从其提供的其他同名银行卡上进行代扣。
            4.若借款人在签订本合同是仍其他未还清的通过天牛金融申请的个人借款，如果借款人选择银行代扣，则借款人对所有未还清的借款在此授权银行代扣，银行代扣的账户将以借款人最新授权的账户为准。
            5.本合同第四条之任何规定不可被视为免除借款人对本合同项下任何款项的偿还义务，如违约金。
            6.借款人可通过天牛金融提供的自主还款渠道进行还款。为避免重复扣款，自主还款日期应早于还款到期日。如出现重复扣款，天牛金融将作为溢缴款予以处理。
            7.到达指定还款账户的款项，按照如下顺序清偿各项债务（包括借款人通过天牛金融申请的其他借款）：先到期的债务先偿还；申请提前还款的债务先偿还；同时到期的债务中，申请日期在前的优先偿还，违约金先于期款偿还；多笔借款的期款同时到期，先签署的借款服务合同所对应的借款先偿还；期款包含的各款项按照以下顺序依次清偿：（1）逾期利息；（2）客户服务违约金；（3）利息；（4）印花税；（5）借款金额；（6）客户服务费；（7）手续费；（8）贵宾服务包费用。
            8.借款人应妥善保管本合同项下已还款项证明。因借款人 还款引起争议的，由借款人对其还款情况承担举证责任。
            <h5>九、逾期还款</h5>
            1.如果借款人未履行本合同项下的还款义务，其应立即偿还拖欠款项并应当按照本条规定支付逾期利息和客户服务违约金。违约金按照期款逾期天数计算，每一期分开计算
            2.借款人授权按照本合同第八条规定的方式支付逾期期款和违约金。
            3.如借款人逾期支付，每逾期一日须向出借人支付逾期期款的0.05%的逾期利息。
            4.如借款人逾期支付，每逾期一日须向天牛金融支付逾期期款0.95%的客户服务违约金。
            <h5>十、提前还款</h5>
            1.借款人有权提前偿还本合同项下的借款并终止合同。还款指在于某月份（“提前还款月份”）的还款到期日当天或之前一次性支付以下款项（“提前还款金额”）：截止提前还款月份的应付利息、借款管理费、手续费（如有）和客户服务费被，本合同项下所有尚未偿还的借款金额，以及为每笔提前还款的借款支付提前还款费人民币二百元。
            2.借款人提前还款，应在提前还款月份还款到期日之前致电天牛金融申请提前还款并授权其向出借人办理提前还款手续。天牛金融的客服人员将告知借款人具体的提前还款金额，提前还款时的利息和客户服务费需计算至借款人收取提前还款后的首个还款日。
            3.借款人提前还款，其还款方式与第八条约定的偿还期款方式相同，但提前还款时，银行代扣可能在还款到期日之前发生。只有借款人在本合同以及任何其他通过天牛金融的借款笔存在逾期情形时，才可申请提前还款。若借款人在前述合同条款项下发生逾期，任何提前还款将自动取消。
            4.借款人申请退货并经商户同意的，借款人应当向天牛金融提出申请，首付金额由商户退还给借款人，尾款金额由商户退还给天牛金融。借款人自确认收货之日起7日内（含)申请退货的（若商品类别为“消费贷款”，则期限为15日，下同）借款人无须支付利息和客户服务费，但应当支付200元的提前还款费；自确认之日起7日后申请退货的，借款人应当依据本条约定申请提前还款，并支付利息、客户服务费被、提前还款费以及本合同约定的其他费用。借款人了解并确认，本合同与借款人和商户之间的商品买卖关系是独立的，借款人不得以商品质量等原因拒绝支付上述费用。
            <h5>十一、陈述与保证</h5>
            1.借款人向愿意分期陈述保证：借款人为办理个人借款所提供的所有信息（包括商品信息）完整、真实、准确并不存在任何可能影响借款人信用的情况，如涉及借款人诉讼、仲裁、行政程序等，无论以任何形式，也无论是否正在进行或者有潜在可能性。因以上任何陈述不真实、不准确而导致的天牛金融的任何损失，均应由借款人足额赔偿。
            2.借款人应积极配合天牛金融对借款人的信用、借款使用情况、借款偿还情况进行监督。
            3.如果借款人违约，借款人不可撤销地同意天牛金融（或者其授权的第三方）直接或者经由可能认识借款人的第三方，就该违约事件通过当面拜访、电话、邮寄、网络等合法形式提醒借款人或者督查借款人对违约行为进行改正，并且同意天牛金融向该第三方披露此违约事件。此项授权应当于合同终止后依然有效。
            4.借款申请表上的借款人信息发生任何变化、借款人个人资产或者财务状况发生重大变化、或者发生了可能影响借款人履行本合同项下义务的任何其他情况时，借款人均应在五日内通知天牛金融。
            5.借款人承诺借款目的的真实地用于购买“借款明细”中列明的商品，且购买商品的目的是用于个人消费，不存在套现、洗钱、5.非法集资或其他不正当交易行为，否则应依法承担法律责任。
            <h5>十二、提前到期</h5>
            1.以下任何事件发生，出借人可要求借款人立即一次性偿还本合同项下的全部款项。（1）借款人违约；（2）借款人在与其出借人或者其他借款方签署的任何其他借款合同项下发生重大违约；（3）天牛金融有合理证据怀疑借款人自借款日起就借款、客户服务费或借款管理费、从事过任何欺诈行为或借款人可能无能力根据合同付款；（4）借款人逾期还款；（5）若按照天牛金融的合理判断，借款人发生可能对天牛金融的权利或利益造成负面影响的任何其他情形。
            2.如果本条发生在出借人发放借款之前，天牛金融可以解除本合同，并且无需发放借款或者承担任何其他责任。
            3.若借款人在某一笔期款的还款到期日90天之后仍未完全偿还该笔期款，则本合同将提前终止，借款人应立即一次性偿还本合同以下全部款项。合同终止之后不再继续收取逾期利息和客户服务违约金。
            4.若借款人尚有其他通过天牛金融申请的未还清的个人借款，则其他借款服务合同的提前终止也将导致本合同的提前终止。
            <h5>十三、争议解决</h5>
            1.凡因合同引起的或与本合同相关的任何争议，应通过协商解决，若协商仍无法解决，任何一方应向天牛金融所在地人民法院提前诉讼。诉讼方应承担为解决本争议所产生的所有费用，包括但不限于诉讼费、律师费、公证费、交通费等。
            2.若争议正在解决过程之中，合同方面进行履行其在本合同项下的所有义务

            <h5>十四、其他约定</h5>
            1.如本合同任何条款之一被司法机关或者其他有权部门认定为无效，该条款将不影响其余条款的有效性。
            2.借款人在此不可撤销地授权天牛金融保存借款人的本合同原件并同意天牛金融在借款人付清本合同项下的全部款项之后销毁该原件。
            3.天牛金融在回访环节评定出借款人潜在风险较高，天牛金融有权要求借款人提前还款或提前结清。
            4.借款人明确了解并同意，每期应支付的期款为以下（1）至（2）项之和，期款以元为最小单位： （1）每月借款金额和利息；（2）每月客户服务费。
            5.如本协议中有关借款的内容与借款合同中有任何不一致之处，天牛金融有权决定其优先适用顺序。
            6.天牛金融可不时的向借款人提供关于合同履行的优惠条款。与本合同的约定相比，该优惠条款将对借款人更有利。借款人可以通过合理的发生或方法对签署的优惠条款进行确认，经借款人确认后即生效，作为对本合同的变更。
            7.借款人主要勾选位于页面下方的“我同意”选项后，即视为借款人已经充分理解和同意本合同全部条款、内容及各类规则，本合同即对借款人及天牛金融产生法律效力，借款人放弃以为签署书面协议为由否认本合同的效力之抗辩或主张。

            <h2 id="flu">附录：还款说明</h2>
            本人确认一旦提交本借款申请表，即应当在签署本借款申请表后3日内将相关商品的首付金额以银行转账方式支付至上述指定还款账户，否则客户服务供应商有权拒绝接受本人提交的申请并将自付金额按照转账汇款原路径退款,
            本人确认该等转账汇款账户系本人合法所有并确保其可以接收退款，只要按照转账汇款原路径退还自付金额，即视为自付金额已经向本人退还。
            本人选择银行代扣还款，表明本人同意并授权出借人深圳天牛互联网金融服务有限公司（“客户服务供应商”）可通过银行从般若指定的银行账户（即以上客户个人账户）中将每月还款额及其他应付款项转入指定还款账户。
            本人同意此银行代扣授权同时适用于之前所签署的一份或多份借款服务合同，即出借人、客户服务供应商、深圳天牛金融服务有限公司可通过银行从容指定的上述银行账户余额中划扣本人在各合同项下应支付给出借人、客户服务供应商、深圳天牛互联网金融服务有限公司的相关款项。
            此外，该账户同时可用于提前清偿等情况引起的资金往来，若上述银行账户无法完成扣款，则本人同意并授权从本人其他银行卡进行代扣以保证本人按时履行每月还款义务。
        </dd>
    </dl>
    <dl class="info_box">

        <h3>《天牛金融通用授权书》</h3>

        <h4>亲爱的天牛金融客户：</h4>

        <dd>您好！在您（姓名：<span><?=$data['c_customer_name']?></span>，身份证号：<span><?=$data['c_customer_id_card']?></span>)向深圳天牛互联网金融服务有限公司（以下简称“天牛金融”）或与天牛金融进行合作的第三方机构[包括但不仅限于:百融金服,前海征信等第三方服务公司]（以下简称“第三方”）申请分期付款或融资借款业务时，需要您同意并授权如下内容：
        </dd>
        <dd>1、您同意并授权天牛金融、出借人或第三方按照相关法律、法规和监管要求，向中国人民银行金融信用信息基础数据库、其他依法设立的征信机构和获取您的个人信息、个人征信相关信息及其他反应您信用状况的信息。</dd>
        <dd>2、您同意并授权天牛金融、出借人或第三方通过公安部公民身份信息数据库、电信运营以及其他合法渠道核实您在申请分期付款或融资借款时提交的身份证件、联系电话、联系地址等信息的真实性。</dd>
        <dd>
            3、您同意并授权天牛金融、出借人或第三方出于为您提供分期付款或融资业务之需，通过其关联公司、业务合作伙伴及其他合法的来源查询并获取您的财产信息、交易信息及您在行政机关、司法机关、金融机构以及其他自然人、法人和组织留存的信息。
        </dd>
        <dd>
            4、天牛金融、出借人或第三方有权留存上述信息，并承担保密义务，非出于为您提供分期付款业务或融资借款业务之需或根据相关法律法规的需求，不得向任何第三方披露。天牛金融或第三方有权通过短息、邮件电话等方式向您推送产品或服务信息。
        </dd>
        <dd>5、在您的分期付款或融资借款申请审核阶段，以及您与有用分期、出售、出借人签订的《借款服务合同》 、 《借款协议》的生效期间内，本授权持续有效，至《借款服务合同》 、《借款协议》终止且您已经偿还所有应还款项时终止。
        </dd>
        <dd>6、若您的分期付款或融资借款申请经有用或出借人或第三方审核不通过，未能向您提供分期或融资借款的，并不影响本授权的效力。</dd>
        <dd>7、您同意并授权第三方通过有相应资质的资金支付平台将借款金额划付至天牛金融或深圳天牛互联网金融服务有限公司的银行账户，并在扣除您应付第三方及天牛金融的居间服务费用后剩余款项支付至提供本次消费商品的相关商户。
        </dd>
        <dd>8、您同意并授权出借人、第三方融资平台或天牛金融委托的第三方机构从您本人名下的银行账户（账号： <span><?=$data['c_banknum']?></span> ，开户行：<span><?=$data['c_bank']?></span> ）扣划《借款服务合同》中约定的应付款项。
        </dd>
        <dd>您点击确认本授权书即表示同意签署本授权书，接受本授权书的全部条款和内容。</dd>
        <dd>授权人：<span><?=$data['c_customer_name']?></span></dd>
        <dd>日期 <?= date('Y-m-d', $data['o_created_at'])?></dd>
    </dl>
</div>
</body>
</html>