<?php
namespace app\controller;
/**
 * 模板框架主页
 * Class Frame
 * @package app\controller
 */

class Frame extends Base{
    function __construct(){
        parent::__construct();
    }
    /**
     * 管理主页显示
     * @remark 模板frame
     */
    public function show(){
        $cacheMenu = $this->cacheFileIns->doing('file',[
            'work' => 'get',
            'key' => hash('md5',$this->initManager['id']).'_menu'
        ]);
        if(empty($cacheMenu)){
            $cacheMenu = ['name'=>'','parent'=>'','child'=>''];
        }else{
            $cacheMenu = @json_decode($cacheMenu,true);
            if(empty($cacheMenu)){
                $cacheMenu = ['name'=>'','parent'=>'','child'=>''];
            }else{
                $cacheMenu['parent'] = htmlspecialchars_decode($cacheMenu['parent']);
                $cacheMenu['child'] = htmlspecialchars_decode($cacheMenu['child']);
            }
        }
        $assignData = [
            'cacheMenu' => $cacheMenu,
            'logoutUrl' => $this->urlGenerate('logout')
        ];
        return $this->doRender('/frame',$assignData);
    }
}
