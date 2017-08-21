<?php
/**
 * Created by PhpStorm.
 * User: Brucelee
 * Date: 2017/8/13
 * Time: 22:59
 * Author: lilaotou <liwansen@foxmail.com>
 */

namespace wechat\controllers;


use common\components\CustomCommonException;
use common\models\LoginForm;
use common\models\Product;
use common\models\Stores;
use common\models\User;
use wechat\Tools\Wechat;
use yii;


class ManageController extends BaseController
{
    /**
     * 微信授权+账号绑定才能进这个页面
     * @return string
     * @author lilaotou <liwansen@foxmail.com>
     * 首页
     */
    public function actionIndex(){
        Wechat::Login(['manage/index']);
        $user = \Yii::$app->getSession()->get('wechat_user');

        $sys_user = (new yii\db\Query())->from(User::tableName())->where(['wechat_openid'=>$user->id])->one();
        // 还没绑定账号
        if(false === $sys_user){
            return $this->redirect(['manage/login']);
        }
        return $this->renderPartial('index');
    }

    /**
     * 绑定用户
     * @return string
     * @author lilaotou <liwansen@foxmail.com>
     * 登录
     */
    public function actionLogin(){
        $request = Yii::$app->getRequest();
        if($request->getIsAjax() && $request->getIsPost()){
            Yii::$app->getResponse()->format = yii\web\Response::FORMAT_JSON;

            $openid = $request->post('openid');
            $model = new LoginForm();
            $data['data'] = $request->post();
            $model->load($data, 'data');

            // 登录成功后，写入openid
            if($model->login()){
                Yii::$app->db->createCommand()->update(User::tableName(), ['wechat_openid'=>$openid], ['id'=>Yii::$app->getUser()->getId()])->execute();
                return ['status'=>1, 'message'=>'绑定成功', 'data'=>Yii::$app->getUrlManager()->createUrl(['manage/index'])];
            }
            return ['status'=>0, 'message'=>'绑定失败'];
        }

        Wechat::Login(['manage/login']);
        $user = \Yii::$app->getSession()->get('wechat_user');
        return $this->renderPartial('login', ['openid'=>$user->id]);
    }

    /**
     * 初始化订单数据
     * @return string
     * @author lilaotou <liwansen@foxmail.com>
     * 提交订单
     */
    public function actionCommitorder(){
        Wechat::Login(['manage/index']);
        $user = \Yii::$app->getSession()->get('wechat_user');


        $sys_user = (new yii\db\Query())->from(User::tableName())->where(['wechat_openid'=>$user->id])->one();
        $sub = (new yii\db\Query())->from('stores_saleman')->select(['ss_store_id'])->where(['ss_saleman_id'=>$sys_user['id']]);
        // 可选商户 同一个区县
        $stores = (new yii\db\Query())->select(['s_id', 's_name'])
            ->from(Stores::tableName())->where(['s_status' => Stores::STATUS_ACTIVE, 's_county' => $sys_user['county'], 's_id'=>$sub])->all();
//        var_dump($stores);die;
        /*var_dump($stores);die;
        if(false === !empty($stores)){
            throw new CustomCommonException('该销售代表尚未绑定商户，请联系相关负责人进行绑定');
        }*/
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

    /**
     * 根据商品类型选择对应的产品
     * 2017-05-22新增
     * @return array
     * @author too <hayto@foxmail.com>
     */
    public function actionGetproductsbytype()
    {
        // 可选产品
        try {
            $p_type = Yii::$app->getRequest()->get('p_type');
            $select = ['p_id', 'p_name', 'p_month_rate', 'p_period', 'p_add_service_fee', 'p_free_pack_fee', 'p_finance_mangemant_fee', 'p_customer_management'];
            $products = (new yii\db\Query())->select($select)
                ->from(Product::tableName())->where(['p_status' => Product::STATUS_OK, 'p_type' => $p_type])->all();

            $ret_str = '';
            if(!empty($products)){
                //throw new CustomCommonException('没有相关产品');
                foreach ($products as $k=>$v){
                    $ret_str .= "<option value='" . $v['p_id'] . "'>" .  $v['p_name'] . "</option>";
                }
            }
            return json_encode(['status' => 1, 'data'=>$ret_str, 'message' => '获取成功']);
        }catch (CustomCommonException $e){
            return json_encode(['status' => 0, 'data'=>[], 'message' => $e->getMessage()]);
        }catch (\Exception $e){
            return json_encode(['status' => 0, 'data'=>[], 'message' => $e->getMessage()]);
        }
    }

    /**
     * 根据当前登录的销售，获取对应的商户
     * 2017-05-22新增
     * @return array
     * @author too <hayto@foxmail.com>
     */
    private function getStoresByUser()
    {
        try {
           // Wechat::Login(['manage/index']);
           // $user = \Yii::$app->getSession()->get('wechat_user');
           //$sub = (new yii\db\Query())->from('stores_saleman')->select(['ss_store_id'])->where(['ss_saleman_id'=>$user->id]);
            $sys_user = (new yii\db\Query())->from(User::tableName())->where(['wechat_openid'=>'o9dGBv_lC9-6tzZPUNyXk-1AM3as'])->one();
            $sub = (new yii\db\Query())->from('stores_saleman')->select(['ss_store_id'])->where(['ss_saleman_id'=>$sys_user['id']]);
            // 可选商户 同一个区县
            $stores = (new yii\db\Query())->select(['s_id', 's_name'])
                ->from(Stores::tableName())->where(['s_status' => Stores::STATUS_ACTIVE, 's_county' => $sys_user['county'], 's_id'=>$sub])->all();

            if(false === !empty($stores)){
                throw new CustomCommonException('该销售代表尚未绑定商户，请联系相关负责人进行绑定');
            }
            return $stores;
        }catch (CustomCommonException $e){
            return ['status' => 0, 'data'=>[], 'message' => $e->getMessage()];
        }catch (\Exception $e){
            return ['status' => 0, 'data'=>[], 'message' => '网络异常'];
        }
    }
}