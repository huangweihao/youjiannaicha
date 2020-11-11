<?php
namespace app\controller\common;
/**
 * 公用方法
 * Class Msg
 * @package app\admin\controller
 */
class CommonWork{
    private $baseObject = null;
    private $modelIns = null;
    private $config = null;
    function __construct(&$baseObject,&$modelIns,$config=[]){
        $this->baseObject = $baseObject;
        $this->modelIns = $modelIns;
        $this->config = $config;
    }
    /**
     * 配置入口
     * @param string $idName
     * @remark 验证key是否为数字，成功后提交公用方法处理
     * @return mixed
     */
    public function checkConfig($idName){
        $check_result = $this->checkEditParams();
        if($check_result['code'] == 200){
            $params = $check_result['data'];
        }else{
            return $this->baseObject->doResponse($check_result['code'],$check_result['msg']);
        }
        $condition = [$idName=>['rule'=>'equal','val'=>$params['key']]];
        return $this->modelIns->getDetail('config',$condition);
    }
    /**
     * 编辑入口
     * @remark 验证key是否为数字，成功后提交公用方法处理
     */
    public function checkEdit(){
        $check_result = $this->checkEditParams();
        if($check_result['code'] == 200){
            $params = $check_result['data'];
        }else{
            return $this->baseObject->doResponse($check_result['code'],$check_result['msg']);
        }
        $condition = ['id'=>['rule'=>'equal','val'=>$params['key']]];
        if(!empty($this->config)){
            return $this->modelIns->getDetail('edit',$condition,null,$this->config);
        }else{
            return $this->modelIns->getDetail('edit',$condition);
        }

    }
    /**
     * 验证参数
     * @return array 验证结果
     */
    private function checkEditParams(){
        $rule = [
            'key' => 'require|number|between:0,10000'
        ];
        $params = [
            'key' => $this->baseObject->getRequestParams('post','key')
        ];
        return $this->baseObject->doValidate($rule,$params);
    }
    /**
     * 更新
     * @param boolean $needCache
     * @return array [code,data]
     */
    public function doUpdate($needCache=false,$relateId=''){
        $check_result = $this->checkUpDelParams('update');
        if($check_result['code'] == 200){
            $params = $check_result['data'];
        }else{
            return $this->baseObject->doResponse($check_result['code'],$check_result['msg']);
        }
        $code = 200;
        $backData = [];
        $workName = '';
        if($code == 200){
            switch ($params['work']){
                case 'normal':
                    $workName = '打开';
                    $data = ['status'=>1,'utime'=>$this->baseObject->init('time')];
                    break;
                case 'lock':
                    $workName = '锁定';
                    $data = ['status'=>0,'utime'=>$this->baseObject->init('time')];
                    break;
                default:
                    $code = 710;
                    break;
            }
            if($code == 200){
                $relateId = empty($relateId) ? 'id' : $relateId;
                $condition = [$relateId=>['rule'=>'in','val'=>explode(',',$params['keys'])]];
                $result = $this->modelIns->upData($condition,$data);
                if(empty($result)){
                    $code = 710;
                }else{
                    if($needCache){
                        $this->baseObject->setCache();
                    }
                    $this->baseObject->workLogSet($params['keys'].' | '.$workName);
                }
            }
        }
        return $this->baseObject->doResponse($code,'',$backData);
    }
    /**
     * 验证参数
     * @param string $from
     * @return array 验证结果
     */
    private function checkUpDelParams($from){
        $rule = [
            'keys' => 'require'
        ];
        $params = [
            'keys' => $this->baseObject->getRequestParams('post','keys')
        ];
        switch ($from){
            case 'update':
                $rule['work'] =  'require|length:2,30';
                $params['work'] = $this->baseObject->getRequestParams('post','work');
                break;
        }
        return $this->baseObject->doValidate($rule,$params);
    }
    /**
     * 删除
     * @param boolean $needCache
     * @return array [code,data]
     */
    public function doDelete($needCache=false){
        $check_result = $this->checkUpDelParams('delete');
        if($check_result['code'] == 200){
            $params = $check_result['data'];
        }else{
            return $this->baseObject->doResponse($check_result['code'],$check_result['msg']);
        }
        $code = 200;
        $backData = [];
        $condition = ['id'=>['rule'=>'in','val'=>explode(',',$params['keys'])]];
        $result = $this->modelIns->delData($condition);
        if(empty($result)){
            $code = 710;
        }else{
            if($needCache){
                $this->baseObject->setCache();
            }
            $this->baseObject->workLogSet($params['keys'].'| 删除');
        }
        return $this->baseObject->doResponse($code,'',$backData);
    }
    /**
     * 删除多表
     * @param array $tables
     * @param boolean $needCache
     * @return mixed
     */
    public function doDeleteTrans($tables=[],$needCache=false){
        $check_result = $this->checkUpDelParams('delete');
        if($check_result['code'] == 200){
            $params = $check_result['data'];
        }else{
            return $this->baseObject->doResponse($check_result['code'],$check_result['msg']);
        }
        $code = 200;
        $backData = [];
        if(!empty($tables)){
            $data = [];
            foreach($tables as $table=>$key){
                $data = [
                    $table => [
                        'rule' => 'del',
                        'condition' => [$key => ['rule'=>'in','val'=>explode(',',$params['keys'])]]
                    ]
                ];
            }
            $result = $this->modelIns->delTransData($data);
            if(empty($result)){
                $code = 710;
            }else{
                if($needCache){
                    $this->baseObject->setCache();
                }
                $this->baseObject->workLogSet($params['keys'].'| 删除');
            }
            return $this->baseObject->doResponse($code,'',$backData);
        }else{
            return $this->baseObject->doResponse(710);
        }
    }
    /**
     * 筛选选择
     * @param string $from
     * @return str
     */
    public function choose($from){
        $check_result = $this->checkChooseParams();
        if($check_result['code'] == 200){
            $params = $check_result['data'];
        }else{
            return $this->doResponse($check_result['code'],$check_result['msg']);
        }
        $condition = [];
        switch ($from){
            case 'city':
                $condition = [
                    'level' => ['rule'=>'equal','val'=>2],
                    'status' => ['rule'=>'equal','val'=>1]
                ];
                break;
            case 'user':
                $condition = [
                    'user_id' => ['rule'=>'large','val'=>0]
                ];
                break;
            case 'shopLucky':
            case 'shopPool':
                $condition = [
                    'shop_id' => ['rule'=>'equal','val'=>$this->config['shopId']]
                ];
                break;
            default:
                $condition['status'] = ['rule'=>'equal','val'=>1];
                break;
        }
        switch ($from){
            case 'user':
                if(!empty($params['keyword'])){
                    $condition['mobile'] = ['rule'=>'like','val'=>$params['keyword']];
                }
                break;
            default:
                if(!empty($params['keyword'])){
                    $condition['name'] = ['rule'=>'like','val'=>$params['keyword']];
                }
                break;
        }
        $result = $this->modelIns->getList('choose',$condition);
        $chooseHtml = '';
        if(!empty($result)){
            switch ($from){
                case 'user':
                    foreach($result as $key=>$value){
                        $redisData = $this->baseObject->getUserDataFromRedis('normal',$value['id']);
                        $value['name'] = $redisData['data']['name'];
                        $result[$key] = $value;
                    }
                    break;
            }
            $chooseHtml = htmlChoose('add',$result);
        }
        if ($this->baseObject->doResponseIsAjax()) {
            return $this->baseObject->doResponse(200,'',['data'=>$chooseHtml]);
        }else{
            $assignData = [
                'searchUrl' => $this->baseObject->urlGenerate('searchChoose'),
                'limit' => 1,
                'chooseHtml' => $chooseHtml,
                'chooseType' => $from,
                'showDom' => 'chooseShow'.ucfirst($from),
                'searchDom' =>  'show'.ucfirst($from),
                'inputDom' =>  'choose'.ucfirst($from)
            ];
            return $this->baseObject->doRender('choose',$assignData);
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
            'keyword' => $this->baseObject->getRequestParams('post','keyword')
        ];
        return $this->baseObject->doValidate($rule,$params);
    }
}
