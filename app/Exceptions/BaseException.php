<?php

namespace App\Exceptions;

use Exception;

use App\Helpers\FileLogger;

use Illuminate\Http\Request;

class BaseException extends Exception {
    private $level;
    private $route;
    private $alertType;

    protected $message;

    private static $logger;

    public function __construct(string $loglevel, string $toastrlevel, string $route, string $message)
    {
        $this->route = $route;
        $this->message = $message;
        $this->loglevel = $loglevel;
        $this->toastrlevel = $toastrlevel;
    }

    private static function log(string $loglevel, array $trace, string $message) {
        if (!self::$logger) {
            self::$logger = new FileLogger('forum41');
        }
        self::$logger->addRecord($loglevel, "{$trace['class']}@{$trace['function']}: {$message}");
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function render(Request $request)
    {
        self::log($this->loglevel, $this->getTrace()[0], $this->message);
        return redirect(route($this->route))->with([
            'alert-type' => $this->toastrlevel,
            'message' => $this->message,
        ]);
    }
}
