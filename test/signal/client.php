<?php
require_once __DIR__."/../../vendor/autoload.php";

// 连接服务端
$fp = stream_socket_client("tcp://127.0.0.1:9500");
fwrite($fp, "hello world lalala");
dd(fread($fp, 65535));