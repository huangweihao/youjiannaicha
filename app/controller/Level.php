<?php
/**
 * 志愿等级类
 * @package Controller/Level
 */
namespace app\controller;
use app\controller\common\CommonWork;
use app\model\Level as LevelModel;
class Level extends Base {
    protected $levelIns = null;
    protected $sword = null;
    public function __construct(){
        parent::__construct();
        $this->levelIns = (new LevelModel());
        $this->sword = $this->getRequestParams('get', 'sword');
    }

    public function list(){
        $pageHtml = '';
        $condition = [];
        if(!empty($this->sword)){
            $condition['title'] = ['rule'=>'like','val'=>$this->sword];
        }
        $order = ['id'=>'desc'];
        $result = $this->levelIns->getListPage('list',$condition,$order,$this->page,$this->pageSize);
        if($result['total'] > 0){
            $pageHtml = $this->pagination($result['total']);
            foreach ($result['data'] as $key => $item) {
                $result['data'][$key]['utime'] = date('Y-m-d H:i:s', $item['utime']);
                $result['data'][$key]['ctime'] = date('Y-m-d H:i:s', $item['ctime']);
            }
        }
        $assignData = [
            'searchWord'=>$this->sword??'',
            'data'=>$result,
            'pageHtml'=>$pageHtml,
            'searchUrl'=>$this->urlGenerate('search'),
            'insertUrl'=>$this->urlGenerate('insert'),
            'editUrl'=> $this->urlGenerate('edit'),
            'workBtn'=>getWorkBtn($this->initModule['power'],$this->urlGenerate('doUpdate'),$this->urlGenerate('doDelete'),false),
        ];
        return $this->doRender('list',$assignData);
    }


    /**
     * 添加组织
     * @remark 公用方法处理
     */
    public function i(){
        $data = ['id'=>'','title'=>'','desc'=>''];
        return $this->work($data,$this->urlGenerate('doInsert'));
    }
    /**
     * 编辑组织
     * @remark 验证key是否为数字，成功后提交公用方法处理
     */
    public function e(){
        $result = (new CommonWork($this,$this->levelIns))->checkEdit();
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
            'title'=>$params['title'],
            'desc'=>$params['desc'],
            'utime'=>$this->init('time')
        ];
        switch ($type){
            case 'insert':
                $data['ctime'] = $this->init('time');
                $result = $this->levelIns->inData($data);
                if(!empty($result)){
                    $params['key'] = $result;
                }
                break;
            case 'edit':
                if(empty($params['key'])){
                    $code = 711;
                }else{
                    $condition = ['id'=>['rule'=>'equal','val'=>$params['key']]];
                    $result = $this->levelIns->upData($condition,$data);
                }
                break;
        }
        if(isset($result) ){
            if(empty($result)){
                $code = 201;
            }
            if($code == 200){
                $this->workLogSet($params['key'].' | '.$params['title']);
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
        return (new CommonWork($this,$this->levelIns))->doUpdate(true);
    }

    /**
     * 验证参数
     * @return array 验证结果
     */
    private function checkInParams(){
        $rule = [
            'title' => 'require|length:2,10',
            'desc' => 'max:120',
            'key' => 'number|between:1,1000000'
        ];
        $params = [
            'title' => $this->getRequestParams('post','title'),
            'desc' => $this->getRequestParams('post','desc'),
            'key' => $this->getRequestParams('post','key')
        ];
        return $this->doValidate($rule,$params);
    }


    /**
     * 删除
     * @param string keys
     * @return array [code,data]
     */
    public function dod(){
        return (new CommonWork($this,$this->levelIns))->doDelete(false);
    }
}