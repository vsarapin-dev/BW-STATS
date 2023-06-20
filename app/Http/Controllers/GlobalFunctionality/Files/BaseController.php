<?php


namespace App\Http\Controllers\GlobalFunctionality\Files;


use App\Http\Controllers\Controller;
use App\Services\GlobalFunctionality\Files\Service;

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
