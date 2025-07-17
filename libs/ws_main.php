<?php
set_time_limit(0);
ini_set("memory_limit", "500M");

require_once ('uni_settings.php');
require_once ('counter-class.php');
require_once ('log-class.php');
require_once __DIR__ . '/vendor/autoload.php';

use Workerman\Worker;
use Workerman\Timer;
use Workerman\Connection\TcpConnection;

function act($act_id, array $body) {
    $data = array_merge(['act' => $act_id], $body);
    return json_encode($data);
}

Worker::$logFile = '/dev/null';

$counter = new CounterClass();
$log = new LogClass();

$chunk = str_repeat('A', Settings::CHUNK_SIZE);
$steps = Settings::TOTAL_SIZE / Settings::CHUNK_SIZE;

$ws_worker = new Worker("websocket://0.0.0.0:".Settings::WSOCKET_PORT);
$ws_worker->count = Settings::WSOCKET_COUNT;

$ws_worker->onWebSocketConnect = function($connection, $header) use ($counter){
  $connection->id = 0;
  $connection->ping = 0;
  $connection->download = false;
  $connection->upload   = false;
  $connection->start = 0;
  $connection->size = 0;
  $connection->totalDelay = 0;

  $connection->timer_id = Timer::add(0.7, function()use($connection){
      if (!$connection->download && !$connection->upload){
        $connection->ping++;
        $connection->send(json_encode(Array('act' => Settings::PING_ID, 'time' => microtime(true), 'id' => $connection->id, 'seq' => $connection->ping)));
      }
  });
};

$ws_worker->onMessage = function(TcpConnection $connection, $data) use ($counter, $log, $chunk, $steps){

  $json = json_decode($data, true);

  if (json_last_error() === JSON_ERROR_NONE) {
      if (isset($json['act']))
        switch ($json['act']) {
          case Settings::AUTH_ID:
                if (isset($json['id']) && (int)$json['id']){
                   $connection->id = (int)$json['id'];
                   $log->set("auth", $connection->id);
                }else {
                  $connection->id = $counter->inc();
                  $log->set("reg", $connection->id);
                  $connection->send(act(Settings::AUTH_ID, ["id" => $connection->id]));
                }
            break;
          case Settings::PING_ID:{
              if (isset($json['time'])){
                $delay = number_format(((microtime(true) - $json['time'])) * 1000, 3);
                $connection->totalDelay+=$delay;
                $log->set("ping", $connection->id, [$json['seq'], $delay, 'ms']);
              }
              break;
          }
          case Settings::GEO_ID:{
              $log->set("geo", $connection->id, [$json['lat'], $json['lon'], $json['acc']]);
              $connection->upload = false;
              break;
          }
          case Settings::DOWNLOAD_ID:{
              if (isset($json['total'])){
                $log->set("download", $connection->id, [$json['total'], $json['delay'], $json['speed']]);
                $connection->download = false;
              }else{

                $avg = number_format($connection->totalDelay/$connection->ping, 3);
                $connection->send(act(Settings::PING_ID, ["avg" => $avg]));


                $connection->download = true;
                for($i=0; $i<=$steps; $i++){
                  $connection->send($chunk);
                }
                $connection->send(act(Settings::DOWNLOAD_ID, ["size" => Settings::TOTAL_SIZE, "chunk" => Settings::CHUNK_SIZE]));
              }
              break;
          }
          case Settings::UPLOAD_ID:{
              if (isset($json['stop'])){

                $size  = round($connection->size/1024/1024, 3);
                $delay = round((microtime(true) - $connection->start), 3);
                $speed = round(($size/$delay)*8, 3);

                $connection->send(act(Settings::UPLOAD_ID, ["total" => $size, "delay" => $delay, "speed" => $speed]));
                $log->set("upload", $connection->id, [$size, $delay, $speed]);

                $connection->size = 0;
                $connection->start = 0;
              }else {
                $connection->upload = true;
                $connection->start = microtime(true);
              }
              break;
          }
          default:
            // code...
            break;
        }
      //var_dump($data); // смотрим структуру
  } else {
    if ($connection->upload){
      $connection->size+=strlen($data);
    }
    else
      echo "Error JSON: " . json_last_error_msg()."\n";
  }
};

$ws_worker->onClose = function(TcpConnection $connection){
    Timer::del($connection->timer_id);
};

// Запуск worker'а
Worker::runAll();
?>
