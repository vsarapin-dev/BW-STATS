<?php


namespace App\Services\TotalValues;

use GeneralizedStats;
use Illuminate\Http\JsonResponse;

class Service
{
    public function store($data): JsonResponse
    {
        if (count($data))
        {
            return GeneralizedStats::saveEditableData($data);
        }
        return response()->json(['message' => 'An error occurred!']);
    }
}
