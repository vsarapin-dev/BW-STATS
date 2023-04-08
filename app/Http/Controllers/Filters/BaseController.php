<?php


namespace App\Http\Controllers\Filters;


use App\Http\Controllers\Controller;
use App\Services\Filters\Service;

class BaseController extends Controller
{
    protected $service;

    /**
     * BaseController constructor.
     * @param $service
     */
    public function __construct(Service $service)
    {
        $this->service = $service;
    }


}
