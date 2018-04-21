<?php

namespace App\Exceptions;

use Exception;
use App\Helpers\FileLogger;

class Base extends Exception {
    private $level;
    private $route;
    private $alertType;

    protected $message;

    private static $logger;

    public function __construct($level, $toastr, $route, $message)
    {
        $this->level = $level;
        $this->route = $route;
        $this->toastr = $toastr;
        $this->message = $message;
    }

    private static function log($level, $trace, $message) {
        if (!self::$logger) {
            self::$logger = new FileLogger('forum41');
        }
        self::$logger->addRecord($level, "{$trace['class']}@{$trace['function']}: {$message}");
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render($request)
    {
        self::log($this->level, $this->getTrace()[0], $this->message);
        return redirect(route($this->route))->with([
            'alert-type' => $this->toastr,
            'message' => $this->message,
        ]);
    }
}
