<?php
namespace DevPhpIO\Async\Swoole;

use DevPhpIO\WorkBase;
use Swoole\Event;

class WorkerSwoole extends WorkBase
{
    protected function accept() {
        Event::add($this->server, $this->createConnect());
    }

    public function createConnect(){
        return function ($socket){
            $connect = stream_socket_accept($this->server);
            if (!empty($connect) && get_resource_type($connect) == "stream") {
                //触发事件的连接的回调
                $this->events['connect']($this, $connect);
                Event::add($connect, $this->sendMessage());
            }
        };
    }

    public function sendMessage(){
        return function ($connect){
            $buffer = fread($connect, 65535);
            if ('' === $buffer || false === $buffer) {
                $this->checkConn($buffer, $connect);
            } else {
                $this->events['receive']($this, $connect, $buffer);
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