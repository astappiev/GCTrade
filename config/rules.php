<?php
return [
    '' => 'site/index',
    '<action:(contact|economy|rail|calc)>' => 'site/<action>',
    '<controler>' => '<controler>/index',

    'shop/parser/<action>' => 'parser/<action>',
    'shop/<action:complaint>' => 'shop/<action>',
    'shop/<action:info|logo|item>/<alias>' => 'shop/<action>',
    'shop/<action:edit|add|delete|export|edititem|removeitem|clearitem>' => 'shop/<action>',
    'shop/<alias>' => 'shop/page',

    'item/<action:full|edit>' => 'item/<action>',
    'item/<alias>' => 'item/page',

    'api/item/<id>' => 'api/item',
    'api/world/<login>' => 'api/world',
    'api/<action>' => 'api/<action>',

    'debug/<controller>/<action>' => 'debug/<controller>/<action>',

    '<controler>/<action>/<id>' => '<controler>/<action>',
    '<controler>/<action>' => '<controler>/<action>',
];
