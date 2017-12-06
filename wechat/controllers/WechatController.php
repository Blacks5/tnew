<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/8/9
 * Time: 23:01
 * @author too <hayto@foxmail.com>
 */

namespace wechat\controllers;

use common\models\User;
use EasyWeChat\Foundation\Application;
use Yii;
use yii\web\Controller;

/**
 * 需要授权的页面调用如下方法即可
 * Wechat::Login(['manage/index']);
 * $user = \Yii::$app->getSession()->get('wechat_user');
 */

/**
 * s-285072.gotocdn.com/manage 这个域名访问
 *
 * Class WechatController
 * @package wechat\controllers
 * @author too <hayto@foxmail.com>
 */
class WechatController extends Controller {
	public function actionIndex() {
		$config = \Yii::$app->params['wechat'];
		$app = new Application($config);

		$app->server->setMessageHandler(function ($message) {
			return "您好！欢迎关注";
		});

		return $app->server->serve()->send();
	}

	// 微信授权回调
	public function actionOauthCallback() {
		$session = Yii::$app->session;

		// 获取配置参数
		$config = Yii::$app->params['wechat'];

		// 获取微信用户相关信息
		try {
			if ($wechat_user = (new Application($config))->oauth->user()) {
				// 检测是否绑定用户信息
				$sys_user = User::findByWechatOpenid($wechat_user->id);

				if ($sys_user) {
					if ($sys_user->status == 10) {
						// 获取所在位置
						$regions = [intval($sys_user->province), intval($sys_user->city), intval($sys_user->county)];

						// 加入地区数据
						$sys_user->areas = array_values(User::getAreas($regions));

						// 保存微信登录信息
						$session->set('wechat_user', $wechat_user);

						// 保存系统相关信息
						$session->set('sys_user', $sys_user);

						$targetUrl = empty($session->get('target_url')) ? '/' : $session->get('target_url');

						return $this->redirect($targetUrl);
					} else {
						return $this->renderPartial('fail');
					}
				} else {
					// 保存微信登录信息
					$session->set('wechat_user', $wechat_user);

					return $this->redirect(['login/bind']);
				}
			}
		} catch (\Overtrue\Socialite\AuthorizeFailedException $e) {
			return $this->redirect(['site/index']);
		}
	}
}