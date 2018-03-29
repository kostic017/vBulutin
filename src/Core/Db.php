<?php

namespace Forum41\Core;

use PDO;

class Db {
    private static $instance;

    private static function connect(): PDO {
        $dbConfig = Config::get("db");

        return new PDO(
            "mysql:host={$dbConfig["host"]};dbname={$dbConfig["name"]}",
            $dbConfig["user"],
            $dbConfig["pass"]
        );
    }

    public static function getInstance(){
        if (self::$instance === null) {
            self::$instance = self::connect();
            self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        return self::$instance;
    }
}
