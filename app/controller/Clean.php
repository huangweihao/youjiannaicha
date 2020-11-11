<?php
/**
 * 清除缓存类
 * @package App/Controller/Clean
 */
namespace app\controller;
class Clean extends Base {
    public function doCache(){
        return $this->doResponse(200);
    }
}
