<?php
/**
 * 权限模型类
 * Class Role
 * @package app\admin\model
 */
namespace app\model;

class Role extends Base{
    protected $table = '';
    function __construct(){
        $this->table = $this->tableInit('role');
    }
    /**
     * 获取列表
     * @param string $type
     * @param array $condition 查询条件
     * @param array $order 排序规则
     * @param int $limit
     * @return array 查询结果
     */
    public function getList($type,$condition=[],$order=[],$limit=100){
        switch ($type){
            case 'cache':
                $field = ['id','name','status'];
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
                $field = ['id','name','status','ctime','utime'];
                break;
        }
        return $this->getListPageData($this->table,$field,$condition,$order,$page,$pageSize);
    }
    /**
     * 获取详情数据
     * @param string $type 取用字段标识
     * @param array $condition 查询条件
     * @return array 查询结果
     */
    public function getDetail($type,$condition){
        switch ($type){
            case 'edit':
                $field = ['id','name','power','status'];
                break;
            case 'login':
                $field = ['power','status'];
                break;
        }
        return $this->getDetailData($this->table,$field,$condition);
    }
    /**
     * 添加
     * @param int id
     * @param array data
     * @return boolean
     */
    public function inData($data){
        return $this->insertDbData($this->table,$data);
    }
    /**
     * 更新
     * @param array $condition
     * @param array $data
     * @return mixed
     */
    public function upData($condition,$data){
        return $this->updateDbData($this->table,$data,$condition);
    }
    /**
     * 删除
     * @param array $condition
     * @return boolean
     */
    public function delData($condition){
        return $this->delDbData($this->table,$condition);
    }
}