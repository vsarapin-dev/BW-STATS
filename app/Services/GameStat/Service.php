<?php


namespace App\Services\GameStat;

use App\Models\Total;
use GeneralizedStats;
use App\Http\Filters\GameStatFilter;
use App\Http\Resources\GameStat\GameStatResource;
use App\Models\GameStat;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Service
{
    public function index($data): JsonResponse
    {
        $userStatQuery = GameStat::whereUserId(Auth::id());
        $gameStatLastCreatedDate = $userStatQuery->max('created_at');

        $lastUpdated = $gameStatLastCreatedDate ?
            Carbon::parse($gameStatLastCreatedDate)->format('d F Y') :
            null;

        $gameStatDataTableResult = $this->paginate(
            GameStatResource::collection(GameStat::whereUserId(Auth::id())
                ->whereSeasonId($data['season_id'])
                ->orderBy($data['sort_by'], $data['sort_desc'])
                ->get()),
            $data['itemsPerPage'],
            $data['page']
        );

        return response()->json([
            'data' => $gameStatDataTableResult,
            'lastUpdated' => $lastUpdated,
        ]);
    }

    public function filter($data): JsonResponse
    {
        if (!$data) $data['user_id'] = Auth::id();

        $filter = app()->make(GameStatFilter::class, ['queryParams' => array_filter($data)]);
        $gameStatFilterResult = $this->paginate(
            GameStatResource::collection(GameStat::filter($filter)->orderBy('game_number', 'desc')->get()),
            $data['itemsPerPage'],
            $data['page']
        );

        return response()->json([
            'data' => $gameStatFilterResult,
        ]);
    }

    public function store($data): JsonResponse
    {
        $gameNumber = GameStat::whereUserId(Auth::id())
            ->whereSeasonId($data['season_id'])
            ->max('game_number');
        $data['user_id'] = Auth::id();
        $data['game_number'] = $gameNumber + 1 ?? 1;

        $stats = GameStat::create($data);

        if (isset($stats)) {
            GeneralizedStats::setAllBestStats($data['season_id']);
            return response()->json(['message' => 'Row created successfully.']);
        }
        return response()->json(['message' => 'Row not created.']);
    }

    public function delete(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $seasonId = $request['season_id'];
        if (isset($request['delete_all'])) {
            return $this->deleteAll($userId, $seasonId);
        }

        if (isset($request['ids'])) {
            return $this->deleteIds($userId, $seasonId, $request['ids']);
        }
    }

    private function deleteAll($userId, $seasonId): JsonResponse
    {
        GameStat::whereUserId($userId)->whereSeasonId($seasonId)->delete();
        $this->recalculateBestStats($userId, $seasonId);

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }

    private function deleteIds($userId, $seasonId, $ids): JsonResponse
    {
        GameStat::whereUserId($userId)->whereSeasonId($seasonId)->whereIn('id', $ids)->delete();
        $this->recalculateBestStats($userId, $seasonId);

        return response()->json([
            'message' => 'Deleted successfully'
        ]);
    }

    private function paginate($items, $perPage = 5, $page = null, $options = [])
    {
        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof Collection ? $items : Collection::make($items);
        return new LengthAwarePaginator($items->forPage($page, $perPage)->values(), $items->count(), $perPage, $page, $options);
    }

    private function recalculateBestStats($userId, $seasonId)
    {
        $gameStat = GameStat::whereUserId($userId)->whereSeasonId($seasonId)->count();
        $totalsRow = Total::whereUserId($userId)->whereSeasonId($seasonId)->first();

        if ($totalsRow != null) {
            if ($gameStat > 0) {
                GeneralizedStats::setAllBestStats($totalsRow->season_id, $totalsRow->id);
            } else {
                GeneralizedStats::deleteAllBestStats($totalsRow->id);
            }
        }
    }
}
