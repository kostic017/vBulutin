<?php
    define("DB_HOST", "localhost");
    define("DB_USER", "root");
    define("DB_PASS", "");
    define("DB_NAME", "forum41");

    define("PRIVATE_DIR", __DIR__);
    define("FILENAME", basename($_SERVER["PHP_SELF"], ".php"));

    define("DEBUG", true); // false for less verbose error messages
    
    define(PASSWORD_ALGORITHM, PASSWORD_BCRYPT);
    
    abstract class VISIBILITY {
        const ALL = 0;
        const VISIBLE = 1;
        const INVISIBLE = 2;
    }

    abstract class FETCH {
        const ONE = 0;
        const ALL = 1;
    }

    abstract class SORT {
        const DEFAULT_VALUE = ["columnName" => "id", "order" => "ASC"];
        const POSITION_ASCENDING = ["columnName" => "position", "order" => "ASC"];
    }
