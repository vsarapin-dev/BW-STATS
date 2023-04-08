<?php


namespace App\Http\Controllers\GameStat;


use App\Http\Controllers\Controller;
use App\Services\GameStat\Service;

class BaseController extends Controller
{
    public $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }
}
