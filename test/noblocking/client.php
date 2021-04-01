<?php
require __DIR__."/../../vendor/autoload.php";

$fp = stream_socket_client("tcp://127.0.0.1:9500");

//设置套接字为非阻塞模型
stream_set_blocking($client, 0);

fwrite($fp,'hello blocking');

$time = time();

echo fread($fp,65535);

echo "\n其他业务\r\n";

$m = time() - $time;

echo "执行时间" . $m . "秒钟\n";

//不断轮询，有内容则打印
while (!feof($client)) {
  echo fread($client, 65535);
}