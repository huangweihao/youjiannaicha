<?php
/**
 * 获取变量过滤
 */
namespace app\common;

use think\facade\Request;

class DoRequest{
    /**
     * 获取请求的数据
     * @param string type 请求类型 post/get/put/delete
     * @param string key 数据的key值
     * @return string 返回获取值
     */
    public function getIsAjax(){
        return Request::isAjax();
    }
    /**
     * 获取请求的数据
     * @param string $type 请求类型 post/get/put/delete
     * @param string $key 数据的key值
     * @param string $default
     * @param string $filter 是否过滤
     * @return mixed
     */
    public function getRequest($type='post',$key='',$default='',$filter=''){
        switch ($type){
            case 'middleware':
                return empty($key) ? '' : Request::middleware($key);
                break;
            case 'header':
                return empty($key) ? Request::header() : Request::header($key,$default,$filter);
                break;
            case 'put':
                return empty($key) ? Request::put() : Request::put($key,$default,$filter);
                break;
            case 'delete':
                return empty($key) ? Request::delete() : Request::delete($key,$default,$filter);
                break;
            case 'token':
                return Request::buildToken('__token__');
                break;
            default:
                return empty($key) ? Request::param() : Request::param($key,$default,$filter);
                break;
        }
    }
    /**
     * 获取请求的模型控制器和方法
     * @return mixed
     */
    public function getControllerMethod(){
        return [Request::controller(),Request::action()];
    }
}