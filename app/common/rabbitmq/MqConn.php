<?php
/**
 * MQ操作类，可以访问rabbitMQ
 * 需先安装php_amqp扩展
 * Date: 2019/7/18
 * Time: 11:39
 */
namespace app\common\rabbitmq;
use think\facade\Log;

class MqConn{
    private $durable = true;
    private $autodelete = false;
    private $_conn = Null;
    private $_queue = Null;

    private function openMQ(){
        if (!$this->_conn) {
            try {
                $configs = config('user.mq');
                $this->_conn = new \AMQPConnection($configs);
                $this->_conn->connect();
            } catch (\AMQPConnectionException $e) {
                Log::record('MQ连接失败，MqConn->openMQ：'.$e->getMessage(), "mq-error");
            }
        }
    }
    /**
     * 发布消息实例
     * @param global string $message
     * @param global array $mqConfig
     * @return boolean
     */
    public function setPublish(&$message,&$mqConfig) {
        try{
            $this->openMQ();
            $channel = new \AMQPChannel($this->_conn);
            $exchange = new \AMQPExchange($channel);
            $exchange->setName($mqConfig['exchange_name']);

            $exchange->setType(AMQP_EX_TYPE_DIRECT);
            if ($this->durable){
                $exchange->setFlags(AMQP_DURABLE);
            }
            if ($this->autodelete){
                $exchange->setFlags(AMQP_AUTODELETE);
            }
            //$exchange->declareExchange();
            $exchange->publish($message,$mqConfig['route_key']);
            return true;
        }catch (\Exception $e){
            Log::write('[发布失败]：'.$e->getMessage().'，配置：'.json_encode($mqConfig).'，参数：'.$message, 'mq-error');
            return false;
        }
    }
    /**
     * 获取队列实例
     * @param global array $mqConfig
     * @return mixed
     */
    public function getQueueInstance(&$mqConfig) {
        if (!$this->_queue) {
            $this->openMQ();
            $this->initConnection($mqConfig);
        }
        return $this->_queue;
    }
    /**
     * 初始化rabbit连接的相关配置
     * @param global array $mqConfig
     * @throws
     */
    private function initConnection(&$mqConfig) {
        $channel = new \AMQPChannel($this->_conn);
        $exchange = new \AMQPExchange($channel);
        $exchange->setName($mqConfig['exchange_name']);

        $exchange->setType(AMQP_EX_TYPE_DIRECT);
        if ($this->durable){
            $exchange->setFlags(AMQP_DURABLE);
        }
        if ($this->autodelete){
            $exchange->setFlags(AMQP_AUTODELETE);
        }
        $exchange->declareExchange();

        $this->_queue = new \AMQPQueue($channel);
        $this->_queue->setName($mqConfig['queue_name']);
        if ($this->durable){
            $this->_queue->setFlags(AMQP_DURABLE);
        }
        if ($this->autodelete){
            $this->_queue->setFlags(AMQP_AUTODELETE);
        }
        $this->_queue->declareQueue();
        $this->_queue->bind($mqConfig['exchange_name'],$mqConfig['route_key']);
    }
    /**
     * 关闭连接
     */
    private function close() {
        if ($this->_conn) {
            $this->_conn->disconnect();
        }
    }
    public function __destruct() {
        $this->close();
    }
}