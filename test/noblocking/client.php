<?php
require __DIR__."/../../vendor/autoload.php";

$fp = stream_socket_client("tcp://127.0.0.1:9500");

//设置套接字为非阻塞模型
stream_set_blocking($fp, 0);

fwrite($fp,'hello NO-blocking');

$time = time();

echo fread($fp,65535);

echo "\n此处执行其他业务代码\r\n";

$m = time() - $time;

echo "执行时间" . $m . "秒钟\n";

fclose($fp);