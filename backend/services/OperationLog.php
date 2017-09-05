<?php

namespace backend\services;

use Yii;
use backend\models\OperationLog as Log;
use common\models\StoresSaleman;
use common\models\User;

class OperationLog
{
    protected $config;
    protected $data = [
        'memo' => null,
        'data' => null,
    ];

    public function active()
    {
        $config = Yii::$app->params['operation-log'];
        $pathInfo = Yii::$app->request->getPathInfo();
        if (!isset($config[$pathInfo])) {
            return;
        }
        $this->config = $config[$pathInfo];
        if (!empty($this->config['active']) and method_exists($this, 'active' . $this->config['active']) ) {
            // 每个 active 函数只给 $this->data 填充数据，不需要每个函数中作其它操作
            $this->{'active' . ucfirst($this->config['active'])}();
        } elseif (empty($this->config['memo'])) {
            return;
        }

        $this->parse();
        $this->write($this->config['tag'], $this->data['memo'], 0, $this->data['data']);
    }

    
    /**
     * 解析通用日志规则中的相关变量
     *
     * @return void
     */
    protected function parse()
    {
        if (empty($this->config['memo'])) {
            return;
        }
        $operator = \Yii::$app->getUser()->getIdentity();
        $varTab = [
            '{OPERATOR_ID}' => $operator->id,
            '{OPERATOR_USERNAME}' => $operator->username,
            '{OPERATOR_REALNAME}' => $operator->realname,
        ];
        if (!$this->data['memo']) {
            $this->data['memo'] = str_replace(array_keys($varTab), array_values($varTab), $this->config['memo']);
        }
    }

    public function activeTest()
    {
        // TODO.
        $this->data['memo'] = '这是各应用中处理的文本说明';
        $this->data['data'] = ['test_a_id' => 9912, 'test_b_id' => 9913];
    }

    /**
     * 写入操作日志
     *
     * @param string $typeTag
     * @param string $memo
     * @param integer $orderId
     * @param array $data
     * @return boolean
     */
    public function write($typeTag, $memo, $orderId = 0, $data = [])
    {
        is_array($data) or $data = json_decode($data, true) ?? [];
        empty($orderId) and $orderId = null;

        $operator = \Yii::$app->getUser()->getIdentity();
        $requestParams = [];
        $get = \Yii::$app->request->get();
        $post = \Yii::$app->request->post();
        empty($get) or $requestParams['_get'] = $get;
        empty($post) or $requestParams['_post'] = $post;
        $data['params'] = $requestParams;

        $d = [
            'type_tag' => $typeTag,
            'operator_id' => $operator->id,
            'ip' => Yii::$app->request->userIP,
            'order_id' => $orderId,
            'memo' => $memo,
            'data' => json_encode($data),
            'created_at' => date('Y-m-d H:i:s'),
        ];
        // var_dump($d);die();
        $log = new Log();
        $log->setAttributes($d);
        return $log->save();        
    }
}