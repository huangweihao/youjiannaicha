<?php
/**
 * 登录日志模型类
 * Class Loginlog
 * @package app\admin\model
 */
namespace app\model;

class LoginLog extends Base{
    protected $table = '';
    function __construct(){
        $this->table = $this->tableInit('manager_login_log');
    }
    /**
     * 获取日志列表
     * @param array condition 查询条件
     * @param array order 排序规则
     * @param int page 当前页码
     * @return array 查询结果
     */
    public function getListPage($type,$condition=[],$order=[],$page=1,$pageSize=15){
        switch ($type){
            case 'list':
                $field = ['manager_id','ip','ctime'];
                break;
        }
        return $this->getListPageData($this->table,$field,$condition,$order,$page,$pageSize);
    }
    /**
     * 添加
     * @param array $data
     * @return boolean
     */
    public function inData(&$data){
        return $this->insertDbData($this->table,$data);
    }
}