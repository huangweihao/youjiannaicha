<?php
/**
 * 验证用户登录态和权限
 * Class CheckUser
 */
namespace app\middleware;
use app\common\LoginStatus;

class CheckUser{
    private $checkStatus = null;
    function __construct(){
        $this->checkStatus = new LoginStatus('admin');
    }
    public function handle($request, \Closure $next){
        $statusResult = $this->checkStatus->getLoginStatus();
        if ($statusResult['code'] != 200) {
            $request->loginUserId = '';
        }else{
            $request->loginUserId = $statusResult['data']['d'];
        }
        $request->UserOrShop = 'admin';
        return $next($request);
    }
}
