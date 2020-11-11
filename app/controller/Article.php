<?php
/**
 * 文章类
 * @package Controller/Article
 */
namespace app\controller;
use app\controller\common\CommonWork;
use app\model\Article as ArticleModel;
use app\model\ArticleType as ArticleTypeModel;
use app\model\common\ModelMap;
class Article extends Base {
    protected $articleIns = null;
    protected $mapIns = null;
    protected $sword = null;
    public function __construct(){
        parent::__construct();
        $this->articleIns = (new ArticleModel());
        $this->mapIns = new ModelMap();
        $this->sword = $this->getRequestParams('get', 'sword');
    }

    public function list(){
        $pageHtml = '';
        $condition = [];
        if(!empty($this->sword)){
            $condition['title'] = ['rule'=>'like','val'=>$this->sword];
        }
        $order = ['id'=>'desc'];
        $result = $this->articleIns->getListPage('list',$condition,$order,$this->page,$this->pageSize);
        if($result['total'] > 0){
            $pageHtml = $this->pagination($result['total']);
            foreach ($result['data'] as $key => $item) {
                $result['data'][$key]['type'] = $this->doEasyRedis('get', ['key' => 'article_type_'.$item['type']]);
                $result['data'][$key]['utime'] = date('Y-m-d H:i:s', $item['utime']);
                $result['data'][$key]['ctime'] = date('Y-m-d H:i:s', $item['ctime']);
                $result['data'][$key]['status_map'] = $this->mapIns->getVolunteerStatusMap('list', $item['status']);
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
     * 获取文章分类下拉选择
     * @remark 公用方法处理
     */
    private function typeSelectHtml($selectName,$data=[],$selected=0){
        $data[0] = '文章类型';
        return htmlSelect($selectName,$data,$selected,'请选择等级');
    }

    /**
     * 添加文章
     * @remark 公用方法处理
     */
    public function i(){
        $data = ['id'=>'','title'=>'','status'=>0,'desc'=>'','content'=>'','cover'=>''];
        return $this->work($data,$this->urlGenerate('doInsert'));
    }
    /**
     * 编辑文章
     * @remark 验证key是否为数字，成功后提交公用方法处理
     */
    public function e(){
        $result = (new CommonWork($this,$this->articleIns))->checkEdit();
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
        $typeResetData=[];
        $typeData = (new ArticleTypeModel())->getList('list');
        if (!empty($typeData)) {
            foreach ($typeData as $value) {
                $typeResetData[$value['id']] = $value['name'];
            }
        }
        $assignData = [
            'data'=>$data,
            'viewUrl'=>$this->urlGenerate('view'),
            'mapUrl' => $this->urlGenerate('map'),
            'typeSelectHtml' => $this->typeSelectHtml('type', $typeResetData, $data['type']??0),
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
            'content'=>$params['content'],
            'cover'=>$params['cover'],
            'type'=>$params['type'],
            'status'=>$params['status'],
            'utime'=>$this->init('time')
        ];
        switch ($type){
            case 'insert':
                $data['ctime'] = $this->init('time');
                $result = $this->articleIns->inData($data);
                if(!empty($result)){
                    $params['key'] = $result;
                }
                break;
            case 'edit':
                if(empty($params['key'])){
                    $code = 711;
                }else{
                    $condition = ['id'=>['rule'=>'equal','val'=>$params['key']]];
                    $result = $this->articleIns->upData($condition,$data);
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
        return (new CommonWork($this,$this->articleIns))->doUpdate(false);
    }

    /**
     * 验证参数
     * @return array 验证结果
     */
    private function checkInParams(){
        $rule = [
            'title' => 'require|length:2,64',
            'desc' => 'max:128',
            'cover' => 'max:255',
            'type' => 'require|number',
            'status' => 'require|number|in:0,1,2',
            'key' => 'number|between:1,1000000'
        ];
        $params = [
            'title' => $this->getRequestParams('post','title'),
            'desc' => $this->getRequestParams('post','desc'),
            'content' => $this->getRequestParams('post','content'),
            'cover' => $this->getRequestParams('post','cover'),
            'type' => $this->getRequestParams('post','type'),
            'status' => $this->getRequestParams('post','status'),
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
        return (new CommonWork($this,$this->articleIns))->doDelete(false);
    }
}