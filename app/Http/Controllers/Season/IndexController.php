<?php


namespace App\Http\Controllers\Season;


class IndexController extends BaseController
{
    public function __invoke()
    {
        return $this->service->index();
    }
}
