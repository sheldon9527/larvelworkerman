<?php
return [
    'Websocket' => 'Websocket://0.0.0.0:7277',//Websocket 地址
    'gateway_name' => 'ChatGateway',//网关名称
    'gateway_count' => 4,//一般设置和 cup 数量 一致
    'gateway_lanIp' => '127.0.0.1',//网关内网ip
    'gateway_startPort' => 2300,//起始端口
    'ping_interval' => 10, //心跳间隔
    'ping_data' => '{"type":"ping"}', //心跳数据

    'worker_name' => 'ChatBusinessWorker',//起始端口
    'worker_count' => 4,//起始端口
    'register_address' => '127.0.0.1:1236',//注册地址
    'event_handler' => 'App\Chat\Events', //需要将eventHandler

    'text' => 'text://0.0.0.0:1236',// register 服务必须是text协议
    'WebServer' => 'http://0.0.0.0:55151', //WebServer
    'WebServer_count' => 4, // WebServer进程数量
    'webServer_domain' => 'dong.dev'// WebServer服务域名


];
