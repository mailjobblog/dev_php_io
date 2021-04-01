<?php
require __DIR__."/../../vendor/autoload.php";

use DevPhpIO\Blocking\Worker;

$server = new Worker('0.0.0.0',9500);

$server->on('connect',function($server,$client){

});

$server->on('receive',function($server,$client,$data){
    dd($data,'处理client的数据');
    $server->send($client, "hello i’m is server");
});

$server->on('close',function($server,$client){

});

$server->start();