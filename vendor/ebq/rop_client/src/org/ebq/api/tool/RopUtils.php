<?php
namespace org\ebq\api\tool;
use org\ebq\api\model\bean\UploadFile;
use Exception;
use org\ebq\api\model\RichServiceRequest;

/**
 * ropUtil工具类
 * @edit yfx 2016-07-02
 * */
class RopUtils{
    static $cv='1.1.1';
    /**
     * 对ropClient请求签名
     * @param $paramValues
     * @param $ignoreParamNames
     * @param $headerMap
     * @param $extInfoMap
     * @param $secret
     * @param string $contentType
     * @return string
     * @throws Exception
     */
    static function sign($paramValues,$ignoreParamNames,$headerMap,$extInfoMap,$secret,$contentType="application/x-www-form-urlencoded; charset=UTF-8"){
        if($headerMap==null||!isset($secret)){
            throw new Exception("请求头或客户端secret为空");
        }
        //初始化字符串
        $contactStr=$secret;
        //加入请求参数
        $contactStr =$contactStr.(self::contactValues($paramValues,$ignoreParamNames,$contentType));
        //加入请坟头参数
        $contactStr =$contactStr.(self::contactValues($headerMap,null));
        //加入ext信息
        $contactStr =$contactStr.(self::contactValues($extInfoMap,null));
        //必须将sha1生成数据转为大写,rop服务端支持大写
        //echo(self::contactValues($paramValues,$ignoreParamNames));
        //echo($contactStr.'</br>'); //
        return strtoupper(sha1($contactStr));
    }

    /**
     * 数组内部数据联接
     * @param $values
     * @param $ignoreParamNames
     * @param string $contentType
     * @return string
     */
    static function contactValues($values,$ignoreParamNames,$contentType="application/x-www-form-urlencoded; charset=UTF-8"){
        $contactStr='';
        $isMultipart=false;
        if(stristr(strtolower($contentType),"multipart/form-data")){
            $isMultipart=true;
        }
        if($values!=null){
            //去重
            $reqArray=array();
            foreach($values as $key=>$val) {
                //in_array判断值是否存在,array_key_exists判断键是否存在
                if($ignoreParamNames!=null&&in_array($key, $ignoreParamNames)){
                    continue;
                }
                if((!is_null($val))){
                    $reqArray[$key]=$val;
                }
            }
            //字符串关联数组排序
            ksort($reqArray);
            //拼接
            foreach($reqArray as $key=>$val) {
                if($val!=null){
                    if(is_a($val,UploadFile::class)){
                        $contactStr=$contactStr.$key.$val->uploadStr;
                    }else{
                        $contactStr=$contactStr.$key.$val;
                    }
                }else if(!$isMultipart){
                    $contactStr=$contactStr.$key;
                }
            }
        }
        return $contactStr;
    }

    /**
     * 得到一个简单的请求头
     * @param string $v
     * @param $method
     * @param $appkey
     * @return array
     * @throws Exception
     */
    static function getHeads($v='1.0',$method,$appkey){
        if(!isset($v)||''==$method){
            throw new Exception("请求method不能为空");
        }
        $headerMap=array(
            "ts"=>time()."000",
            "locale"=>"zh_CN",
            "v"=>$v,
            "method"=>$method,
            "appKey"=>$appkey);
        return $headerMap;
    }

    /**
     * 得到一个简单的扩展信息
     * @return array
     */
    static function getExtInfoMap(){
        $extInfoMap=array(
            "cv"=>(self::$cv)
        );
        return $extInfoMap;
    }

    /**
     * 扩展信息转为string
     * @param $extInfoMap
     * @return string
     */
    static function encryptExtInfo($extInfoMap){
        if($extInfoMap == null ){
            return "";
        }
        $contactStr="";
        foreach($extInfoMap as $key=>$val){
            $contactStr=$contactStr.$key."\001".$val."\002";
        }
        $contactStr=substr($contactStr,0,strlen($contactStr)-1);
        return urlencode($contactStr);
    }

    /**
     * 取得请求头str，注意\r\n \001这种有转意的需用""符，''符号php部份函数不支持
     * @param $headerMap
     * @param $extInfoMap
     * @param $sign
     * @param string $contentType
     * @return string
     */
    static function createHeaderStr($headerMap,$extInfoMap,$sign,$contentType="application/x-www-form-urlencoded; charset=UTF-8"){
        $contactStr="";
        foreach($headerMap as $key=>$val){
            $contactStr=$contactStr.$key.':'.$val."\r\n";
        }
        //ext
        $contactStr=$contactStr.'ext:'.self::encryptExtInfo($extInfoMap)."\r\n";
        //sign
        $contactStr=$contactStr.'sign:'.$sign."\r\n";
        //其它信息
        $contactStr=$contactStr."user-agent:php\r\n";
        $contactStr=$contactStr."Content-type: ".$contentType."\r\n";

        $contactStr=$contactStr."accept:text/html, image/gif, image/jpeg, *; q=.2, */*; q=.2\r\n";
        $contactStr=$contactStr."connection:keep-alive\r\n";
        return $contactStr;
    }

    /**
     * 去重请求参数
     * @param $paramValues
     * @return array
     */
    static function createParamsStr($paramValues){
        $reqArray=array();
        if($paramValues!=null){
            //去重
            foreach($paramValues as $key=>$val) {
                $reqArray[$key]=$val;
            }
        }
        return $reqArray;
    }

    /**
     *  请求rop服务端
     * @param $paramValues
     * @param $ignoreParamNames
     * @param $headerMap
     * @param $secret
     * @param string $service_url
     * @param string $contentType
     * @return bool|string
     */
    private static function doPost($paramValues,$ignoreParamNames,$headerMap,$secret,$service_url,$contentType="application/x-www-form-urlencoded; charset=UTF-8"){
        $extInfoMap=self::getExtInfoMap();
        $sign=self::sign($paramValues, $ignoreParamNames, $headerMap, $extInfoMap,$secret,$contentType);
        $headerStr=self::createHeaderStr($headerMap, $extInfoMap, $sign,$contentType);
        $paramStr=self::createParamsStr($paramValues);
        if(stristr(strtolower($contentType),"multipart/form-data")){
            $content=self::buildFormData($paramStr,UploadFile::$boundary);
            $headerStr=$headerStr."Content-Length:".strlen($content)."\r\n";
        }else{
            $content=http_build_query($paramStr);
        }
        //生成请求参数
        //var_dump($headerStr); //
        //var_dump($content); //
        $header_data=array(
            "http" => array (
                'method' => 'POST',
                'header'=> $headerStr,
                'content' => $content
            )
        );
        $request = stream_context_create($header_data);
        $response = file_get_contents($service_url, false, $request);
        //$response = fopen(ClientInfo::$services_url, 'r', false, $request);
        return $response;
    }

    /**
     * 请求rop服务端
     * @param $requestObj RichServiceRequest
     * @param $appkey
     * @param $secret
     * @param $service_url
     * @return bool|string
     * @throws Exception
     */
    static function doPostByObj($requestObj,$appkey,$secret,$service_url){
        if(is_object($requestObj)&&is_subclass_of($requestObj,RichServiceRequest::class)){
            if($requestObj->validate()){
                $headerMap=self::getHeads($requestObj->getVersion(), $requestObj->getMethod(),$appkey);
                $requestArr=$requestObj->getObject2Array();
                return self::doPost($requestArr, $requestObj->getIgnoreSign(), $headerMap,$secret,$service_url,$requestObj->contentType);
            }else{
                throw new Exception("校验失败了");
            }
        }else{
            throw new Exception("requestObj参数不是一个org\ebq\api\model\RichServiceRequest对象");
        }
    }

    /**
     * 创建用于上传文件的请求body体（html文件上传的正确格式）
     * rop框架其实没有使用到正确的构建文件的格式体
     * rop上传提交文件，其实是对文件内容做了编码改为键值在传递
     * @param $paramArr
     * @param string $boundary form文件上传分隔线
     * @return string
     */
    static function buildFormData($paramArr,$boundary="00content0boundary00"){
        $contactStr='';
        if($paramArr!=null){
            foreach($paramArr as $key=>$val) {
                if($val!=null){
                    $fileFlag=is_a($val,UploadFile::class);
                    //是文件体
                    $contactStr=$contactStr."--".$boundary."\r\n";
                    //if($fileFlag){
                    //	$contactStr=$contactStr."Content-Disposition: form-data; name=\"".$key."\"; filename=\"".$val->fileName."\"\r\n";
                    //	$contactStr=$contactStr."Content-Type: ".$val->fileType."\r\n";
                    //}else{
                    $contactStr=$contactStr."Content-Disposition: form-data; name=\"".$key."\"\r\n";
                    //}
                    $contactStr=$contactStr."\r\n";
                    //参数处理
                    if($fileFlag){
                        //是文件体
                        $contactStr=$contactStr.$val->uploadStr."\r\n";
                    }else{
                        $contactStr=$contactStr.$val."\r\n";
                    }
                }
            }
            if($contactStr!=""){
                $contactStr=$contactStr."--".$boundary."--\r\n";
            }
        }
        return $contactStr;
    }


    /**
     * 通过路径返回文件名（只操作了字符串）
     * @param  $filePath
     * @return string 文件名(包括后辍)
     */
    static function getFileName($filePath){
        if(strrpos($filePath,"/")>-1){
            return substr($filePath, strrpos($filePath,"/")+1);
        }else if(strrpos($filePath,"\\")>-1){
            return substr($filePath, strrpos($filePath,"\\")+1);
        }else{
            return $filePath;
        }
    }

    /**
     * http文件下载
     * @param $url
     * @param string $file
     * @param int $timeout
     * @return bool|mixed|string
     * @throws Exception
     */
    static function httpcopy($url, $file="", $timeout=60) {
        $file = empty($file) ? pathinfo($url,PATHINFO_BASENAME) : $file;
        $dir = pathinfo($file,PATHINFO_DIRNAME);
        !is_dir($dir) && @mkdir($dir,0755,true);
        $url = str_replace(" ","%20",$url);

        if(function_exists('curl_init')) {

            $headers['User-Agent'] = 'windows';
            $headerArr = array();
            foreach( $headers as $n => $v ) {
                $headerArr[] = $n .':' . $v;
            }
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArr);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            $temp = curl_exec($ch);
            if(@file_put_contents($file, $temp) && !curl_error($ch)) {
                return $file;
            } else {
                throw new Exception(curl_error($ch));
            }
        } else {
            $params = array(
                "http"=>array(
                    "method"=>"GET",
                    "header"=>"User-Agent:windows",
                    "timeout"=>$timeout)
            );
            $context = stream_context_create($params);
            if(@copy($url, $file, $context)) {
                //$http_response_header
                return $file;
            } else {
                return false;
            }
        }
    }

    /**
     * 使json_encode支持5.4.0以下
     * @param $value object 传入为对象
     * @return mixed|string
     */
    static function json_encode($value){
        if (version_compare(PHP_VERSION,'5.4.0','<')){
            $str = json_encode($value);
            $str = preg_replace_callback("#\\\u([0-9a-f]{4})#i",
                function($matchs){
                    return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
                },
                $str
            );
            return $str;
        }else{
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
    }

}