<?php
return [
    'components' => [
        'db' => [
                       'class' => 'yii\db\Connection',
                        'dsn' => 'mysql:host=211.149.163.238;port=4546;dbname=wcb',
                        'username' => 'haytoo',
                        'password' => 'hsd89h&hhOH09UH90jo',
                        'charset' => 'utf8',

             /*'class' => 'yii\db\Connection',
             'dsn' => 'mysql:host=192.168.0.7;port=4546;dbname=wcb',
             'username' => 'root',
             'password' => '123456',
             'charset' => 'utf8',*/

        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];


