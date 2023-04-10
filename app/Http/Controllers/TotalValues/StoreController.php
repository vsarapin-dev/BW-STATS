<?php

namespace App\Http\Controllers\TotalValues;

use App\Http\Requests\TotalValues\StoreRequest;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request)
    {
        $data = $request->validated();
        return $data;
        return $this->service->store($data);
    }
}
