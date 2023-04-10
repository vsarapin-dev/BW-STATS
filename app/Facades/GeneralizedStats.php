<?php


namespace App\Facades;

use App\Http\Resources\GameStatTotalValue\GameStatTotalValueResource;
use App\Models\GameStat;
use App\Models\GameStatTotalValue;
use App\Models\Map;
use App\Models\Result;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

class GeneralizedStats
{
    public function getAllBestStats($seasonId)
    {
        return new GameStatTotalValueResource(GameStatTotalValue::whereUserId(Auth::id())->whereSeasonId($seasonId)->first());
    }

    public function bestMaps($seasonId): array
    {
        return $this->getBestMaps($seasonId);
    }

    public function setAllBestStats($seasonId)
    {
        DB::beginTransaction();
        try {
            $arr = [];
            array_push($arr, $this->setSmurfPercent($seasonId));
            array_push($arr, $this->setWOPercent($seasonId));
            array_push($arr, $this->setMaxStreaks($seasonId));
            array_push($arr, $this->setTotalWinStats($seasonId));

            $result = [];
            foreach ($arr as $row) {
                $result = array_merge($row, $result);
            }

            GameStatTotalValue::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'season_id' => $seasonId,
                ],
                $result
            );

            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
        }
    }


    private function getBestMaps($seasonId): array
    {
        return [
            'bestDataResults' => [
                'bestMaps' => Map::select('maps.name', DB::raw('SUM(CASE WHEN results.name = "W" THEN 1 ELSE 0 END) AS wins, SUM(CASE WHEN results.name = "L" THEN 1 ELSE 0 END) AS losses, (SUM(CASE WHEN results.name = "W" THEN 1 ELSE 0 END) / (SUM(CASE WHEN results.name = "W" THEN 1 ELSE 0 END) + SUM(CASE WHEN results.name = "L" THEN 1 ELSE 0 END))) * 100 AS win_percentage'))
                    ->join('game_stats', 'maps.id', '=', 'game_stats.map_id')
                    ->join('results', 'game_stats.result_id', '=', 'results.id')
                    ->where('game_stats.user_id', Auth::id())
                    ->where('game_stats.season_id', $seasonId)
                    ->groupBy('maps.id')
                    ->orderByDesc('win_percentage')
                    ->limit(3)
                    ->get()
            ]
        ];
    }

    private function setSmurfPercent($seasonId): ?array
    {
        $total_games = GameStat::whereUserId(Auth::id())
            ->whereSeasonId($seasonId)
            ->count();

        $smurfs_games = GameStat::whereUserId(Auth::id())
            ->whereSeasonId($seasonId)
            ->whereIsSmurf(true)
            ->count();

        $smurfPercent = $total_games > 0 ? $smurfs_games / $total_games * 100 : 0;

        return ['smurf_percent' => round((float)$smurfPercent, 2)];
    }

    private function setWOPercent($seasonId): ?array
    {
        $games = GameStat::whereUserId(Auth::id())
            ->whereSeasonId($seasonId)
            ->get();

        $games->load('result');

        $total_games = $games->count();
        $wo_games = $games->filter(function ($game) {
            return $game->result->name == 'W/O';
        })->count();

        $wo_percent = $total_games > 0 ? $wo_games / $total_games * 100 : 0;

        return ['wo_percent' => round((float)$wo_percent, 2)];
    }

    private function setMaxStreaks($seasonId): ?array
    {
        $winResultId = Result::whereName('W')->first()->id;
        $defeatResultId = Result::whereName('L')->first()->id;

        $gameStats = GameStat::whereUserId(Auth::id())
            ->whereSeasonId($seasonId)
            ->orderBy('created_at', 'asc')
            ->get();

        $maxWins = $this->computeGameResultStreak($gameStats, $winResultId);
        $maxDefeats = $this->computeGameResultStreak($gameStats, $defeatResultId);

        return [
            'max_wins' => $maxWins,
            'max_defeats' => $maxDefeats,
        ];
    }

    private function setTotalWinStats($seasonId): ?array
    {
        $queryString = GameStat::whereUserId(Auth::id())->whereSeasonId($seasonId);

        $total_games = $queryString->count();

        $general_wins = $queryString
            ->whereHas('result', function ($query) {
                return $query->whereIn('name', ['W', 'W/O']);
            })->count();

        $real_wins = $queryString
            ->whereHas('result', function ($query) {
                return $query->where('name', 'W');
            })->count();

        return [
            'total_games' => $total_games,
            'general_wins' => $general_wins,
            'real_wins' => $real_wins,
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
