<?php

namespace app\common\storage\engine;

use think\Exception;

/**
 * 存储引擎抽象类
 * Class server
 * @package app\common\library\storage\drivers
 */
abstract class Server
{
    /* @var $file \think\File */
    protected $file;
    protected $error;
    protected $fileName;
    protected $fileInfo;

    /**
     * 构造函数
     * Server constructor.
     */
    protected function __construct()
    {
    }

    /**
     * 设置上传的文件信息
     * @param string $name
     * @throws Exception
     */
    public function setUploadFile($name)
    {
        // 接收上传的文件
        $this->file = request()->file($name);
        if (empty($this->file)) {
            throw new Exception('未找到上传文件的信息');
        }
        // 生成保存文件名
        $this->fileName = $this->buildSaveName();
        // 文件信息
        //$this->fileInfo = $this->file->getFileInfo();
        $this->fileInfo = [
            'extension' => $this->file->getOriginalName(),
            'name' => $this->file->getFilename().$this->file->getOriginalExtension(),
            'size' => $this->file->getSize()
        ];
    }

    /**
     * 文件上传
     * @return mixed
     */
    abstract protected function upload();

    /**
     * 文件删除
     * @param $fileName
     * @return mixed
     */
    abstract protected function delete($fileName);

    /**
     * 返回上传后文件路径
     * @return mixed
     */
    abstract public function getFileName();

    /**
     * 返回文件信息
     * @return mixed
     */
    public function getFileInfo()
    {
        return $this->fileInfo;
    }

    /**
     * 返回错误信息
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 生成保存文件名
     */
    private function buildSaveName()
    {
        // 要上传图片的本地路径
        $realPath = $this->file->getRealPath();
        // 扩展名
        $ext = pathinfo($this->file->getOriginalName(), PATHINFO_EXTENSION);
        // 自动生成文件名
        return date('YmdHis') . substr(md5($realPath), 0, 5)
            . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT) . '.' . $ext;
    }

}
