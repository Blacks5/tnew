<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'timeZone'=>'Asia/Chongqing',
    'language' => 'zh-CN',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],

        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
                
                // 记录 SQL 执行日志
                [
                    'class' => 'common\services\SqlTarget',
                    'levels' => ['info'],
                    'categories' => [
                        'yii\db\Command::query',
                        'yii\db\Command::execute',
                    ],
                ],
                // .end
            ],
        ],

//test 7891
    ],
];
