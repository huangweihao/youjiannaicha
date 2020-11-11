<?php
/**
 * 管理员操作记录
 * Class ManagerWorkLog
 * @package app\controller
 */
namespace app\controller;

use app\model\ManagerWorkLog as ManagerWorkLogModel;
use app\common\Auth;

class ManagerWorkLog extends Base{
    private $currentControlIns = null;
    function __construct(){
        parent::__construct();
        $this->currentControlIns = new ManagerWorkLogModel();
    }
    /**
     * 列表显示
     * @remark list
     */
    public function getList(){
        $pageHtml = '';
        $condition = [];
        if(!empty($this->srank)){
            switch($this->srank){
                case '2':
                    $condition['work']=['rule'=>'equal', 'val'=>'doi'];
                    break;
                case '3':
                    $condition['work']=['rule'=>'equal', 'val'=>'doe'];
                    break;
                case '4':
                    $condition['work']=['rule'=>'equal', 'val'=>'dod'];
                    break;
            }
        }
        if(!empty($this->sgroup)){
            $condition['column'] = ['rule'=>'equal','val'=>$this->sgroup];
        }
        $order = ['id'=>'desc'];
        $result = $this->currentControlIns->getListPage('list',$condition,$order,$this->page,$this->pageSize);
        if($result['total'] > 0){
            $pageHtml = $this->pagination($result['total']);
        }

        $work = ['0'=>'全部','2'=>'添加','3'=>'编辑','4'=>'删除'];
        $controller = ['0'=>'全部'];
        $controllers = (new Auth())->menuInit();
        foreach ($controllers as $key => $value) {
            if(!empty($value) && !empty($value['child'])){
                foreach ($value['child'] as $childKey => $childValue) {
                    $controller[$childKey]= $childValue['name'];
                }
            }
        }
        $assignData = [
            'groupSelectStr' => htmlSelect('sgroup',$controller, $this->sgroup??''),
            'workSelectStr' => htmlSelect('srank',$work, $this->srank??''),
            'data'=>$result,
            'pageHtml'=>$pageHtml,
            'searchUrl'=>$this->urlGenerate('search'),
            'workBtn'=>getWorkBtn($this->initModule['power'],null,$this->urlGenerate('doDelete'),null)
        ];
        return $this->doRender('list',$assignData);
    }
    /**
     * 删除
     * @param string keys
     * @return array [code,data]
     */
    public function dod(){
        return (new CommonWork($this,$this->currentControlIns))->doDelete(true);
    }
    /**
     *  记录日志
     * @param string $desc
     * @return mixed
     */
    public function doRecord($desc=''){
        $workName = '';
        switch ($this->initModule['action']){
            case 'doi':
                $workName = '添加 | ';
                break;
            case 'doe':
                $workName = '更新 | ';
                break;
            case 'd':
                $workName = '删除 | ';
                break;
        }
        $data = [];
        $data['manager_id'] = $this->initManager['id'];
        $data['column'] = $this->initModule['controller'];
        $data['work'] = $this->initModule['action'];
        $data['content'] = $this->initManager['name'].' | '.$this->initModule['controller'].' | '.$workName.$desc;
        $data['ctime'] = $this->init('time');

        return (new ManagerWorkLogModel())->inData($data);
    }
}
