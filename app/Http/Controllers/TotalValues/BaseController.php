<?php


namespace App\Http\Controllers\TotalValues;


use App\Http\Controllers\Controller;
use App\Services\TotalValues\Service;

class BaseController extends Controller
{
    public Service $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
