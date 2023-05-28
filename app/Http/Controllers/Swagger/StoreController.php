<?php

namespace App\Http\Controllers\Swagger;

use App\Http\Controllers\GameStat\BaseController;

/**
 * @OA\Post(
 *     path="/api/auth/store-stats",
 *     summary="Store stats",
 *     @OA\RequestBody(),
 *
 *     @OA\REsponse(
 *         response="200",
 *         description="Success"
 *     ),
 * )
 */
class StoreController extends BaseController
{
}
