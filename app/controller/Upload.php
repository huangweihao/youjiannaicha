<?php
/**
 * 文件库管理
 * Class Upload
 * @package app\admin\controller
 */
namespace app\controller;

use app\model\UploadFile as UploadFileModel;
use app\common\storage\Driver as StorageDriver;

class Upload extends Base{
    protected $use = 'qcloud';
    protected $config = [
        'engine'=>[
            'qcloud'=>[]
         ]
    ];
    function __construct(){
        parent::__construct();
        $this->config['engine']['qcloud']['bucket'] = config('user.qcloud.base.bucket');
        $this->config['engine']['qcloud']['region'] = config('user.qcloud.base.region');
        $this->config['engine']['qcloud']['secret_id'] = config('user.qcloud.base.secret_id');
        $this->config['engine']['qcloud']['secret_key'] = config('user.qcloud.base.secret_key');
        $this->config['engine']['qcloud']['domain'] = config('user.qcloud.base.domain');
    }
    /**
     * 图片上传接口
     * @param int $group_id
     * @return array
     * @throws \think\Exception
     */
    public function image($group_id = -1){
        // 实例化存储驱动
        $StorageDriver = new StorageDriver($this->config,$this->use);
        // 设置上传文件的信息
        $StorageDriver->setUploadFile('iFile');
        // 上传图片
        if (!$StorageDriver->upload()) {
            return $this->doResponse(621,$StorageDriver->getError());
        }
        // 图片上传路径
        $fileName = $StorageDriver->getFileName();
        // 图片信息
        $fileInfo = $StorageDriver->getFileInfo();
        // 添加文件库记录
        $uploadFile = $this->addUploadFile($group_id, $fileName, $fileInfo, 'image');
        // 图片上传成功
        return $this->doResponse(202,'',$uploadFile);
    }
    /**
     * 添加文件库上传记录
     * @param $group_id
     * @param $fileName
     * @param $fileInfo
     * @param $fileType
     * @return mixed
     */
    private function addUploadFile($group_id, $fileName, $fileInfo, $fileType)
    {
        $data = [
            'group_id' => $group_id > 0 ? (int)$group_id : 0,
            'storage' => $this->use,
            'file_url' => $this->config['engine'][$this->use]['domain'],
            'file_name' => $fileName,
            'file_size' => $fileInfo['size'],
            'file_type' => $fileType,
            'extension' => pathinfo($fileName, PATHINFO_EXTENSION),
            'status' => 1
        ];
        // 添加文件库记录
        $uploadFileModel = new UploadFileModel;
        $result = $uploadFileModel->insertFile($data);
        if(!empty($result)){
            $data['file_id'] = $result;
            $data['file_path'] = $data['file_url'].'/'.$data['file_name'];
            return $data;
        }else{
            return false;
        }
    }

    public function upVideo($group_id=-1)
    {
        // 实例化存储驱动
        $StorageDriver = new StorageDriver($this->config,$this->use);
        // 设置上传文件的信息
        $StorageDriver->setUploadFile('file');
        // 上传文件
        if (!$StorageDriver->upload()) {
            return $this->doResponse(621,$StorageDriver->getError());
        }
        // 文件上传路径
        $fileName = $StorageDriver->getFileName();
        // 文件信息
        $fileInfo = $StorageDriver->getFileInfo();
        // 添加文件库记录
        $uploadFile = $this->addUploadFile($group_id, $fileName, $fileInfo, 'video');
        // 文件上传成功
        return json($uploadFile);
    }

}
