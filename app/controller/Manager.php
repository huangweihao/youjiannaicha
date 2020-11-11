<?php
/**
 * 管理员管理
 * Class Manager
 * @package app\controller
 */
namespace app\controller;

use app\model\Manager as ManagerModel;
use app\controller\common\CommonWork;

class Manager extends Base{
    private $currentControlIns = null;
    function __construct(){
        parent::__construct();
        $this->currentControlIns = new ManagerModel();
    }
    /**
     * 页面显示
     * @remark manager/index
     */
    public function getList(){
        $pageHtml = '';
        $condition = [];
        if(!empty($this->sword)){
            $condition['name'] = ['rule'=>'like','val'=>$this->sword];
        }
        if(!empty($this->sgroup)){
            $condition['role_id'] = ['rule'=>'equal','val'=>$this->sgroup];
        }
        $order = ['id'=>'desc'];
        $result = $this->currentControlIns->getListPage('list',$condition,$order,$this->page,$this->pageSize);
        if($result['total'] > 0){
            $pageHtml = $this->pagination($result['total']);
        }
        $roleData = $this->getRole();

        $assignData = [
            'searchWord'=>$this->sword,
            'roleData'=>$roleData,
            'roleSelectStr'=>$this->roleSelectHtml('sgroup',$roleData,$this->sgroup),
            'data'=>$result,
            'pageHtml'=>$pageHtml,
            'logOpenUrl'=>$this->urlGenerate('open-logUrl',['vkey'=>'']),
            'searchUrl'=>$this->urlGenerate('search'),
            'insertUrl'=>$this->urlGenerate('insert'),
            'editUrl'=> $this->urlGenerate('edit'),
            'workBtn'=>getWorkBtn($this->initModule['power'],$this->urlGenerate('doUpdate'),$this->urlGenerate('doDelete')),
        ];
        return $this->doRender('list',$assignData);
    }
    /**
     * 获取角色
     * @remark 公用方法处理
     */
    private function getRole(){
        $backData = [];
        $result = $this->cacheFileIns->doing('file',[
            'work' => 'get',
            'key' =>'role'
        ]);
        if(!empty($result)){
            $result = @json_decode($result,true);
            if(!empty($result)) {
                foreach ($result as $key => $value) {
                    $backData[$value['id']] = $value['name'];
                }
            }
        }
        return $backData;
    }
    /**
     * 获取角色下拉选择
     * @remark 公用方法处理
     */
    private function roleSelectHtml($selectName,$data=[],$selected=0){
        $data[0] = '选择权限';
        return htmlSelect($selectName,$data,$selected,'请选择权限组');
    }
    /**
     * 添加入口
     * @remark 公用方法处理
     */
    public function i(){
        $data = ['id'=>'','name'=>'','role_id'=>'','status'=>''];
        return $this->work($data,$this->urlGenerate('doInsert'));
    }
    /**
     * 编辑入口
     * @remark 验证key是否为数字，成功后提交公用方法处理
     */
    public function e(){
        $result = (new CommonWork($this,$this->currentControlIns))->checkEdit();
        if(!empty($result)){
            return $this->work($result,$this->urlGenerate('doEdit'));
        }else{
            return $this->doResponse(710);
        }
    }
    /**
     * 添加编辑页面
     * @param  array data 页面显示的数据
     * @param string url ajax请求的地址
     * @return manager/work
     */
    private function work(&$data,$url){
        $roleData = $this->getRole();
        $assignData = [
            'roleData'=>$roleData,
            'roleSelectStr'=>$this->roleSelectHtml('role',$roleData,$data['role_id']),
            'data'=>$data,
            'viewUrl'=>$this->urlGenerate('view'),
            'workUrl'=>$url
        ];
        return $this->doRender('work',$assignData);
    }
    /**
     * 添加权限入库入口
     * @remark 公用方法处理
     */
    public function doi(){
        return $this->doWork('insert');
    }
    /**
     * 编辑权限入库入口
     * @remark 验证key是否为数字，成功后提交公用方法处理
     */
    public function doe(){
        return $this->doWork('edit');
    }
    /**
     * 权限入库公用方法
     * @param int key
     * @return array [code,data]
     * @throws
     */
    private function doWork($type){
        $check_result = $this->checkInParams();
        if($check_result['code'] == 200){
            $params = $check_result['data'];
        }else{
            return $this->doResponse($check_result['code'],$check_result['msg']);
        }
        $code = 200;
        $backData = [];
        $data = [
            'name'=>$params['username'],
            'role_id'=>$params['role'],
            'status'=>$params['status'],
            'utime'=>$this->init('time')
        ];
        if(!empty($params['pwd'])){
            $data['password'] = hash('md5',$params['pwd']);
        }
        switch ($type){
            case 'insert':
                $data['ctime'] = $this->init('time');
                $result = $this->currentControlIns->inData($data);
                if(!empty($result)){
                    $params['key'] = $result;
                }
                break;
            case 'edit':
                if(empty($params['key'])){
                    $code = 711;
                }else{
                    $condition = ['id'=>['rule'=>'equal','val'=>$params['key']]];
                    $result = $this->currentControlIns->upData($condition,$data);
                }
                break;
        }
        if(isset($result) ){
            if(empty($result)){
                $code = 201;
            }
            if($code == 200){
                $this->setCache();
                $this->workLogSet($params['key'].' | '.$params['username']);
            }
            $backData['workUrl'] = $this->urlGenerate('edit').$params['key'];
            $backData['backUrl'] = $this->urlGenerate('view');
        }else{
            $code = 713;
        }
        return $this->doResponse($code,'',$backData);
    }
    /**
     * 验证参数
     * @return array 验证结果
     */
    private function checkInParams(){
        $rule = [
            'username' => 'require|length:2,30',
            'pwd' => 'length:6,30',
            'role' => 'require|number|between:1,10000',
            'status' => 'require|number|between:0,1',
            'key' => 'number|between:1,1000000'
        ];
        $params = [
            'username' => $this->getRequestParams('post','username'),
            'pwd' => $this->getRequestParams('post','pwd'),
            'role' => $this->getRequestParams('post','role'),
            'status' => $this->getRequestParams('post','status'),
            'key' => $this->getRequestParams('post','key')
        ];
        return $this->doValidate($rule,$params);
    }
    /**
     * 更新
     * @param string work normal/lock
     * @param string keys
     * @return array [code,data]
     */
    public function dou(){
        return (new CommonWork($this,$this->currentControlIns))->doUpdate(true);
    }
    /**
     * 管理员删除
     * @param string keys
     * @return array [code,data]
     */
    public function dod(){
        return (new CommonWork($this,$this->currentControlIns))->doDelete(true);
    }
    /**
     * 生成缓存
     * @remark 缓存角色数据
     * @return boolean
     */
    private function setCache(){
        $data = [];
        $result = $this->currentControlIns->getList('cache');
        if(!empty($result)){
            foreach($result as $key=>$value){
                $data[$value['id']] = $value;
            }
        }
        $this->cacheFileIns->doing('file',[
            'key' => 'manager',json_encode($data),
            'work' => 'set'
        ]);
    }
}
