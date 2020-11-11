<?php
/**
 * 管理员密码修改
 * Class Pwd
 * @package app\controller
 */
namespace app\controller;
use app\model\Manager as ManagerModel;

class Pwd extends Base{
    private $currentControlIns = null;
    function __construct(){
        parent::__construct();
        $this->currentControlIns = new ManagerModel();
    }
    /**
     *页面显示
     * @remark 模板login/index
     */
    public function e(){
        $assignData = [
            'editUrl' => $this->urlGenerate('doEdit')
        ];
        return $this->doRender('work',$assignData);
    }
    /**
     * 修改密码
     * @param int id 当前的用户ID
     * @return boolean 修改是否成功
     */
    public function doe(){
        $check_result = $this->checkInParams();
        if($check_result['code'] == 200){
            $params = $check_result['data'];
        }else{
            return $this->doResponse($check_result['code'],$check_result['msg']);
        }
        $code = 200;
        $password = hash('md5',$params['new']);
        $old = hash('md5',$params['old']);
        $condition = ['id'=>['rule'=>'equal','val'=>$this->initManager['id']]];
        $result = $this->currentControlIns->getDetail('pwd',$condition);
        if(!empty($result)){
            if(strcmp($result['password'],$old) == 0){
                if(!$this->currentControlIns->upData($condition,['password'=>$password,'utime'=>$this->init('time')])){
                    $code = 705;
                }
            }else{
                $code = 703;
            }
        }
        return $this->doResponse($code);
    }
    /**
     * 验证参数
     * @return array 验证结果
     */
    private function checkInParams(){
        $rule = [
            'old' => 'require|length:5,30',
            'new' => 'require|length:5,30',
        ];
        $params = [
            'old' => $this->getRequestParams('post','old'),
            'new' => $this->getRequestParams('post','new')
        ];
        return $this->doValidate($rule,$params);
    }
}
