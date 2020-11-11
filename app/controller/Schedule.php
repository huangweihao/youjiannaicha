<?php
/**
 * 排班类
 * @package Controller/Schedule
 */
namespace app\controller;
use app\model\Schedule as ScheduleModel;
use app\controller\common\CommonWork;
use app\model\VolunteerActivity as ActivityModel;
class Schedule extends Base {
    protected $scheduleIns = null;
    protected $key = null;
    protected $pageSizeLayer = 20;
    protected $page = 0;
    protected $activityIns = null;
    public function __construct(){
        parent::__construct();
        $this->scheduleIns = (new ScheduleModel());
        $this->key = $this->getRequestParams('get', 'key');
        $this->page = $this->getRequestParams('get', 'page');
        $this->activityIns = new ActivityModel();
    }

    public function list(){
        $pageHtml = '';
        $condition = [];
        $isLayerOpen = false;
        if(!empty($this->key)){
            $isLayerOpen = true;
            $condition['activity_id'] = ['rule'=>'equal','val'=>$this->key];
        }
        $order = ['id'=>'desc'];
        $result = $this->scheduleIns->getListPage('list',$condition,$order,$this->page,$isLayerOpen?$this->pageSizeLayer:$this->pageSize);
        if($result['total'] > 0){
            $pageHtml = $this->pagination($result['total'],$isLayerOpen?$this->pageSizeLayer:$this->pageSize,$this->page,$isLayerOpen?'simple':'all');
            foreach ($result['data'] as $key => $v) {
                $result['data'][$key]['schedule_begin'] = empty($v['schedule_begin']) ? '-' : date('Y-m-d H:i:s', $v['schedule_begin']);
                $result['data'][$key]['schedule_end'] = empty($v['schedule_end']) ? '-' : date('Y-m-d H:i:s', $v['schedule_end']);
                $result['data'][$key]['ctime'] = empty($v['ctime']) ? '-' : date('Y-m-d H:i:s', $v['ctime']);
                $result['data'][$key]['users'] = implode(',', json_decode($v['user_id'], true))??'';
            }
        }
        $assignData = [
            'data'=>$result,
            'pageHtml'=>$pageHtml,
            'editUrl'=> $this->urlGenerate('edit'),
            'workBtn'=>getWorkBtn($this->initModule['power'],false,$this->urlGenerate('doDelete'),false),
        ];
        return $this->doRender('list',$assignData);
    }

    /**
     * 添加排班
     * @remark 公用方法处理
     */
    public function i(){
        $data = ['id'=>'','title'=>'','content'=>'', 'begin_time'=>'','end_time'=>'','join_begin'=>'', 'join_end'=>'','address'=>'','status'=>''];
        return $this->work($data,$this->urlGenerate('doInsert'));
    }
    /**
     * 编辑
     * @remark 验证key是否为数字，成功后提交公用方法处理
     */
    public function e(){
        $result = (new CommonWork($this,$this->scheduleIns))->checkEdit();
        if(!empty($result)){
            $condition['id'] = ['rule' => 'equal', 'val' => $result['activity_id']];
            $activityData = $this->activityIns->getDetail('edit', $condition);
            $backData = [
                'id' => $result['id'],
                'activity_id' => $result['activity_id'],
                'title' => $activityData['title'],
                'address' => $activityData['address'],
                'content' => $activityData['content'],
                'begin_time' => date('Y-m-d H:i:s', $activityData['begin_time']),
                'end_time' => date('Y-m-d H:i:s', $activityData['end_time']),
                'schedule_title' => $result['title'],
                'schedule_end' => date('Y-m-d H:i:s', $result['schedule_end']),
                'schedule_begin' => date('Y-m-d H:i:s', $result['schedule_begin']),
                'user_id' => $result['user_id'],
                'users' => array_values(json_decode($result['user_id'], true))??""
            ];

            return $this->work($backData,$this->urlGenerate('doEdit'));
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
            'title' => $params['title'],
            'schedule_begin' => strtotime($params['schedule_begin']),
            'schedule_end' => strtotime($params['schedule_end']),
            'user_id'=>$params['user_id'],
            'activity_id'=>$params['activity_id'],
            'utime'=>$this->init('time')
        ];
        if (empty(json_decode($data['user_id'], true))) {
            return $this->doResponse(712);
        }
        switch ($type){
            case 'insert':
                $data['ctime'] = $this->init('time');
                $result = $this->scheduleIns->inData($data);
                if(!empty($result)){
                    $params['key'] = $result;
                    $this->doRecordServiceTime($params['user_id'], $data['schedule_begin'], $data['schedule_end']);
                }
                break;
            case 'edit':
                if(empty($params['key'])){
                    $code = 711;
                }else{
                    $condition = ['id'=>['rule'=>'equal','val'=>$params['key']]];
                    $result = $this->scheduleIns->upData($condition,$data);
                }
                break;
        }
        if(isset($result) ){
            if(empty($result)){
                $code = 201;
            }
            if($code == 200){
                $this->workLogSet($params['key'].' | '.$params['activity_id']);
            }
            $backData['workUrl'] = $this->urlGenerate('schedule',[],'volunteeractivity').'?key='.$params['activity_id'];
            $backData['backUrl'] = $this->urlGenerate('view',[],'volunteeractivity');
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
            'key' => 'number|between:1,1000000',
            'schedule_begin' => 'require|date',
            'schedule_end' => 'require|date',
            'title' => 'require',
            'activity_id' => 'require|number|between:1,1000000',
            'user_id' => 'require',
        ];
        $params = [
            'title' => $this->getRequestParams('post','title'),
            'schedule_begin' => $this->getRequestParams('post','schedule_begin'),
            'schedule_end' => $this->getRequestParams('post','schedule_end'),
            'activity_id' => $this->getRequestParams('post','activity_id'),
            'user_id' => $this->getRequestParams('post','user_id'),
            'key' => $this->getRequestParams('post','key')
        ];
        return $this->doValidate($rule,$params);
    }


    /**
     * 删除排班
     * @param string keys
     * @return array [code,data]
     */
    public function dod(){
        return (new CommonWork($this,$this->scheduleIns))->doDelete(false);
    }

    /**
     * 记录服务时长
     * @param string $userId
     * @param int $beginTime
     * @param int $endTime
     */
    public function doRecordServiceTime($userId='', $beginTime=0, $endTime=0)
    {
        $userId = @json_decode($userId, true);
        if (!empty($userId)) {
            $time = $this->formatTime($beginTime, $endTime);
            foreach ($userId as $key => $item) {
                $this->doEasyRedis('inc', ['key' => 'times_'.$key, 'step' => $time]);
            }
        }
    }

    /**
     * 时间/小时
     * @param $begin
     * @param $end
     * @return float|int
     */
    public function formatTime($begin, $end)
    {
        $time=0;
        if (!empty($begin) && !empty($end)){
            $time = ceil(($end-$begin)/3600);
        }
        return $time;
    }
}