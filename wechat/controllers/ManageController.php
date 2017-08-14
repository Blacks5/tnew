<?php
/**
 * Created by PhpStorm.
 * User: Brucelee
 * Date: 2017/8/13
 * Time: 22:59
 * Author: lilaotou <liwansen@foxmail.com>
 */

namespace wechat\controllers;


use common\models\Product;
use common\models\Stores;
use yii\web\Controller;
use yii;

class ManageController extends Controller
{
    /**
     * @return string
     * @author lilaotou <liwansen@foxmail.com>
     * 首页
     */
    public function actionIndex(){
        return $this->renderPartial('index');
    }

    /**
     * @return string
     * @author lilaotou <liwansen@foxmail.com>
     * 登录
     */
    public function actionLogin(){
        return $this->renderPartial('login');
    }

    /**
     * @return string
     * @author lilaotou <liwansen@foxmail.com>
     * 提交订单
     */
    public function actionCommitorder(){
        $userinfo = Yii::$app->getUser()->getIdentity();
        // 可选商户 同一个区县
//        $stores = (new yii\db\Query())->select(['s_id', 's_name'])
//            ->from(Stores::tableName())->where(['s_status' => Stores::STATUS_ACTIVE, 's_county' => $userinfo->county])->all();

        $stores = (new yii\db\Query())->select(['s_id', 's_name'])
            ->from(Stores::tableName())->where(['s_status' => Stores::STATUS_ACTIVE])->all();
        // 商品类型
        $goods_type = Yii::$app->params['goods_type'];

        // 可选产品
        $select = ['p_id', 'p_name', 'p_month_rate', 'p_period', 'p_add_service_fee', 'p_free_pack_fee', 'p_finance_mangemant_fee', 'p_customer_management'];
        $products = (new yii\db\Query())->select($select)
            ->from(Product::tableName())->where(['p_status' => Product::STATUS_OK])->all();

        $marital_status = \Yii::$app->params['marital_status'];
        $bank_list = Yii::$app->params['bank_list'];

        $house_info = Yii::$app->params['house_info'];
        $kinship = Yii::$app->params['kinship'];
        $company_type = Yii::$app->params['company_type'];
        $company_kind = Yii::$app->params['company_kind'];

        $data = [
            'stores' => $stores,
            'goods_type' => $goods_type,
            'products' => $products,
            'marital_status' => $marital_status,
            'bank_list' => $bank_list,
            'house_info' => $house_info,
            'kinship' => $kinship,
            'company_kind' => $company_kind,
            'company_type' => $company_type,
        ];

        //省市区JSON
        $data_json = $this->actionGetcity();
        return $this->renderPartial('commit_order',[
            'data_json'=>$data_json,
            'data'=>$data
        ]);
    }

    /**
     * @author lilaotou <liwansen@foxmail.com>
     * 处理省市区
     */
    public function actionGetcity(){
        $rows = (new \yii\db\Query())
            ->select(['*'])
            ->from('too_region')
            ->where(['parent_id' => 1])
            ->all();
        $data = [];
        foreach ($rows as $k=>$v){
            $data[$k]['label'] = $v['region_name'];
            $data[$k]['value'] = $v['region_id'];
            $rows_2 = (new \yii\db\Query())
                ->select(['*'])
                ->from('too_region')
                ->where(['parent_id' => $v['region_id']])
                ->all();
            if($rows_2){
                $children = [];
                foreach ($rows_2 as $k1=>$v1){
                    $children[$k1]['label'] = $v1['region_name'];
                    $children[$k1]['value'] = $v1['region_id'];
                    $rows_3 = (new \yii\db\Query())
                        ->select(['*'])
                        ->from('too_region')
                        ->where(['parent_id' => $v1['region_id']])
                        ->all();
                    if($rows_3){
                        $children2 = [];
                        foreach ($rows_3 as $k2=>$v2){
                            $children2[$k2]['label'] = $v2['region_name'];
                            $children2[$k2]['value'] = $v2['region_id'];
                        }

                        $children[$k1]['children'] = $children2;
                    }
                }
                $data[$k]['children'] = $children;
            }
        }
        return json_encode($data);
    }

}