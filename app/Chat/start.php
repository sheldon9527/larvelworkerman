<?php

use \Workerman\Worker;
use \Workerman\WebServer;
use \GatewayWorker\Gateway;
use \GatewayWorker\BusinessWorker;
use \GatewayWorker\Register;
use \Workerman\Autoloader;

// bussinessWorker 进程
$worker = new BusinessWorker();
// worker名称
$worker->name = config('chat.worker_name');
// bussinessWorker进程数量
$worker->count = config('chat.worker_count');
// 服务注册地址
$worker->registerAddress = config('chat.register_address');
// 需要将eventHandler的默认值Events修改成Event就可以了
$worker->eventHandler = config('chat.event_handler');

// gateway 进程
$gateway = new Gateway(config('chat.Websocket'));
// 设置名称，方便status时查看
$gateway->name = config('chat.gateway_name');
// 设置进程数，gateway进程数建议与cpu核数相同
$gateway->count = config('chat.gateway_count');
// 分布式部署时请设置成内网ip（非127.0.0.1）
$gateway->lanIp = config('chat.gateway_lanIp');
// 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
// 则一般会使用4001 4002 4003 4004 4个端口作为内部通讯端口
$gateway->startPort = config('chat.gateway_startPort');
// 心跳间隔
$gateway->pingInterval = config('chat.ping_interval');
// 心跳数据
$gateway->pingData = config('chat.ping_data');

// WebServer
$web = new WebServer(config('chat.WebServer'));
// WebServer进程数量
$web->count = config('chat.WebServer_count');
// 设置站点根目录
$web->addRoot(config('chat.webServer_domain'), __DIR__.'/../../public');

// register 服务必须是text协议
$register = new Register(config('chat.text'));

// 如果不是在根目录启动，则运行runAll方法
if (!defined('GLOBAL_START')) {
    Worker::runAll();
}
