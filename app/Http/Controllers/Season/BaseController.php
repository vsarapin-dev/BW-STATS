<?php


namespace App\Http\Controllers\Season;


use App\Http\Controllers\Controller;
use App\Services\Season\Service;

class BaseController extends Controller
{
    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
