<?php
namespace backend\controllers;
use common\tools\yijifu\Loan;
use Yii;
use backend\core\CoreBackendController;
use common\models\YijifuLoan;
use yii\db\Query;
use common\models\Orders;
use common\components\CustomCommonException;
use common\components\Helper;
use common\models\UploadFile;
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
     * 放款测试
     */
    public function actionTestloan(){
        $order_id = Yii::$app->getRequest()->post('order_id');
        $_data = (new Query())->from(Orders::tableName())
            ->join('LEFT JOIN', 'stores', 'orders.o_store_id = stores.s_id')
            //->join('LEFT JOIN', 'order_images', 'orders.o_id = order_images.oi_id')
            ->where(['orders.o_id'=>$order_id,'orders.o_status'=>10])
            ->one();

        if($_data === false){
            return $this->error('数据不存在!' );
        }else{
            if(!$_data['s_photo_seven']){
                return $this->error('暂无合同图片无法放款!' );
            }

            if($_data['o_total_price'] <= $_data['o_total_deposit']){
                return $this->error('系统错误!' );
            }

            $Loan_model = new Loan();
            $t = new UploadFile();
            //构造公私共有的请求参数
            $amount = $_data['o_total_price'] - $_data['o_total_deposit'];
            $outOrderNo = $_data['o_id'];
            $contractUrl = $t->getUrl($_data['s_photo_seven']);
            $realName = ($_data['s_bank_is_private'] == 1) ? $_data['s_bank_people_name'] : $_data['s_gov_name'];//$realName如果对私为结算账户的账户所有人姓名.对公则为商铺工商局注册名称
            $mobileNo = $_data['s_owner_phone'];
            $certNo = $_data['s_idcard_num'];
            $bankCardNo = $_data['s_bank_num'];


//        $amount = 10000;
//        $outOrderNo = '12345ddd' . time();
//        $contractUrl = 'https://static.zhihu.com/static/revved/img/index/logo.6837e927.png';
//        $realName = '李正周';//$realName如果对私为结算账户的账户所有人姓名.对公则为商铺工商局注册名称
//        $mobileNo = '15951215597';
//        $certNo = '320382198909181037';
//        $bankCardNo = '6228480413868991410';

            if($_data['s_bank_is_private'] == 1){
               // 对私
                $bank_data = $Loan_model->getbancode($_data['s_bank_sub'],1);
                if(empty($bank_data)){
                    throw new CustomCommonException('该收款商户的银行暂不支持!');
                }
                $return_data = $Loan_model->userLoan($amount,$outOrderNo,$contractUrl,$realName,$mobileNo,$certNo,$bankCardNo);
            }else{
                //对公

                //验证商户银行是否支持
                $bank_data = $Loan_model->getbancode($_data['s_bank_sub'],2);
                if(empty($bank_data)){
                    return $this->error('该收款商户的银行暂不支持!');
                }

//                //对公必传参数
                $helper_address = new Helper();
                $bankCode = $bank_data['bankcode'];
                $bankName = $bank_data['bankname'];
                $sellerBankProvince = $helper_address->getAddrName($_data['s_province']) ? $helper_address->getAddrName($_data['s_province']) : $_data['s_bank_addr'];
                $sellerBankCity = $helper_address->getAddrName($_data['s_city']) ? $helper_address->getAddrName($_data['s_city']) : $_data['s_bank_addr'];
                $sellerBankAddress = $_data['s_bank_sub'];

                $return_data = $Loan_model->userLoan(
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
                //新增
                $wait_inster_data = [
                    'order_id'=>$outOrderNo,
                    'amount'=>$amount,
                    'realRemittanceAmount'=>'',
                    'contractNo'=>'',
                    'status'=>2, // 1接口调用失败  2接口调用成功处理中
                    'operator_id'=>Yii::$app->getUser()->getIdentity()->getId(),
                    'created_at'=>$_SERVER['REQUEST_TIME']
                ];
                \Yii::$app->getDb()->createCommand()->insert(YijifuLoan::tableName(), $wait_inster_data)->execute();
                return $this->success('接口调用成功,等待处理');
            }else{
                return $this->error('接口调用失败 ' . $return_data['resultCode']);
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
    }

    /**
     * 放款回调方法
     * @author lilaotou <liwansen@foxmail.com>
     */
    public function actionAsyncloan()
    {
        $post = Yii::$app->getRequest()->post();
        //获取放款记录
        $_data = (new Query())->from(YijifuLoan::tableName())
            ->where(['order_id'=>$post['outOrderNo']])
            ->one();

        //异步回调方法里写放款记录(根据回调信息,添加不同的状态,如果此订单的记录存在且status状态值为1未放款,就修改,如果没有数据就添加一条)
        if($post['status'] == 'REMITTANCE_SUCCESS'){
            $status = 2;
        }else{
            $status = 1;
        }
        $operator_id = Yii::$app->getUser()->getIdentity()->getId();
        if($_data){
            if($_data['status'] == 1){
                //修改
                $_data['contractNo'] = $post['contractNo'];
                $_data['status'] = $status;
                $_data['operator_id'] = $operator_id;
                $_data['updated_at'] = $_SERVER['REQUEST_TIME'];
                \Yii::$app->getDb()->createCommand()->update(YijifuLoan::tableName(), $_data, ['id'=>$_data['id']])->execute();
            }
        }else{
            //新增
            $wait_inster_data = [
                'order_id'=>$post['outOrderNo'],
                'amount'=>$post['amount'],
                'realRemittanceAmount'=>$post['realRemittanceAmount'],
                'contractNo'=>$post['contractNo'],
                'status'=>$status, // 3放款处理失败  4放款处理成功
                'operator_id'=>$operator_id,
                'created_at'=>$_SERVER['REQUEST_TIME']
            ];
            \Yii::$app->getDb()->createCommand()->insert(YijifuLoan::tableName(), $wait_inster_data)->execute();
        }
        echo "success";

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

    public function actionTest(){
        $user_id=Yii::$app->getUser()->getIdentity()->getId();
        print_r(Yii::$app->getUser()->getIdentity());
    }
}
