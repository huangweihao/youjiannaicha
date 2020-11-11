<?php
/**
 * 省市区表
 * Class District
 * @package app\model
 */
namespace app\model;

class District extends Base{
    protected $table = '';
    function __construct(){
        $this->table = $this->tableInit('district');
    }
    /**
     * 获取记录列表
     * @param string $type
     * @param array $condition 查询条件
     * @param array $order 排序规则
     * @param int $limit 查询数量
     * @return array 查询结果
     */
    public function getList($type,$condition=[],$order=[],$limit=500){
        switch ($type){
            case 'zone':
                $field = ['adcode','name'];
                break;
        }
        return $this->getListData($this->table,$field,$condition,$order,$limit);
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
                $field = ['id','adcode','name','longitude','latitude','status','level','rank'];
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
                $field = ['id','adcode','name','longitude','latitude','status','level','rank'];
                break;
            case 'main':
                $field = ['name'];
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