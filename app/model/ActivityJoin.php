<?php
/**
 * 活动报名模型
 * @package Model/ActivityJoin
 */
namespace app\model;
class ActivityJoin extends Base {
    protected $table = '';
    function __construct(){
        $this->table = $this->tableInit('activity_join');
    }
    /**
     * 获取列表
     * @param string $type
     * @param array $condition 查询条件
     * @param array order 排序规则
     * @param int $limit
     * @return array 查询结果
     */
    public function getList($type,$condition=[],$order=[],$limit=1000){
        switch ($type){
            case 'list':
                $field = ['user_id','activity_id',];
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
                $field = ['id','phone','identity','user_id','username','activity_id','ctime'];                break;
        }
        return $this->getListPageData($this->table,$field,$condition,$order,$page,$pageSize);
    }
    /**
     * 获取基础信息
     * @param string $type
     * @param array $condition
     * @return array 查询结果
     */
    public function getDetail($type,$condition){
        switch ($type){
            case 'edit':
                $field = ['id','content','address','title','begin_time','end_time','join_begin','join_end','status','ctime'];
                break;
        }
        return $this->getDetailData($this->table,$field,$condition);
    }
}