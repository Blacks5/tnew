<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/8/30
 * Time: 14:39
 */

namespace backend\controllers;


use backend\components\CustomBackendException;
use backend\models\DataSearch;
use backend\models\Log;
use backend\models\YejiSearch;
use common\components\Helper;
use backend\core\CoreBackendController;
use common\models\Customer;
use common\models\Goods;
use common\models\OrderImages;
use common\models\Orders;
use common\models\Product;
use common\models\Repayment;
use common\models\YijifuSign;
use common\services\Order;
use common\tools\yijifu\ReturnMoney;
use yii;

class DataController extends CoreBackendController
{
    public $enableCsrfValidation = false;

    public function actionIndex()
    {

    }

    /**
     * 获取平台总数据
     * @return string
     * @author OneStep
     */
    public function actionGather()
    {
        $data = new DataSearch();
        $list = $data->getLoanTotal(\yii::$app->request->getQueryParams());
        $province = Helper::getAllProvince();

        return $this->render('gather',[
            'data'=>$list['data'],
            'users'=>$list['user'],
            'area' => $province,
            'sear' => $list['sear'],
        ]);
    }

    public function actionCashGather()
    {
        return $this->render('cash-gather');
    }

    /**
     * 获取审核数据
     * @return string
     * @author OneStep
     */
    public function actionVerify()
    {
        $data = new DataSearch();
        $list = $data->verify(\yii::$app->request->getQueryParams());

        return $this->render('verify', [
          'all' => $list['all'],
          'sear'=> $list['sear'],
        ]);
    }

    /**
     * 系统操作日志
     * @return string
     * @author OneStep
     */
    public function actionLogs()
    {
        $data = new DataSearch();
        $list = $data->getLogs(\yii::$app->request->getQueryParams());
        return $this->render('logs', 
            [
                'data' => $list['data'],
                'sear' => $list['sear'],
                'type' => $list['type'],
                'pages'=> $list['pages'],
            ]
        );
    }

    /**
     * 获取客户资料
     * @return $this->render
     */
    public function actionCustomerInfo()
    {
        return $this->render('customer');
    }

    /**
     * 生成并下载客户信息(林丹妮使用)
     */
    public function actionDownloadCustomerCsv()
    {
        $post = Yii::$app->request->post();
        $s_time = strtotime($post['start_time']);
        $e_time = strtotime($post['end_time']);
       $data = new DataSearch();
       $list = $data->getCustomerOders($s_time, $e_time);
    }

    /**
     * actionChangeSign 重新签约易极付
     * 用于修复旧版本中老订单签约时签约金额错误的问题（只签约了1个月）
     * 保留此函数以便需要时使用
     *
     * @return void
     * _NO_EXECUTE_b34f1bb78d4624e1df6678deb21a2ff8cdf1d241
     */
    public function actionChangeSign()
    {
        $oldSign = YijifuSign::find()
            ->select([
                'yijifu_sign.*',
                'customer.c_customer_name',
                'customer.c_customer_id_card',
                'customer.c_banknum',
                'customer.c_customer_cellphone',
                'order_images.oi_after_contract',
                'goods.g_goods_name',
                'goods.g_goods_models',
                'orders.o_total_price',
                'orders.o_total_deposit',
                'orders.o_service_fee',
                'orders.o_inquiry_fee',
                'orders.o_id'
            ])
            ->leftJoin(Orders::tableName(), 'orders.o_serial_id=yijifu_sign.o_serial_id')
            ->leftJoin(Customer::tableName(),'o_customer_id=c_id')
            ->leftJoin(OrderImages::tableName(), 'oi_id=orders.o_images_id')
            ->leftJoin(Goods::tableName(), 'g_order_id=o_id')
            ->where(['yijifu_sign.o_serial_id' => '17111100000005214402'])
            ->andWhere(['status'=>1])
            ->andWhere(['o_status'=> 10])
            ->asArray()->all();
        echo '待重新签约记录数：' . count($oldSign) . PHP_EOL;
        // var_dump($oldSign);die();
        $change = new ReturnMoney();
        $isChange = $change->changeYijifuSign($oldSign);
    }
}