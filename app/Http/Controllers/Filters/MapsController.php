<?php

namespace App\Http\Controllers\Filters;


class MapsController extends BaseController
{
    public function __invoke()
    {
        return $this->service->getMapsOptions();
    }
}
