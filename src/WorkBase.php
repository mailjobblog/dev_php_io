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

    protected $type = 'tcp';

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
        \fclose($client);
        dd($client);
    }

    /**
     * 启动服务
     */
    public function start(){
        dd($this->events,'服务注册的事件');
        $this->check();// 检查启动信息
        $this->accept(); // 启动
    }

    /**
     * 用于校验服务启动信息
     * 
     * 校验是否注册事件，以及注册的事件类型
     */
    public function check()
    {
        if ($this->type == 'tcp') {
            if (empty($this->event['connect']) || !$this->event['connect'] instanceof Closure) {
                dd("tcp服务必须要有回调事件: connect");
                exit;
            }

            if (empty($this->event['receive']) || !$this->event['receive']  instanceof Closure ) {
                dd("tcp服务必须要有回调事件: receive");
                exit;
            }

            if (empty($this->event['close']) || !$this->event['close'] instanceof Closure ) {
                dd("tcp服务必须要有回调事件: close");
                exit;
            }
        } else if ($this->type == 'http') {
            if (empty($this->event['request']) || !$this->event['request'] instanceof Closure ) {
                dd("http服务必须要有回调事件: request");
                exit;
            }
        }
    }
}
