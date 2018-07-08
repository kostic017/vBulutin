<?php

namespace App\Helpers;

use Carbon;

class Logger
{
    private $file;

    public static $logger;

    private function __construct($name) {
        $date = Carbon::now()->toDateString();
        $this->file = fopen(storage_path("logs/$name-$date.log"), 'a');
    }

    private function __destruct() {
        fclose($this->file);
    }

    private function add_record($level, $method, $message) {
        $level = strtoupper($level);
        $date = Carbon::now()->toDateString();
        fwrite($this->file, "[$date] $level@$method: $message\n");
    }

    public static function log($message, $level = 'info', $method = '') {
        if (!self::$logger) {
            self::$logger = new Logger('forum_log');
        }
        self::$logger->add_record($level, $method, $message);
    }
}
