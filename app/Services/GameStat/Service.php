<?php


namespace App\Services\GameStat;

use App\Models\MmrRank;
use GeneralizedStats;
use App\Http\Filters\GameStatFilter;
use App\Http\Resources\GameStat\GameStatResource;
use App\Http\Resources\Season\SeasonResource;
use App\Models\GameStat;
use App\Models\Season;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class Service
{
    public function index($data): JsonResponse
    {
        $userStatQuery = GameStat::whereUserId(Auth::id());

        $seasonId = $data['season_id'] ?? $userStatQuery->max('season_id');
        $gameStatLastCreatedDate = $userStatQuery->max('created_at');
        $availableSeasons = SeasonResource::collection(Season::whereIn('id', GameStat::select('season_id')->distinct()->get()->pluck('season_id'))->get());
        $lastUpdated = $gameStatLastCreatedDate ? Carbon::parse($gameStatLastCreatedDate)->format('d F Y') : null;
        $currentSeason = $seasonId ? new SeasonResource(Season::whereId($seasonId)->first()) : null;


        $gameStatDataTableResult = $this->paginate(
            GameStatResource::collection(GameStat::whereUserId(Auth::id())->whereSeasonId($seasonId)->orderBy('game_number', 'desc')->get()),
            $data['itemsPerPage'],
            $data['page']
        );

        $result = [
            'data' => $gameStatDataTableResult,
            'currentSeason' => $currentSeason,
            'lastUpdated' => $lastUpdated,
            'availableSeasons' => $availableSeasons,
        ];
        $generalizedStats = $seasonId ? json_decode(GeneralizedStats::getAllBestStats($seasonId)->toJson(), true) : [];

        return response()->json(array_merge_recursive($generalizedStats, $result));
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
            'gameStatFilterResult' => $gameStatFilterResult,
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
}
