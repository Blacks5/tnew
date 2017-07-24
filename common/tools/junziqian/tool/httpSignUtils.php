<?php 
namespace com_junziqian_api_tool;
require_once(dirname(__FILE__).'/../cfg/clientInfo.php');
require_once(dirname(__FILE__).'/../tool/shaUtils.php');
use Exception as Exception;
use com_junziqian_api_cfg\ClientInfo as ClientInfo;
use com_junziqian_api_tool\ShaUtils as ShaUtils;
/**
 * httpSignUtils工具类,用于http请求处理的签名,及签名服务调用方法<br/>
 * @edit yfx 2015.10.26
 * */
class HttpSignUtils{
	/**
	 * 通过字符和时间点生成签名地址
	 **/
	static function createHttpUrl($bodyParams,$timestamp=null){
		if($timestamp==null){
			$timestamp=self::getMillisecond();
		}
		$sign=self::createHttpSign($bodyParams,$timestamp);
		$url='timestamp='.$timestamp.'&';//timestamp必须放在最前面民
		foreach($bodyParams as $key=>$val) {
			$val=trim($val);
			$val=urlencode($val);
			$url.=$key.'='.$val.'&';
		}
		$url.='sign='.$sign;
		return $url;
	}
	
	/**
	 * 通过字符和时间点生成签名
	 **/
	static function createHttpSign($bodyParams,$timestamp=null){
		if($timestamp==null){
			$timestamp=self::getMillisecond();
		}
		$contactStr=self::getParams($bodyParams);
		$contactStr.="timestamp".$timestamp;
		$contactStr.="appKey".ClientInfo::$app_key;
		$contactStr.="appSecret".ClientInfo::$app_secret;
		$sign=ShaUtils::getSha1($contactStr);
		return $sign;
	}
	/**
	 * 检查签名
	 **/
	static function checkHttpSign($bodyParams,$timestamp,$appKey,$appSecret,$sign,$expireLength=1800000){
		if($bodyParams==null||!is_array($bodyParams)){
			throw new Exception("bodyParams不正确");
		}
		if($timestamp==null||!is_numeric($timestamp)){
			throw new Exception("timestamp不正确");
		}
		$sign1=self::createHttpSign($bodyParams,$timestamp);
		if(!strcasecmp($sign1,$sign)==0){
			throw new Exception("签名不正确");
		}
		if(!$timestamp+$expireLength>self::getMillisecond()){
			throw new Exception("请求已过期");
		}
	}
	
	/**
	 * 将字典转换为字符串
	 **/
	static function getParams($maps){
		$contactStr='';
		if($maps!=null){
			//字符串关联数组排序
			ksort($maps);
			//拼接
			foreach($maps as $key=>$val) {
				$val=self::trim($val);
				$contactStr=$contactStr.$key.$val;
			}
		}
		return $contactStr;
	}
	
	/**
	 * 处理无效字符串
	 **/
	static function trim($str){
		if($str==null){
			if(is_numeric($str)){
				return '0';
			}
			return '';
		}else{
			return trim($str.'');
		}
	}
	
	/**
	 * 毫秒级时间戳
	 * */
	static function getMillisecond() {
		list($t1, $t2) = explode(' ', microtime());
		return (float)sprintf('%.0f', (floatval($t1) + floatval($t2)) * 1000);
	}
	
	/**
	 * 请求sign服务端
	 * @param 请求对象  $requestObj
	 * @throws Exception
	 * @return string 返回请求结果
	 */
	static function doPostByObj($url,$requestObj){
		if(is_null($url)){
			throw new Exception("url is null");
		}
		if(is_object($requestObj)&&is_subclass_of($requestObj,'org_mapu_themis_rop_model\RichServiceRequest')){
			if($requestObj->validate("sign")){
				$requestArr=$requestObj->getObject2Array();
				return self::doPost($url,$requestArr);
			}
		}else{
			throw new Exception("requestObj参数不是一个org_mapu_themis_rop_model\RichServiceRequest对象");
		}
	}
	

	/**
	 * 请求sign服务端
	 * @param unknown $url 服务地址
	 * @param unknown $paramValues 请求参数
	 * @param string $contentType 上传http类型,默认表单文本提交
	 * @return string 返回文本
	 */
	static function doPost($url,$paramValues,$contentType="application/x-www-form-urlencoded; charset=UTF-8"){
		$content=self::createHttpUrl($paramValues);
		$headerStr=self::createHeaderStr();
		//生成请求参数
		//var_dump($content); //
		$header_data=array(
				"http" => array (
						'method' => 'POST',
						'header'=> $headerStr,
						'content' => $content//$paramStr
				)
		);
		$request = stream_context_create($header_data);
		$response = file_get_contents($url, false, $request);
		return $response;
	}
	
	/**
	 * 创建请求头
	 * @param string $contentType 
	 * @return string
	 */
	static function createHeaderStr($contentType="application/x-www-form-urlencoded;charset=UTF-8"){
		$contactStr="";
		//其它信息
		$contactStr=$contactStr.'appKey:'.ClientInfo::$app_key."\r\n";
		$contactStr=$contactStr."user-agent:php\r\n";
		$contactStr=$contactStr."Content-type: ".$contentType."\r\n";
	
		$contactStr=$contactStr."accept:text/html, image/gif, image/jpeg, *; q=.2, */*; q=.2\r\n";
		$contactStr=$contactStr."connection:keep-alive\r\n";
		return $contactStr;
	}
}
?>