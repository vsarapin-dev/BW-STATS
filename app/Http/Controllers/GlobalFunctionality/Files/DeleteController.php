<?php

namespace App\Http\Controllers\GlobalFunctionality\Files;


use Illuminate\Http\Request;

class DeleteController extends BaseController
{
    public function __invoke(Request $request)
    {
        return $this->service->delete($request);
    }
}
