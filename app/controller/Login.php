<?php
/**
 * 管理员登录
 * Class Login
 * @package app\controller
 */
namespace app\controller;

use app\common\LoginStatus;
use app\common\Auth;
use app\model\Manager as ManagerModel;
use app\model\Role as RoleModel;
use app\model\LoginLog as LoginLogModel;

class Login extends Base{
    private $LoginStatusIns = null;
    function __construct(){
        parent::__construct();
        $this->LoginStatusIns = new LoginStatus();
    }
    /**
     * 登录页面显示
     * @remark 模板login/index
     */
    public function show(){
        return $this->doRender('show',['loginUrl'=>$this->urlGenerate('login')]);
    }
    /**
     * 验证管理员登录
     * @param string username 4-30位字符串
     * @param string password 6-30位字符串
     * @remark ajax post请求
     * @return array [code=>'',msg=>''.data=>[url=>'']]
     */
    public function doLogin(){
        $check_result = $this->checkLoginParams();
        if($check_result['code'] == 200){
            $params = $check_result['data'];
        }else{
            return $this->doResponse($check_result['code'],$check_result['msg']);
        }
        $code = 200;
        $data = [];
        $username = $params['username'];
        $password = $params['password'];

        if(!(!empty($username) && strlen($username)>4 && strlen($username)<30)){
            $code = 701;
        }
        if($code == 200 && !(!empty($password) && strlen($password)>5 && strlen($password)<30)){
            $code = 702;
        }
        if($code == 200){
            $condition = ['name'=>['rule'=>'equal','val'=>$username]];
            $managerModel = new ManagerModel();
            $result = $managerModel->getDetail('login',$condition);
            if(empty($result)){
                $code = 604;
            }else{
                if($result['status'] == 1){
                    $password = hash('md5',$password);
                    if(strcmp($result['password'],$password) == 0){
                        $roleData = $this->getRolePower($result['role_id']);
                        if($roleData['code'] == 200){
                            $menuData = $this->getMenu($roleData['power']);
                            $menuData['name'] = $username;
                            if($this->setCache($result['id'],['name'=>$username,'power'=>$roleData['power']],$menuData)){
                                $token = hash('md5',$result['id'].'_'.$this->init('ip').'_'.$this->init('time').'_'.mt_rand(10000,99999));
                                if($this->setLoginStatus($result['id'],$token)){
                                    if($managerModel->upData(['id'=>['rule'=>'equal','val'=>$result['id']]],['token'=>$token,'last_time'=>$this->init('time'),'last_ip'=>ip2long($this->init('ip'))])){
                                        $data = ['url'=>$this->urlGenerate('frame')];
                                        $this->setLoginRecord($result['id']);
                                    }else{
                                        $code = 705;
                                    }
                                }else{
                                    $code = 706;
                                }
                            }else{
                                $code = 704;
                            }
                        }else{
                            $code = $roleData['code'];
                        }
                    }else{
                        $code = 703;
                    }
                }else{
                    $code = 605;
                }
            }
        }
        return doResponse($code,'',$data);
    }
    /**
     * 验证参数
     * @return array 验证结果
     */
    private function checkLoginParams(){
        $rule = [
            'username' => 'require|length:5,30',
            'password' => 'require|length:5,30'
        ];
        $params = [
            'username' => $this->getRequestParams('post','username'),
            'password' => $this->getRequestParams('post','password')
        ];
        return $this->doValidate($rule,$params);
    }
    /**
     * 获取登录用户角色权限
     * @param int roleId 权限组ID
     * @return array [code=>'',power=>[]]
     */
    private function getRolePower($roleId=0){
        $code = 200;
        $powerData = '';
        $condition = ['id'=>['rule'=>'equal','val'=>$roleId]];
        $result = (new RoleModel())->getDetail('login',$condition);
        if(!empty($result)){
            if($result['status'] == 1){
                $powerData = !empty($result['power']) ? explode(',',$result['power']) : [];
            }else{
                $code = 615;
            }
        }else{
            $code = 614;
        }
        return ['code'=>$code,'power'=>$powerData];
    }
    /**
     * 生成用户所使用的菜单
     * @param array $power 所属权限组的power
     * @return mixed 缓存是否成功
     */
    private function getMenu($power=[]){
        $menuInit = (new Auth())->menuInit();
        $menuParentStr = '';
        $menuChildStr = '';
        foreach($menuInit as $parentKey=>$parentData){
            if(in_array($parentData['mark'].'w1',$power) > 0){
                $menuParentStr .= '<li class="sidebar-nav-link"><a href="javascript:void(0)" data-i="'.$parentData['mark'].'"><i class="iconfont sidebar-nav-link-logo '.$parentData['icon'].'"></i>'.$parentData['name'].'</a></li>';
                if(!empty($parentData['child'])){
                    $menuChildStr .= '<div id="second_'.$parentData['mark'].'" style="display:none">';
                    $menuChildStr .= '<li class="sidebar-second-title">'.$parentData['name'].'</li>';
                    $menuChildStr .= '<li class="sidebar-second-item">';
                    foreach($parentData['child'] as $childKey=>$childData){
                        if(in_array($childData['mark'].'w1',$power) > 0 && $childData['show']){
                            $menuChildStr .= ' <a href="'.$this->urlGenerate('module').$childKey.'" target="main">'.$childData['name'].'</a>';
                        }
                    }
                    $menuChildStr .= '</li></div>';
                }
            }
        }
        return ['parent'=>$menuParentStr,'child'=>$menuChildStr];
    }
    /**
     * 缓存用户相关数据
     * @param int id 当前的用户ID
     * @param array manager 用户的相关信息[name=>'',power=>'']
     * @param array menu 菜单的相关信息[name=>'',power=>'']
     * @return boolean 缓存是否成功
     */
    private function setCache($id,$manager,$menu){
        $data = ['manager'=>$manager,'menu'=>$menu];
        return $this->LoginStatusIns->setLoginCache($id,$data);
    }
    /**
     * 设置用户登录态
     * @param int id 当前的用户ID
     * @param string $token 当次登录生成的token
     * @return boolean 缓存是否成功
     */
    private function setLoginStatus($id=0,$token=''){
        return $this->LoginStatusIns->setLoginStatus($id,['id'=>$id,'token'=>$token]);
    }
    /**
     * 记录用户登录日志
     * @param int id 当前的用户ID
     * @return boolean 缓存是否成功
     */
    private function setLoginRecord($managerId){
        $data = ['manager_id'=>$managerId,'ip'=>ip2long($this->init('ip')),'ctime'=>$this->init('time')];
        return (new LoginLogModel())->inData($data);
    }
    /**
     * 清除当前登录态
     * @remark 根据cookie managerToken 刷新session managerKey，Token1天内有效
     * @return mixed
     */
    public function doLoginOut(){
        (new LoginStatus())->clearLoginStatus();
        return $this->doRender('logout',['loginUrl'=>$this->urlGenerate('login')]);
    }
}
