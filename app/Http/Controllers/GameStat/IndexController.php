<?php

namespace App\Http\Controllers\GameStat;

use App\Http\Requests\GameStat\IndexRequest;

class IndexController extends BaseController
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $gameStats = $this->service->index($data);

        return $gameStats;
    }
}
