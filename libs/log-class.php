<?php
/**
 *
 */
class LogClass
{
  const separator = ";";

  function __construct()
  {
    // code...
  }

  function dir($dir){
    return (file_exists($dir))?true:mkdir($dir, 0777, true);
  }

  function set($label, $client_id, $data = Array()){
    $dir = __DIR__.Settings::LOGFOLDER;

    if ($this->dir($dir)){
        $file = "$dir/".date("Ymd").".csv";
        file_put_contents($file, date("H:i:s").self::separator.$client_id.self::separator.$label.self::separator.implode(self::separator, $data).PHP_EOL, FILE_APPEND);
    }}
}
?>
