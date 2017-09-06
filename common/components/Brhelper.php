<?php
/**
 * 百融Api
 */
namespace common\components;
use Yii;

class Brhelper {
	// 登录生产环境API
	const LOGIN_API_PRODUCE = 'https://api.100credit.cn/bankServer2/user/login.action';
	// 登录开发测试API
	const LOGIN_API_DEVELOP = 'https://sandbox-api.100credit.cn/bankServer2/user/login.action';
	// 海纳生产环境API
	const HAINA_API_PRODUCE = 'https://api.100credit.cn/HainaApi/data/getData.action';
	// 海纳开发测试API
	const HAINA_API_DEVELOP = 'https://sandbox-api.100credit.cn/HainaApi/data/getData.action';
	// username
	public $username;
	// password
	public $password;
	// api code
	public $apicode;
	// tokenid cache time
	private $expire = 3599;
	// after request fail retry times
	private $retry = 1;
	// retried times
	private $retried = 0;

	/**
	 * 登录操作
	 * @param  boolean $force 是否强制登录
	 * @return mixed $tokenid 返回tokenid
	 */
	public function getTokenId($force = false) {
		// 获取链接
		// $url = YII_ENV === 'prod' ? self::LOGIN_API_PRODUCE : self::LOGIN_API_DEVELOP;
		$url = self::LOGIN_API_PRODUCE;

		// 缓存KEY
		$key = md5(YII_ENV . ':' . $this->username . ':' . $this->apicode);

		// 获取tokenid
		$tokenid = Yii::$app->cache->get($key);
		if ($tokenid && !$force) {
			return base64_decode($tokenid);
		}

		// 请求数据
		$response = $this->post($url, [
			'userName' => $this->username,
			'password' => $this->password,
			'apiCode' => $this->apicode,
		]);

		$response = json_decode($response, true);

		if (isset($response['code']) && isset($response['tokenid']) && $response['code'] == '000') {
			$tokenid = $response['tokenid'];
			Yii::$app->cache->set($key, base64_encode($tokenid), $this->expire);
			return $tokenid;
		}

		return null;
	}

	/**
	 * 检测用户信息是否合法
	 * @param  array  $data 待检测数据
	 * @return [type]       [description]
	 */
	public function check($data = []) {
		// 获取tokenid
		if (!$tokenid = $this->getTokenId()) {
			return false;
		}

		// url
		// $url = YII_ENV === 'prod' ? self::HAINA_API_PRODUCE : self::HAINA_API_DEVELOP;
		$url = self::HAINA_API_PRODUCE;

		// 数据
		$data = json_encode([
			'meal' => 'BankFourPro',
			'id' => $data['idcard'],
			'cell' => $data['mobile'],
			'bank_id' => $data['creditcard'],
			'name' => $data['realname']
		]);

		$postData = array(
			'apiName' => 'HainaApi',
			'tokenid' => $tokenid,
			'apiCode' => $this->apicode,
			'jsonData' => $data,
			'checkCode' => md5($data . md5($this->apicode . $tokenid)),
		);

		// 发起请求
		$response = $this->post($url, $postData);

		$response = json_decode($response, true);

		// 返回结果信息
		if (isset($response['code']) && $response['code'] == '600000') {
			if(isset($response['product']['result']) && $response['product']['result'] == 0){
				return true;
			}
		}

		return false;
	}

	/**
	 * post 请求
	 * @param  string $url  	请求url
	 * @param  mixed $data 		请求参数
	 * @param  mixed $timeout 	超时时间
	 * @return
	 */
	public function post($url, $data, $timeout = 30) {
		$ssl = substr($url, 0, 8) == "https://" ? TRUE : FALSE;
		$ch = curl_init();
		$opt = array(
			CURLOPT_URL => $url,
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_POSTFIELDS => http_build_query($data),
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_TIMEOUT => $timeout,
		);
		if ($ssl) {
			$opt[CURLOPT_SSL_VERIFYHOST] = FALSE;
			$opt[CURLOPT_SSL_VERIFYPEER] = FALSE;
		}
		curl_setopt_array($ch, $opt);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}