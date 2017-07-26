<?php
namespace jzq\test;
/**
 * 客户端配置信息
 * @author yfx 2016-07-02
 */
class ClientInfo{
	
	/**
	 * 沙箱和正式环境的app_key、app_secret、services_url均不同 
	 * 前期测试请联系技术支持申请沙箱的：app_key、app_secret
	 * 生产上不要用于测试，请使用正规内容进行保全api操作。
	 * */
	
	//本地
	//static $services_url = 'http://localhost:8091/services';
	//static $services_url = 'http://www.junziqian.dev:8081/m/applaySign/affirmBack';
	//app_key对应从服务商申请到的appkey
	//static $app_key = 'e5fed7488fb65b1f';
	//appkey对应的密钥,客户使用,不能公开
	//static $app_secret = 'b54b0c77e5fed7488fb65b1f647df94c';
	
	//sandbox
	static $services_url = '你的services_url';
	//app_key对应从服务商申请到的appkey
	static $app_key = "你的app_key";//'请输入$app_secret';
	//appkey对应的密钥,客户使用,不能公开
	static $app_secret = "你的app_secret";//'请输入$app_secret';
	
	//生产
    //static $services_url = '你的services_url';
    //app_key对应从服务商申请到的appkey
    //static $app_key = "你的app_key";//'请输入$app_secret';
    //appkey对应的密钥,客户使用,不能公开
    //static $app_secret = "你的app_secret";//'请输入$app_secret';
}
?>
