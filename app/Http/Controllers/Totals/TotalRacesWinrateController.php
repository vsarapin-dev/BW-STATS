<?php

namespace App\Http\Controllers\Totals;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TotalRacesWinrateController extends BaseController
{
    /**
     * Handle the incoming request.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return $this->service->racesWinrate($request);
    }
}
