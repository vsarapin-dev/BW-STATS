<?php


namespace App\Http\Controllers\Excel;

use Illuminate\Http\Request;

class ImportController extends BaseController
{
    public function __invoke(Request $request)
    {
        return $this->service->importFile($request->file('excel_file'), $request->season_number);
    }
}
