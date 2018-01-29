<?php

/**
 * 用户服务
 * @Author: MuMu
 * @Date:   2017-11-22 10:48:34
 * @Last Modified by:   MuMu
 * @Last Modified time: 2018-01-29 14:59:29
 */

namespace common\services;

use backend\components\CustomBackendException;
use common\components\CustomCommonException;
use Yii;

class User extends Service {
	// 微服务地址
	protected $microServiceUrl = 'http://users.api.tnew.cn/v1/';
	// 微服务地址【开发】
	protected $devMicroServiceUrl = 'http://users.devapi.tnew.cn/v1/';
	// 获取用户TOKEN路由
	private $queryUserTokenRouter = '/users/{login_name}/tokens';
	// 创建用户
	private $createUserRouter = '/users';

	/**
	 * 获取用户TOKEN
	 * @param  string $username 用户名称
	 * @return string           token信息
	 */
	public function queryUserToken($username) {
		// 获取请求地址
		$url = $this->buildUrl($this->queryUserTokenRouter, [
			'login_name' => $username,
		]);

		$res = $this->httpPost($url);

		if ($res['success']) {
			return $res['data']['token']['access_token'];
		} else {
			throw new CustomCommonException($res['errors'][0]['message']);
		}
	}

	/**
	 * 创建用户
	 * @param  [type] $params [description]
	 * @return [type]         [description]
	 */
	public function createUser($params) {
		try {
			$user = new \common\models\User;

			$params['status'] = \common\models\User::STATUS_ACTIVE;

			if (!$user->createUser(['User' => $params])) {
				return false;
			}
		} catch (CustomBackendException $e) {
			throw new CustomCommonException($e->getMessage());
		}

		try {
			// 获取系统用户
			$sys_user = Yii::$app->session->get('sys_user');

			// 获取请求地址
			$url = $this->buildUrl($this->createUserRouter);

			$res = $this->httpPost($url, [
				'type' => 'agent',
				'v1_id' => $user->id,
				'inviter' => $sys_user->id,
				'phone' => $params['cellphone'],
				'name' => $params['realname'],
				'login_name' => $params['username'],
				'password' => $params['password_hash'],
				'realname' => $params['realname'],
				'id_card_num' => $params['id_card_num'],
				'province' => $params['province'],
				'city' => $params['city'],
				'county' => $params['county'],
				'address' => $params['address'],
				'email' => $params['email'],
				'department_id' => $params['department_id'],
				'job_id' => $params['job_id'],
				'leader' => $params['leader'],
				'level' => $params['level'],
				'id_card_pic_one' => $params['id_card_pic_one'],
			]);

			if ($res['success']) {
				return true;
			} else {
				$user->delete();
				throw new CustomCommonException($res['errors'][0]['message']);
			}
		} catch (CustomCommonException $e) {
			$user->delete();
			throw new CustomCommonException($e->getMessage());
		}
	}

	/**
	 * 获取区域
	 * @return [type] [description]
	 */
	public function region() {
		$cache = Yii::$app->cache;

		if ($json = $cache->get('city_json_data')) {
			return $json;
		}

		$rows = (new \yii\db\Query())
			->select(['*'])
			->from('too_region')
			->where(['parent_id' => 1])
			->all();
		$data = [];
		foreach ($rows as $k => $v) {
			$data[$k]['label'] = $v['region_name'];
			$data[$k]['value'] = $v['region_id'];
			$rows_2 = (new \yii\db\Query())
				->select(['*'])
				->from('too_region')
				->where(['parent_id' => $v['region_id']])
				->all();
			if ($rows_2) {
				$children = [];
				foreach ($rows_2 as $k1 => $v1) {
					$children[$k1]['label'] = $v1['region_name'];
					$children[$k1]['value'] = $v1['region_id'];
					$rows_3 = (new \yii\db\Query())
						->select(['*'])
						->from('too_region')
						->where(['parent_id' => $v1['region_id']])
						->all();
					if ($rows_3) {
						$children2 = [];
						foreach ($rows_3 as $k2 => $v2) {
							$children2[$k2]['label'] = $v2['region_name'];
							$children2[$k2]['value'] = $v2['region_id'];
						}

						$children[$k1]['children'] = $children2;
					}
				}
				$data[$k]['children'] = $children;
			}
		}
		$json = json_encode($data);

		$cache->set('city_json_data', $json, 3600);

		return $json;
	}
}
