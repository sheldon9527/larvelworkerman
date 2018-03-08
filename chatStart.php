<?php
/**
 * run with command
 * php start.php start
 */

ini_set('display_errors', 'on');
use Workerman\Worker;

if (strpos(strtolower(PHP_OS), 'win') === 0) {
    exit("start.php not support windows, please use start_for_win.bat\n");
}

// 检查扩展
if (!extension_loaded('pcntl')) {
    exit("Please install pcntl extension.\n");
}

if (!extension_loaded('posix')) {
    exit("Please install posix extension.");
}

// 标记是全局启动
define('GLOBAL_START', 1);

require_once __DIR__ . '/vendor/autoload.php';
Worker::$pidFile = 'storage/workerman.pid';
Worker::$logFile = 'storage/workerman.log';

//加载框架
include __DIR__.'/bootstrap/autoload.php';

$app = include_once __DIR__.'/bootstrap/app.php';

$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

// 加载所有Applications/*/start.php，以便启动所有服务
foreach (glob(__DIR__.'/app/Chat/start*.php') as $start_file) {
    require_once $start_file;
}

// 运行所有服务
Worker::runAll();
