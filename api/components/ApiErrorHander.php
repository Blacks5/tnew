<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2017/3/15
 * Time: 14:58
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace api\components;


use yii\base\ErrorException;
use yii\base\Exception;
use yii\base\UserException;
use yii\web\ErrorHandler;
use yii\web\HttpException;

/**
 * api错误接管
 * Class ApiErrorHander
 * @package api\components
 * @author 涂鸿 <hayto@foxmail.com>
 */
class ApiErrorHander extends ErrorHandler
{
    /**
     * Converts an exception into an array.
     * @param \Exception $exception the exception being converted
     * @return array the array representation of the exception.
     */
    protected function convertExceptionToArray($exception)
    {
//        p($exception);
        if (!YII_DEBUG && !$exception instanceof UserException && !$exception instanceof HttpException) {
            $exception = new HttpException(500, Yii::t('yii', 'An internal server error occurred.'));
        }

        $array = [
            'name' => ($exception instanceof Exception || $exception instanceof ErrorException) ? $exception->getName() : 'Exception',
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
        ];
        if ($exception instanceof HttpException) {
            $array['status'] = $exception->statusCode;
        }
        if (YII_DEBUG) {
            $array['type'] = get_class($exception);
            if (!$exception instanceof UserException) {
                $array['file'] = $exception->getFile();
                $array['line'] = $exception->getLine();
                $array['stack-trace'] = explode("\n", $exception->getTraceAsString());
                if ($exception instanceof \yii\db\Exception) {
                    $array['error-info'] = $exception->errorInfo;
                }
            }
        }
        if (($prev = $exception->getPrevious()) !== null) {
            $array['previous'] = $this->convertExceptionToArray($prev);
        }

        // 2017-03-15 为了适配安卓，他只接收200状态码
        $statusCode = $exception->statusCode;
        $exception->statusCode = 200; // 设置为200
        $array = [
            'status'=> $statusCode,
            'message'=>$exception->getMessage()
        ];
        return $array;
    }
}