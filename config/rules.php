<?php
return [
    // Controller [[Site]]
    '' => 'site/index',
    '<_action:(contact|statistics|rail|calc|donate)>' => 'site/<_action>',

    // Controller [[API]]
    '<_controler:api>/<_action:[\w\-]+>' => '<_controler>/<_action>',
    '<_controler:api>/<_action:(world|head|badges)>/<login:[a-zA-Z0-9_-]{3,20}+>' => '<_controler>/<_action>',
    '<_controler:api>/<_action:(shop|item|price)>/<request>' => '<_controler>/<_action>',

    // General
    '<_controler:[\w\-]+>' => '<_controler>/index',
    '<_controler:[\w\-]+>/<_action:[\w\-]+>' => '<_controler>/<_action>',
    '<_controler:[\w\-]+>/<_action:[\w\-]+>/<id:\d+>' => '<_controler>/<_action>',
    '<_module:[\w\-]+>/<_controler:[\w\-]+>/<_action:[\w\-]+>' => '<_module>/<_controler>/<_action>',
];
