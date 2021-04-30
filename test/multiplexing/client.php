<?php
require_once __DIR__."/../../vendor/autoload.php";

// 连接服务端
$fp = stream_socket_client("tcp://127.0.0.1:9500");
fwrite($fp, "hello world");
dd(fread($fp, 65535));

// 这里阻塞 10s 是为了便于演示
sleep(10);

fwrite($fp, "第二个消息");
dd(fread($fp, 65535));
