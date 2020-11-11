<?php
/**
 * cookie类
 */
namespace app\common\cache;

use think\facade\Cookie;

class DoCookie{
    /**
     * cookie操作
     * @param string $work get/set/del/clear
     * @param string $key cookie的key值
     * @param string $value cookie的value值
     * @param int $expire cookie过期时间，默认3600
     * @return string/boolean 返回结果
     */
    public function doWork($work='get',$key='',$value='',$expire=3600){
        try{
            $result = true;
            switch ($work){
                case 'get':
                    $result = Cookie::get($key);
                    break;
                case 'set':
                    $result = Cookie::set($key,$value,$expire);
                    break;
                case 'del':
                    $result = Cookie::delete($key);
                    break;
                case 'clear':
                    $result = Cookie::clear();
                    break;
            }
            return $result;
        }catch(\Exception $e){
            $logData = [
                'work' => $work,
                'key' => $key,
                'value' => $value,
                '错误' => $e->getMessage()
            ];
            doRecordLog('Cookie失败', $logData,'cache');
            return false;
        }
    }
}