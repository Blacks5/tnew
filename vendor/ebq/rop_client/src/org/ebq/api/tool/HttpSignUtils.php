<?php 
namespace org\ebq\api\tool;
use Exception;
/**
 * httpSignUtils工具类,用于http请求处理的签名,及签名服务调用方法<br/>
 * @edit yfx 2015.10.26
 * */
class HttpSignUtils{

    /**
     * 通过字符和时间点生成签名地址
     * @param $bodyParams
     * @param null $timestamp
     * @param $app_key
     * @param $app_secret
     * @return string
     */
	static function createHttpUrl($bodyParams,$timestamp=null,$app_key,$app_secret){
		if($timestamp==null){
			$timestamp=self::getMillisecond();
		}
		$sign=self::createHttpSign($bodyParams,$timestamp,$app_key,$app_secret);
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
     * @param $bodyParams
     * @param null $timestamp
     * @param $app_key
     * @param $app_secret
     * @return mixed
     */
	static function createHttpSign($bodyParams,$timestamp=null,$app_key,$app_secret){
		if($timestamp==null){
			$timestamp=self::getMillisecond();
		}
		$contactStr=self::getParams($bodyParams);
		$contactStr.="timestamp".$timestamp;
		$contactStr.="appKey".$app_key;
		$contactStr.="appSecret".$app_secret;
		$sign=ShaUtils::getSha1($contactStr);
		return $sign;
	}

    /**
     *  检查签名
     * @param $bodyParams
     * @param $timestamp
     * @param $appKey
     * @param $appSecret
     * @param $sign
     * @param int $expireLength
     * @throws Exception
     */
	static function checkHttpSign($bodyParams,$timestamp,$appKey,$appSecret,$sign,$expireLength=1800000){
		if($bodyParams==null||!is_array($bodyParams)){
			throw new Exception("bodyParams不正确");
		}
		if($timestamp==null||!is_numeric($timestamp)){
			throw new Exception("timestamp不正确");
		}
		$sign1=self::createHttpSign($bodyParams,$appKey,$appSecret,$timestamp);
		if(!strcasecmp($sign1,$sign)==0){
			throw new Exception("签名不正确");
		}
		if(!$timestamp+$expireLength>self::getMillisecond()){
			throw new Exception("请求已过期");
		}
	}

    /**
     *  将字典转换为字符串
     * @param $maps
     * @return string
     */
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
     * @param $str
     * @return string
     */
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
     * @param $url
     * @param $requestObj
     * @return string 返回请求结果
     * @throws Exception
     */
	static function doPostByObj($url,$requestObj){
		if(is_null($url)){
			throw new Exception("url is null");
		}
		if(is_object($requestObj)&&is_subclass_of($requestObj,RichServiceRequest::class)){
			if($requestObj->validate("sign")){
				$requestArr=$requestObj->getObject2Array();
				return static::doPost($url,$requestArr);
			}
		}else{
			throw new Exception("requestObj参数不是一个org\ebq\api\model\RichServiceRequest对象");
		}
		return null;
	}

    /**
     * 请求sign服务端
     * @param $url
     * @param $paramValues
     * @param $appKey
     * @param $appSecret
     * @return bool|string
     */
	static function doPost($url,$paramValues,$appKey,$appSecret){
		$content=self::createHttpUrl($paramValues,null,$appKey,$appSecret);
		$headerStr=self::createHeaderStr($appKey);
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
     * @param $appKey
     * @param string $contentType
     * @return string
     */
	static function createHeaderStr($appKey,$contentType="application/x-www-form-urlencoded;charset=UTF-8"){
		$contactStr="";
		//其它信息
		$contactStr=$contactStr.'appKey:'.$appKey."\r\n";
		$contactStr=$contactStr."user-agent:php\r\n";
		$contactStr=$contactStr."Content-type: ".$contentType."\r\n";
	
		$contactStr=$contactStr."accept:text/html, image/gif, image/jpeg, *; q=.2, */*; q=.2\r\n";
		$contactStr=$contactStr."connection:keep-alive\r\n";
		return $contactStr;
	}
}