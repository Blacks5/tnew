<?php
	require_once dirname(__FILE__) . '/../tool/ShaUtils.php';
	require_once dirname(__FILE__) . '/../tool/RopUtils.php';
	require_once dirname(__FILE__).'/../model/applySignFileRequest.php';
	require_once dirname(__FILE__) . '/../model/Signatory.php';
	require_once dirname(__FILE__).'/../model/enum.php';
	require_once dirname(__FILE__) . '/../model/UploadFile.php';
    require_once dirname(__FILE__) . '/../model/SequenceInfo.php';
	
	use com_junziqian_api_tool\RopUtils as RopUtils;
	use com_junziqian_api_model\ApplySignFileRequest as ApplySignFileRequest;
	use com_junziqian_api_model\Signatory as Signatory;
	use com_junziqian_api_model\IdentityType as IdentityType;
	use com_junziqian_api_model\AuthLevel as AuthLevel;
	use com_junziqian_api_model\UploadFile as UploadFile;
	use com_junziqian_api_model\SignLevel as SignLevel;
    use com_junziqian_api_model\SequenceInfo as SequenceInfo;
    use com_junziqian_api_model\DealType as DealType;
	
	//组建请求参数
	$requestObj=new ApplySignFileRequest();
	
	//组建请求参数
	$requestObj->file=new UploadFile("E:\\tmp\\abcd.pdf");
	$requestObj->contractName="合同0001";
  //$requestObj->authLevelRange="1";//验证范围，为string，暂只支持正整数，且小于验证方式数量。
  //$requestObj->authLevel=[
  //      AuthLevel::$FACE,AuthLevel::$BANKTHREE,AuthLevel::$USEKEY,AuthLevel::$BANKCARD
  //    ];
  //$requestObj->faceThreshold=70;//人脸识别阈值

	//签合同方
	$signatories=array();
//测试时请改为自己的个人信息进行测试（姓名、身份证号、手机号不能部分或全部隐藏）
	$signatory=new Signatory();
	$signatory->setSignatoryIdentityType(IdentityType::$IDCARD);
	$signatory->fullName="张三";
	$signatory->identityCard="440204198204297235";
	$signatory->mobile='15123649601';
	//$signatory->authLevel=[authLevel::$BANKCARD];  //银行三要素
	//$signatory->forceAuthentication=0; //0 只需第一次认证过，后面不用认证;1 每次签约都要认证
	$signatory->signLevel=SignLevel::$GENERAL;//GENERAL,标准图形章;SEAL，手写或公章
	$signatory->noNeedVerify=0; //0：短信验证(默认) 1：不验证短信
	//$signatory->noNeedEvidence=0;//隐藏添加现场，0不隐藏 ，1 隐藏
	//$signatory->forceEvidence=0;//强制添加签约现场图片，0不强制，1强制
	$signatory->orderNum=1;//签字顺序
	//$signatory->insureYear=3;//购买保险年限
	//$signatory->readTime=20; //强制阅读时间,单位：秒
  $signatory->serverCaAuto=1;//0 手动签，1 自动签
	//$signatory->forceAuthentication=1; //0:只需第一次认证过，后面不用认证   1:每次签约都要认证
	
	//[{"page":0,","chaptes":[{"offsetX":0.12,"offsetY":0.23}]},{"page":1,"chaptes":[{"offsetX":0.45,"offsetY":0.67}]}]
	//固定签章位置，以文件页左上角(0.0,0.0)为基准，按百分比进行设置）page为页码，从0开始计数，offsetX,offsetY(x,y为比例，值范围设置为0-1之间)  每页为一个数组，以此类推。

	$signatory->setChapteJson(array(
		array(
			'page'=>0,
			'chaptes'=>array(
				array("offsetX"=>0.12,"offsetY"=>0.23),
				array("offsetX"=>0.45,"offsetY"=>0.67)
			)
		)
	));

	array_push($signatories, $signatory);
		
	$signatory=new Signatory();
	$signatory->setSignatoryIdentityType(IdentityType::$BIZLIC);//证件类型
	$signatory->fullName="测试公司";//企业名称
	$signatory->identityCard="461313456461316";//营业执照号或统一社会信用代码
	$signatory->email='1244268365@qq.com';//企业注册账户邮箱
	$signatory->orderNum=2;
	$signatory->signLevel=SignLevel::$SEAL; //GENERAL,标准图形章;SEAL，手写或公章
	$signatory->serverCaAuto=1;   //1 自动签;0 手动签
	$signatory->setChapteJson(array(
  	 	   array(
 			'page'=>1,
 			'chaptes'=>array(
 				array("offsetX"=>0.31,"offsetY"=>0.72),
 				array("offsetX"=>0.72,"offsetY"=>0.72)
 			)
          ),
    	    array(
 			'page'=>-2,
 			'chaptes'=>array(
 				array("offsetX"=>0.8,"offsetY"=>0.82)

 			)
          ),
 	        array(
 			'page'=>2,
 			'chaptes'=>array(
 				array("offsetX"=>0.45,"offsetY"=>0.61)

 			)
          ),
         ));
	
	array_push($signatories, $signatory);
	
	$requestObj->signatories=$signatories;
	
	//$requestObj->signLevel=SignLevel::$GENERAL;
	
	/**
	 * 强制认证(默认0)
	 * 0：不强制认证（需要认证时，认证一次就可），
	 * 1：强制认证（需要认证时，每次都要进行认证）.
	 */
	//$requestObj->forceAuthentication=1;//0：不强制认证（需要认证时，认证一次就可），1：强制认证（需要认证时，每次都要进行认证）
	//$requestObj->preRecored="前执记录，会计录到日志中！";
	$requestObj->orderFlag=1;//1表示按顺序签（按signatories.orderNum顺序），默认不按顺序
	//$requestObj->needCa=1;//是否需要CA，空0为不需要，1需要
    //$requestObj->sequenceInfo=new SequenceInfo("XX001",2,2);//连续签，第一个字段，连续签合同唯一id，自行设定；第二个字段，第几个签；第三个字段，总的合同份数
	$requestObj->serverCa=1; //使用云证书，0 不使用，1 使用
  $requestObj->dealType=DealType::$AUTH_SIGN; //签约类型,0或DEFAULT, "默认用户手动签字"；1或AUTH_SIGN, "自动签字并保全"；2或ONLY_PRES,"只做保全，用户不做签字"；5或AUTH_SIGN_PART, "部份云证书自动签字"
	//请求
	$response=RopUtils::doPostByObj($requestObj);
	//以下为返回的一些处理
	$responseJson=json_decode($response);
	print_r("response:".$response."</br>");
	print_r("format:</br>");
	var_dump($responseJson); 
	if($responseJson->success){
		echo $requestObj->getMethod()."->处理成功";
	}else{
		echo $requestObj->getMethod()."->处理失败";
	}

?>