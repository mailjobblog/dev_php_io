<?php
namespace DevPhpIO\Reactor;

use DevPhpIO\WorkBase;
use \Event as Event;
use \EventBase as EventBase;

class Worker extends WorkBase
{
    public $events = [];

    public function accept()
    {
        Reactor::getInstance()->add($this->server, Reactor::EVENT, $this->createConn());
        Reactor::getInstance()->run();
    }

    public function createConn()
    {
        return function($socket){
            dd("createConn");
            $conn = stream_socket_accept($socket);
            if (!empty($conn) && get_resource_type($conn) == "stream") {
                //触发事件的连接的回调
                dd("触发事件的连接的回调触发事件的连接的回调");
                $this->events['connect']($this, $conn);
                dd("发送信息");
                (new Connection($conn, $this))->handler();
            }
        };
    }
}