<?php
namespace DevPhpIO\Signal;

use DevPhpIO\WorkBase;
/**
 * 信号模型
 */
class Worker extends WorkBase
{
   
    protected function accept(){
        while (true) {
            // 监听是否存在连接
            $conn = stream_socket_accept($this->server);
            if (!empty($conn)) {
                // 触发建立连接事件
                $this->events['connect']($this, $conn);
            }

            // 信号模型实现
            pcntl_signal(SIGIO, $this->sigHandler($conn));
            posix_kill(posix_getpid(), SIGIO);
            pcntl_signal_dispatch();
        }
    }

    public function sigHandler($conn)
    {
        return function($sig) use ($conn){
            switch ($sig) {
            case SIGIO:
                $this->sendMessage($conn);
                break;
            }
        };
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
