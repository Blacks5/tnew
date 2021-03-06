<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php'),
    require(__DIR__ . '/operation-log.php')
);

return [
    "modules" => [
        "admin" => [
            "class" => 'mdm\admin\Module',
        ],
    ],
    "aliases" => [
        "@mdm/admin" => "@vendor/mdmsoft/yii2-admin",
    ],

    'as access' => [
        //ACF肯定要加,加了才会自动验证是否有权限
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [

            //允许访问的action
//            '*',
            'borrow/check-product-code', // 检测商品代码是否重复，为了防止分配遗漏，干脆就不检测权限了
            'borrownew/check-product-code', // 检测商品代码是否重复，为了防止分配遗漏，干脆就不检测权限了
            'user/get-leader', //获取上级领导, 权限太复杂,   放这里了
            'tools/*', // 测试工具（易极付等）

            'login/login',
            'contract/index', // 订单合同页面，Android app里webview用
            'contract/paymentdesc', // 代扣说明页面，Android app里webview用1
//            'site/test',
//            'site/test2',
//            'gii/*',
//            'site/ws', // ws测试页
//            'site/sendws', // ws测试页
            'borrow/verify-pass-callback', // 易极付签约回调地址
            'repayment/deduct-callback', // 易极付代扣回调地址
            'repaymentnew/deduct-callback', // 易极付单期代扣回调地址(新API)
            'borrownew/deduct-callback',//易极付提前还款代扣回调地址(新API)
//            'borrow/deduct-callback', // 易极付代扣回调地址(旧方法中已被注释掉)
            'borrow/update-bank-call-back', //修改银行卡易极付回调
            'loan/async', // 易极付回调地址
            'jun/callback', // 君子签回调
            /*'jun/a',
            'jun/a6',
            'jun/a7',
            'jun/a10',
            'jun/a11',
            'jun/a12',
            'jun/a13',
            'jun/a15',
            'jun/a16',*/

        ]
    ],

    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
            'cookieValidationKey' => '3X3GCw78PsgKB5ApPTYw8fexUweeKTq2',
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '/messages',
                    'fileMap' => [
                        'common' => 'common.php'
                    ],
                ],
            ],
        ],



        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => false,
            'authTimeout' => 3000,
//            'loginUrl'=>['login/login'], // 没有登录会跳这里
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            /*'on beforeLogin' => function ($event) {
                $event->identity->updated_at = $_SERVER['REQUEST_TIME'];
                $event->identity->save(false);
            },*/
            /*'on afterLogin' => function ($event) {

            },
            'on beforeLogout' => function ($event) {

            },
            'on afterLogout' => function ($event) {

            },*/
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],

        "urlManager" => [
            //用于表明urlManager是否启用URL美化功能，在Yii1.1中称为path格式URL，
            // Yii2.0中改称美化。
            // 默认不启用。但实际使用中，特别是产品环境，一般都会启用。
            "enablePrettyUrl" => true,
            // 是否启用严格解析，如启用严格解析，要求当前请求应至少匹配1个路由规则，
            // 否则认为是无效路由。
            // 这个选项仅在 enablePrettyUrl 启用后才有效。
            "enableStrictParsing" => false,
            // 是否在URL中显示入口脚本。是对美化功能的进一步补充。
            "showScriptName" => false,
            // 指定续接在URL后面的一个后缀，如 .html 之类的。仅在 enablePrettyUrl 启用时有效。.
            "suffix" => "",
            "rules" => [
                "<controller:\w+>/<id:\d+>"=>"<controller>/view",
                "<controller:\w+>/<action:\w+>"=>"<controller>/<action>"
            ],
        ],

        'authManager' => [
            'class' => 'yii\rbac\DbManager', // 使用数据库管理配置文件
            "defaultRoles" => ["guest"],
        ],

        /*禁止自带jquery*/
        'assetManager'=>[
            'bundles'=>[
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => []
                ],
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'js' => []
                ],
            ],

        ]
    ],

    'params' => $params,
];
