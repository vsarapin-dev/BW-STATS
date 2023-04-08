<?php

namespace App\Http\Controllers\Filters;

use App\Http\Controllers\Controller;
use App\Http\Requests\Filters\NicknameLoginRequest;
use Illuminate\Http\Request;

class NicknameLoginController extends BaseController
{
    public function __invoke(NicknameLoginRequest $request)
    {
        $data = $request->validated();
        return $this->service->getNicknameOrLogin($data);
    }
}
