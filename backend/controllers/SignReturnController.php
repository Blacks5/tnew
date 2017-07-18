<?php
namespace backend\controllers;
use common\models\YijifuDeduct;
use common\models\YijifuSign;
use common\tools\yijifu\ReturnMoney;
use yii;
use backend\core\CoreBackendController;
use common\models\YijifuLoan;
use yii\db\Query;
use common\models\Orders;
use common\components\CustomCommonException;
use backend\components\CustomBackendException;
use common\components\Helper;
use common\models\UploadFile;
use \yii\httpclient\Client as httpClient;
use WebSocket\Client;
/**
 * Loan controller
 * 签约回款
 */
class SignReturnController extends CoreBackendController
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * 签约记录列表
     * @author lilaotou <liwansen@foxmail.com>
     */
    public function actionSignlogs(){
        $request = Yii::$app->getRequest();
        $o_serial_id = $request->get('o_serial_id') ? trim($request->get('o_serial_id')) : '';

        $query = (new Query())->from(YijifuSign::tableName());
        $query->Where(['>','id','0']);
        if (!empty($o_serial_id)) {
            $query->andWhere(['o_serial_id'=>$o_serial_id]);
        }
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('signlogs', [
            /*'model' => $data,
            'o_serial_id'=>$o_serial_id,
            'totalpage' => $pages->pageCount,
            'pages' => $pages*/
        ]);
    }

    /**
     * 签约记录详情
     * @author lilaotou <liwansen@foxmail.com>
     */

    public function actionSignview($o_serial_id){
        $_data = (new Query())->from(YijifuSign::tableName())->where(['o_serial_id'=>$o_serial_id])->one();
        if(!$_data){
            $this->error('信息不存在!');
        }
        //请求查询接口查询并将结果返回前台
        $loan = new ReturnMoney();
        $data = $loan->querySignedCustomer($_data['merchOrderNo']);

        return $this->render('view', [
            'model' => $data,
        ]);
    }



    /**
     * 回款记录列表
     * @author lilaotou <liwansen@foxmail.com>
     */
    public function actionDeductlogs(){
        $request = Yii::$app->getRequest();
        $o_serial_id = $request->get('o_serial_id') ? trim($request->get('o_serial_id')) : '';

        $query = (new Query())->from(YijifuDeduct::tableName());
        $query->Where(['>','id','0']);
        if (!empty($y_serial_id)) {
            $query->andWhere(['o_serial_id'=>$o_serial_id]);
        }
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['yijifu_loan.created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('loanlogs', [
            'model' => $data,
            'o_serial_id'=>$o_serial_id,
            'totalpage' => $pages->pageCount,
            'pages' => $pages
        ]);
    }

    /**
     * 回款记录详情
     * @author lilaotou <liwansen@foxmail.com>
     */

    public function actionDeductview($o_serial_id){
        $_data = (new Query())->from(YijifuDeduct::tableName())->where(['o_serial_id'=>$o_serial_id])->one();
        if(!$_data){
            $this->error('信息不存在!');
        }
        //请求查询接口查询并将结果返回前台
        $loan = new ReturnMoney();
        $data = $loan->queryDeduct($_data['merchOrderNo']);

        return $this->render('view', [
            'model' => $data,
        ]);
    }
}
