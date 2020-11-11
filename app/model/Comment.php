<?php
/**
 * 社区评论模型
 * @package Model/Lesson
 */
namespace app\model;
class Comment extends Base {
    protected $table = '';
    function __construct(){
        $this->table = $this->tableInit('comment');
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
                $field = ['id','title','desc','type','video','cover','content','user_id','utime','ctime','status'];
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
                $field = ['a.id','a.content','a.ctime','a.utime','a.user_id,c.content as mes_content','a.praise','a.status','b.username'];
                $table = [
                    $this->table => [
                        'alias' => 'a',
                        'joins' => [
                            $this->tableInit('volunteer')=>[
                                'alias' => 'b',
                                'rule'=>'left',
                                'val'=>'a.user_id=b.id'
                            ],
                            $this->tableInit('message')=>[
                                'alias' => 'c',
                                'rule'=>'left',
                                'val'=>'a.mes_id=c.id'
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
                $field = ['id','title','desc','type','video','cover','content','user_id','utime','ctime','status'];
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