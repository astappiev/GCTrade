<?php
return [
    'bootstrap' => ['log', 'debug', 'gii'],
    'components' => [
        'log' => [
            'traceLevel' => 3,
        ],
    ],
    'modules' => [
        'debug' => [
            'class' => 'yii\debug\Module',
            'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*'],
        ],
        'gii' => [
            'class' => 'yii\gii\Module',
            'allowedIPs' => ['127.0.0.1', '::1', '192.168.0.*'],
        ],
    ],
];