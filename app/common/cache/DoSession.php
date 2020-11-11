<?php
/**
 * session类
 */
namespace app\common\cache;

use think\facade\Session;

class DoSession{
    /**
     * session操作
     * @param string $work get/set/del/clear
     * @param string $key session的key值
     * @param string $value session的value值
     * @return string/boolean 返回结果
     */
    public function doWork($work='get',$key='',$value=''){
        try{
            $result = true;
            switch ($work){
                case 'get':
                    $result = Session::get($key);
                    break;
                case 'set':
                    Session::set($key,$value);
                    break;
                case 'del':
                    Session::delete($key);
                    break;
                case 'clear':
                    Session::clear();
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
            doRecordLog('session失败', $logData,'cache');
            return false;
        }
    }
}