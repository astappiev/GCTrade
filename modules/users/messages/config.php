<?php

return [
    'sourcePath' => __DIR__ . '/..',
    'messagePath' => __DIR__,
    'languages' => ['ru-RU'],
    'translator' => 'Yii::t',
    'sort' => false,
    'overwrite' => true,
    'removeUnused' => false,
    'except' => [
        '.svn',
        '.git',
        '.gitignore',
        '.gitkeep',
        '.hgignore',
        '.hgkeep',
        '/messages',
    ],
    'only' => ['*.php'],
    'format' => 'php',
];
