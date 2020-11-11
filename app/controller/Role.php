<?php
/**
 * 权限管理
 * Class Role
 * @package app\controller
 */
namespace app\controller;

use app\model\Role as RoleModel;
use app\common\Auth;
use app\controller\common\CommonWork;

class Role extends Base{
    private $currentControlIns = null;
    function __construct(){
        parent::__construct();
        $this->currentControlIns = new RoleModel();
    }
    /**
     * 权限页面显示
     * @remark role/index
     */
    public function getList(){
        $pageHtml = '';
        $condition = [];
        if(!empty($this->sword)){
            $condition['name'] = ['rule'=>'like','val'=>$this->sword];
        }
        $order = ['id'=>'desc'];
        $result = $this->currentControlIns->getListPage('list',$condition,$order,$this->page,$this->pageSize);
        if($result['total'] > 0){
            $pageHtml = $this->pagination($result['total']);
        }
        $assignData = [
            'searchWord'=>$this->sword,
            'data'=>$result,
            'pageHtml'=>$pageHtml,
            'searchUrl'=>$this->urlGenerate('search'),
            'insertUrl'=>$this->urlGenerate('insert'),
            'editUrl'=> $this->urlGenerate('edit'),
            'workBtn'=>getWorkBtn($this->initModule['power'],$this->urlGenerate('doUpdate'),$this->urlGenerate('doDelete')),
        ];
        return $this->doRender('list',$assignData);
    }
    /**
     * 添加权限
     * @remark 公用方法处理
     */
    public function i(){
        $data = ['id'=>'','name'=>'','power'=>'','status'=>''];
        return $this->work($data,$this->urlGenerate('doInsert'));
    }
    /**
     * 编辑权限
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
     * 添加编辑权限页面
     * @param  array data 页面显示的数据
     * @param string url ajax请求的地址
     * @return role/work
     */
    private function work(&$data,$url){
        $assignData = [
            'htmlPower' => $this->htmlPowerChoose($data['power']),
            'viewUrl' => $this->urlGenerate('view'),
            'workUrl' => $url,
            'data' => $data
        ];
        return $this->doRender('work',$assignData);
    }
    /**
     * 权限选择初始化
     * @param string power 当前用户的权限
     * @return 权限选择的HTML
     */
    private function htmlPowerChoose($power){
        $power = empty($power) ? [] : explode(',',$power);
        $powerStr = '';
        $menuInit = (new Auth())->menuInit();
        foreach($menuInit as $parentKey=>$parentData){
            $powerStr .= $this->htmlPowerItem($power,$parentData);
            if(!empty($parentData['child'])){
                foreach($parentData['child'] as $childKey=>$childData){
                    $powerStr .= $this->htmlPowerItem($power,$childData);
                }
            }
        }
        return $powerStr;
    }
    /**
     * 栏目权限选择单条封装
     * @param array power 当前用户的权限
     * @param array itemPowerValue 当前栏目的权限
     * @return 栏目权限选择的HTML
     */
    private function htmlPowerItem(&$power,$itemPowerValue){
        $powerStr = '';
        $powerStr .= '<div class="am-form-group">';
        $powerStr .= '<label class="am-u-sm-2 am-form-label">';
        if(empty($itemPowerValue['child'])){
            $powerStr .= '------';
        }
        $powerStr .= $itemPowerValue['name'].'</label>';
        foreach($itemPowerValue['power'] as $value){
            $curPower = $itemPowerValue['mark'].'w'.$value;
            $powerStr .= '<label class="am-checkbox-inline">';
            $powerStr .= '<input type="checkbox" value="'.$curPower.'"  name="power"';
            if(in_array($curPower,$power)){
                $powerStr .= ' checked';
            }
            $powerStr .= '> ';
            switch ($value){
                case 1:
                    $powerStr .= ' 查看';
                    break;
                case 2:
                    $powerStr .= ' 添加';
                    break;
                case 3:
                    $powerStr .= ' 编辑';
                    break;
                case 4:
                    $powerStr .= ' 删除';
                    break;
                default:
                    $powerStr .= ' 其它';
                    break;
            }
            $powerStr .= '</label>';
        }
        $powerStr .= '</div>';

        return $powerStr;
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
     * @param string $type
     * @return mixed
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
            'name'=>$params['name'],
            'power'=>$params['power'],
            'status'=>$params['status'],
            'utime'=>$this->init('time')
        ];
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
                $this->workLogSet($params['key'].' | '.$params['name']);
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
            'name' => 'require|length:2,30',
            'power' => 'require',
            'status' => 'require|number|between:0,1',
            'key' => 'number|between:1,1000000'
        ];
        $params = [
            'name' => $this->getRequestParams('post','name'),
            'power' => $this->getRequestParams('post','power'),
            'status' => $this->getRequestParams('post','status'),
            'key' => $this->getRequestParams('post','key')
        ];
        return $this->doValidate($rule,$params);
    }
    /**
     * 权限更新
     * @param string work normal/lock
     * @param string keys
     * @return array [code,data]
     */
    public function dou(){
        return (new CommonWork($this,$this->currentControlIns))->doUpdate(true);
    }
    /**
     * 权限删除
     * @param string keys
     * @return array [code,data]
     */
    public function dod(){
        return (new CommonWork($this,$this->currentControlIns))->doDelete(true);
    }
    /**
     * 生成缓存
     * @remark 缓存角色数据
     * @return mixed
     */
    public function setCache(){
        $data = [];
        $result = $this->currentControlIns->getList('cache');
        if(!empty($result)){
            foreach($result as $key=>$value){
                $data[$value['id']] = $value;
            }
        }
        $this->cacheFileIns->doing('file',[
            'work' => 'set',
            'key' => 'role',
            'data' => json_encode($data)
        ]);
    }
}
