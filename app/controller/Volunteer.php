<?php
/**
 * 志愿者类
 * @package ControllerVolunteer
 */
namespace app\controller;
use app\model\common\ModelMap;
use app\model\Volunteer as VolunteerModel;
use app\model\Level as LevelModel;
use app\model\Reward as RewardModel;
use app\controller\common\CommonWork;
use app\model\common\ModelMap as ModelMapModel;
use app\model\Schedule as ScheduleModel;
use app\model\VolunteerReward;

class Volunteer extends Base {
    /**
     * 志愿者ins
     * @var VolunteerModel|null
     */
    protected $volunteerIns = null;
    protected $levelIns = null;
    protected $rewardIns = null;
    protected $mapIns = null;
    protected $sword = null;
    protected $sstatus = null;
    protected $sbeasy = null;
    /**
     * 排班ins
     * @var null
     */
    protected $scheduleIns = null;

    public function __construct(){
        parent::__construct();
        $this->volunteerIns = (new VolunteerModel());
        $this->levelIns = new LevelModel();
        $this->rewardIns = new RewardModel();
        $this->mapIns = (new ModelMapModel());
        $this->sword = $this->getRequestParams('get', 'sword');
        $this->sstatus = $this->getRequestParams('get', 'sstatus');
        $this->sbeasy = $this->getRequestParams('get', 'sbeasy');
        $this->scheduleIns = new ScheduleModel();
    }

    /**
     * 列表
     * @return string
     */
    public function list(){
        $pageHtml = '';
        $condition = [];
        if(!empty($this->sword)){
            $condition['username'] = ['rule'=>'like','val'=>$this->sword];
        }
        if ($this->sstatus=='' || $this->sstatus== '-1') {
            $condition['status'] = ['rule'=>'largeEqual','val'=>0];
            $this->sstatus = -1;
        }else{
            $condition['status'] = ['rule' => 'equal', 'val'=>$this->sstatus];
        }
        if (!empty($this->sbeasy) && is_numeric($this->sbeasy) && $this->sbeasy > 0) {
            $scheCondition = [];
            $scheCondition['schedule_end'] = ['rule' => 'large', 'val' => $this->init('time')];
            $userInPositionData = $this->scheduleIns->getList('list', $scheCondition);                    $dataId = [];
            if (!empty($userInPositionData)){
                $userIdStr = array_column($userInPositionData, 'user_id');
                if (!empty($userIdStr)) {
                    foreach ($userIdStr as $key => $item) {
                        $item = @json_decode($item, true);
                        foreach ($item as $k => $v) {
                            $dataId[] = $k;
                        }
                    }
                }
            }
            switch ($this->sbeasy) {
                case 1:
                    if (!empty($dataId)) {
                        $condition['id'] = ['rule' => 'notIn', 'val' => $dataId];
                    }
                    break;
                case 2:
                    if (!empty($dataId)) {
                        $condition['id'] = ['rule' => 'in', 'val' => $dataId];
                    }
                    break;
            }
        }
        $order = ['id'=>'desc'];
        $result = $this->volunteerIns->getListPage('list',$condition,$order,$this->page,$this->pageSize);
        if($result['total'] > 0){
            $pageHtml = $this->pagination($result['total']);
            foreach ($result['data'] as $key => $item) {
                $result['data'][$key]['health'] = $this->mapIns->getHealthMap('list', $item['health']);
                $result['data'][$key]['status_map'] = $this->mapIns->getVolunteerStatusMap('list', $item['status']);
                $result['data'][$key]['experience'] = $this->mapIns->getVolunteerServiceMap('list', $item['experience']);
                $result['data'][$key]['ctime'] = date('Y-m-d H:i:s', $item['ctime']);
            }
        }
        $statusData = $this->mapIns->getStatusMap('all');
        $beasyData = $this->mapIns->getBeasyStatusMap('all');
        $assignData = [
            'searchWord'=>$this->sword??'',
            'data'=>$result,
            'statusSelectStr'=>$this->statusSelectHtml('sstatus',$statusData,$this->sstatus),
            'beasySelectStr'=>$this->beasySelectHtml('sbeasy',$beasyData,$this->sbeasy??0),
            'pageHtml'=>$pageHtml,
            'searchUrl'=>$this->urlGenerate('search'),
            'insertUrl'=>$this->urlGenerate('insert'),
            'editUrl'=> $this->urlGenerate('edit'),
            'examineUrl'=> $this->urlGenerate('examine'),
            'rewardUrl'=> $this->urlGenerate('rewardup'),
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
        return htmlSelect($selectName,$data,$selected,'全部状态');
    }
    /**
     * 获取状态下拉选择
     * @remark 公用方法处理
     */
    private function beasySelectHtml($selectName,$data=[],$selected=0){
        return htmlSelect($selectName,$data,$selected,'全部');
    }
    /**
     * 获取评级下拉选择
     * @remark 公用方法处理
     */
    private function levelSelectHtml($selectName,$data=[],$selected=0){
        $data[0] = '选择等级';
        return htmlSelect($selectName,$data,$selected,'请选择等级');
    }

    /**
     * 获取评级下拉选择
     * @remark 公用方法处理
     */
    private function rewardSelectHtml($selectName,$data=[],$selected=0){
        $data[0] = '选择奖励';
        return htmlSelect($selectName,$data,$selected,'请选择奖励');
    }

    /**
     * 编辑志愿者
     * @remark 验证key是否为数字，成功后提交公用方法处理
     */
    public function e(){
        $result = (new CommonWork($this,$this->volunteerIns))->checkEdit();
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
            'status'=>$params['status'],
            'age'=>$params['age'],
            'health'=>$params['health'],
            'level' => $params['level'],
            'utime'=>$this->init('time')
        ];
        if (empty($params['reward_id'])) {
            switch ($type){
                case 'insert':
                    $data['ctime'] = $this->init('time');
                    $result = $this->volunteerIns->inData($data);
                    if(!empty($result)){
                        $params['key'] = $result;
                    }
                    break;
                case 'edit':
                    if(empty($params['key'])){
                        $code = 711;
                    }else{
                        $condition = ['id'=>['rule'=>'equal','val'=>$params['key']]];
                        $result = $this->volunteerIns->upData($condition,$data);
                    }
                    break;
            }
        }else{
            $saveData = [
                'volunteer_id' => $params['key'],
                'reward_id' => $params['reward_id'],
                'ctime' => $this->init('time')
            ];
            $result = (new VolunteerReward())->inData($saveData);
        }
        if(isset($result) ){
            if(empty($result)){
                $code = 201;
            }
            if($code == 200){
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
     * 权限更新
     * @param string work normal/lock
     * @param string keys
     * @return array [code,data]
     */
    public function dou(){
        return (new CommonWork($this,$this->volunteerIns))->doUpdate(true);
    }

    /**
     * 验证参数
     * @return array 验证结果
     */
    private function checkInParams(){
        $rule = [
            'status' => 'require|number|in:0,1,2',
            'key' => 'number|between:1,1000000',
        ];
        $params = [
            'age' => $this->getRequestParams('post','age'),
            'username' => $this->getRequestParams('post','username'),
            'health' => $this->getRequestParams('post','health'),
            'status' => $this->getRequestParams('post','status'),
            'key' => $this->getRequestParams('post','key'),
            'reward_id' => $this->getRequestParams('post', 'reward_id'),
            'level' => $this->getRequestParams('post','level'),
        ];
        return $this->doValidate($rule,$params);
    }


    /**
     * 权限志愿者
     * @param string keys
     * @return array [code,data]
     */
    public function dod(){
        return (new CommonWork($this,$this->volunteerIns))->doDelete(true);
    }

    /**
     * 志愿者等级考核
     * @return mixed|string
     */
    public function examine()
    {
        $result = (new CommonWork($this,$this->volunteerIns))->checkEdit();
        if(!empty($result)){
            $levelData = $this->levelIns->getList('list');
            $levelResetData=[];
            if (!empty($levelData)) {
                foreach ($levelData as $value) {
                    $levelResetData[$value['id']] = $value['name'];
                }
            }
            $assignData = [
                'data' => $result,
                'viewUrl' => $this->urlGenerate('view'),
                'workUrl' => $this->urlGenerate('doEdit'),
                'levelSelectStr'=> $this->levelSelectHtml('level',$levelResetData,$result['level']??0),
            ];
            return $this->doRender('examine', $assignData);
        }else{
            return $this->doResponse(710);
        }
    }

    /**
     * 志愿者奖励颁发
     * @return mixed|string
     */
    public function rewardUp()
    {
        $result = (new CommonWork($this,$this->volunteerIns))->checkEdit();
        if(!empty($result)){
            $levelData = $this->rewardIns->getList('list');
            $levelResetData=[];
            if (!empty($levelData)) {
                foreach ($levelData as $value) {
                    $levelResetData[$value['id']] = $value['name'];
                }
            }
            $assignData = [
                'data' => $result,
                'viewUrl' => $this->urlGenerate('view'),
                'workUrl' => $this->urlGenerate('doEdit'),
                'rewardSelectStr'=> $this->rewardSelectHtml('reward_id',$levelResetData,$result['level']??0),
            ];
            return $this->doRender('reward', $assignData);
        }else{
            return $this->doResponse(710);
        }
    }

    /**
     * 志愿者信息
     * @return string
     */
    public function info(){
        $pageHtml = '';
        $condition = [];
        if(!empty($this->sword)){
            $condition['username'] = ['rule'=>'like','val'=>$this->sword];
        }
        if ($this->sstatus=='' || $this->sstatus=='-1') {
            $condition['status'] = ['rule'=>'largeEqual','val'=>0];
            $this->sstatus = -1;
        }else{
            $condition['status'] = ['rule' => 'equal', 'val'=>$this->sstatus];
        }
        $order = ['id'=>'desc'];
        $result = $this->volunteerIns->getListPage('info',$condition,$order,$this->page,$this->pageSize);
        if($result['total'] > 0){
            $pageHtml = $this->pagination($result['total']);
            foreach ($result['data'] as $key => $item) {
                $result['data'][$key]['health'] = $this->mapIns->getHealthMap('list', $item['health']);
                $result['data'][$key]['status_map'] = $this->mapIns->getVolunteerStatusMap('list', $item['status']);
                $result['data'][$key]['experience'] = $this->mapIns->getVolunteerServiceMap('list', $item['experience']);
                $result['data'][$key]['ctime'] = date('Y-m-d H:i:s', $item['ctime']);
            }
        }
        $statusData = $this->mapIns->getStatusMap('all');
        $assignData = [
            'searchWord'=>$this->sword??'',
            'data'=>$result,
            'statusSelectStr'=>$this->statusSelectHtml('sstatus',$statusData,$this->sstatus??1),
            'pageHtml'=>$pageHtml,
            'searchUrl'=>$this->urlGenerate('search'),
            'insertUrl'=>$this->urlGenerate('insert'),
            'editUrl'=> $this->urlGenerate('edit'),
            'examineUrl'=> $this->urlGenerate('examine',['key'=>'']),
            'workBtn'=>getWorkBtn($this->initModule['power'],$this->urlGenerate('doUpdate'),$this->urlGenerate('doDelete')),
        ];
        return $this->doRender('info',$assignData);
    }

}