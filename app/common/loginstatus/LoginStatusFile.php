<?php
/**
 * 数据库登录态验证类
 * Class LoginStatusFile
 * @package app\common
 */
namespace app\common\loginstatus;

use app\common\DoCache;
use app\model\Manager as ManagerModel;

class LoginStatusFile{
    private $accessToken = '';
    private $cacheIns = null;
    function __construct($accessToken){
        $this->accessToken = $accessToken;
        $this->cacheIns = new DoCache();
    }
    /**
     * 验证当前登录态
     * @return array $code 状态值 $data用户数据
     */
    public function getLoginStatus(){
        if(empty($this->accessToken)){
            $sessionManagerId = '';
            $code = 401;
        }else{
            $sessionManagerId = $this->cacheIns->doing('session',[
                'work' => 'get',
                'key' => config('user.login.session_name')
            ]);
            if(empty($sessionManagerId)){
                $sessionManagerId = $this->refreshLoginStatus();
            }
            $code = 200;
        }
        return ['code'=>$code,'data'=>['d'=>$sessionManagerId]];
    }
    /**
     * 清除当前登录态
     * @remark 根据cookie managerToken 刷新session managerKey，Token1天内有效
     * @return string|boolean
     */
    public function clearLoginStatus(){
        if(!empty($this->accessToken)){
            try{
                $this->cacheIns->doing('session',[
                    'work' => 'del',
                    'key' => config('user.login.session_name')
                ]);
                $this->cacheIns->doing('cookie',[
                    'work' => 'del',
                    'key' => config('user.login.cookie_name')
                ]);
                return true;
            }catch(\Exception $e){
                $logData = [
                    'token' => $this->accessToken,
                    '错误' => $e->getMessage()
                ];
                $this->recordLog('[登录]登录态删除失败', $logData);
                return true;
            }
        }else{
            return true;
        }
    }
    /**
     * 延长登录态
     * @remark 根据cookie managerToken 刷新session managerKey，Token1天内有效
     * @return string|boolean
     */
    private function refreshLoginStatus(){
        if(!empty($this->accessToken)){
            try{
                $condition = ['token'=>['rule'=>'equal','val'=>$this->accessToken]];
                $result = (new ManagerModel())->getDetail('token',$condition);
                if(!empty($result)){
                    if($result['status'] == 1){
                        $this->cacheIns->doing('session',[
                            'key' => config('user.login.session_name'),
                            'work' => 'set',
                            'data' => $result['id']
                        ]);
                        return $result['id'];
                    }
                }
                return false;
            }catch(\Exception $e){
                $logData = [
                    'token' => $this->accessToken,
                    '错误' => $e->getMessage()
                ];
                $this->recordLog('[登录]登录态刷新失败', $logData);
                return false;
            }
        }else{
            return false;
        }
    }
    /**
     * 注册登录态
     * @param string $accessToken
     * @param array $data
     * @remark 根据cookie managerToken 刷新session managerKey，Token1天内有效
     * @return string|boolean
     */
    public function setLoginStatus($accessToken,$data){
        if(!empty($accessToken)){
            try{
                $backStatus = $this->cacheIns->doing('session',[
                    'key' => config('user.login.session_name'),
                    'work' => 'set',
                    'data' => $data['id']
                ]);
                if($backStatus){
                    $this->cacheIns->doing('cookie',[
                        'key' => config('user.login.cookie_name'),
                        'work' => 'set',
                        'data' => $data['token'],
                        'expire' => config('user.login.expire.customer'),
                    ]);
                    return true;
                }else{
                    return false;
                }
            }catch(\Exception $e){
                $logData = [
                    'token' => $accessToken,
                    '数据' => $data,
                    '错误' => $e->getMessage()
                ];
                $this->recordLog('[登录]登录态缓存失败', $logData);
                return false;
            }
        }else{
            return false;
        }
    }
    /**
     * 注册登录缓存信息
     * @param string $accessToken
     * @param array $data
     * @return string|boolean
     */
    public function setLoginCache($accessToken,&$data){
        if(!empty($accessToken)){
            try{
                $key = hash('md5',$accessToken);
                $backStatus = $this->cacheIns->doing('file',[
                    'key' => $key.'_data',
                    'work' => 'set',
                    'data' => json_encode($data['manager'])
                ]);
                $backStatus = $backStatus ? $this->cacheIns->doing('file',[
                    'key' => $key.'_menu',
                    'work' => 'set',
                    'data' => json_encode($data['menu'])
                ]) : false;
                return $backStatus;
            }catch(\Exception $e){
                $logData = [
                    'token' => $accessToken,
                    '数据' => $data,
                    '错误' => $e->getMessage()
                ];
                $this->recordLog('[登录]登录信息缓存失败', $logData);
                return false;
            }
        }else{
            return false;
        }
    }
    /**
     * 记录日志
     * @param string $errName
     * @param array $logData
     */
    private function recordLog($errName,&$logData){
        doRecordLog($errName,$logData, "login");
    }
}