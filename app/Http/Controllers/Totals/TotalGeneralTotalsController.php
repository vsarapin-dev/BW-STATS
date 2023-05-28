<?php

namespace App\Http\Controllers\Totals;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TotalGeneralTotalsController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return $this->service->generalStats($request);
    }
}
