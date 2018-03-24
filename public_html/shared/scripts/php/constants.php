<?php
    define("DB_HOST", "localhost");
    define("DB_NAME", "id5177213_forum41");
    define("DB_USER", "id5177213_admin");
    define("DB_PASS", "admin");
    
    define("FILENAME", basename($_SERVER["PHP_SELF"], ".php"));

    define("MAIL_USERNAME", "forum41web@gmail.com");
    define("MAIL_PASSWORD", "forum41nikola");

    define("REDIRECT_TIMEOUT", 3000);

    define("DEBUG", true); // false for less verbose error messages
    define("SMTP_DEBUG", 0);

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
        const DEFAULT_VALUE = ["id" => "ASC"];
        const POSITION_ASCENDING = ["position" => "ASC"];
    }
