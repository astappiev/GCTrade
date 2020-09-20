<?php
return [
    'components' => [
        'mailer' => [
            'useFileTransport' => false,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.ukraine.com.ua',
                'username' => 'admin@gctrade.ru',
                'password' => '4hhJUd77',
                'port' => '25s',
                'encryption' => 'tls',
            ],
        ],
        'db' => [
            'dsn' => 'mysql:host=astappev.mysql.ukraine.com.ua;dbname=astappev_gctrade',
            'username' => 'astappev_gctrade',
            'password' => 'f198ifs9',
            'tablePrefix' => 'tg_',
        ],
        'db_analytics' => [
            'dsn' => 'mysql:host=astappev.mysql.ukraine.com.ua;dbname=astappev_gcstat',
            'username' => 'astappev_gcstat',
            'password' => 'vnsx3wrv',
            'tablePrefix' => 'tg_',
        ],
    ],
];
