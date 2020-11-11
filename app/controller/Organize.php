<?php
/**
 * 组织类
 * @package Controller/Organize
 */
namespace app\controller;
use app\model\common\ModelMap;
use app\model\Organize as OrganizeModel;
use app\controller\common\CommonWork;
use app\model\common\ModelMap as ModelMapModel;
class Organize extends Base {
    protected $organizeIns = null;
    protected $mapIns = null;
    protected $sword = null;
    protected $sstatus = null;
    public function __construct(){
        parent::__construct();
        $this->organizeIns = (new OrganizeModel());
        $this->mapIns = (new ModelMapModel());
        $this->sword = $this->getRequestParams('get', 'sword');
        $this->sstatus = $this->getRequestParams('get', 'sstatus');
    }

    public function list(){
        $pageHtml = '';
        $condition = [];
        if(!empty($this->sword)){
            $condition['name'] = ['rule'=>'like','val'=>$this->sword];
        }
        if ($this->sstatus=='' || $this->sstatus=='-1') {
            $condition['status'] = ['rule'=>'largeEqual','val'=>0];
            $this->sstatus = -1;
        }else{
            $condition['status'] = ['rule' => 'equal', 'val'=>$this->sstatus];
        }
        $order = ['id'=>'desc'];
        $result = $this->organizeIns->getListPage('list',$condition,$order,$this->page,$this->pageSize);
        if($result['total'] > 0){
            $pageHtml = $this->pagination($result['total']);
            foreach ($result['data'] as $key => $item) {
                $result['data'][$key]['status_map'] = $this->mapIns->getOrgStatusMap('list', $item['status']);
                $result['data'][$key]['ctime'] = date('Y-m-d H:i:s', $item['ctime']);
            }
        }
        $statusData = $this->mapIns->getOrgStatusMap('all');
        $assignData = [
            'searchWord'=>$this->sword??'',
            'data'=>$result,
            'statusSelectStr'=>$this->statusSelectHtml('sstatus',$statusData,$this->sstatus??1),
            'pageHtml'=>$pageHtml,
            'searchUrl'=>$this->urlGenerate('search'),
            'insertUrl'=>$this->urlGenerate('insert'),
            'editUrl'=> $this->urlGenerate('edit'),
            'workBtn'=>getWorkBtn($this->initModule['power'],$this->urlGenerate('doUpdate'),$this->urlGenerate('doDelete')),
        ];
        return $this->doRender('list',$assignData);
    }

    /**
     * 获取状态下拉选择
     * @remark 公用方法处理
     */
    private function statusSelectHtml($selectName,$data=[],$selected=-1){
        $data[-1] = '全部状态';
        return htmlSelect($selectName,$data,$selected,'请选择权限组');
    }

    /**
     * 添加组织
     * @remark 公用方法处理
     */
    public function i(){
        $data = ['id'=>'','name'=>'','status'=>''];
        return $this->work($data,$this->urlGenerate('doInsert'));
    }
    /**
     * 编辑组织
     * @remark 验证key是否为数字，成功后提交公用方法处理
     */
    public function e(){
        $result = (new CommonWork($this,$this->organizeIns))->checkEdit();
        if(!empty($result)){
            return $this->work($result,$this->urlGenerate('doEdit'));
        }else{
            return $this->doResponse(710);
        }
    }

    /**
     * 添加入口
     * @remark 公用方法处理
     */
    public function doi(){
        return $this->doWork('insert');
    }
    /**
     * 编辑入口
     * @remark 验证key是否为数字，成功后提交公用方法处理
     */
    public function doe(){
        return $this->doWork('edit');
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
            'mapUrl' => $this->urlGenerate('map'),
            'chooseShopUrl'=> $this->urlGenerate('chooseShop'),
            'workUrl'=>$url,
            'uploadUrl'=>'/'
        ];
        return $this->doRender('work',$assignData);
    }
    /**
     * 入库公用方法
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
            'status'=>$params['status'],
            'utime'=>$this->init('time')
        ];
        switch ($type){
            case 'insert':
                $data['ctime'] = $this->init('time');
                $result = $this->organizeIns->inData($data);
                if(!empty($result)){
                    $params['key'] = $result;
                }
                break;
            case 'edit':
                if(empty($params['key'])){
                    $code = 711;
                }else{
                    $condition = ['id'=>['rule'=>'equal','val'=>$params['key']]];
                    $result = $this->organizeIns->upData($condition,$data);
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
     * 更新
     * @param string work normal/lock
     * @param string keys
     * @return array [code,data]
     */
    public function dou(){
        return (new CommonWork($this,$this->organizeIns))->doUpdate(true);
    }

    /**
     * 验证参数
     * @return array 验证结果
     */
    private function checkInParams(){
        $rule = [
            'name' => 'require|length:4,30',
            'status' => 'require|number|in:0,1,2',
            'key' => 'number|between:1,1000000'
        ];
        $params = [
            'name' => $this->getRequestParams('post','name'),
            'status' => $this->getRequestParams('post','status'),
            'key' => $this->getRequestParams('post','key')
        ];
        return $this->doValidate($rule,$params);
    }

    /**
     * 生成缓存
     * @remark 缓存志愿者数据
     * @return mixed
     */
    public function setCache(){
        $data = [];
        $result = $this->organizeIns->getList('cache');
        if(!empty($result)){
            foreach($result as $key=>$value){
                $data[$value['id']] = $value;
            }
        }
        $this->cacheIns->doing('file',[
            'work' => 'set',
            'key' => 'role',
            'data' => json_encode($data)
        ]);
    }

    /**
     * 删除
     * @param string keys
     * @return array [code,data]
     */
    public function dod(){
        return (new CommonWork($this,$this->organizeIns))->doDelete(true);
    }
}