<?php
namespace jzq\test\http;
/**
 * 用于测试一下文件下载功能
 * Created by PhpStorm.
 * User: yfx
 * Date: 2017-04-17 0017
 * Time: 15:35
 */
//TODO 要自行增加head头信息|暂只加了一个下载头
$filename = 'test3.pdf';
header("Content-type: application/pdf");
header("Accept-Ranges: bytes");
header("Content-Disposition: attachment; filename=".$filename);
header("Cache-Control: must-revalidate, post-check=0, pre-check=0" );
header("Pragma: no-cache" );
header("Expires: 0" );

$params = array(
    "http"=>array(
        "method"=>"GET",
        "header"=>"User-Agent:windows",
        "timeout"=>60)
);
$context = stream_context_create($params);
$file_context = readfile("文件地址",null,$context);