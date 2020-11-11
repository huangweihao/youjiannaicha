<?php
/**
 * 模板框架主页
 * Class Main
 * @package app\admin\controller
 */
namespace app\controller;

class Main extends Base{
    /**
     * 管理主页显示
     * @remark 模板frame
     */
    public function show(){
        return $this->doRender('show');
    }
}
