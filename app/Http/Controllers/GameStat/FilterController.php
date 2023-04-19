<?php

namespace App\Http\Controllers\GameStat;

use App\Http\Controllers\Controller;
use App\Http\Requests\GameStat\FilterRequest;
use Illuminate\Http\Request;

class FilterController extends BaseController
{
    public function __invoke(FilterRequest $request)
    {
        $data = $request->validated();
        return $this->service->filter($data);
    }
}
