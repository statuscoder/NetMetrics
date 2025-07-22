<!DOCTYPE html><html class="camera">
<head>
  <meta charset="utf-8">
  <title>NetMetric</title>
  <style type="text/css">
  body html{padding:0px;margin0px;}
  </style>
  <link href="./css/style.css?v=1" rel="stylesheet" type="text/css" />
</head>
<body>
<?php
  require_once ('./libs/uni_settings.php');

  $folder = __DIR__."/libs/".Settings::LOGFOLDER;
  $client = Array();

  if (file_exists($folder)){
    $files = glob($folder . '/*.csv');
    foreach ($files as $file) {

      echo "<h2>File analysis $file</h2>";

      $data = file($file);
      if (is_array($data)){
        foreach ($data as $line){
          //echo $line."<br>";

          $line = str_replace(PHP_EOL," ", $line);
          $field = explode(";", $line);

          switch($field[2]){
            case 'reg':{

            }
            case 'auth':{
              if (isset($client[$field[1]])){
                  //с другого ip
                  if ($client[$field[1]]['ip'] != $field[3])
                      total($client[$field[1]]);
                  else {
                    break;
                  }
              }

              $client[$field[1]]['ip'] = $field[3];
              $client[$field[1]]['ping']     = [0, 0];
              $client[$field[1]]['download'] = [0, 0];
              $client[$field[1]]['upload']   = [0, 0];
              break;
            }
            case 'ping':{
              if (isset($client[$field[1]])){
                $client[$field[1]]['ping'][0] += $field[4];
                $client[$field[1]]['ping'][1] ++;
              }
              break;
            }
            case 'download':{
                  if (isset($client[$field[1]])){
                    $client[$field[1]]['download'][0] += $field[5];
                    $client[$field[1]]['download'][1] ++;
                  }
              break;
            }

            case 'upload':{
                  if (isset($client[$field[1]])){
                    $client[$field[1]]['upload'][0] += $field[5];
                    $client[$field[1]]['upload'][1] ++;
                  }
              break;
            }
          }
        }
      }
    }

    if (count($client)){
      foreach ($client as $id => $value) {
        total($value);
      }
    }
    else {
      echo "<h2>No statistics</h2>";
    }

  }else {
    echo "<h2>No log files</h2>";
  }
  function total($data){
    $data['avg_ping'] = round($data['ping'][0] / $data['ping'][1], 4);

    if (isset($data['download']) && $data['download'][1] > 0)
        $data['avg_download'] = round($data['download'][0] / $data['download'][1], 4);

    if (isset($data['upload']) && $data['upload'][1] > 0)    
    $data['avg_upload'] = round($data['upload'][0] / $data['upload'][1], 4);
    echo json_encode($data)."<br>";
  }
?>
</body></html>
