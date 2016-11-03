<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=211.149.163.238;port=4546;dbname=wcb_latest',
            'username' => 'haytoo',
            'password' => 'hsd89h&hhOH09UH90jo',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'smtp.qq.com',
                'username' => '网站邮箱',
                'password' => '邮箱密码或授权码',
                'port' => '587', // 不走ssl就选25，不过腾讯必须走ssl端口
                'encryption' => 'tls', // 不走ssl就填false
            ],
        ],
        /*'authManager' => [
            'class' => 'yii\rbac\DbManager',
            'itemTable' => 'auth_item',
            'assignmentTable' => 'auth_assignment',
            'itemChildTable' => 'auth_item_child',
        ],*/
    ],
];
