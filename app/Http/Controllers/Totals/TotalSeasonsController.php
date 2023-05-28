<?php

namespace App\Http\Controllers\Totals;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TotalSeasonsController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        return $this->service->seasons();
    }
}
