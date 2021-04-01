<?php
require __DIR__."/../../vendor/autoload.php";

use DevPhpIO\Blocking\Worker;

$server = new Worker('0.0.0.0',9500);

$server->on('connect',function($server,$client){
    dd($client,'连接成功');
});

$server->on('receive',function($server,$client,$data){
    dd($data,'处理client的数据');
    sleep(5); // 进行阻塞，方便演示
    $server->send($client, "hello i’m is server");

    $server->close($client);
});

$server->on('close',function($server,$client){
    dd($client,'连接断开');
});

$server->start();