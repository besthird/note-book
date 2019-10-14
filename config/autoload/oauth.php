<?php

return [
    'wechat' => [
        'app_id' => env('WECHAT_APPID'),
        'secret' => env('WECHAT_SECRET'),

        // 下面为可选项
        // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
        'response_type' => 'array',

        'log' => [
            'level' => 'debug',
            'file' => BASE_PATH . '/runtime/logs/wechat.log',
        ],
    ],
];