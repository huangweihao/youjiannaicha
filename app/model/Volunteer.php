<?php
/**
 * 志愿者模型
 * @package Model/Volunteer
 */
namespace app\model;
class Volunteer extends Base {
    protected $table = '';
    function __construct(){
        $this->table = $this->tableInit('volunteer');
    }
    /**
     * 获取列表
     * @param string $type
     * @param array $condition 查询条件
     * @param array order 排序规则
     * @param int $limit
     * @return array 查询结果
     */
    public function getList($type,$condition=[],$order=[],$limit=100){
        switch ($type){
            case 'choose':
                $field = ['id','username'];
                break;
            case 'excel':
                $field = ['id', 'username', 'phone', 'experience', 'identity'];
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
                $field = ['id','username','phone','age','experience','identity','health','status','ctime','utime'];
                break;
            case 'info':
                $field = ['id','username','phone','skill','experience','identity','health','status','ctime','utime'];
                break;
            case 'station':
                $field = ['id', 'username', 'phone', 'experience', 'identity'];
                break;
        }
        return $this->getListPageData($this->table,$field,$condition,$order,$page,$pageSize);
    }
    /**
     * 获取志愿者基础信息
     * @param string $type
     * @param array $condition
     * @return array 查询结果
     */
    public function getDetail($type,$condition){
        switch ($type){
            case 'edit':
                $field = ['id','level','level','username','phone','age','experience','identity','health','status','ctime','utime'];
                break;
        }
        return $this->getDetailData($this->table,$field,$condition);
    }
    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function inData($data){
        return $this->insertDbData($this->table,$data);
    }
    /**
     * 更新信息
     * @param array $condition
     * @param array $data
     * @return mixed
     */
    public function upData($condition,$data){
        return $this->updateDbData($this->table,$data,$condition);
    }
    /**
     * 删除信息
     * @param array $condition
     * @return mixed
     */
    public function delData($condition){
        return $this->delDbData($this->table,$condition);
    }
}