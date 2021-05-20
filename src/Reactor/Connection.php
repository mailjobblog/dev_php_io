<?php
namespace DevPhpIO\Reactor;

class Connection
{
    protected $conn;
    protected $server;

    public function __construct($conn, $server)
    {
        $this->conn = $conn;
        $this->server = $server;
    }

    public function handler()
    {
        Reactor::getInstance()->add($this->conn, Reactor::EVENT, $this->sendMessage());
    }

    public function sendMessage()
    {
        return function($conn){
            // dd("接收服务的信息");
            // sleep(3);
            // 接收服务的信息
            $buffer = fread($conn, 65535);
            // dd($buffer, "buffer");
            // sleep(3);
            if ('' === $buffer || false === $buffer) {
                // 校验是否断开连接
                $this->checkConn($buffer, $conn);
            } else {
                // dd("回复用户");
                // sleep(5);
                $this->server->events['receive']($this->server, $conn, $buffer);
            }
        };
    }

    /**
     * 校验连接状态
     * @method closeConn
     * @param  socket    $conn 连接信息
     */
    public function checkConn($buffer, $conn)
    {
        if (strlen($buffer) === 0) {
            if (!get_resource_type($conn) == "Unknown") {
                // 关闭连接
                $this->close($conn);
            }
            $this->server->events['close']($this->server, $conn);
        }
        Reactor::getInstance()->del($conn, Reactor::EVENT);
    }
}