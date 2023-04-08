<?php


namespace App\Services\Filters;


use App\Http\Filters\GameStatFilter;
use App\Http\Resources\Filters\MapResource;
use App\Http\Resources\Filters\RaceResource;
use App\Http\Resources\Filters\ResultResource;
use App\Models\GameStat;
use App\Models\Map;
use App\Models\Race;
use App\Models\Result;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class Service
{
    public function getResultsOptions(): JsonResponse
    {
        return response()->json([
            'results' => ResultResource::collection(Result::all()),
        ]);
    }

    public function getMapsOptions(): JsonResponse
    {
        return response()->json([
            'maps' => MapResource::collection(Map::all()),
        ]);
    }

    public function getRacesOptions(): JsonResponse
    {
        return response()->json([
            'races' => RaceResource::collection(Race::all()),
        ]);
    }

    public function getNicknameOrLogin($data): JsonResponse
    {
        $data['user_id'] = Auth::id();
        $filter = app()->make(GameStatFilter::class, ['queryParams' => array_filter($data)]);
        $enemyLogins = GameStat::select('enemy_login')->filter($filter)->distinct()->get();

        return response()->json([
            'enemy_logins' => $enemyLogins,
        ]);
    }
}
