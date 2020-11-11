<?php
/**
 * 登录日志管理
 * Class LoginLog
 * @package app\controller
 */
namespace app\controller;
use app\model\LoginLog as LoginLogModel;

class LoginLog extends Base{
    /**
     * 登录日志页面显示
     * @remark 通过vkey判断是否是弹出层打开
     * @remark log/index
     */
    public function getList(){
        $pageHtml = '';
        $condition = [];
        $isLayerOpen = false;
        if(!empty($this->vkey)){
            $isLayerOpen = true;
            $condition['manager_id'] = ['rule'=>'equal','val'=>$this->vkey];
        }else{
            $condition['manager_id'] = ['rule'=>'equal','val'=>$this->initManager['id']];
        }
        $order = ['id'=>'desc'];
        $result = (new LoginLogModel())->getListPage('list',$condition,$order,$this->page,$isLayerOpen?$this->pageSizeLayer:$this->pageSize);
        $assignData = [
            'isLayerOpen'=>$isLayerOpen,
            'data'=>$result
        ];
        if($result['total'] > 0){
            $pageHtml = $this->pagination($result['total'],$isLayerOpen?$this->pageSizeLayer:$this->pageSize,0,$isLayerOpen?'simple':'all');
            $assignData['managerData'] =$this->getManager();
        }
        $assignData['pageHtml'] = $pageHtml;

        return $this->doRender('list',$assignData);
    }
    /**
     * 获取管理员
     * @remark 公用方法处理
     */
    private function getManager(){
        $backData = [];
        $result = $this->cacheFileIns->doing('file',[
            'work' => 'get',
            'key' => 'manager'
        ]);
        if(!empty($result)){
            $result = @json_decode($result,true);
            if(!empty($result)) {
                foreach ($result as $key => $value) {
                    $backData[$value['id']] = $value['name'];
                }
            }
        }
        return $backData;
    }
}
