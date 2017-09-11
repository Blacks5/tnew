<?php

namespace backend\services;

use Yii;
use \yii\httpclient\Client as httpClient;

class YijifuDebugger extends \common\tools\yijifu\AbstractYijifu
{
    protected $customers = [
        'wwj' => [
            'realName' => '王万俊',
            'certNo' => '500243198709222133',
            'bankCardNo' => '6226222008749561',
            'mobileNo' => '18180661461',
        ],
        'pxs' => [],
    ];

    private function getCustomer($flag)
    {
        return $this->customers[$flag] ?? null;
    }


    /**
     * 调试签约
     *
     * @return void
     */
    public function sign($customerFlag)
    {
        $orderId = date('YmdHis') . mt_rand(1000, 9999);
        $customer = $this->getCustomer($customerFlag);
        if (!$customer) {
            throw new \Exception('无法找到测试用户数据:' . $customerFlag, 2001);
        }
        // 构造api请求参数
        $param_arr = [
            'merchOrderNo'          => $orderId,
            'merchContractNo'       => $orderId . '-' . mt_rand(1000, 9999),
            'merchContractImageUrl' => 'http://119.23.15.90:8383/img/tianniu.jpg',
            'realName'              => $customer['realName'],
            'certNo'                => $customer['certNo'],
            'bankCardNo'            => $customer['bankCardNo'],
            'mobileNo'              => $customer['mobileNo'],
            'productName'           => '测试产品-A',
            'loanAmount'            => 90, // 可以不填的，优先不填
            'totalRepayAmount'      => 80,
            'operateType'           => 'SIGN',
            'service'   => 'fastSign',
            // 'notifyUrl' => 'http://119.23.15.90:8383/tools/yijifunotify',
        ];
        $this->notifyUrl = \Yii::$app->params['domain'] ."/tools/yijifunotify";

        $commonParams = $this->getCommonParams();
        $param_arr = array_merge($commonParams, $param_arr);
        $param_arr = $this->prepQueryParams($param_arr);

        echo '请求参数:<br>';
        $this->dd($param_arr);
        echo '<hr><br>';

        // 发起请求
        $http_client = new httpClient();
        $response = $http_client->post($this->api, $param_arr)/*->setFormat(httpClient::FORMAT_JSON)*/->send();
        if($response->getIsOk()){
            echo '<b>http成功，返回数据：</b>';
            $this->dd($response->getData());
        } else {
            echo '<b>-----http失败----，返回数据：</b>';
        }

    }



    /**
     * 调试代扣
     *
     * @return void
     */
    public function deduct()
    {
        //
    }

    private function dd($var)
    {
        echo PHP_EOL . '<pre>';
        var_dump($var);
        echo '<pre>' . PHP_EOL;
    }
}
