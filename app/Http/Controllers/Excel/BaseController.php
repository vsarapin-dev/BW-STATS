<?php


namespace App\Http\Controllers\Excel;


use App\Http\Controllers\Controller;
use App\Services\Excel\Service;

class BaseController extends Controller
{
    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
