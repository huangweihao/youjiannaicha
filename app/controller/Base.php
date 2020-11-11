<?php
/**
 * 基础入口类.
 * Class Base
 * @package app\admin\controller
 */
namespace app\controller;

use app\common\DoCache;
use think\facade\View;
use think\facade\Validate;
use app\common\Auth;
use app\common\Page;
use app\common\DoRequest;
use think\facade\Cache;
class Base{
    protected $initManager = ['id'=>'','name'=>'','power'=>'','city_id'=>''];
    protected $initModule = ['module'=>'','controller'=>'','action'=>'','name'=>'','power'=>''];
    protected $page = 1;
    protected $pageSize = 20;
    protected $pageSizeLayer = 10;
    protected $requestIns = null;
    protected $cacheIns = null;
    protected $cacheFileIns = null;
    function __construct(){
        $this->requestIns = new DoRequest();
        $this->cacheIns = Cache::store('redis-base');
        $this->cacheFileIns = new DoCache();
        $module = $this->requestIns->getControllerMethod();
        $this->initModule['controller'] = $module[0];
        $this->initModule['action'] = $module[1];
        if(!in_array($this->initModule['controller'],['Login','Msg','City'])){
            $checkType = $this->requestIns->getRequest('middleware','UserOrShop');
            switch ($checkType){
                case 'admin':
                    $loginUserId = $this->requestIns->getRequest('middleware','loginUserId');
                    if(empty($loginUserId)){
                        $this->doResponse(401);
                    }else{
                        $this->initManager['id'] = $loginUserId;
                        $this->initManager['city_id'] = 310100;
                    }
                    break;
                default:
                    $this->doResponse(413);
                    break;
            }
            $this->getAuth();
        }
    }
    /**
     * 获取权限
     */
    private function getAuth(){
        switch($this->initModule['controller']){
            case 'ShopMain':
                $checkController = 'Shop';
                break;
            case 'PoolMain':
                $checkController = 'ShopPool';
                break;
            case 'UserMain':
                $checkController = 'User';
                break;
            case 'LuckyMain':
                $checkController = 'ShopLucky';
                break;
            default:
                if(in_array($this->initModule['controller'],['Recommend','Pool']) && in_array($this->initModule['action'],['getApply'])){
                    switch ($this->initModule['controller']){
                        case 'Recommend':
                            $checkController = 'LuckyApply';
                            break;
                        case 'Pool':
                            $checkController = 'PoolApply';
                            break;
                    }

                }else{
                    $checkController = $this->initModule['controller'];
                }
                break;
        }
        if(!in_array($this->initModule['controller'],['Upload','Frame','Main','Pwd','LoginLog','Map'])){
            $cacheManager = $this->cacheFileIns->doing('file',[
                'work' => 'get',
                'key' => hash('md5',$this->initManager['id']).'_data'
            ]);
            if(empty($cacheManager)){
                $this->doResponse(413);
            }else{
                $cacheManager = @json_decode($cacheManager,true);
                if(empty($cacheManager)){
                    $this->doResponse(413);
                }else{
                    $curPower = $this->authCheck($cacheManager['power'],strtolower($checkController));
                    if(empty($curPower['isHasPower'])){
                        $this->doResponse(707);
                    }else{
                        $this->initManager['name'] = $cacheManager['name'];
                        $this->initModule['name'] = $curPower['columnName'];
                        $this->initModule['power'] = $curPower['columnPower'];
                    }
                }
            }
        }
        if(in_array($this->initModule['action'],['getList','getApply'])){
            $this->queryCommon();
            $this->pageSizeCheck();
        }else{
            if(in_array($this->initModule['controller'],['ShopUser','ShopLucky','ShopPool','ShopJoin','ShopGift','UserJoin','UserGift','UserAddress','LuckyMain','PoolMain'])){
                $this->queryCommon();
            }
        }
    }
    /**
     * 获取系统需要的初始化值
     * @param string key 初始化值的key
     * @return string 返回key的值
     */
    public function init($key=''){
        $initData = [
            'imgUrl' => '',
            'elementUrl' => '/static/admin/',
            'version' => '1.0.0',
            'pageSize' => [15,20,30,50,80,100],
            'ip' => request()->ip(),
            'time' => time(),
            'today' => strtotime(date('Y-m-d'))
        ];
        return empty($initData[$key]) ? '' : $initData[$key];
    }
    /**
     * 获取当前登录态的TOKEN
     * @return string 返回key的值
     */
    public function getLoginToken(){
        return $this->cacheFileIns->doing('cookie',[
            'work' => 'get',
            'key' => config('user.login.cookie_name')
        ]);
    }
    /**
     * 请求返回是否ajax
     * @return mixed
     */
    public function doResponseIsAjax(){
        return $this->requestIns->getIsAjax();
    }
    /**
     * 请求返回
     * @param string $code
     * @param string $msg
     * @param array $data
     * @return mixed
     */
    public function doResponse($code='',$msg='',$data=[]){
        if($this->doResponseIsAjax()){
            return doResponse($code,$msg,$data);
        }else{
            $url = $this->urlGenerate('message',['url'=>$data['backUrl']??'','msg'=>$msg,'code'=>$code]);
            return doRedirect($url);
        }
    }
    /**
     * 获取传入参数
     * @param string $type 类型
     * @param string $key 参数key值
     * @param string $default 默认值
     * @return 获得结果
     */
    public function getRequestParams($type='post',$key='',$default=''){
        return $this->requestIns->getRequest($type,$key,$default);
    }
    /**
     * 验证传入参数
     * @param array rule 验证规则
     * @param array params 验证数据
     * @return array 验证结果
     */
    public function doValidate(&$rule,&$params){
        $validate = Validate::rule($rule);
        if(!$validate->check($params)){
            return ['code'=>403,'msg'=>$validate->getError()];
        }else{
            return ['code'=>200,'data'=>$params];
        }
    }
    /**
     * 获取并验证公用Querystring值
     * @remark 根据Querystring给全局对象赋值 ,s开头代表search，v开头代表view
     */
    protected function queryCommon(){
        $this->page = $this->getRequestParams('get','page');
        $this->page = (!empty($this->page) && ctype_digit($this->page))? $this->page:1;
        $this->sparent = $this->getRequestParams('get','sparent');
        $this->sshop = $this->getRequestParams('get','sshop');
        $this->suser = $this->getRequestParams('get','suser');
        $this->slucky = $this->getRequestParams('get','slucky');
        $this->spool = $this->getRequestParams('get','spool');
        $this->sword = $this->getRequestParams('get','sword');
        $this->sgroup = $this->getRequestParams('get','sgroup');
        $this->srank = $this->getRequestParams('get','srank');
        $this->vkey = $this->getRequestParams('get','vkey');
    }
    /**
     * 重组query值
     * @param $expect
     * @return array
     */
    protected function queryInit($expect=[]){
        $queryData = $this->requestIns->getRequest('get');
        if(!empty($queryData)){
            foreach ($queryData as $key=>$value){
                if(empty($value) || in_array($key,$expect)){
                    unset($queryData[$key]);
                }
            }
        }
        return $queryData;
    }
    /**
     * 操作权限验证
     * @param array $power
     * @param string $controller
     * @return mixed
     */
    private function authCheck(&$power,$controller=''){
        $controller = empty($controller) ? $this->initModule['controller'] : $controller;
        return (new Auth())->checkPower($controller, strtolower($this->initModule['action']),$power);
    }
    /**
     * 页面模板输出
     * @param string $tpl 模板位置
     * @param array $assignData 需要传入模板的值
     * @return string 模板内容
     * @throws
     */
    public function doRender($tpl,$assignData=[]){
        $columnName = $this->initModule['name'];
        switch ($this->initModule['action']){
            case 'i':
                $columnName .= '添加';
                break;
            case 'e':
                $columnName .= '编辑';
                break;
        }
        $data = [
            'webName' => '社教系统管理后台',
            'moduleUrl' => '/',
            'elementPath' => $this->init('elementUrl'),
            'version' => $this->init('version'),
            'columnController' => $this->initModule['controller'],
            'columnAction' => $this->initModule['action'],
            'columnName' => $columnName,
            'columnPower' => $this->initModule['power'],
            'uploadUrl' => $this->urlGenerate('module'),
        ];
        if(!empty($assignData)){
            $data = $data+$assignData;
        }
        return View::fetch($tpl,$data);
    }
    /**
     * URL生成
     * @param string type 生成的类型
     * @param array data Url的补充数据 [key=>value]
     * @return string 返回生成后的url
     */
    public function urlGenerate($type='login',$data=[],$controllerInit=''){
        $url = '';
        $queryInit = [];
        $openType = 'big';
        $isOpenUrl = false;
        $typeSplit = explode('-',$type);
        $typeSplitCount = count($typeSplit);
        if($typeSplitCount > 1){
            $isOpenUrl = $typeSplit[0] == 'open' ? true : false;
            switch ($typeSplitCount){
                case '2':
                    $type = $typeSplit[1];
                    break;
                case '3':
                    $openType = $typeSplit[1];
                    $type = $typeSplit[2];
                    break;
            }
        }
        $controller = strtolower($this->initModule['controller']);
        $action = strtolower($this->initModule['action']);
        switch ($type){
            case 'frame':
                $url = 'frame';
                break;
            case 'login':
                $url = 'login';
                break;
            case 'logout':
                $url = 'login/logout';
                break;
            case 'map':
                $url = 'map';
                break;
            case 'message':
                $url = 'msg';
                break;
            case 'pageSet':
                $url = 'pageSet';
                break;
            case 'scheduleList':
                $url = 'schedule';
                break;
            case 'shopPool':
                $url = $type;
                break;
            case 'choosePool':
                $url = 'shoppool/choose';
                break;
            case 'chooseUser':
                $url = 'user/choose';
                break;
            case 'recordUrl':
                if(!empty($controllerInit)){
                    $url = $controllerInit.'/record';
                }else{
                    $url = $controller.'/record';
                }
                break;
            case 'view':
                if (!empty($controllerInit)) {
                    $url = $controllerInit;
                }else{
                    $queryInit = $this->queryInit(['key']);
                    $url = $controller;
                }
                break;
            case 'page':
                $queryInit = $this->queryInit(['page']);
                $url = $controller;
                break;
            case 'search':
                $queryInit = $this->queryInit(['keyword']);
                $url = $controller;
                break;
            case 'searchChoose':
                $queryInit = $this->queryInit(['keyword']);
                $url = $controller.'/'.$action;
                break;
            case 'insert':
                $queryInit = $this->queryInit();
                $url = $controller.'/i';
                break;
            case 'delete':
                if(!empty($controllerInit)){
                    $url = $controllerInit.'/d';
                }else{
                    $queryInit = $this->queryInit(['key']);
                    $url = $controller.'/d';
                }
                break;
            case 'edit':
                if(!empty($controllerInit)){
                    $url = $controllerInit.'/e';
                }else{
                    $queryInit = $this->queryInit(['key']);
                    $url = $controller.'/e';
                }
                break;
            case 'schedule':
                if(!empty($controllerInit)){
                    $url = $controllerInit.'/schedule';
                }else{
                    $queryInit = $this->queryInit(['key']);
                    $url = $controller.'/schedule';
                }
                break;
            case 'show':
                if(!empty($controllerInit)){
                    $url = $controllerInit.'/show';
                }else{
                    $queryInit = $this->queryInit(['key']);
                    $url = $controller.'/show';
                }
                break;
            case 'examine':
                $queryInit = $this->queryInit(['key']);
                $url = $controller.'/examine';
                break;
            case 'rewardup':
                $queryInit = $this->queryInit(['key']);
                $url = $controller.'/rewardup';
                break;
            case 'config':
                $queryInit = $this->queryInit(['key']);
                $url = $controller.'/c';
                break;
            case 'doConfig':
                $queryInit = $this->queryInit();
                $url = $controller.'/doc';
                break;
            case 'doUpdate':
                $queryInit = $this->queryInit();
                $url = $controller.'/dou';
                break;
            case 'doInsert':
                if (!empty($controllerInit)) {
                    $url = $controllerInit.'/doi';
                }else{
                    $queryInit = $this->queryInit();
                    $url = $controller.'/doi';
                }
                break;
            case 'doEdit':
                $queryInit = $this->queryInit();
                $url = $controller.'/doe';
                break;
            case 'doDelete':
                $queryInit = $this->queryInit();
                $url = $controller.'/dod';
                break;
            case 'logUrl':
                $url = 'log';
                break;
            case 'excel':
                $url = 'excel';
                break;
        }
        if(!empty($queryInit)){
            $data = array_merge ($data,$queryInit);
        }
        if(!empty($data)){
            $data = http_build_query($data);
            $url .= '?'.$data;
        }
        switch ($type){
            case 'page':
            case 'edit':
            case 'examine':
            case 'rewardup':
            case 'schedule':
            case 'config':
            case 'delete':
                if(!strpos($url,'?')){
                    $url .= '?';
                }else{
                    $url .= '&';
                }
                switch ($type){
                    case 'page':
                        $url .= 'page=';
                        break;
                    case 'edit':
                    case 'examine':
                    case 'rewardup':
                    case 'schedule':
                    case 'delete':
                    case 'config':
                        $url .= 'key=';
                        break;
                }
                break;
        }
        $url = '/'.$url;
        if($isOpenUrl){
            switch ($openType){
                case 'big':
                    $url = $isOpenUrl ? 'javascript:void(0);" onclick="TB_Common._OpenBig(this,\''.$url.'\')' : $url;
                    break;
            }
        }
        return $url;
    }
    /**
     * 获取公用Querystring值
     * @remark 根据Querystring给全局对象赋值
     */
    protected function pageSizeCheck(){
        $this->pageSize = $this->cacheFileIns->doing('cookie',[
            'work' => 'get',
            'key' => config('user.base.cookie_select_name')
        ]);
        $this->pageSize = (!empty($this->pageSize) && ctype_digit($this->pageSize))? $this->pageSize:$this->pageSize=15;
        $this->pageSize = in_array($this->pageSize,$this->init('pageSize')) ? $this->pageSize : 15;
    }
    /**
     * 数据分页
     * @param int total 数据总量
     * @param int pagesize 每页显示条数
     * @param int page 当前页码
     * @param string type 页码样式 all/simple
     * @param string url 分页链接
     * @return string 页码html
     */
    public function pagination($total,$pageSize=0,$page=0,$type='all',$url=''){
        if(empty($url))$url = $this->urlGenerate('page');
        if(empty($pageSize))$pageSize = $this->pageSize;
        if(empty($page))$page = $this->page;
        return (new Page())->pageShow($pageSize,$total,$page,5,$url,$type,$this->init('pageSize'),$this->urlGenerate('pageSet'));
    }
    /**
     * 记录日志
     * @param string $content
     * @return mixed
     */
    public function workLogSet($content){
        return (new ManagerWorkLog())->doRecord($content);
    }
    /**
     * 从redis获取数据
     * @param string $type
     * @param array $data
     * @return mixed
     */
    protected function doEasyRedis($type, $data=[]){
        $backData=[];
        $key = $data['key'];
        $val = $data['val']??'';
        $expire = $data['expire']??0;
        switch ($type) {
            case 'get':
                $backData = $this->cacheIns->get($key);
                break;
            case 'set':
                $backData = $this->cacheIns->set($key, $val, $expire);
                break;
            case 'inc':
                $step = $data['step']??1;
                $backData = $this->cacheIns->inc($key, $step);
                break;
            case 'del':
                $backData = $this->cacheIns->del($key);
                break;
        }
        return $backData;
    }
}
