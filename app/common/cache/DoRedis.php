<?php
/**
 * redis类
 */
namespace app\common\cache;

use think\facade\Cache;

class DoRedis{
    private $cacheRedisObject = [];
    private $cacheRedisIns = null;
    /**
     * 获取redis对象实例
     * @param $from
     */
    private function getRedisIns($from){
        try{
            if(empty($this->cacheRedisObject[$from])){
                $this->cacheRedisObject[$from] = Cache::store($from)->handler();
            }
            $this->cacheRedisIns = $this->cacheRedisObject[$from];
        }catch (\Exception $e){
            $logData = [
                '来自' => $from,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('getRedisIns',$logData);
        }
    }
    /**
     * redis操作
     * @param string $from  取的哪个redis实例
     * @param string $type
     * @param string $work get/set/del/clear
     * @param array $data 值
     * @return mixed 返回结果
     * @throws
     */
    public function doWork($from='',$type='string',$work='get',$data=[]){
        $this->getRedisIns($from);
        switch ($type) {
            case 'string':
                return $this->doString($work,$data);
                break;
            case 'list':
                return $this->doList($work,$data);
                break;
            case 'set':
                return $this->doSet($work,$data);
                break;
            case 'zset':
                return $this->doZSet($work,$data);
                break;
            case 'hash':
                return $this->doHash($work,$data);
                break;
            case 'clear':
                return $this->doClear();
                break;
            case 'exists':
                return $this->doExists($data);
                break;
            case 'ttl':
                return $this->doTTL($data);
                break;
            case 'expire':
                return $this->doExpire($data);
                break;
            case 'del':
                return $this->doDel($data);
                break;
        }
    }
    /**
     * 清除redis
     * @return mixed
     */
    private function doClear(){
        try{
            return $this->cacheRedisIns->clear();
        }catch (\Exception $e){
            $logData = [
                '错误' => $e->getMessage()
            ];
            $this->recordLog('doExists',$logData);
            return false;
        }
    }
    /**
     * 删除键
     * @param $data 操作的数据
     * @return mixed
     */
    private function doDel(&$data){
        try{
            return $this->cacheRedisIns->del($data['key']);
        }catch (\Exception $e){
            $logData = [
                '数据' => $data,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('doDel',$logData);
            return false;
        }
    }
    /**
     * 获取到期时间
     * @param $data 操作的数据
     * @return mixed
     */
    private function doTTL(&$data){
        try{
            return $this->cacheRedisIns->ttl($data['key']);
        }catch (\Exception $e){
            $logData = [
                '数据' => $data,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('doTTL',$logData);
            return false;
        }
    }
    /**
     * 设置过期
     * @param $data 操作的数据
     * @return mixed
     */
    private function doExpire(&$data){
        try{
            return $this->cacheRedisIns->expire($data['key'],$data['expire']);
        }catch (\Exception $e){
            $logData = [
                '数据' => $data,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('doExpire',$logData);
            return false;
        }
    }
    /**
     * 查找key是否存在
     * @param $data 操作的数据
     * @return mixed
     */
    private function doExists(&$data){
        try{
            return $this->cacheRedisIns->exists($data['key']);
        }catch (\Exception $e){
            $logData = [
                '数据' => $data,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('doExists',$logData);
            return false;
        }
    }
    /**
     * 字符串类型操作
     * @param $work 操作方式
     * @param $data 操作的数据
     * @return mixed|string
     */
    private function doString($work,&$data){
        try{
            $result = '';
            switch ($work){
                case 'get':
                    $result = $this->cacheRedisIns->get($data['key']);
                    $result = @json_decode($result, true);
                    break;
                case 'set':
                    $result = $this->cacheRedisIns->set($data['key'],json_encode($data['value']),$data['expire']);
                    break;
            }
            return $result;
        }catch (\Exception $e){
            $logData = [
                '操作' => $work,
                '数据' => $data,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('doString',$logData);
            return false;
        }
    }
    /**
     * LIST类型操作
     * @param $work 操作方式
     * @param $data 操作的数据
     * @return mixed|string
     */
    private function doList($work,&$data){
        try{
            $result = '';
            switch ($work){
                case 'lpush':
                    $result = $this->cacheRedisIns->lPush($data['key'],$data['value']);
                    break;
                case 'rpush':
                    $result = $this->cacheRedisIns->rPush($data['key'],$data['value']);
                    break;
                case 'lrange':
                    $result = $this->cacheRedisIns->lRange($data['key'],$data['begin'],$data['end']);
                    break;
                case 'lpop':
                    $result = $this->cacheRedisIns->lPop($data['key']);
                    break;
                case 'rpop':
                    $result = $this->cacheRedisIns->rPop($data['key']);
                    break;
                case 'llen':
                    $result = $this->cacheRedisIns->lLen($data['key']);
                    break;
            }
            return $result;
        }catch (\Exception $e){
            $logData = [
                '操作' => $work,
                '数据' => $data,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('doList',$logData);
            return false;
        }

    }
    /**
     * Set类型操作
     * @param $work 操作方式
     * @param $data 操作的数据
     * @return mixed|string
     */
    private function doSet($work,&$data){
        try{
            $result = '';
            switch ($work){
                case 'sadd':
                    $result = $this->cacheRedisIns->sAdd($data['key'],$data['value']);
                    break;
                case 'scard'://获取成员数
                    $result = $this->cacheRedisIns->scard($data['key']);
                    break;
                case 'sismember':
                    $result = $this->cacheRedisIns->sismember($data['key'],$data['value']);
                    break;
                case 'smembers':
                    $result = $this->cacheRedisIns->smembers($data['key']);
                    break;
                case 'srem':
                    $result = $this->cacheRedisIns->sRsem($data['key'],$data['value']);
                    break;
            }
            return $result;
        }catch (\Exception $e){
            $logData = [
                '操作' => $work,
                '数据' => $data,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('doSet',$logData);
            return false;
        }
    }
    /**
     * ZSet类型操作
     * @param $work 操作方式
     * @param $data 操作的数据
     * @return mixed|string
     */
    private function doZSet($work,&$data){
        try{
            $result = '';
            switch ($work){
                case 'zadd':
                    $result = $this->cacheRedisIns->zAdd($data['key'],$data['score'],$data['member']);
                    break;
                case 'zcard':
                    $result = $this->cacheRedisIns->zCard($data['key']);
                    break;
                case 'zincrby':
                    $result = $this->cacheRedisIns->zIncrBy($data['key'],$data['score'],$data['member']);
                    break;
                case 'zrem':
                    $result = $this->cacheRedisIns->zRem($data['key'],$data['member']);
                    break;
                case 'zscore':
                    $result = $this->cacheRedisIns->zScore($data['key'],$data['member']);
                    break;
                case 'zrange':
                    $result = $this->cacheRedisIns->zrange($data['key'],$data['begin'],$data['end'],true);
                    break;
                case 'zrevrange':
                    $result = $this->cacheRedisIns->zRevRange($data['key'],$data['begin'],$data['end'],true);
                    break;
                case 'zrangebyscore':
                    $result = $this->cacheRedisIns->zRangeByScore($data['key'],$data['begin'],$data['end'], ['withscores' => true, 'limit' => array($data['offset'], $data['count'])]);
                    break;
            }
            return $result;
        }catch (\Exception $e){
            $logData = [
                '操作' => $work,
                '数据' => $data,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('doZSet',$logData);
            return false;
        }
    }
    /**
     * Hash类型操作
     * @param $work 操作方式
     * @param $data 操作的数据
     * @return mixed|string
     */
    private function doHash($work,&$data){
        try{
            $result = '';
            switch ($work){
                case 'hset':
                    $result = $this->cacheRedisIns->hSet($data['key'],$data['field'],$data['value']);
                    break;
                case 'hget':
                    $result = $this->cacheRedisIns->hGet($data['key'],$data['value']);
                    break;
                case 'hgetall':
                    $result = $this->cacheRedisIns->hGetAll($data['key']);
                    break;
                case 'hdel':
                    $result = $this->cacheRedisIns->hDel($data['key'],$data['value']);
                    break;
                case 'hmset':
                    $result = $this->cacheRedisIns->hMset($data['key'],$data['value']);
                    if(!empty($data['expire'])){
                        $this->doExpire($data);
                    }
                    break;
                case 'hmget':
                    $result = $this->cacheRedisIns->hMget($data['key'],$data['value']);
                    break;
                case 'hsetnx':
                    $result = $this->cacheRedisIns->hSetNx($data['key'],$data['field'],$data['value']);
                    break;
            }
            return $result;
        }catch (\Exception $e){
            $logData = [
                '操作' => $work,
                '数据' => $data,
                '错误' => $e->getMessage()
            ];
            $this->recordLog('doHash',$logData);
            return false;
        }
    }
    /**
     * 记录日志
     * @param string $errName
     * @param array $logData
     */
    private function recordLog($errName,&$logData){
        doRecordLog($errName,$logData, "cache");
    }
}