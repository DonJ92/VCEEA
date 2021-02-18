<?php
define('MALE', 1);
define('FEMALE', 2);

define('MORE', 1);
define('LESS', 2);

define('ACTIVE', 1);
define('INACTIVE', 2);

define('ADMIN_NORMAL', 1);
define('ADMIN_TOP', 2);

define('LOG_LOGIN', 1);
define('LOG_LOGOUT', 2);
define('LOG_PLAN_PAGE', 3);
define('LOG_FOLLOW_PAGE', 4);
define('LOG_SCORE_PAGE', 5);
define('LOG_AI_PAGE', 6);
define('LOG_SETTING_PAGE', 7);
define('LOG_SIMULATE_PAGE', 8);

return [
    'college_type' => [
        ['id' => 1, 'type' => '本科'],
        ['id' => 2, 'type' => '专科']
    ],

    'college_property' => [
        ['id' => 1, 'property' => '公办'],
        ['id' => 2, 'property' => '民办'],
        ['id' => 3, 'property' => '独立学院'],
    ],

    'log_type' => [
        ['id' => 1, 'type' => '登录'],
        ['id' => 2, 'type' => '退出'],
        ['id' => 3, 'type' => '招生计划查询'],
        ['id' => 4, 'type' => '关注例表'],
        ['id' => 5, 'type' => '往年录取成绩数据查询'],
        ['id' => 6, 'type' => '智能推荐'],
        ['id' => 7, 'type' => '个人设定'],
        ['id' => 8, 'type' => '模拟填报志愿及导出'],
    ],
];