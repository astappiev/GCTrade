<?php
return [
    // Controller [[Site]]
    '' => 'site/index',
    '<_action:(contact|statistics|rail|calc|donate)>' => 'site/<_action>',

    // Controller [[API]]
    '<_controler:api>/<_action:(goods)>/<request>' => '<_controler>/<_action>',
    '<_controler:api>/<_action:(shop|item|price)>s/<request>' => '<_controler>/<_action>',
    '<_controler:api>/<_action:[\w]+>/<_method:[\w]+>/<request>' => '<_controler>/<_action>-<_method>',
    '<_controler:api>/<_action:(goods)>' => '<_controler>/<_action>-search',
    '<_controler:api>/<_action:(shop|item|price)>s' => '<_controler>/<_action>-search',
    '<_controler:api>/<_action:(head|badges)>/<request>' => '<_controler>/user-<_action>',
    '<_controler:api>/<_action:[\w]+>/<_method:[\w]+>' => '<_controler>/<_action>-<_method>',

    // General
    '<_controler:[\w\-]+>' => '<_controler>/index',
    '<_controler:[\w\-]+>/<_action:[\w\-]+>' => '<_controler>/<_action>',
    '<_controler:[\w\-]+>/<_action:[\w\-]+>/<id:\d+>' => '<_controler>/<_action>',
    '<_module:[\w\-]+>/<_controler:[\w\-]+>/<_action:[\w\-]+>' => '<_module>/<_controler>/<_action>',
];
