<?php
namespace backend\controllers;
use common\tools\yijifu\Loan;
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
 * 放款接口
 */
class LoanController extends CoreBackendController
{

    public function beforeAction($action)
    {
        if('async' === $action->id){
            $this->enableCsrfValidation = false;
        }
        return true;
    }
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
     * 放款
     */
    public function actionLoan(){
        $request = Yii::$app->getRequest();
        if($request->getIsAjax()){
            try {
                Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;
                $o_serial_id = $request->post('o_serial_id');
                $_data = (new Query())->from(Orders::tableName())
                    ->join('LEFT JOIN', 'stores', 'orders.o_store_id = stores.s_id')
                    //->join('LEFT JOIN', 'order_images', 'orders.o_id = order_images.oi_id')
                    ->where(['orders.o_serial_id'=>$o_serial_id,'orders.o_status'=>10])
                    ->one();
                if($_data === false){
                    // return $this->error('数据不存在!' );
                    return ['status' => 2, 'message' => '数据不存在!'];
                }else{
                    if(!$_data['s_photo_seven']){
                        //return $this->error('暂无合同图片无法放款!' );
                        return ['status' => 2, 'message' => '暂无合同图片无法放款!'];
                    }

                    if($_data['o_total_price'] <= $_data['o_total_deposit']){
                        //return $this->error('系统错误!' );
                        return ['status' => 2, 'message' => '系统错误!1'];
                    }

                    //获取放款记录
                    $loan_data = (new Query())->from(YijifuLoan::tableName())
                        ->where(['y_serial_id'=>$o_serial_id])
                        ->one();

                    if($loan_data['status'] == 2){
                        //return $this->error('暂无合同图片无法放款!' );
                        return ['status' => 2, 'message' => '代发请求处理中..'];
                    }
                    $Loan_model = new Loan();
                    $t = new UploadFile();
                    //构造公私共有的请求参数
                    $amount = $_data['o_total_price'] - $_data['o_total_deposit'];
                    $outOrderNo = $_data['o_serial_id'] . time();
                    $contractUrl = $t->getUrl($_data['s_photo_seven']);
                    $realName = ($_data['s_bank_is_private'] == 1) ? $_data['s_bank_people_name'] : $_data['s_gov_name'];//$realName如果对私为结算账户的账户所有人姓名.对公则为商铺工商局注册名称
                    $mobileNo = $_data['s_owner_phone'];
                    $certNo = $_data['s_idcard_num'];
                    $bankCardNo = $_data['s_bank_num'];

                    if($_data['s_bank_is_private'] == 1){
                        // 对私
                        $bank_data = $Loan_model->getbancode($_data['s_bank_sub'],1);
                        if(empty($bank_data)){
                            //return $this->error('该收款商户的银行暂不支持!' );
                            return ['status' => 2, 'message' => '该收款商户的银行暂不支持!'];
                        }
                        $return_data = $Loan_model->userLoan($o_serial_id,$amount,$outOrderNo,$contractUrl,$realName,$mobileNo,$certNo,$bankCardNo);
                    }else{
                        //对公

                        //验证商户银行是否支持
                        $bank_data = $Loan_model->getbancode($_data['s_bank_sub'],2);
                        if(empty($bank_data)){
                            // return $this->error('该收款商户的银行暂不支持!');
                            return ['status' => 2, 'message' => '该收款商户的银行暂不支持!'];
                        }

//                //对公必传参数
                        $helper_address = new Helper();
                        $bankCode = $bank_data['bankcode'];
                        $bankName = $bank_data['bankname'];
                        $sellerBankProvince = $helper_address->getAddrName($_data['s_province']) ? $helper_address->getAddrName($_data['s_province']) : $_data['s_bank_addr'];
                        $sellerBankCity = $helper_address->getAddrName($_data['s_city']) ? $helper_address->getAddrName($_data['s_city']) : $_data['s_bank_addr'];
                        $sellerBankAddress = $_data['s_bank_sub'];

                        $return_data = $Loan_model->userLoan(
                            $o_serial_id,
                            $amount,
                            $outOrderNo,
                            $contractUrl,
                            $realName,
                            $mobileNo,
                            $certNo,
                            $bankCardNo,
                            $bankCode,
                            $bankName,
                            $sellerBankProvince,
                            $sellerBankCity,
                            $sellerBankAddress
                        );
                    }

                    //根据响应参数输出数据
                    if($return_data['resultCode']=='EXECUTE_SUCCESS'){
                        //如果此订单的放款记录不存在就新增
                        if(!$loan_data){
                            $wait_inster_data = [
                                'y_serial_id'=>$_data['o_serial_id'],
                                'outOrderNo'=>$outOrderNo,
                                'amount'=>$amount,
                                'realRemittanceAmount'=>0,
                                'contractNo'=>0,
                                'chargeAmount'=>0,
                                'status'=>2, // 1接口调用失败  2接口调用成功处理中 3放款处理失败  4放款处理成功
                                'operator_id'=>Yii::$app->getUser()->getIdentity()->getId(),
                                'y_operator_realname'=>Yii::$app->getUser()->getIdentity()->realname,
                                'created_at'=>$_SERVER['REQUEST_TIME'],
                                'resultmsg'=>$return_data['resultMessage']
                            ];
                            \Yii::$app->getDb()->createCommand()->insert(YijifuLoan::tableName(), $wait_inster_data)->execute();
                        }else{
                            //修改
                            $_data_save['outOrderNo'] = $outOrderNo;
                            $_data_save['updated_at'] = $_SERVER['REQUEST_TIME'];
                            $_data_save['status'] = 2;
                            \Yii::$app->getDb()->createCommand()->update(YijifuLoan::tableName(), $_data_save, ['id'=>$loan_data['id']])->execute();
                        }
                        return ['status' => 1, 'message' => '接口调用成功，请等待注意查看通知！'];
                    }else{
                        //如果此订单的放款记录不存在就新增
                        if(!$loan_data){
                            $wait_inster_data = [
                                'y_serial_id'=>$_data['o_serial_id'],
                                'outOrderNo'=>$outOrderNo,
                                'amount'=>$amount,
                                'realRemittanceAmount'=>0,
                                'contractNo'=>orderNo,
                                'chargeAmount'=>0,
                                'status'=>1, // 1接口调用失败  2接口调用成功处理中 3放款处理失败  4放款处理成功
                                'operator_id'=>Yii::$app->getUser()->getIdentity()->getId(),
                                'y_operator_realname'=>Yii::$app->getUser()->getIdentity()->realname,
                                'created_at'=>$_SERVER['REQUEST_TIME'],
                                'resultmsg'=>$return_data['resultMessage']
                            ];
                            \Yii::$app->getDb()->createCommand()->insert(YijifuLoan::tableName(), $wait_inster_data)->execute();
                        }else{
                            //修改
                            $_data_save['outOrderNo'] = $outOrderNo;
                            $_data_save['updated_at'] = $_SERVER['REQUEST_TIME'];
                            \Yii::$app->getDb()->createCommand()->update(YijifuLoan::tableName(), $_data_save, ['id'=>$loan_data['id']])->execute();
                        }
                        return ['status' => 2, 'message' => $return_data['resultMessage']];
                    }
                }
            } catch (CustomBackendException $e) {
                return ['status' => $e->getCode(), 'message' => $e->getMessage()];
            } catch (yii\base\Exception $e) {
                return ['status' => 2, 'message' => '系统错误'];
            }
        }


//同步响应参数
//        array(10) {
//            ["sign"]=> string(32) "6192ca15b4adb671d53528dc774bd0cd"
//            ["protocol"]=> string(8) "httpPost"
//            ["orderNo"]=> string(21) "149977776084089497780"
//            ["signType"]=> string(3) "MD5"
//            ["service"]=> string(20) "yxtQuicklyRemittance"
//            ["resultCode"]=> string(15) "EXECUTE_SUCCESS"
//            ["partnerId"]=> string(20) "20160831020000752643"
//            ["resultMessage"]=> string(6) "成功"
//            ["success"]=> bool(true)
//            ["version"]=> string(3) "1.0"
//        }

        /*测试数据start*/
//        $amount = 10000;
//        $outOrderNo = '12345ddd' . time();
//        $contractUrl = 'https://static.zhihu.com/static/revved/img/index/logo.6837e927.png';
//        $realName = '李正周';//$realName如果对私为结算账户的账户所有人姓名.对公则为商铺工商局注册名称
//        $mobileNo = '15951215597';
//        $certNo = '320382198909181037';
//        $bankCardNo = '6228480413868991410';
//
//        $Loan_model = new Loan();
//        $return_data = $Loan_model->userLoan($amount,$outOrderNo,$contractUrl,$realName,$mobileNo,$certNo,$bankCardNo);
//        header("Location:" . $return_data);
        /*测试数据end*/
    }

    /**
     * 放款回调方法
     * @author lilaotou <liwansen@foxmail.com>
     */
    public function actionAsync()
    {
        /*异步回调开始*/
                $post = Yii::$app->getRequest()->post();
                //获取放款记录
                $_data = (new Query())->from(YijifuLoan::tableName())
                    ->where(['outOrderNo'=>$post['outOrderNo']])
                    ->one();

                //已放款
                if($_data['status'] == 4){
                    echo 'success';
                }else{
                    //异步回调方法里写放款记录
                    if($post['status'] == 'REMITTANCE_SUCCESS'){
                        $status = 4;// 1接口调用失败  2接口调用成功处理中 3放款处理失败  4放款处理成功
                    }else{
                        $status = 3;// 1接口调用失败  2接口调用成功处理中 3放款处理失败  4放款处理成功
                    }
                    $userinfo = Yii::$app->getUser()->getIdentity();
                    if($_data){
                        //修改
                        $_data['contractNo'] = $post['contractNo'];
                        $_data['status'] = $status;
                        $_data['operator_id'] = $userinfo->getId();
                        $_data['y_operator_realname'] = $userinfo->realname;
                        $_data['updated_at'] = $_SERVER['REQUEST_TIME'];
                        \Yii::$app->getDb()->createCommand()->update(YijifuLoan::tableName(), $_data, ['id'=>$_data['id']])->execute();
                    }

                    //$client = new Client();
                    // todo 写websocket服务，然后就可以测试了
                    //$client->send();
                    echo "success";
                }
        /*异步回调结束*/
        /*异步响应参数
              array(21) {
                ["bankCode"]=>
                  string(3) "ABC"
                        ["realRemittanceAmount"]=>
                  string(7) "1000.00"
                        ["amount"]=>
                  string(7) "1000.00"
                        ["orderNo"]=>
                  string(21) "149978111235455705557"
                        ["bankCardNo"]=>
                  string(19) "622848*********1410"
                        ["contractNo"]=>
                  string(20) "000g03001h5uzrhms800"
                        ["notifyTime"]=>
                  string(19) "2017-07-11 21:52:08"
                        ["resultCode"]=>
                  string(15) "EXECUTE_SUCCESS"
                        ["sign"]=>
                  string(32) "6f13bcc14975f9dc5d4fbfd98223f86f"
                        ["memo"]=>
                  string(12) "代发成功"
                        ["resultMessage"]=>
                  string(6) "成功"
                        ["outOrderNo"]=>
                  string(30) "000500201g1zvaztwp061499781112"
                        ["version"]=>
                  string(3) "1.0"
                        ["realName"]=>
                  string(9) "李正周"
                        ["protocol"]=>
                  string(8) "httpPost"
                        ["service"]=>
                  string(20) "yxtQuicklyRemittance"
                        ["success"]=>
                  string(4) "true"
                        ["signType"]=>
                  string(3) "MD5"
                        ["chargeAmount"]=>
                  string(4) "1.00"
                        ["partnerId"]=>
                  string(20) "20160831020000752643"
                        ["status"]=>
                  string(18) "REMITTANCE_SUCCESS"
        }*/

    }

    /**
     * 放款记录列表
     * @author lilaotou <liwansen@foxmail.com>
     */
    public function actionLoanlogs(){
        $request = Yii::$app->getRequest();
        $y_serial_id = $request->get('y_serial_id') ? trim($request->get('y_serial_id')) : '';
        $contractNo = $request->get('contractNo') ? trim($request->get('contractNo')) : '';

        $query = (new Query())->from(YijifuLoan::tableName());
        $query->Where(['>','id','0']);
        if (!empty($y_serial_id)) {
            $query->andWhere(['y_serial_id'=>$y_serial_id]);
        }
        if (!empty($contractNo)) {
            $query->andWhere(['contractNo'=>$contractNo]);
        }
        $querycount = clone $query;
        $pages = new yii\data\Pagination(['totalCount' => $querycount->count()]);
        $pages->pageSize = Yii::$app->params['page_size'];
        $data = $query->orderBy(['yijifu_loan.created_at' => SORT_DESC])->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('loanlogs', [
            'model' => $data,
            'y_serial_id'=>$y_serial_id,
            'contractNo'=>$contractNo,
            'totalpage' => $pages->pageCount,
            'pages' => $pages
        ]);
    }

    /**
     * 放款记录详情
     * TODO 待测试
     * @author lilaotou <liwansen@foxmail.com>
     */

    public function actionView($y_serial_id){
        $_data = (new Query())->from(YijifuLoan::tableName())->where(['y_serial_id'=>$y_serial_id])->one();
        if(!$_data){
            $this->error('信息不存在!');
        }
        //请求查询接口查询并将结果返回前台
        $loan = new Loan($_data['outOrderNo'],$_data['contractNo']);
        $loanlog = $loan->queryRemittance($y_serial_id,$_data['contractNo']);
        print_r($loanlog);
//        return $this->render('view', [
//            'model' => $loanlog
//            'y_serial_id' => $y_serial_id
//        ]);
    }


    public function actionTestgetuser(){
        var_dump(Yii::$app->getUser()->getIdentity()->realname);
        //var_dump(Yii::$app->getUser()->getIdentity());
    }
}
