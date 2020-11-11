<?php
/**
 * 文件库分组模型.
 * Class UploadGroup
 * @package app\model
 */
namespace app\model;

class UploadGroup extends Base{
    protected $table = '';
    function __construct(){
        $this->table = $this->tableInit('upload_group');
    }
    /**
     * 获取列表记录
     * @param string $condition
     * @param string $order
     * @param int $limit
     * @return false|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getList($condition,$order,$limit=100){
        $field = ['group_id','group_name'];
        return $this->getListData($this->table,$field,$condition,$order,$limit);
    }
    /**
     * 添加新记录
     * @param $data
     * @return false|int
     */
    public function insertFileGroup($data){
        return $this->insertDbData($this->table,$data);
    }
    /**
     * 更新记录
     * @param $data
     * @return bool|int
     */
    public function updateFileGroup($condition,$data){
        return $this->updateDbData($this->table,$data,$condition);
    }
    /**
     * 删除记录
     * @return int
     */
    public function delFileGroup($condition){
        return $this->delDbData($this->table,$condition);
    }

}
