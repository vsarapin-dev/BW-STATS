<?php


namespace App\Services\Season;
use App\Http\Resources\Season\SeasonResource;
use App\Models\Season;
use Illuminate\Http\JsonResponse;

class Service
{
    public function index(): JsonResponse
    {
        return response()->json([
            'seasons' => SeasonResource::collection(Season::all()),
        ]);
    }
}
