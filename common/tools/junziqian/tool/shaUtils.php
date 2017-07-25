<?php
namespace com_junziqian_api_tool;
/**
 * 以下只针对utf-8字符串进行sha加密
 * @edit yfx 2016-07-02
 */
class ShaUtils {
	/*
	 * 加密字符串sha1
	 * $str 字符串
	 */
	static function getSha1($str) {
		return sha1 ( $str );
	}
	/*
	 * 加密字符串sha256
	 * $str 字符串
	 */
	static function getSha256($str) {
		return hash ( 'sha256', $str );
	}
	/*
	 * 加密字符串sha512
	 * $str 字符串
	 */
	static function getSha512($str) {
		return hash ( 'sha512', $str );
	}
	/*
	 * 加密文件sha1
	 * $filePath 文件路径
	 */
	static function getFileSha1($filePath) {
		return sha1_file ( $filePath );
	}
	
	/*
	 * 加密文件sha256
	 * $filePath 文件路径
	 */
	static function getFileSha256($filePath) {
		$str = file_get_contents ( $filePath );
		return self::getSha256 ( $str );
	}
	
	/*
	 * 加密文件sha512
	 * $filePath 文件路径
	 */
	static function getFileSha512($filePath) {
		$str = file_get_contents ( $filePath );
		return self::getSha512 ( $str );
	}
}
/**
 * //以上为一般sha解析使用方法，不建议给大文件使用以上方法；如需对大文件进行sha256,512操作，需使用插件，参考http://www.example-code.com/phpExt/hash_file.asp
 * echo ShaUtils::getSha512("123456");
 * $shaUtils =new ShaUtils();
 * echo $shaUtils->getFileSha1("/SPTest.java");
 * echo '<br/>';
 * echo $shaUtils->getFileSha256("/SPTest.java");
 * echo '<br/>';
 * echo $shaUtils->getFileSha512("/SPTest.java");
 */
?>
