<?php
// 连接服务端
$fp = stream_socket_client("tcp://127.0.0.1:9500");
fwrite($fp, " is client");
var_dump(fread($fp, 65535));
fclose($fp);
