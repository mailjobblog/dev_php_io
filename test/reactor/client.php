<?php
$fp = stream_socket_client("tcp://127.0.0.1:9500");
fwrite($fp, " is client");
dd(fread($fp, 65535));
fclose($fp);