<?php
namespace DevPhpIO\Signal;

use DevPhpIO\WorkBase;
/**
 * 多路复用模型
 */
class Worker extends WorkBase
{
    /**
     * 服务的连接 socket 定义
     */
    protected $socket = [];

    /**
     * 资源初始化
     */
    public function __construct($host, $port)
    {
        parent::__construct($host, $port);

        // 设置为非阻塞模型
        stream_set_blocking($this->server, 0);

        // 获取 server 的资源标识，并进行数组的赋值
        // 记录服务的 socket
        $this->socket[(int)$this->server] = $this->server;
        
    }

    protected function accept(){
        while(true) {
            $reads = $this->socket;
            stream_select($reads, $w, $e, 0);
            foreach($reads as $key => $socket) {
                if($socket == $this->server) {
                    // 有新的连接，要建立通信
                    $conn = $this->createConnect();
                    if($conn) {
                        $this->socket[(int)$conn] = $conn;
                    }else{
                        dd('连接建立失败');
                    }
                }else{
                    // 进行消息通信
                    $this->sendMessage($socket);
                }
            }
        }
    }

    /**
     * 建立连接
     */
    protected function createConnect(){
         // 监听是否存在连接
         $conn = stream_socket_accept($this->server);
         if (!empty($conn)) {
            $this->events['connect']($this, $conn);
            return $conn;
        }
        return null;
    }

     /**
      * 消息通信
      */
    protected function sendMessage($conn){
         // 接收服务的信息
         $data = fread($conn, 65535);

         if ('' === $data || false === $data) {
            $this->checkConnect($data, $conn);
        } else {
            $this->events['receive']($this, $conn, $data);
        }
    }

    /**
     * 校验连接
     */
    protected function checkConnect($buffer, $conn)
    {
        if (strlen($buffer) === 0) {
            if (!get_resource_type($conn) == "Unknown"){
                // 断开连接
                $this->close($conn);
            }
            call_user_func($this->events['close'], $this, $conn );
            unset($this->socket[(int) $conn]);
        }
    }
}
