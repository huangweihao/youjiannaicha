<?php
/**
 * 登录态验证类
 * Class LoginStatus
 * @package app\businessman\common
 * @remark session验证，如果不存在，cookie Token验证
 * @remark redis验证，默认一天过期
 */
namespace app\common;

use app\common\loginstatus\LoginStatusFile;

class LoginStatus{
    private $checkIns = null;
    private $accessToken = '';
    function __construct($from='admin'){
        switch ($from){
            case 'admin':
                $this->accessToken = (new DoCache())->doing('cookie',[
                    'work' => 'get',
                    'key' => config('user.login.cookie_name')
                ]);
                $this->checkIns = new LoginStatusFile($this->accessToken);
                break;
        }
    }
    /**
     * 验证当前登录态
     * @return array $code 状态值 $data用户数据
     */
    public function getLoginStatus(){
        return $this->checkIns->getLoginStatus();
    }
    /**
     * 清除当前登录态
     * @remark 根据cookie managerToken 刷新session managerKey，Token1天内有效
     * @return string|boolean
     */
    public function clearLoginStatus(){
        return $this->checkIns->clearLoginStatus();
    }
    /**
     * 注册登录态
     * @param string $accessToken
     * @param string $data
     * @remark 根据cookie managerToken 刷新session managerKey，Token1天内有效
     * @return string|boolean
     */
    public function setLoginStatus($accessToken,$data){
        return $this->checkIns->setLoginStatus($accessToken,$data);
    }
    /**
     * 注册登录缓存
     * @param string $accessToken
     * @param string $data
     * @remark 根据cookie managerToken 刷新session managerKey，Token1天内有效
     * @return string|boolean
     */
    public function setLoginCache($accessToken,$data){
        return $this->checkIns->setLoginCache($accessToken,$data);
    }
}