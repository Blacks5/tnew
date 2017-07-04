<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/14
 * Time: 14:26
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace common\core;

use yii;
use yii\web\Controller;

class CoreCommonController extends Controller
{
    /** @param string $url 跳转的URL地址
     * @param integer $wait 跳转等待时间
     * @return mixed
     */
    public function success($msg = '', $url = null, $wait = 500)
    {
        $postaction = Yii::$app->getUrlManager()->createUrl(['login/login']);
        $result = [
            'msg' => $msg,
            'url' => is_null($url) && isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : $url,
            'wait' => $wait,
            'loginurl' => $postaction
        ];
        return $this->render('@backend/views/jump/success.php', ['result' => $result]);
    }

    /**
     * 操作成功跳转的快捷方法
     * @param mixed $msg 提示信息
     * @param string $url 跳转的URL地址
     * @param integer $wait 跳转等待时间
     * @return mixed
     */
    public function error($msg = '', $url = null, $wait = 5000)
    {
        $postaction = Yii::$app->getUrlManager()->createUrl(['login/login']);
        $result = [
            'msg' => $msg,
            'url' => is_null($url) && isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : $url,
            'wait' => $wait,
            'loginurl' => $postaction,
        ];
        return $this->render('@backend/views/jump/error.php', ['result' => $result]);
    }
}