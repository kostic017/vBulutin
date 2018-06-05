<?php

namespace App\Helpers;

class Logger
{
    private $file;

    public static $logger;

    private function __construct($name)
    {
        $date = \Carbon::now()->toDateString();
        $this->file = fopen(storage_path("logs/$name-$date.log"), 'a');
    }

    private function __destruct()
    {
        fclose($this->file);
    }

    private function addRecord($level, $method, $message)
    {
        $level = strtoupper($level);
        $date = \Carbon::now()->toDateString();
        fwrite($this->file, "[$date] $level@$method: $message\n");
    }

    public static function log($level, $method, $message)
    {
        if (!self::$logger) {
            self::$logger = new Logger('forum41');
        }
        self::$logger->addRecord($level, $method, $message);
    }
}
