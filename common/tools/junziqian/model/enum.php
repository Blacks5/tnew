<?php
namespace com_junziqian_api_model;
/**
 *用户身分证明类型枚举
 *@edit yfx 2016-07-02
 */
class IdentityType{
	/**"身份证"*/
	static $IDCARD=array("code" => 1, "type" => 0);
	/**"护照"*/
	static $PASSPORT=array("code" => 2, "type" => 0);
	/**"台胞证"*/
	static $MTP=array("code" => 3, "type" => 0);
	/**"港澳居民来往内地通行证"*/
	static $RTMP=array("code" => 4, "type" => 0);
	/**"营业执照"*/
	static $BIZLIC=array("code" => 11, "type" => 1);
	/**"统一社会信用代码"*/
	static $USCC=array("code" => 12, "type" => 1);
	/**"其他"*/
	static $OTHER=array("code" => 99, "type" => 3);
}
/**
 * 验证等级
 * @deprecated 类已过时，请使用AuthLevel
 */
class AuthenticationLevel{
	/**无认证*/
	static $NONE="0";
	/**USBKey数字证书认证CA*/
	static $USEKEY="1";
	/**银行卡认证*/
	static $BANKCARD="2";
	/**支付宝认证*/
	static $ALIPAY="3";
	/**请选择任意一种验证方式*/
	static $ANYONE="4";
	/**请选择任意两种验证方式*/
	static $ANYTWO="5";
	/**请选择任意三种验证方式*/
	static $ANYTHREE="6";
	/**请使用CA及银行卡验证*/
	static $USEBANK="7";
	/**请使用银行卡及支付宝验证*/
	static $BANKALI="8";
	/**请使用CA及支付宝验证*/
	static $USEALI="9";
	/**三要素认证*/
	static $BANKTHREE="10";
	/**其他*/
	static $OTHER="99";
}
/**
 * 验证等级
 */
class AuthLevel{
    /**USBKey数字证书认证CA*/
    static $USEKEY=1;
    /**银行卡认证*/
    static $BANKCARD=2;
    /**支付宝认证*/
    static $ALIPAY=3;
    /**三要素认证*/
    static $BANKTHREE=10;
    /**人脸识别*/
    static $FACE=11;
}
/**
 * 企业类型
 * */
class OrganizationType{
	/**"企业"*/
	static $ENTERPRISE="0";
	/**"事业单位"*/
	static $PUBLIC_INSTITUTION="1";
}
/**签章等级*/
class SignLevel {
	/**"标准图形章"*/
	static $GENERAL="0";
	/**"公章或手写"*/
	static $SEAL="1";
	/**"公章手写或手写"*/
	static $ESIGNSEAL="2";
}
/**签字状态*/
class SignStatus{
	/**尚未生成初始化合同*/
	static $NOTINIT=-1;
	/**待签*/
	static $INPROGRESS=0;
	/**完成*/
	static $COMPLETED=1;
	/**拒签*/
	static $REFUSE=2;
	/**已保全*/
	static $PRES=3;
}
/**合同的处理类型，可以人签，也可以自动签*/
class DealType{
    /**尚未生成初始化合同*/
    static $DEFAULT="0";
	/**自动签字并保全*/
    static $AUTH_SIGN="1";
    /**只做保全，用户不做签字*/
    static $ONLY_PRES="2";
	/**部份云证书自动签字*/
	static $AUTH_SIGN_PART='5';
}
?>
