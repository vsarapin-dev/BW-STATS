<?php

namespace App\Http\Controllers\GameStat;

use App\Http\Requests\GameStat\StoreRequest;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        return $this->service->store($data);
    }
}
