<?php
/**
 * 活动模板管理表
 * Class ActivityTemplate
 * @package app\model
 */
namespace app\model;

class ActivityTemplate extends Base{
    protected $table = '';
    function __construct(){
        $this->table = $this->tableInit('activity_template');
    }
    /**
     * 获取列表带分页
     * @param string $type
     * @param array $condition 查询条件
     * @param array $order 排序规则
     * @param int $page 当前页码
     * @param int $pageSize
     * @return array 查询结果
     */
    public function getListPage($type,$condition=[],$order=[],$page=1,$pageSize=15){
        switch ($type){
            case 'list':
                $field = ['id','name','status','ctime','utime'];
                break;
        }
        return $this->getListPageData($this->table,$field,$condition,$order,$page,$pageSize);
    }
    /**
     * 获取详情
     * @param string $type 查询标识
     * @param array $condition 查询条件
     * @param array $order 排序
     * @return array 查询结果
     */
    public function getDetail($type,$condition,$order=[]){
        switch ($type){
            case 'edit':
                $field = ['id','name','photo','status'];
                break;
        }
        return $this->getDetailData($this->table,$field,$condition,$order);
    }
    /**
     * 添加信息
     * @param array data 数据
     * @return mixed 返回结果
     * @throws
     */
    public function inData(&$data){
        return $this->insertDbData($this->table,$data);
    }
    /**
     * 更新
     * @param array $condition 更新条件
     * @param array $data 数据
     * @return array 返回结果
     */
    public function upData($condition,&$data){
        return $this->updateDbData($this->table,$data,$condition);
    }
}