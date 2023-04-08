<?php

namespace App\Http\Controllers\Filters;

class ResultsController extends BaseController
{
    public function __invoke()
    {
        return $this->service->getResultsOptions();
    }
}
