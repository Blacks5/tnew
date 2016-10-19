<?php
/**
 * Created by PhpStorm.
 * Date: 16/8/20
 * Time: 15:55
 * @author 涂鸿 <hayto@foxmail.com>
 */

namespace backend\controllers;

use app\models\Customer;
use common\models\CustomerSearch;
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
    /**
     * 用户列表
     * @author 涂鸿 <hayto@foxmail.com>
     */
    public function actionIndex()
    {
        $this->getView()->title = '客户列表';
        $model = new CustomerSearch();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['c_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        array_walk($data, function(&$v){
            $v['c_status'] = Customer::getAllStatus()[$v['c_status']];
            $v['c_created_at'] = date('Y-m-d H:i:s', $v['c_created_at']);
        });
        return $this->render('index', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
        ]);
        return $this->render('index');
    }

    public function actionView($c_id)
    {
        if($data = Customer::getOneDetail($c_id)){
            $this->getView()->title = $data['c_customer_name'];
            $data['c_status'] = Customer::getAllStatus()[$data['c_status']];
            return $this->render('view', ['model'=>$data]);
        }
    }
}