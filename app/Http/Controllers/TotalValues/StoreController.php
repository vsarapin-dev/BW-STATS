<?php

namespace App\Http\Controllers\TotalValues;

use App\Http\Requests\TotalValues\StoreRequest;
use Illuminate\Http\JsonResponse;

class StoreController extends BaseController
{
    public function __invoke(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        return $this->service->store($data);
    }
}
