<?php


namespace App\Services\GameStat;

use App\Http\Filters\GameStatFilter;
use App\Http\Resources\GameStat\GameStatResource;
use App\Http\Resources\Season\SeasonResource;
use App\Models\GameStat;
use App\Models\GameStatTotalValue;
use App\Models\Map;
use App\Models\Season;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Service
{
    public function index($data): JsonResponse
    {
        $userStatQuery = GameStat::whereUserId(Auth::id());

        $seasonId = $data['season_id'] ?? $userStatQuery->max('season_id');
        $gameStatLastCreatedDate = $userStatQuery->max('created_at');

        $gameStatTotalValueResult = $this->getWinStats($seasonId);
        $bestMaps = $this->getBestMaps($seasonId);


        $gameStatFilterResult = $this->paginate(
            GameStatResource::collection(GameStat::whereUserId(Auth::id())->whereSeasonId($seasonId)->orderBy('game_number', 'desc')->get()),
            $data['itemsPerPage'],
            $data['page']
        );

        return response()->json([
            'gameStatFilterResult' => $gameStatFilterResult,
            'gameStatTotalValueResult' => $gameStatTotalValueResult,
            'currentSeason' => $seasonId ? new SeasonResource(Season::whereId($seasonId)->first()) : null,
            'lastUpdated' => $gameStatLastCreatedDate ? Carbon::parse($gameStatLastCreatedDate)->format('d F Y') : null,
            'availableSeasons' => SeasonResource::collection(Season::whereIn('id', GameStat::select('season_id')->distinct()->get()->pluck('season_id'))->get()),
            'bestMaps' => $bestMaps,
        ]);
    }

    public function filter($data): JsonResponse
    {
        if (!$data) $data['user_id'] = Auth::id();

        $filter = app()->make(GameStatFilter::class, ['queryParams' => array_filter($data)]);
        $gameStatTotalValueResult = GameStatTotalValue::where('user_id', Auth::id())->first();
        $gameStatFilterResult = $this->paginate(
            GameStatResource::collection(GameStat::filter($filter)->orderBy('game_number', 'desc')->get()),
            $data['itemsPerPage'],
            $data['page']
        );

        return response()->json([
            'gameStatFilterResult' => $gameStatFilterResult,
            'gameStatTotalValueResult' => $gameStatTotalValueResult,
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
            return response()->json(['message' => 'Row created successfully.']);
        }
        return response()->json(['message' => 'Row not created.']);
    }

    public function delete(array $rowsIds): JsonResponse
    {
        GameStat::whereIn('id', $rowsIds)->delete();

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

    private function getWinStats($seasonId): Collection
    {
        $userId = Auth::id();
        $queryString = GameStat::whereUserId($userId)->whereSeasonId($seasonId);

        $total_games = $queryString->count();

        $general_wins = $queryString
            ->whereHas('result', function ($query) {
                return $query->whereIn('name', ['W', 'W/O']);
            })->count();

        $real_wins = $queryString
            ->whereHas('result', function ($query) {
                return $query->where('name', 'W');
            })->count();

        return collect([
            'total_games' => $total_games,
            'general_wins' => $general_wins,
            'real_wins' => $real_wins,
        ]);
    }

    private function getBestMaps($seasonId)
    {
        return Map::select('maps.name', DB::raw('SUM(CASE WHEN results.name = "W" THEN 1 ELSE 0 END) AS wins, SUM(CASE WHEN results.name = "L" THEN 1 ELSE 0 END) AS losses, (SUM(CASE WHEN results.name = "W" THEN 1 ELSE 0 END) / (SUM(CASE WHEN results.name = "W" THEN 1 ELSE 0 END) + SUM(CASE WHEN results.name = "L" THEN 1 ELSE 0 END))) * 100 AS win_percentage'))
            ->join('game_stats', 'maps.id', '=', 'game_stats.map_id')
            ->join('results', 'game_stats.result_id', '=', 'results.id')
            ->where('game_stats.user_id', Auth::id())
            ->where('game_stats.season_id', $seasonId)
            ->groupBy('maps.id')
            ->orderByDesc('win_percentage')
            ->limit(3)
            ->get();
    }
}
