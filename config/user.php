<?php
/**
 * 用户自定义参数
 * User: Lenovo
 * Date: 2019/10/8
 * Time: 16:38
 */
use think\facade\Env;

return [
    'base' => [
        'runtime_path' => runtime_path(),
        'due_expire' => 3600,
        'cookie_select_name' => '94745b4901155f3b'
    ],
    'wx' => [
        'logo_domain' => Env::get('wx.logo_domain')
    ],
    'qcloud' => [
        'base' => [
            'bucket' => Env::get('qcloud.bucket'),
            'region' => Env::get('qcloud.region'),
            'secret_id' => Env::get('qcloud.secret_id'),
            'secret_key' => Env::get('qcloud.secret_key'),
            'domain' => Env::get('qcloud.domain')
        ]
    ],
    'login' => [
        'session_name' => 'cdd_manager',
        'cookie_name' => 'bdc33b90ea06ae8a',
        'expire' => [
            'customer' => 604800
        ]
    ]
];