<?php
namespace DevPhpIO\Blocking;

use DevPhpIO\WorkBase;
/**
 * 阻塞模型和非阻塞模型继承类
 */
class Worker extends WorkBase
{
    protected function accept(){
        while(true){
            // 监听是否存在连接
            $conn = stream_socket_accept($this->server);
            // dd($conn);

            if (!empty($conn)) {
                // 触发建立连接事件
                $this->events['connect']($this, $conn);

                // 接收服务的信息
                $data = fread($conn, 65535);
                $this->events['receive']($this, $conn, $data);
            }

            # 此处缺乏心跳检测 #

            // 判断连接是否断开
            if (!empty($conn) && \get_resource_type($conn) == "Unknown") {
                $this->events['close']($this, $conn);// 断开连接
            }
        }
    }

    public function index()
    {
        return "Test：this is DevPhpIO/indexClass";
    }
}
