<?php
/**
 * 缓存类
 */
namespace app\common;

use app\common\cache\DoRedis;
use app\common\cache\DoFile;
use app\common\cache\DoSession;
use app\common\cache\DoCookie;
class DoCache{
    /**
     * factory
     * @param string $type cookie/session/redis/file
     * @param array $data 数据
     * @return mixed 返回结果
     * @throws
     */
    public function doing($type='redis',$data=[]){
        switch ($type){
            case 'file':
                $fileIns = new DoFile();
                if(empty($data['data']))$data['data'] = '';
                if(empty($data['from']))$data['from'] = 'file';
                return $fileIns->doWork($data['from'],$data['work'],$data['key'],$data['data']);
                break;
            case 'cookie':
                $fileIns = new DoCookie();
                if(empty($data['data']))$data['data'] = '';
                if(empty($data['expire']))$data['expire'] = 0;
                return $fileIns->doWork($data['work'],$data['key'],$data['data'],$data['expire']);
                break;
            case 'session':
                $fileIns = new DoSession();
                if(empty($data['data']))$data['data'] = '';
                return $fileIns->doWork($data['work'],$data['key'],$data['data']);
                break;
            case 'redis':
                if(empty($data['key']))return false;
                $redisData = [
                    'from' => $data['from'],
                    'type' => 'hash',
                    'work' => $data['work'],
                    'data' => [
                        'key'=>$data['key']
                    ]
                ];
                switch ($data['work']){
                    case 'hset':
                    case 'hsetnx':
                        $redisData['data']['field'] = $data['field'];
                        $redisData['data']['value'] = $data['value'];
                        break;
                    case 'hmget':
                        $redisData['data']['value'] = $data['value'];
                        break;
                    case 'hmset':
                        $redisData['data']['value'] = $data['value'];
                        if(!empty($data['expire'])){
                            $redisData['data']['expire'] = $data['expire'];
                        }
                        break;
                    case 'del':
                        $redisData['type'] = 'del';
                        break;
                    case 'ttl':
                        $redisData['type'] = 'ttl';
                        break;
                    case 'expire':
                        $redisData['type'] = 'expire';
                        $redisData['data']['expire'] = $data['expire'];
                        break;
                }

                $redisIns = new DoRedis();
                return $redisIns->doWork($redisData['from'],$redisData['type'],$redisData['work'],$redisData['data']);
                break;
        }
    }
}