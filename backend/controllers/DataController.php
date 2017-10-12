<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 2017/8/30
 * Time: 14:39
 */

namespace backend\controllers;


use backend\models\DataSearch;
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
        return $this->render('logs',[
                'data' => $list['data'],
                'sear' => $list['sear'],
                'type' => $list['type'],
                'pages'=> $list['pages'],
            ]);
    }

    /**
     * 批量修改易极付9月12号之前的签约
     * @author OneStep
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
            ->where(['<', 'yijifu_sign.created_at', '1505145600'])
            ->andWhere(['status'=>1])
            ->andWhere(['o_status'=> 10])
            ->asArray()->all(); //获取9月12日之前签约成功的所有签约
        echo '待重新签约记录数：' . count($oldSign) . PHP_EOL;
        $change = new ReturnMoney();
        $isChange = $change->changeYijifuSign($oldSign);
        if($isChange){
            echo '修改成功';
        }
    }
    
}