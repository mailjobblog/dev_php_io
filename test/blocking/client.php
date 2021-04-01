<?php
require __DIR__."/../../vendor/autoload.php";

$fp = stream_socket_client("tcp://127.0.0.1:9500");

fwrite($fp,'hello blocking');

var_dump(fread($fp,65535));