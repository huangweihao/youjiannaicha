<?php
/**
 * 消息提示页
 * Class Msg
 * @package app\controller
 */
namespace app\controller;
use app\common\Message;

class Msg extends Base{
    /**
     * 消息提示页显示
     * @remark 模板msg/index
     */
    public function show(){
        $code = $this->getRequestParams('get','code');
        $msg = $this->getRequestParams('get','msg');
        $url = $this->getRequestParams('get','url');
        $noticeMsg = '';
        if(!empty($code)){
            $noticeMsg = (new Message())->get($code);
        }
        if(empty($msg)){
            $msg = '';
        }
        if(empty($url)){
            $url = '';
            switch ($code){
                case '401':
                    $url = $this->urlGenerate('login');
                    break;
            }
        }
        if(empty($code)){
            $code = 201;
        }
        switch ($code){
            case '201':
                $noticeTitle = '<span class="am-text-warning">操作执行</span>';
                $noticeIcon = 'am-icon-meh-o am-text-warning';
                break;
            case '200':
            case '202':
                $noticeTitle = '<span class="am-text-success">操作成功</span>';
                $noticeIcon = 'am-icon-smile-o am-text-success';
                break;
            default:
                $noticeTitle = '<span class="am-text-danger">操作失败</span>';
                $noticeIcon = 'am-icon-frown-o am-text-danger';
                break;
        }

        $assignData = [
            'noticeTitle' => $noticeTitle,
            'noticeMsg' => $noticeMsg,
            'noticeIcon' => $noticeIcon,
            'msg' => $msg,
            'url' => $url
        ];
        return $this->doRender('show',$assignData);
    }
    /**
     * 设置分页数量
     * @remark 设置分页数量
     */
    public function pageSet(){
        $code = 200;
        $size = $this->getRequestParams('post','size');
        if(!(!empty($size) && ctype_digit($size) && in_array($size,$this->init('pageSize')))){
            $code = 712;
        }else{
            $this->cacheIns->doing('cookie',[
                'work' => 'set',
                'key' => config('user.base.cookie_select_name'),
                'data' => $size,
                'expire' => 60480000
            ]);
        }
        return $this->doResponse($code);
    }

    // 生成token函数
    public function getToken() {
        $request = \think\facade\Request::instance();
        return $this->doResponse(200,'',['token'=>$request->buildToken()]);
    }
}
