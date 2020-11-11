<?php
/**
 * 商圈表
 * Class Zone
 * @package app\model
 */
namespace app\model;

class Zone extends Base{
    protected $table = '';
    function __construct(){
        $this->table = $this->tableInit('zone');
    }
    /**
     * 获取列表
     * @param string $type
     * @param array $condition 查询条件
     * @param array $order 排序规则
     * @param int $limit 查询数量
     * @return array 查询结果
     */
    public function getList($type,$condition=[],$order=[],$limit=500){
        switch ($type){
            case 'all':
                $field = ['id','district_id','name','status'];
                break;
        }
        return $this->getListData($this->table,$field,$condition,$order,$limit);
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
            case 'main':
            case 'shop':
                $field = ['name'];
                break;
            case 'edit':
                $field = ['id','district_id','name','status','rank'];
                break;
        }
        return $this->getDetailData($this->table,$field,$condition,$order);
    }
    /**
     * 添加数据
     * @param array data
     * @return boolean
     */
    public function inData($data){
        return $this->insertDbData($this->table,$data);
    }
    /**
     * 更新数据
     * @param array condition 更新条件
     * @param array data 更新字段
     * @return mixed
     */
    public function upData($condition,$data){
        return $this->updateDbData($this->table,$data,$condition);
    }
    /**
     * 删除数据
     * @param array condition
     * @return boolean
     */
    public function delData($condition){
        return $this->delDbData($this->table,$condition);
    }
}