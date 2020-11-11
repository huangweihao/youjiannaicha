<?php

// +----------------------------------------------------------------------
// | 缓存设置
// +----------------------------------------------------------------------
use think\facade\Env;

return [
    // 默认缓存驱动
    'default' => env('cache.driver', 'file'),

    // 缓存连接方式配置
    'stores'  => [
        'file' => [
            // 驱动方式
            'type'       => 'File',
            // 缓存保存目录
            'path'       => '',
            // 缓存前缀
            'prefix'     => '',
            // 缓存有效期 0表示永久缓存
            'expire'     => 0,
            // 缓存标签前缀
            'tag_prefix' => 'tag:',
            // 序列化机制 例如 ['serialize', 'unserialize']
            'serialize'  => [],
        ],
        // redis缓存
        'redis-config'   =>  [
            // 驱动方式
            'type'   => 'redis',
            // 服务器地址
            'host'   => Env::get('redis.host'),
            // 服务器端口
            'port'   => Env::get('redis.port'),
            // 缓存密码
            'password' => Env::get('redis.password'),
            // 缓存前缀
            'select' => 0,
            // 缓存前缀
            'prefix' => '',
            // 缓存有效期 0表示永久缓存
            'expire' => 0,
            // 序列化机制 例如 ['serialize', 'unserialize']
            'serialize'  => []
        ],
        // redis缓存
        'redis-base'   =>  [
            // 驱动方式
            'type'   => 'redis',
            // 服务器地址
            'host'   => Env::get('redis.host'),
            // 服务器端口
            'port'   => Env::get('redis.port'),
            // 缓存密码
            'password' => Env::get('redis.password'),
            // 缓存前缀
            'select' => 15,
            // 缓存前缀
            'prefix' => '',
            // 缓存有效期 0表示永久缓存
            'expire' => 0,
            // 序列化机制 例如 ['serialize', 'unserialize']
            'serialize'  => []
        ],
        // 更多的缓存连接
    ],
];
