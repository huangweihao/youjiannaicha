<?php
namespace app\controller;
/**
 * 活动模板管理
 * Class ActivityTemplate
 * @package app\controller
 */
use app\model\ActivityTemplate as ActivityTemplateModel;
use app\controller\common\CommonWork;

class ActivityTemplate extends Base{
    private $currentControlIns = null;
    function __construct(){
        parent::__construct();
        $this->currentControlIns = new ActivityTemplateModel();
    }
    /**
     * 页面显示
     * @remark index
     */
    public function getList(){
        $pageHtml = '';
        $order = [];
        $condition = [];
        if(!empty($this->sword)){
            $condition['name'] = ['rule'=>'like','val'=>$this->sword];
        }
        $order['id'] = 'desc';
        $result = $this->currentControlIns->getListPage('list',$condition,$order,$this->page,$this->pageSize);
        if($result['total'] > 0){
            $pageHtml = $this->pagination($result['total']);
        }
        $assignData = [
            'data' => $result,
            'pageHtml' => $pageHtml,
            'insertUrl' => $this->urlGenerate('insert'),
            'editUrl' => $this->urlGenerate('edit'),
            'searchUrl' => $this->urlGenerate('search'),
            'searchWord' => $this->sword,
            'workBtn' => getWorkBtn($this->initModule['power'],$this->urlGenerate('doUpdate'),$this->urlGenerate('doDelete'))
        ];
        return $this->doRender('list',$assignData);
    }
    /**
     * 添加入口
     * @remark 公用方法处理
     */
    public function i(){
        $data = [
            'id'=>'',
            'photo'=>'',
            'name'=>'',
            'status'=>0
        ];
        return $this->work($data,$this->urlGenerate('doInsert'));
    }
    /**
     * 编辑入口
     * @remark 验证key是否为数字，成功后提交公用方法处理
     */
    public function e(){
        $result = (new CommonWork($this,$this->currentControlIns))->checkEdit();
        if(!empty($result)){
            $result['photo'] = empty($result['photo']) ? '' : reformImgPath('out',$result['photo']);
            return $this->work($result,$this->urlGenerate('doEdit'));
        }else{
            return $this->doResponse(710);
        }
    }
    /**
     * 添加编辑页面
     * @param  array data 页面显示的数据
     * @param string url ajax请求的地址
     * @return work
     */
    private function work(&$data,$url){

        $assignData = [
            'data'=>$data,
            'viewUrl'=>$this->urlGenerate('view'),
            'workUrl'=>$url
        ];
        return $this->doRender('work',$assignData);
    }
    /**
     * 添加入库入口
     * @remark 公用方法处理
     */
    public function doi(){
        return $this->doWork('insert');
    }
    /**
     * 编辑入库入口
     * @remark 验证key是否为数字，成功后提交公用方法处理
     */
    public function doe(){
        return $this->doWork('edit');
    }
    /**
     * 入库公用方法
     * @param int key
     * @return array [code,data]
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
        $params['photo'] = empty($params['photo']) ? '' : reformImgPath('in',$params['photo']);
        $data = [
            'photo'=> $params['photo'],
            'name'=> $params['name'],
            'status'=> $params['status'],
            'utime'=> $this->init('time')
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
                    $condition =['id'=>['rule'=>'equal','val'=>$params['key']]];
                    $result = $this->currentControlIns->upData($condition,$data);
                }
                break;
        }
        if(isset($result) ){
            if(empty($result)){
                $code = 201;
            }
            if($code == 200){
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
            'photo' => 'require|length:3,200',
            'status' => 'require|number|between:0,1',
            'key' => 'number|between:1,1000000'
        ];
        $params = [
            'name' => $this->getRequestParams('post','name'),
            'photo' => $this->getRequestParams('post','photo'),
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
        return (new CommonWork($this,$this->currentControlIns))->doUpdate();
    }
    /**
     * 删除
     * @param string keys
     * @return array [code,data]
     */
    public function dod(){
        return (new CommonWork($this,$this->currentControlIns))->doDelete();
    }
}
