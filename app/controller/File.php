<?php
/**
 * 上传文件管理
 * Class File
 * @package app\admin\controller
 */
namespace app\controller;

use app\model\UploadFile as UploadFileModel;
use app\model\UploadGroup as UploadGroupModel;

class File extends Base{
    /**
     * 文件库列表
     * @param string $type
     * @param int $group_id
     * @return mixed
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function fileList($type='image',$group_id=-1){
        // 分组列表
        $condition = ['group_type'=>['rule'=>'equal','val'=>$type],'status'=>['rule'=>'equal','val'=>1]];
        $order = ['rank'=>'desc','group_id'=>'desc'];
        $group_list = (new UploadGroupModel())->getList($condition,$order);
        // 文件列表
        $condition = ['file_type'=>['rule'=>'equal','val'=>$type],'status'=>['rule'=>'equal','val'=>1]];
        if($group_id > 0){
            $condition['group_id'] = ['rule'=>'equal','val'=>intval($group_id)];
        }
        $order = ['file_id'=>'desc'];
        $file_list = (new UploadFileModel())->getlist($condition, $order,32);

        return $this->doResponse('200','', compact('group_list', 'file_list'));
    }
    /**
     * 新增分组
     * @param $group_name
     * @param string $group_type
     * @return array
     */
    public function addGroup($group_name, $group_type = 'image'){
        $back = [];
        $code = 200;
        $result = (new UploadGroupModel)->insertFileGroup(['group_name'=>$group_name,'group_type'=>$group_type]);
        if (!empty($result)) {
            $back['group_id'] = $result;
            $back['group_name'] = $group_name;
        }else{
            $code = 713;
        }
        return $this->doResponse($code, $back);
    }
    /**
     * 编辑分组
     * @param $group_id
     * @param $group_name
     * @return array
     * @throws \think\exception\DbException
     */
    public function editGroup($group_id, $group_name){
        $back = [];
        $code = 200;
        $condition = ['group_id'=>['rule'=>'equal','val'=>$group_id]];
        $data = ['group_name'=>$group_name];
        $result = (new UploadGroupModel)->updateFileGroup($condition,$data);
        if(isset($result)){
            if (!empty($result)) {
                $back['group_name'] = $group_name;
            }else{
                $code = 201;
            }
        }else{
            $code = 713;
        }
        return $this->doResponse($code, $back);
    }
    /**
     * 删除分组
     * @param $group_id
     * @return array
     * @throws \think\exception\DbException
     */
    public function deleteGroup($group_id){
        $code = 200;
        $data = ['status'=>0];
        $condition = ['group_id'=>['rule'=>'equal','val'=>$group_id]];
        $result = (new UploadGroupModel)->updateFileGroup($condition,$data);
        if(isset($result)){
            if (empty($result)) {
                $code = 201;
            }
        }else{
            $code = 713;
        }
        return $this->doResponse($code);
    }
    /**
     * 批量删除文件
     * @param $fileIds
     * @return array
     */
    public function deleteFiles($fileIds){
        $code = 200;
        $data = ['status'=>0];
        $condition = ['file_id'=>['rule'=>'in','val'=>$fileIds]];
        $result = (new UploadFileModel())->updateFile($condition,$data);
        if(isset($result)){
            if (empty($result)) {
                $code = 201;
            }
        }else{
            $code = 713;
        }
        return $this->doResponse($code);
    }
    /**
     * 批量移动文件分组
     * @param $group_id
     * @param $fileIds
     * @return array
     */
    public function moveFiles($group_id, $fileIds){
        $code = 200;
        $condition = ['file_id'=>['rule'=>'in','val'=>$fileIds]];
        $data = ['group_id'=>$group_id];
        $result = (new UploadFileModel)->updateFile($condition,$data);
        if (!isset($result)) {
            $code = 713;
        }
        return $this->doResponse($code);
    }
}
