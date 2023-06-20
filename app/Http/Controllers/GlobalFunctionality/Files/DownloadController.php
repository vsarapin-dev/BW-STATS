<?php

namespace App\Http\Controllers\GlobalFunctionality\Files;


use Illuminate\Http\Request;

class DownloadController extends BaseController
{
    public function __invoke(Request $request)
    {
        return $this->service->download($request);
    }
}
