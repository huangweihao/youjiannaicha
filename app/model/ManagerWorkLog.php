<?php
/**
 * 管理员日志类
 * Class ManagerWorkLog
 * @package app\admin\model
 */
namespace app\model;

class ManagerWorkLog extends Base{
    protected $table = '';
    function __construct(){
        $this->table = $this->tableInit('manager_work_log');
    }
    /**
     * 获取列表
     * @param string $type
     * @param array $condition 查询条件
     * @param array $order 排序规则
     * @param int $page 当前页码
     * @param int $pageSize 每页数量
     * @return array 查询结果
     */
    public function getListPage($type,$condition=[],$order=[],$page=1,$pageSize=15){
        switch ($type){
            case 'list':
                $field = ['id','content','ctime'];
                break;
        }
        return $this->getListPageData($this->table,$field,$condition,$order,$page,$pageSize);
    }
    /**
     * 添加数据
     * @param array $data
     * @return mixed
     */
    public function inData($data){
        return $this->insertDbData($this->table,$data);
    }
}