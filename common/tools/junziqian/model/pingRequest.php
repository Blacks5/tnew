<?php
namespace com_junziqian_api_model;
require_once dirname(__FILE__).'/../model/richServiceRequest.php';
use com_junziqian_api_model\RichServiceRequest as RichServiceRequest;
/**
 * ping 请求
 * @edit yfx 2016-07-02
 */
class PingRequest extends RichServiceRequest{
	
	static $v="1.0";
	static $method="ping";

}
?>
