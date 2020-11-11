<?php
/**
 * amqp协议操作类，可以访问rabbitMQ
 * 需先安装php_amqp扩展
 * Date: 2019/7/18
 * Time: 11:39
 */
namespace app\common\rabbitmq;

use think\facade\Log;

class MqConsumer{
    public $callback;
    function __construct(object $callbackObject){
        $this->callback = $callbackObject;
    }
    public function doConsume($mqConfig=[]){
        try{
            $mqConn = new MqConn();
            $queue = $mqConn->getQueueInstance($mqConfig);
            while (True) {
                $queue->doConsume([$this,'doUpdate']);
            }
        }catch (\Exception $e){
            Log::write("[消费失败]：".$e->getMessage().'，配置：'.json_encode($mqConfig), 'mq-error');
        }
    }

    public function doUpdate($mqInstance, $queueInstance){
        $msg = $mqInstance->getBody();
        $this->callback->consume($msg);
        $queueInstance->ack($mqInstance->getDeliveryTag());
    }
}
