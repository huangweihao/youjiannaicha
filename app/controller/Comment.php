<?php
/**
 * 社区评论类
 * @package Controller/Comment
 */
namespace app\controller;
use app\model\Comment as CommentModel;
use app\controller\common\CommonWork;
class Comment extends Base {
    protected $CommentIns = null;
    protected $mapIns = null;
    protected $sword = null;
    public function __construct(){
        parent::__construct();
        $this->CommentIns = (new CommentModel());
        $this->sword = $this->getRequestParams('get', 'sword');
    }

    public function list(){
        $pageHtml = '';
        $condition = [];
        if(!empty($this->sword)){
            $condition['a.content'] = ['rule'=>'like','val'=>$this->sword];
        }
        $order = ['id'=>'desc'];
        $result = $this->CommentIns->getListPage('list',$condition,$order,$this->page,$this->pageSize);
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
            'workBtn'=>getWorkBtn($this->initModule['power'],$this->urlGenerate('doUpdate'),$this->urlGenerate('doDelete')),
        ];
        return $this->doRender('list',$assignData);
    }


    /**
     * 添加组织
     * @remark 公用方法处理
     */
    public function i(){
        $data = ['id'=>'','title'=>'','desc'=>'','content'=>'','video'=>'','cover'=>''];
        return $this->work($data,$this->urlGenerate('doInsert'));
    }
    /**
     * 编辑组织
     * @remark 验证key是否为数字，成功后提交公用方法处理
     */
    public function e(){
        $result = (new CommonWork($this,$this->CommentIns))->checkEdit();
//        dump($result);die;
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
            'cover'=>$params['cover'],
            'content'=>$params['content'],
            'video'=>$params['video'],
            'status'=>$params['status'],
            'utime'=>$this->init('time')
        ];
        switch ($type){
            case 'insert':
                $data['ctime'] = $this->init('time');
                $result = $this->CommentIns->inData($data);
                if(!empty($result)){
                    $params['key'] = $result;
                }
                break;
            case 'edit':
                if(empty($params['key'])){
                    $code = 711;
                }else{
                    $condition = ['id'=>['rule'=>'equal','val'=>$params['key']]];
                    $result = $this->CommentIns->upData($condition,$data);
                }
                break;
        }
        if(isset($result) ){
            if(empty($result)){
                $code = 201;
            }
            if($code == 200){
                $this->setCache();
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
        return (new CommonWork($this,$this->CommentIns))->doUpdate(true);
    }

    /**
     * 验证参数
     * @return array 验证结果
     */
    private function checkInParams(){
        $rule = [
            'title' => 'require|length:1,30',
            'cover' => 'require',
            'video' => 'require',
            'desc' => 'require',
            'content' => 'require',
            'key' => 'number|between:1,1000000'
        ];
        $params = [
            'title' => $this->getRequestParams('post','title'),
            'video' => $this->getRequestParams('post','video'),
            'desc' => $this->getRequestParams('post','desc'),
            'cover' => $this->getRequestParams('post','cover'),
            'content' => $this->getRequestParams('post','content'),
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
        $result = $this->CommentIns->getList('cache');
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
        return (new CommonWork($this,$this->CommentIns))->doDelete(true);
    }
}