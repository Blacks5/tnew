<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/14
 * Time: 17:34
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\core;

use backend\services\OperationLog;
use common\core\CoreCommonController;
use yii;

class CoreBackendController extends CoreCommonController {
	public function beforeAction($action) {
		Yii::$app->getView()->title = '天牛金融--后台';

		$controllerName = $action->controller->id;

		$actionName = $action->id;

		$router = $controllerName . '/' . $actionName;

		$enable_arr = \Yii::$app->params['enable_csrf_validation'];

		if (in_array($router, $enable_arr)) {
			$this->enableCsrfValidation = false;
		}

		$log = new OperationLog();
		$log->active();

		return parent::beforeAction($action);
	}

	public function afterAction($action, $result) {
		return parent::afterAction($action, $result);
	}
}