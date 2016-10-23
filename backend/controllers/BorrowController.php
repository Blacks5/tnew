<?php
/**
 * Created by PhpStorm.
 * User: too
 * Date: 2016/10/20
 * Time: 14:31
 * @author 涂鸿 <hayto@foxmail.com>
 */
namespace backend\controllers;

use common\models\Orders;
use yii;
use backend\core\CoreBackendController;
use common\models\OrdersSearch;

class BorrowController extends CoreBackendController
{
    public function actionIndex()
    {

    }

    // 列表 待审核
    public function actionListWaitVerify()
    {

    }

    // 列表 审核被拒
    public function actionListVeriftRefuse()
    {

    }

    // 列表 审核通过
    public function actionListVeriftPass()
    {
        $this->getView()->title = '借款通过列表';
        $model = new OrdersSearch();
        $query = $model->search(Yii::$app->getRequest()->getQueryParams());
        $query = $query->andWhere(['o_status' => Orders::STATUS_PAYING]);
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['orders.o_created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        return $this->render('index', [
            'sear' => $model->getAttributes(),
            'model' => $data,
            'totalpage' => $pages->pageCount,
            'pages'=>$pages
        ]);
    }

    public function actionView($order_id)
    {
        if($model = Orders::getOne($order_id)){
            return $this->render('view', ['model'=>$model]);
        }
        return $this->error('数据不存在！'/*, yii\helpers\Url::toRoute(['borrow'])*/);
    }

    // 审核通过
    public function actionVeriftPass($b_id)
    {

    }

    // 审核拒绝
    public function actionVeriftRefuse($b_id)
    {

    }

    // 审核取消
    public function actionVeriftCancel($b_id)
    {

    }

    // 撤销订单
    public function actionVeriftRevoke($b_id)
    {

    }
}