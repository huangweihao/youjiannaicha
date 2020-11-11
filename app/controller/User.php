<?php
/**
 * 用户类
 * @package Controller/User
 */
namespace app\controller;
use app\model\User as UserModel;
use app\controller\common\CommonWork;
use app\model\ActivityJoin as JoinModel;
use app\model\Volunteer as VolunteerModel;
class User extends Base {
    protected $userIns = null;
    protected $volunteerIns = null;
    protected $joinIns = null;
    public function __construct(){
        parent::__construct();
        $this->userIns = (new UserModel());
        $this->joinIns = new JoinModel();
        $this->volunteerIns = new VolunteerModel();
    }

    public function list(){
        $pageHtml = '';
        $condition = [];
        $order = ['id'=>'desc'];
        $result = $this->userIns->getListPage('list',$condition,$order,$this->page,$this->pageSize);
        if($result['total'] > 0){
            $pageHtml = $this->pagination($result['total']);
            foreach ($result['data'] as $key => $item) {
                $result['data'][$key]['ctime'] = date('Y-m-d H:i:s', $item['ctime']);
                $result['data'][$key]['utime'] = date('Y-m-d H:i:s', $item['utime']);
            }
        }
        $assignData = [
            'data'=>$result,
            'pageHtml'=>$pageHtml,
            'editUrl'=> $this->urlGenerate('edit'),
            'workBtn'=>getWorkBtn($this->initModule['power'],$this->urlGenerate('doUpdate'),$this->urlGenerate('doDelete'),false),
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
        $result = (new CommonWork($this,$this->userIns))->checkEdit();
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
            'user_id'=>$params['user_id'],
            'activity_id'=>$params['activity_id'],
            'utime'=>$this->init('time')
        ];
        switch ($type){
            case 'insert':
                $data['ctime'] = $this->init('time');
                $result = $this->userIns->inData($data);
                if(!empty($result)){
                    $params['key'] = $result;
                }
                break;
            case 'edit':
                if(empty($params['key'])){
                    $code = 711;
                }else{
                    $condition = ['id'=>['rule'=>'equal','val'=>$params['key']]];
                    $result = $this->userIns->upData($condition,$data);
                }
                break;
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
     * 验证参数
     * @return array 验证结果
     */
    private function checkInParams(){
        $rule = [
            'key' => 'number|between:1,1000000',
            'activity_id' => 'require|number|between:1,1000000',
            'user_id' => 'require',
        ];
        $params = [
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
        return (new CommonWork($this,$this->userIns))->doDelete(true);
    }

    /**
     * 筛选选择
     * @param string $from
     * @return str
     */
    public function choose(){
        $check_result = $this->checkChooseParams();
        if($check_result['code'] == 200){
            $params = $check_result['data'];
        }else{
            return $this->doResponse($check_result['code'],$check_result['msg']);
        }
        $condition = [
            'id' => ['rule'=>'large','val'=>0]
        ];
        if(!empty($params['keyword'])){
            $condition['username'] = ['rule'=>'like','val'=>$params['keyword']];
        }
        //从报名当前活动的用户中筛选
        $key = $this->getRequestParams('', 'key');
        $joinData = $this->joinIns->getList('list', ['activity_id' => ['rule' => 'equal', 'val' => $key]]);
        $chooseHtml = '';
        if (!empty($joinData)) {
            $userIdData = array_column($joinData, 'user_id');
            $condition['id'] = ['rule' => 'in', 'val' => $userIdData];
            $result = $this->volunteerIns->getList('choose',$condition);
            if(!empty($result)){
                foreach($result as $key=>$value){
                    $result[$key] = $value;
                }
                $chooseHtml = htmlChoose('user',$result);
            }
        }

        if ($this->doResponseIsAjax()) {
            return $this->doResponse(200,'',['data'=>$chooseHtml]);
        }else{
            $assignData = [
                'searchUrl' => $this->urlGenerate('searchChoose'),
                'limit' => 10,
                'chooseHtml' => $chooseHtml,
                'chooseType' => 'user',
                'showDom' => 'chooseShow'.ucfirst('user'),
                'searchDom' =>  'show'.ucfirst('user'),
                'inputDom' =>  'choose'.ucfirst('user')
            ];
            return $this->doRender('choose',$assignData);
        }
    }
    /**
     * 验证参数
     * @return array 验证结果
     */
    private function checkChooseParams(){
        $rule = [
            'keyword' => 'length:1,30'
        ];
        $params = [
            'keyword' => $this->getRequestParams('post','keyword')
        ];
        return $this->doValidate($rule,$params);
    }
}