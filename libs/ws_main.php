<?php
set_time_limit(0);
ini_set("memory_limit", "200M");

require_once ('uni_settings.php');
require_once __DIR__ . '/vendor/autoload.php';

use Workerman\Worker;
use Workerman\Connection\TcpConnection;

Worker::$logFile = '/dev/null';

$ws_worker = new Worker("websocket://0.0.0.0:".Settings::WSOCKET_PORT);
$ws_worker->count = Settings::WSOCKET_COUNT;


$ws_worker->onMessage = function(TcpConnection $connection, $data)
{
    // Отправляем "hello $data" клиенту
    $connection->send('hello ' . $data);
};

// Запуск worker'а
Worker::runAll();
?>
