<?php
/**
 * 上传文件模型类.
 * Class UploadFile
 * @package app\model
 */
namespace app\model;

class UploadFile extends Base{
    protected $table = '';
    function __construct(){
        $this->table = $this->tableInit('upload_file');
    }
    /**
     * 获取列表记录
     * @param int $groupId 分组id
     * @param string $fileType 文件类型
     * @param bool|int $isRecycle
     * @return \think\Paginator
     * @throws \think\exception\DbException
     */
    public function getList($condition, $order, $pageSize,$query=''){
        $field = ['file_id','file_name','file_url'];
        return $this->getListPaginateData($this->table,$field,$condition,$order,$pageSize,$query);
    }
    /**
     * 移入|移出回收站
     * @param bool $isRecycle
     * @return false|int
     */
    public function setRecycle($isRecycle = true){
        return $this->save(['is_recycle' => (int)$isRecycle]);
    }
    /**
     * 添加文件
     * @param int id
     * @param array data
     * @return boolean
     */
    public function insertFile($data){
        return $this->insertDbData($this->table,$data);
    }
    /**
     * 根据文件Id，更新文件信息
     * @param array condition
     * @param array data
     * @return boolean
     */
    public function updateFile($condition,$data){
        return $this->updateDbData($this->table,$data,$condition);
    }
    /**
     * 删除记录
     * @return int
     */
    public function delFile($condition){
        return $this->delDbData($this->table,$condition);
    }
}
