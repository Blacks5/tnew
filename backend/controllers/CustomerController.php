<?php
/**
 * Created by PhpStorm.
 * Date: 16/8/20
 * Time: 15:55
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace backend\controllers;

use common\components\Helper;
use common\models\Customer;
use common\models\CustomerSearch;
use common\models\OrdersSearch;
use common\models\User;
use yii;
use backend\core\CoreBackendController;

/**
 * 用户管理
 * Class CustomerController
 * @package app\controllers
 * @author 涂鸿 <hayto@foxmail.com>
 */
class CustomerController extends CoreBackendController
{
    public function actionIndexp()
    {
        echo '客户管理';
    }
    /**
     * 用户列表
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionIndex()
    {
        $this->getView()->title = '客户列表';
        $model = new CustomerSearch();
        $params = Yii::$app->getRequest()->getQueryParams();
        $query = $model->search($params);

        // 如果查看某个销售的客户，就执行
        $user = null;
        if(!empty($params["CustomerSearch"]['u_id'])){
            $user['realname'] = (new yii\db\Query())->select(['realname'])->from(User::tableName())->where(['id'=>$params["CustomerSearch"]['u_id']])->scalar();
            $user['u_id'] = $params["CustomerSearch"]['u_id'];
        }

        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['c_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        array_walk($data, function(&$v){
            $v['c_status'] = Customer::getAllStatus()[$v['c_status']];
            $v['c_created_at'] = date('Y-m-d H:i:s', $v['c_created_at']);
            $v['c_updated_at'] = date('Y-m-d H:i:s', $v['c_updated_at']);
        });
        $provinces = Helper::getAllProvince();
        return $this->render('index', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages'=>$pages,
            'provinces'=>$provinces,
            'user'=>$user
        ]);
    }

    public function actionView($c_id)
    {
        if($data = Customer::getOneDetail($c_id)){
            $this->getView()->title = $data['c_customer_name'];
//            $data['c_status'] = Customer::getAllStatus()[$data['c_status']];
            return $this->render('view', ['model'=>$data]);
        }
    }

    /**
     * 获取某个客户的所有订单
     * @return string
     * @author too <hayto@foxmail.com>
     */
    public function actionGetAllOrdersByCustomer()
    {
        $this->getView()->title = '所有订单';
        $model = new OrdersSearch();
        $params = Yii::$app->getRequest()->getQueryParams();
        $query = $model->search($params);

        // 如果查看某个客户的所有订单，就执行
        $customer = null;
        if(!empty($params["OrdersSearch"]['customer_id'])){
            $customer['c_customer_name'] = (new yii\db\Query())->select(['c_customer_name'])->from(Customer::tableName())->where(['c_id'=>$params["OrdersSearch"]['customer_id']])->scalar();
            $customer['customer_id'] = $params["OrdersSearch"]['customer_id'];
        }

        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['orders.o_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('allordersbycustomer', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages' => $pages,
            'customer'=>$customer
        ]);
    }
}