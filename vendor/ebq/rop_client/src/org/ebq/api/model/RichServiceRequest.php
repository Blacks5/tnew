<?php
/**
 * User: huhu
 * DateTime: 2017-05-15 0015 11:36
 */
namespace org\ebq\api\model;
use org\ebq\api\model\bean\UploadFile;

/**
* 客户端请求体.抽像类
*/
abstract class RichServiceRequest{
	/**默认请求类型，使用form表单非muti方式上传，继承后不要覆盖此属性*/
    public $contentType="application/x-www-form-urlencoded; charset=UTF-8";

    static $v="1.0";
    static $method="abstract";
    /**
	 * 请求的版本号
	 * @desc 使用static可取子类属性
     * @return string
     */
	public function getVersion(){
		//使用static可取子类属性
		return static::$v;
	}

    /**
	 * 请求的方法
     * @return string
     */
    public function getMethod(){
        return static::$method;
	}

	/**
	 * top验证方法
     * @return bool
     */
	function validate(){
		return true;
	}

	//请求的返回处理类 暂不开发
	//function getResponseClass();

    /**
	 * 对象转为数组
     * @return array
     */
	function getObject2Array(){
		$_array = is_object($this) ? get_object_vars($this) : $this;
		$array=array();
		$cContentType=false;
		foreach ($_array as $key => $value) {
			if((!$cContentType)&&is_a($value,UploadFile::class)){
				$this->contentType="multipart/form-data; boundary=".UploadFile::$boundary;
				$value->uploadStr=base64_encode($value->fileName)."@".base64_encode($value->content);
				//url安全转换
				$value->uploadStr=str_replace(array('+','/','='),array('-','_',''),$value->uploadStr);
				//清空文件内容
				$value->content=null;
			}
			if($key!="contentType"){
				$array[$key] = $value;
			}
		}
		return $array;
	}
	
	/**处理无效params转数字null或0为'0'*/
    /**
     * @param null $str
     * @return string
     */
	static function trim($str=null){
		if(is_null($str)){
			if(is_numeric($str)){
				return '0';
			}
			return '';
		}else{
			return trim($str.'');
		}
	}

    /**
	 * 不签名的字段，返回string数组
     * @return array
     */
	function getIgnoreSign(){
		return null;
	}
}
