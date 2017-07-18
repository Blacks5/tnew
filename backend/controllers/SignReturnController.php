<?php
namespace backend\controllers;
use common\models\YijifuDeduct;
use common\models\YijifuSign;
use common\tools\yijifu\ReturnMoney;
use yii;
use backend\core\CoreBackendController;
use yii\db\Query;
use common\models\User;
/**
 * SignReturn controller
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

    public function actionIndexp()
    {
        echo '菜单';
    }
    /**
     * 签约记录列表
     * @author lilaotou <liwansen@foxmail.com>
     */
    public function actionSignlogs(){
        $request = Yii::$app->getRequest();
        $o_serial_id = $request->get('o_serial_id') ? trim($request->get('o_serial_id')) : '';
        $merchOrderNo = $request->get('merchOrderNo') ? trim($request->get('merchOrderNo')) : '';

        $query = (new Query())->from(YijifuSign::tableName())
            ->leftJoin(User::tableName(),"yijifu_sign.operator_id = user.id")
            ->select("yijifu_sign.*,user.realname");
        $query->Where(['>','yijifu_sign.id','0']);
        if (!empty($o_serial_id)) {
            $query->andWhere(['yijifu_sign.o_serial_id'=>$o_serial_id]);
        }
        if (!empty($merchOrderNo)) {
            $query->andWhere(['yijifu_sign.merchOrderNo'=>$merchOrderNo]);
        }
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['yijifu_sign.created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('signlogs', [
            'model' => $data,
            'o_serial_id'=>$o_serial_id,
            'merchOrderNo'=>$merchOrderNo,
            'totalpage' => $pages->pageCount,
            'pages' => $pages
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

        return $this->render('signview', [
            'o_serial_id'=>$o_serial_id,
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
        $merchOrderNo = $request->get('merchOrderNo') ? trim($request->get('merchOrderNo')) : '';

        $query = (new Query())->from(YijifuDeduct::tableName())
            ->leftJoin(User::tableName(),"yijifu_deduct.operator_id = user.id")
            ->select("yijifu_deduct.*,user.realname");
        $query->Where(['>','yijifu_deduct.id','0']);
        if (!empty($o_serial_id)) {
            $query->andWhere(['yijifu_deduct.o_serial_id'=>$o_serial_id]);
        }
        if (!empty($merchOrderNo)) {
            $query->andWhere(['yijifu_deduct.merchOrderNo'=>$merchOrderNo]);
        }
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['yijifu_deduct.created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('deductlogs', [
            'model' => $data,
            'o_serial_id'=>$o_serial_id,
            'merchOrderNo'=>$merchOrderNo,
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

        return $this->render('deductview', [
            'model' => $data,
        ]);
    }

    /**
     * 下载易极付对账文件
     * @author too <hayto@foxmail.com>
     */
    public function actionDownloadBill()
    {
        $request = Yii::$app->getRequest();
        if($request->getIsAjax()){
            Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;
            $day = $request->get('day', '20170719');

            $handle = new ReturnMoney();
            $data = $handle->downloadBill($day);
            return $data;
        }
        return $this->render('downloadbill');


    }
}
