<?php
/**
 * cookie类
 */
namespace app\common\cache;

use think\facade\Cache;

class DoFile{
    /**
     * file操作
     * @param string $from 使用配置文件的哪种缓存
     * @param string $work 操作方法get/set/del/clear
     * @param string $key 文件的key值
     * @param string $value 文件的value值
     * @return string/boolean 返回结果
     * @throws
     */
    public function doWork($from='',$work='get',$key='',$value=''){
        try{
            $result = false;
            switch ($work){
                case 'get':
                    $result = Cache::store($from)->get($key);
                    break;
                case 'set':
                    $result = Cache::store($from)->set($key,$value);
                    break;
                case 'del':
                    $result = Cache::store($from)->delete($key);
                    break;
                case 'clear':
                    $result = Cache::store($from)->clear();
                    break;
            }
            return $result;
        }catch(\Exception $e){
            $logData = [
                'from' => $from,
                'work' => $work,
                'key' => $key,
                'value' => $value,
                '错误' => $e->getMessage()
            ];
            doRecordLog('文件缓存失败', $logData,'cache');
            return false;
        }
    }
}