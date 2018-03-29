<?php

namespace Forum41\Core;

use Forum41\Exceptions\NotFoundException;

class Config {
    private static $data;

    public static function get(string $key) {
        if (self::$data === null) {
            $json = file_get_contents(__DIR__ . "/../../config/app.json");
            self::$data = json_decode($json, true);
        }

        if (!isset(self::$data[$key])) {
            throw new NotFoundException("Key {$key} not in config.");
        }

        return self::$data[$key];
    }

}
