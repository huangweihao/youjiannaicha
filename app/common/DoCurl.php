<?php
/**
 * CURL请求类
 */
namespace app\common;

class DoCurl{
    /**
     * 请求接口的方法
     * @param int $way 0 get 1 post
     * @param string $url
     * @param array $data
     * @return mixed
     */
    public function getData($way,$url,&$data){
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检测
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Expect:')); //解决数据包大不能提交
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, $way); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回

        $result = curl_exec($curl); // 执行操作
        curl_close($curl); // 关键CURL会话

        return $result; // 返回数据
    }
    /**
     * CURL交互数据格式加密
     * @param array $params
     * @param string $salt
     * @return mixed
     */
    public function encryptParams($params=[],$salt=''){
        $params['cipher'] = $salt;
        ksort($params);
        foreach($params as $key => $value) {
            if(empty($value)){
                unset($params[$key]);
            }
        }
        return hash('md5',(implode('&', $params)));
    }
}