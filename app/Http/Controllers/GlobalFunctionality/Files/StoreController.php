<?php

namespace App\Http\Controllers\GlobalFunctionality\Files;


use Illuminate\Http\Request;

class StoreController extends BaseController
{
    public function __invoke(Request $request)
    {
        return $this->service->store($request);
    }
}
