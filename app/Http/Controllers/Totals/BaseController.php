<?php


namespace App\Http\Controllers\Totals;


use App\Http\Controllers\Controller;
use App\Services\Totals\Service;

class BaseController extends Controller
{
    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
