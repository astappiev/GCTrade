<?php
return [
    // Контроллер [[Site]]
    '' => 'site/index',
    '<_action:(contact|economy|rail|calc)>' => 'site/<_action>',

    // Общие правила
    '<_controler:[\w\-]+>' => '<_controler>/index',

    // Контроллер [[Shop]]
    '<_controler:shop>/<_action:(uploadTempLogo|deleteTempLogo|complaint|delete-logo|edit|create|delete|export|edititem|removeitem|clearitem)>' => '<_controler>/<_action>',
    '<_controler:shop>/<_action:(update|logo|item)>/<alias:[a-zA-Z0-9_-]{3,20}+>' => '<_controler>/<_action>',
    '<_controler:shop>/parser/<_action>' => 'parser/<_action>',
    '<_controler:shop>/<alias:[a-zA-Z0-9_-]{3,20}+>' => '<_controler>/view',

    // Контроллер [[Item]]
    '<_controler:item>/<_action:(full|edit)>' => '<_controler>/<_action>',
    '<_controler:item>/<alias:[0-9.]{1,10}+>' => '<_controler>/view',

    // Контроллер [[API]]
    '<_controler:api>/<_action:[\w\-]+>' => '<_controler>/<_action>',
    '<_controler:api>/<_action:item>/<id:\d+>' => '<_controler>/<_action>',
    '<_controler:api>/<_action:world>/<login:[a-zA-Z0-9_-]{3,20}+>' => '<_controler>/<_action>',

    // Служебные
    '<_module:(debug|gii)>/<_controler:\w+>/<_action:\w+>' => '<_module>/<_controler>/<_action>',

    // Общие CRUD правила
    'POST <_controler:[\w\-]+>' => '<_controler>/create',
    'POST <_controler:[\w\-]+>/<id:\d+>' => '<_controler>/update',
    'DELETE <_controler:[\w\-]+>/<id:\d+>' => '<_controler>/delete',

    'POST <_controler:[\w\-]+>/<_action:[\w\-]+>' => '<_controler>/<_action>',
    'POST <_controler:[\w\-]+>/<_action:[\w\-]+>/<id:\d+>' => '<_controler>/<_action>',
    'DELETE <_controler:[\w\-]+>/<_action:[\w\-]+>' => '<_controler>/<_action>',
    'DELETE <_controler:[\w\-]+>/<_action:[\w\-]+>/<id:\d+>' => '<_controler>/<_action>',

    // Универсальные
    '<_controler:[\w\-]+>/<_action:[\w\-]+>' => '<_controler>/<_action>',
    '<_controler:[\w\-]+>/<_action:[\w\-]+>/<id:\d+>' => '<_controler>/<_action>',
];
