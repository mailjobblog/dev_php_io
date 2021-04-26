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

// # 1
// 用 feof 判断是否到达结尾的位置，如果到达，则跳出输出服务端返回的结果
// while(!feof($fp)){
//  sleep(1);
//  var_dump(fread($fp,65535));
// }

// # 2
// 用 stream_select 去循环遍历server的读写状态
// while(!feof($fp)){
//   sleep(1);
//   $read[] = $fp;
//   stream_select($read, $write, $error, 1);
//   var_dump($read);
//   var_dump(fread($fp,65535));
// }

fclose($fp);