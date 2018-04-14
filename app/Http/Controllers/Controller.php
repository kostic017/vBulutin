<?php

namespace App\Http\Controllers;

use App\Helpers\Common\FileLogger;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $logger;

    public function __construct()
    {
        $this->logger = new FileLogger('forum41');
    }

}
