<?php
/**
 * 志愿活动类
 * @package Controller/VolunteerActivity
 */
namespace app\controller;
use app\model\common\ModelMap;
use app\model\VolunteerActivity as VolunteerActivityModel;
use app\controller\common\CommonWork;
use app\model\common\ModelMap as ModelMapModel;
use app\model\Schedule as ScheduleModel;
class VolunteerActivity extends Base {
    protected $volunteerIns = null;
    protected $mapIns = null;
    protected $sword = null;
    protected $sstatus = null;
    protected $scheduleIns = null;
    public function __construct(){
        parent::__construct();
        $this->volunteerIns = (new VolunteerActivityModel());
        $this->mapIns = (new ModelMapModel());
        $this->sword = $this->getRequestParams('get', 'sword');
        $this->sstatus = $this->getRequestParams('get', 'sstatus');
        $this->scheduleIns = new ScheduleModel();
    }

    public function list(){
        $pageHtml = '';
        $condition = [];
        if(!empty($this->sword)){
            $condition['title'] = ['rule'=>'like','val'=>$this->sword];
        }
        if ($this->sstatus=='' || $this->sstatus=='-1') {
            $condition['status'] = ['rule'=>'largeEqual','val'=>0];
            $this->sstatus = -1;
        }else{
            $condition['status'] = ['rule' => 'equal', 'val'=>$this->sstatus];
        }
        $order = ['id'=>'desc'];
        $result = $this->volunteerIns->getListPage('list',$condition,$order,$this->page,$this->pageSize);
        if($result['total'] > 0){
            $pageHtml = $this->pagination($result['total']);
            foreach ($result['data'] as $key => $item) {
                $result['data'][$key]['status_map'] = $this->mapIns->getActivityStatusMap('list', $item['status']);
                $result['data'][$key]['ctime'] = date('Y-m-d H:i:s', $item['ctime']);
                $result['data'][$key]['utime'] = date('Y-m-d H:i:s', $item['utime']);
                $result['data'][$key]['begin_time'] = date('Y-m-d H:i:s', $item['begin_time']);
                $result['data'][$key]['end_time'] = date('Y-m-d H:i:s', $item['end_time']);
                $result['data'][$key]['join_begin'] = date('Y-m-d H:i:s', $item['join_begin']);
                $result['data'][$key]['join_end'] = date('Y-m-d H:i:s', $item['join_end']);
            }
        }
        $statusData = $this->mapIns->getActivityStatusMap('all');
        $assignData = [
            'searchWord'=>$this->sword??'',
            'data'=>$result,
            'statusSelectStr'=>$this->statusSelectHtml('sstatus',$statusData,$this->sstatus??1),
            'pageHtml'=>$pageHtml,
            'searchUrl'=>$this->urlGenerate('search'),
            'insertUrl'=>$this->urlGenerate('insert'),
            'editUrl'=> $this->urlGenerate('edit'),
            'schedulUrl'=> $this->urlGenerate('schedule',['key'=>'']),
            'showUrl'=> $this->urlGenerate('open-scheduleList'),
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
        $data = ['id'=>'','title'=>'','content'=>'', 'begin_time'=>'','end_time'=>'','join_begin'=>'', 'join_end'=>'','address'=>'','status'=>'','cover'=>'','number'=>0];
        return $this->work($data,$this->urlGenerate('doInsert'));
    }
    /**
     * 编辑
     * @remark 验证key是否为数字，成功后提交公用方法处理
     */
    public function e(){
        $result = (new CommonWork($this,$this->volunteerIns))->checkEdit();
        if(!empty($result)){
            $result['begin_time'] = date('Y-m-d H:i:s',$result['begin_time']);
            $result['end_time'] = date('Y-m-d H:i:s',$result['end_time']);
            $result['join_begin'] = date('Y-m-d H:i:s',$result['join_begin']);
            $result['join_end'] = date('Y-m-d H:i:s',$result['join_end']);
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
            'title'=>$params['title'],
            'number'=>$params['number'],
            'address'=>$params['address'],
            'begin_time'=>strtotime($params['begin_time']),
            'end_time'=>strtotime($params['end_time']),
            'join_begin'=>strtotime($params['join_begin']),
            'join_end'=>strtotime($params['join_end']),
            'content'=>$params['content'],
            'cover'=>$params['cover'],
            'utime'=>$this->init('time')
        ];
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
        if(isset($result) ){
            if(empty($result)){
                $code = 201;
            }
            if($code == 200){
                $this->workLogSet($params['key'].' | '.$params['title']);
                $this->doEasyRedis('set', ['key' => 'activity_'.$params['key'], 'val'=>$params['title']]);
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
        return (new CommonWork($this,$this->volunteerIns))->doUpdate(false);
    }

    /**
     * 验证参数
     * @return array 验证结果
     */
    private function checkInParams(){
        $rule = [
            'status' => 'require|number|in:0,1,2,3',
            'key' => 'number|between:1,1000000',
            'number' => 'require|number|between:1,100000',
            'title' => 'require',
            'address' => 'require',
            'begin_time' => 'require|date',
            'end_time' => 'require|date',
            'join_begin' => 'require|date',
            'join_end' => 'require|date',
            'content' => 'require',
        ];
        $params = [
            'title' => $this->getRequestParams('post','title'),
            'address' => $this->getRequestParams('post','address'),
            'number' => $this->getRequestParams('post','number'),
            'begin_time' => $this->getRequestParams('post','begin_time'),
            'end_time' => $this->getRequestParams('post','end_time'),
            'join_begin' => $this->getRequestParams('post','join_begin'),
            'join_end' => $this->getRequestParams('post','join_end'),
            'content' => $this->getRequestParams('post','content'),
            'status' => $this->getRequestParams('post','status'),
            'cover' => $this->getRequestParams('post','cover'),
            'key' => $this->getRequestParams('post','key')
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
     * 排班页面
     * @return string
     */
    public function schedule()
    {
        $result = (new CommonWork($this,$this->volunteerIns))->checkEdit();
        if (!empty($result)) {
            $result['begin_time'] = date('Y-m-d H:i:s', $result['begin_time']);
            $result['end_time'] = date('Y-m-d H:i:s', $result['end_time']);
        }
        $assignData = [
            'data' => $result,
            'workUrl' => $this->urlGenerate('doInsert', [], 'schedule')
        ];
        return $this->doRender('schedule',$assignData);
    }

    /**
     * 活动排班列表
     * @return mixed|string
     */
    public function show()
    {
        $pageHtml = '';
        $isLayerOpen = false;
        $key = $this->getRequestParams('get', 'key');
        if (empty($key)) {
            return $this->doResponse(711);
        }else{
            $isLayerOpen = true;
        }
        $activityData = $this->volunteerIns->getDetail('show', ['id' => ['rule' => 'equal', 'val' => $key]]);
        $condition['activity_id'] = ['rule'=>'equal', 'val'=>$key];
        $order = 'id desc';
        $scheduleData = $this->scheduleIns->getListPage('list', $condition, $order,$this->page, $this->pageSize);
        if (!empty($scheduleData) && $scheduleData['total']>0) {
            $pageHtml = $this->pagination($scheduleData['total'],$isLayerOpen?$this->pageSizeLayer:$this->pageSize,0,$isLayerOpen?'simple':'all');
            foreach ($scheduleData['data'] as $k => $v) {
                $scheduleData['data'][$k]['schedule_begin'] = empty($v['schedule_begin']) ? '-' : date('Y-m-d H:i;s', $v['schedule_begin']);
                $scheduleData['data'][$k]['schedule_end'] = empty($v['schedule_end']) ? '-' : date('Y-m-d H:i;s', $v['schedule_end']);
                $scheduleData['data'][$k]['ctime'] = empty($v['ctime']) ? '-' : date('Y-m-d H:i;s', $v['ctime']);
                $scheduleData['data'][$k]['users'] = implode(',', json_decode($v['user_id'], true))??'';
            }
        }
        $assignData = [
            'data' => $scheduleData,
            'pageHtml' => $pageHtml,
            'activity_name' => $activityData['title']??""
        ];
        return $this->doRender('show', $assignData);
    }

}