<?php
/**
 * 商圈管理
 * Class Zone
 * @package app\controller
 */
namespace app\controller;
use app\model\Zone as ZoneModel;
use app\model\District as DistrictModel;
use app\controller\common\CommonWork;

class Zone extends Base{
    private $currentControlIns = null;
    function __construct(){
        parent::__construct();
        $this->currentControlIns = new ZoneModel();
    }
    /**
     * 页面显示
     * @remark index
     */
    public function getList(){
        $pageHtml = '';
        $condition = [
            'city_id' => ['rule'=>'equal','val'=>$this->initManager['city_id']]
        ];
        $order = [];
        if(!empty($this->sword)){
            $condition['name'] = ['rule'=>'like','val'=>$this->sword];
        }
        $order['rank'] = 'desc';
        $result = $this->currentControlIns->getList('list',$condition,$order);
        $rankData = [];
        if(!empty($result)){
            $districtData = $this->getDistrictData();
            foreach($result as $value){
                if(!empty($rankData[$value['district_id']])){
                    $rankData[$value['district_id']]['child'][] = $value;
                }else{
                    $rankData[$value['district_id']] = [
                        'name'=> !empty($districtData[$value['district_id']]) ? $districtData[$value['district_id']] : '',
                        'child' => [$value],
                    ];
                }
            }
        }
        $assignData = [
            'data' => $rankData,
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
     * 获取区域下拉选择
     * @param $selectName
     * @param $data
     * @param $selected
     * @remark 公用方法处理
     * @return mixed
     */
    private function districtSelectHtml($selectName,$data=[],$selected=0){
        $data[0] = '选择区县';
        return htmlSelect($selectName,$data,$selected,'请选择区县');
    }
    /**
     * 获取区域信息
     * @return mixed
     */
    private function getDistrictData(){
        $condition = [
            'parent_adcode' => ['rule'=>'equal','val'=>$this->initManager['city_id']]
        ];
        $result = (new DistrictModel())->getList('zone',$condition);
        $districtData = [];
        if(!empty($result)){
            foreach($result as $value){
                $districtData[$value['adcode']] = $value['name'];
            }
        }
        return $districtData;
    }
    /**
     * 添加入口
     * @remark 公用方法处理
     */
    public function i(){
        $data = [
            'id'=>'',
            'district_id'=>0,
            'name'=>'',
            'status'=>0,
            'rank'=>0
        ];
        return $this->work($data,$this->urlGenerate('doInsert'));
    }
    /**
     * 编辑入口
     * @remark 验证key是否为数字，成功后提交公用方法处理
     * @return mixed
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
     * @param  array $data 页面显示的数据
     * @param string $url ajax请求的地址
     * @return mixed
     */
    private function work(&$data,$url){
        $districtData = $this->getDistrictData();
        $districtSelectStr = $this->districtSelectHtml('district',$districtData,$data['district_id']);
        $assignData = [
            'districtSelectStr' => $districtSelectStr,
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
     * @param string $type
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
        $data = [
            'city_id'=> $this->initManager['city_id'],
            'district_id'=> $params['district'],
            'name'=> $params['name'],
            'status'=> $params['status'],
            'rank'=> $params['rank']
        ];
        switch ($type){
            case 'insert':
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
            'district' => 'require|number|between:100000,1000000',
            'rank' => 'require|number|between:0,50000',
            'status' => 'require|number|between:0,1',
            'key' => 'number|between:1,1000000'
        ];
        $params = [
            'name' => $this->getRequestParams('post','name'),
            'district' => $this->getRequestParams('post','district'),
            'rank' => $this->getRequestParams('post','rank'),
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
