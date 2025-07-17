<?php
class CounterClass
{
  const COUNTER_FILE = 'counter.txt';

  function __construct(){
    if (!file_exists(self::COUNTER_FILE)) {
        file_put_contents(self::COUNTER_FILE, 0);
    }
  }

  function inc(){
    $count = (int)file_get_contents(self::COUNTER_FILE);
    $count++;
    file_put_contents(self::COUNTER_FILE, $count);

    return $count;
  }
}

?>
