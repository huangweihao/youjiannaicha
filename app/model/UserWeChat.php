<?php
/**
 * 用户微信模型
 * Class UserWeChat
 * @package app\model
 */
namespace app\model;

class UserWeChat extends Base{
    protected $table = '';
    function __construct(){
        $this->table = $this->tableInit('user_wechat');
    }
    /**
     * 获取列表
     * @param string $type
     * @param array $condition 查询条件
     * @param array $order 排序规则
     * @param int $limit
     * @return mixed
     */
    public function getList($type,$condition=[],$order=[],$limit=100){
        switch ($type){
            case 'choose':
                $field = ['rule'=>'raw','data'=>"CONCAT(id,'_',user_id) AS id,mobile"];
                break;
            case 'excel':
                $field = ['id','user_id', 'mobile', 'last_ip', 'last_time', 'ctime'];
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
                $field = ['id','mobile','last_ip','last_time','user_id','utime','ctime'];
                $table = $this->table;
                break;
            case 'test':
                $field = ['wechat.id'];
                $table = [
                    $this->table => [
                        'alias' => 'wechat',
                        'joins' => [
                            $this->tableInit('user', ['wechat.id'])=>[
                                'alias' => 'user',
                                'rule'=>'left',
                                'val'=>'wechat.user_id=user.id'
                            ]
                        ]
                    ]
                ];
                break;
        }
        return $this->getListPageData($table,$field,$condition,$order,$page,$pageSize);
    }

    /**
     * 获取详情
     * @param $type
     * @param $condition
     * @return array
     */
    public function getDetail($type, $condition)
    {
        switch ($type){
            case 'main':
                $field=['last_ip'];
                break;
            case 'record':
                $field=['mobile'];
                break;
        }
        $table = $this->table;
        return $this->getDetailData($table, $field, $condition);
    }

    public function userSuffix($userId)
    {
        return substr($userId, -2);
    }
}