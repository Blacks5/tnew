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
            $this->{'active' . ucfirst($this->config['active'])}();
        } else if (empty($this->config['memo'])) {
            return;
        }

        $this->parse();
        $this->write($this->config['tag'], $this->data['memo']);

        // get_defined_vars
        // $operator = \Yii::$app->getUser()->getIdentity();
        
    }

    
    protected function parse()
    {
        // $varsTab = get_defined_vars();
        $operator = \Yii::$app->getUser()->getIdentity();
        $varTab = [
            '{OPERATOR_ID}' => $operator->id,
            '{OPERATOR_USERNAME}' => $operator->username,
            '{OPERATOR_REALNAME}' => $operator->realname,
        ];
        $this->data['memo'] = str_replace(array_keys($varTab), array_values($varTab), $this->config['memo']);
    }

    public function activeTest()
    {
        // TODO.
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