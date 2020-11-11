<?php
/**
 * 第三方活动模型
 * @package Model/ThirdActivity
 */
namespace app\model;
class ThirdActivity extends Base {
    protected $table = '';
    function __construct(){
        $this->table = $this->tableInit('third_activity');
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
            case 'cache':
                $field = ['id','title','content','begin_time','end_time','join_begin','join_end','user_id','address','utime','ctime','status'];
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
        $condition['a.user_id'] = ['rule'=>'notequal','val'=>0];
        switch ($type){
            case 'list':
                $field = ['a.id','a.title','a.content','a.begin_time','a.end_time','a.join_begin','a.join_end','a.user_id','a.address','a.utime','a.ctime','a.status','b.name as thirduser_name'];
                $table = [
                    $this->table => [
                        'alias' => 'a',
                        'joins' => [
                            $this->tableInit('thirdparty_user')=>[
                                'alias' => 'b',
                                'rule'=>'left',
                                'val'=>'a.user_id=b.id'
                            ]
                        ]
                    ]
                ];
                break;
        }
        return $this->getListPageData($table,$field,$condition,$order,$page,$pageSize);
    }
    /**
     * 获取培训基础信息
     * @param string $type
     * @param array $condition
     * @return array 查询结果
     */
    public function getDetail($type,$condition){
        switch ($type){
            case 'edit':
                $field = ['a.id','a.title','a.content','a.begin_time','a.end_time','a.join_begin','a.join_end','a.user_id','a.address','a.utime','a.ctime','a.status','b.name as thirduser_name'];
                $table = [
                    $this->table => [
                        'alias' => 'a',
                        'joins' => [
                            $this->tableInit('thirdparty_user')=>[
                                'alias' => 'b',
                                'rule'=>'left',
                                'val'=>'a.user_id=b.id'
                            ]
                        ]
                    ]
                ];
                $detail_condition['a.id'] = ['rule'=>'equal','val'=>$condition['id']['val']];
                $detail = $this->getDetailData($table,$field,$detail_condition);
                $option_field = ['a.id','title','content','option_id','activity_id'];
                $op_table = [
                    'sj_activity_op_value' => [
                        'alias' => 'a',
                        'joins' => [
                            $this->tableInit('activity_option')=>[
                                'alias' => 'b',
                                'rule'=>'left',
                                'val'=>'a.option_id=b.id'
                            ]
                        ]
                    ]
                ];
                $op_condition['activity_id'] = ['rule'=>'equal','val'=>$condition['id']['val']];
                $order = [];
                $detail['option'] = $this->getListData($op_table,$option_field,$op_condition,$order,100);
                return $detail;
                break;
        }
        return $detail = $this->getDetailData($this->table,$field,$condition);
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