<?php

namespace App\Http\Controllers\Filters;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MapsController extends BaseController
{
    public function __invoke()
    {
        return $this->service->getMapsOptions();
    }
}
