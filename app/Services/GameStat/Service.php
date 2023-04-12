<?php


namespace App\Services\GameStat;

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
use Illuminate\Support\Facades\DB;

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
            'matches_count' => GameStat::join('races as my_race', 'game_stats.my_race_id', '=', 'my_race.id')
                ->join('races as enemy_race', 'game_stats.enemy_race_id', '=', 'enemy_race.id')
                ->join('results', 'game_stats.result_id', '=', 'results.id')
                ->whereNull('enemy_random_race_id')
                ->whereIn('results.name', ['W', 'L'])
                ->select(
                    DB::raw('count(*) as total_matches'),
                    DB::raw('sum(case when results.name = "W" then 1 else 0 end) as total_wins'),
                    DB::raw('sum(case when results.name = "L" then 1 else 0 end) as total_losses'),
                    DB::raw('ROUND(sum(case when results.name = "W" then 1 else 0 end) / count(*) * 100, 2) as win_rate')
                )
                ->groupBy('my_race.name', 'enemy_race.name')
                ->orderByDesc('win_rate')
                ->selectRaw('CONCAT(LEFT(my_race.name, 1), "v", LEFT(enemy_race.name, 1)) as matchup_shorthand')
                ->get(),
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
