<?php
/**
 * amqp协议操作类，可以访问rabbitMQ
 * 需先安装php_amqp扩展
 * Date: 2019/7/18
 * Time: 11:39
 */

namespace app\common\rabbitmq;

class MqProducer{
    /**
     * 发布队列消息
     * @param json string $message
     * @param array $mqConfig
     */
    public function doPublish($message,$mqConfig=[]){
        try{
            $mqConn = new MqConn();
            $mqConn->setPublish($message,$mqConfig);
        }catch (\Exception $e){
            Log::write("[发布失败]：".$e->getMessage().'，配置：'.json_encode($mqConfig).'，信息：'.json_encode($message), 'mq-error');
        }
    }
}
