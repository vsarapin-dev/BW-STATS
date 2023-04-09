<?php


namespace App\Services\GameStat;

use App\Http\Filters\GameStatFilter;
use App\Http\Resources\GameStat\GameStatResource;
use App\Http\Resources\Season\SeasonResource;
use App\Models\GameStat;
use App\Models\GameStatTotalValue;
use App\Models\Map;
use App\Models\Result;
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
        $woPercent = $this->getWOPercent($seasonId);
        $smurfPercent = $this->getSmurfPercent($seasonId);
        $maxStreaks = $this->getMaxStreaks($seasonId);


        $gameStatFilterResult = $this->paginate(
            GameStatResource::collection(GameStat::whereUserId(Auth::id())->whereSeasonId($seasonId)->orderBy('game_number', 'desc')->get()),
            $data['itemsPerPage'],
            $data['page']
        );

        return response()->json([
            'data' => $gameStatFilterResult,
            'gameStatTotalValueResult' => $gameStatTotalValueResult,
            'currentSeason' => $seasonId ? new SeasonResource(Season::whereId($seasonId)->first()) : null,
            'lastUpdated' => $gameStatLastCreatedDate ? Carbon::parse($gameStatLastCreatedDate)->format('d F Y') : null,
            'availableSeasons' => SeasonResource::collection(Season::whereIn('id', GameStat::select('season_id')->distinct()->get()->pluck('season_id'))->get()),
            'bestDataResults' => [
                'bestMaps' => $bestMaps,
                'maxStreaks' => $maxStreaks,
                'percents' => [
                    'woPercent' => $woPercent,
                    'smurfPercent' => $smurfPercent,
                ],
            ],
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

    private function getWinStats($season_id): Collection
    {
        $userId = Auth::id();
        $queryString = GameStat::whereUserId($userId)->whereSeasonId($season_id);

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

    private function getBestMaps($season_id): ?Collection
    {
        return Map::select('maps.name', DB::raw('SUM(CASE WHEN results.name = "W" THEN 1 ELSE 0 END) AS wins, SUM(CASE WHEN results.name = "L" THEN 1 ELSE 0 END) AS losses, (SUM(CASE WHEN results.name = "W" THEN 1 ELSE 0 END) / (SUM(CASE WHEN results.name = "W" THEN 1 ELSE 0 END) + SUM(CASE WHEN results.name = "L" THEN 1 ELSE 0 END))) * 100 AS win_percentage'))
            ->join('game_stats', 'maps.id', '=', 'game_stats.map_id')
            ->join('results', 'game_stats.result_id', '=', 'results.id')
            ->where('game_stats.user_id', Auth::id())
            ->where('game_stats.season_id', $season_id)
            ->groupBy('maps.id')
            ->orderByDesc('win_percentage')
            ->limit(3)
            ->get();
    }

    private function getSmurfPercent($season_id): float
    {
        $total_games = GameStat::whereUserId(Auth::id())
            ->whereSeasonId($season_id)
            ->count();

        $smurfs_games = GameStat::whereUserId(Auth::id())
            ->whereSeasonId($season_id)
            ->whereIsSmurf(true)
            ->count();

        $smurfPercent = $total_games > 0 ? $smurfs_games / $total_games * 100 : 0;

        return round((float)$smurfPercent, 2);
    }

    private function getWOPercent($season_id): float
    {
        $games = GameStat::whereUserId(Auth::id())
            ->whereSeasonId($season_id)
            ->get();

        $games->load('result');

        $total_games = $games->count();
        $wo_games = $games->filter(function ($game) {
            return $game->result->name == 'W/O';
        })->count();

        $wo_percent = $total_games > 0 ? $wo_games / $total_games * 100 : 0;
        return round((float)$wo_percent, 2);
    }

    private function getMaxStreaks($season_id): array
    {
        $winResultId = Result::whereName('W')->first()->id;
        $defeatResultId = Result::whereName('L')->first()->id;

        $gameStats = GameStat::whereUserId(Auth::id())
            ->whereSeasonId($season_id)
            ->orderBy('created_at', 'asc')
            ->get();

        $maxWins = $this->computeGameResultStreak($gameStats, $winResultId);
        $maxDefeats = $this->computeGameResultStreak($gameStats, $defeatResultId);

        return [
            'maxWins' => $maxWins,
            'maxDefeats' => $maxDefeats
        ];
    }

    private function computeGameResultStreak($stats, $resultToFind): int
    {
        $maxValue = 0;
        $currentValue = 0;

        foreach ($stats->pluck('result_id') as $resultId) {
            if ($resultId === $resultToFind) {
                $currentValue++;
                if ($currentValue > $maxValue) {
                    $maxValue = $currentValue;
                }
            } else {
                $currentValue = 0;
            }
        }
        return $maxValue;
    }
}
