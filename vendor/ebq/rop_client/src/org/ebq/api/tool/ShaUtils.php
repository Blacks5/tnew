<?php
namespace org\ebq\api\tool;
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