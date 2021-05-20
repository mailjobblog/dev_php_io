<?php
namespace DevPhpIO\Async\Swoole;

use DevPhpIO\WorkBase;
use Swoole\Event;

class WorkerSwoole extends WorkBase
{
    protected function accept()
    {
        Event::add($this->server, $this->createConn());
    }
    // 创建连接
    public function createConn()
    {
        return function($socket){
            $conn = @stream_socket_accept($this->server);
            if (!empty($conn) && get_resource_type($conn) == "stream") {
                //触发事件的连接的回调
                $this->events['connect']($this, $conn);
                Event::add($conn, $this->sendMessage());
            }
        };
    }

    public function sendMessage()
    {
        return function($conn){
            $buffer = fread($conn, 65535);
            if ('' === $buffer || false === $buffer) {
                $this->checkConn($buffer, $conn);
            } else {
                $this->events['receive']($this, $conn, $buffer);
            }
        };
    }

    public function checkConn($buffer, $conn)
    {
        if (strlen($buffer) === 0) {
            if (get_resource_type($conn) == "stream") {
                // 关闭连接
                $this->close($conn);
            }
            $this->events['close']($this, $conn);
        }
    }
    public function delEvent($conn)
    {
        Event::del($conn);
    }
}