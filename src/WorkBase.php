<?php
namespace DevPhpIO;
/**
 * 基础基类
 */
abstract class WorkBase {

    protected $server;

    // 事件
    protected $events = [
        // 回复
        // 'receive' => null,
        // 连接
        // 'connect' => null
    ];

    protected $config;

    public function __construct($host, $port){
        $this->server = stream_socket_server("tcp://{$host}:{$port}");
        echo "tcp://{$host}:{$port}\n";
    }

    /**
     * 建立连接
     */
    abstract protected function accept();

    /**
     * 记录事件
     * 
     * @param event 事件
     * @param call 方法
     */
    public function on($event, $call){
        $this->events[strtolower($event)] = $call;
    }

    /**
     * 设置配置
     */
    public function set(){}

    /**
     * 回复消息
     */
    public function send($client,$data){
        fwrite($client, $data);
    }

    /**
     * 关闭
     */
    public function close($client){

    }

    /**
     * 启动服务
     */
    public function start(){
        dd($this->events,'服务注册的事件');
        $this->accept();
    }
}
