<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/10/20
 * Time: 17:36
 */

namespace console\controllers;


use backend\components\CustomBackendException;
use common\models\YijifuSign;
use yii\console\Controller;
use yii\db\Exception;
use yii\log\FileTarget;
use Yii;

class SignController extends Controller
{
    public function actionChangeSign()
    {
        $sign = YijifuSign::find()->where(['!=', 'logs', 'NULL'])->asArray()->all();
        $trans = Yii::$app->getDb()->beginTransaction();
        $count = 0;
        try{
            foreach ($sign as $k => $v){
                $logs = json_decode($v['logs']);
                if(!empty($logs->created_at) && $logs->merchOrderNo != $v['merchOrderNo']){
                    $change = YijifuSign::updateAll([
                        'created_at'=>$logs->created_at,
                        'updated_at'=>strtotime(date('Y-m-d'))
                    ],['id'=>$v['id']]);
                    if($change){
                        $count ++;
                    }
                }
            }
            $trans->commit();
            echo "成功修改".$count.'条数据的日期';
            $message = ['成功修改了'.$count.'条数据的日期',1,'success',strtotime(date('Y-m-d'))];

        }catch (Exception $e){
            $trans->rollBack();
            $message = ['message'=>'修改日期失败了'. $e->getMessage() ,'error','error',strtotime(date('Y-m-d'))];
        }catch (CustomBackendException $e){
            $trans->rollBack();
            $message = ['message'=>'修改日期失败了'. $e->getMessage(),'error','error',strtotime(date('Y-m-d'))];
        }
        $logsFile = new FileTarget();
        $logsFile->logFile = Yii::$app->getRuntimePath() .'/logs/ChangeSign.log';
        $logsFile->messages[] = $message;
        $logsFile->export();
    }
}