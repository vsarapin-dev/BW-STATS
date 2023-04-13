<?php


namespace App\Facades\GeneralizedStats\StatsSaver;


use App\Models\GameStat;
use App\Models\GeneralStats;
use App\Models\Result;
use Illuminate\Support\Facades\DB;
use Throwable;

class GeneralStatsSaver extends BaseStatsSaver
{
    public function computeAndSaveData()
    {
        $arr = $this->computeAllValues();
        $this->saveAllValues($arr);
    }

    private function computeAllValues()
    {
        $arr = [];
        array_push($arr, $this->computeTotalWinStats());
        array_push($arr, $this->computeWinrate());
        array_push($arr, $this->computeSmurfPercentCount());
        array_push($arr, $this->computeWOPercentCount());
        array_push($arr, $this->computeDropPercentCount());
        array_push($arr, $this->computeDrawPercentCount());

        //array_push($arr, $this->setMaxStreaks());
        //array_push($arr, $this->setRandomPercent());

        return $arr;
    }

    private function saveAllValues($arr)
    {
        DB::beginTransaction();
        try {
            $this->createGeneralStats($arr);
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
        }
    }

    private function createGeneralStats($arr)
    {
        $result = [];
        foreach ($arr as $row) {
            $result = array_merge($row, $result);
        }

        return GeneralStats::updateOrCreate(
            [
                'total_id' => $this->totalTableId,
            ],
            $result
        );
    }

    private function computeSmurfPercentCount(): ?array
    {
        $smurfsCount = GameStat::whereUserId($this->userId)
            ->whereSeasonId($this->seasonId)
            ->whereIsSmurf(true)
            ->count();

        $smurfsPercent = $this->noWO > 0 ? $smurfsCount / $this->noWO * 100 : 0;

        return [
            'smurfs_count' => $smurfsCount,
            'smurfs_percent' => round((float)$smurfsPercent, 2),
        ];
    }

    private function computeWOPercentCount(): ?array
    {
        $woPercent = $this->gamesCount > 0 ? $this->wo / $this->gamesCount * 100 : 0;

        return [
            'wo_count' => $this->wo,
            'wo_percent' => round((float)$woPercent, 2),
        ];
    }

    private function setRandomPercent(): ?array
    {
        $games = GameStat::whereUserId($this->userId)
            ->whereSeasonId($this->seasonId)
            ->get();

        $totalGames = $games->count();
        $randomGames = $games->filter(function ($game) {
            return $game->enemy_random_race_id != null;
        })->count();

        $randomPercent = $totalGames > 0 ? $randomGames / $totalGames * 100 : 0;

        return ['random_percent' => round((float)$randomPercent, 2)];
    }

    private function setMaxStreaks(): ?array
    {
        $winResultId = Result::whereName('W')->first()->id;
        $defeatResultId = Result::whereName('L')->first()->id;

        $gameStats = GameStat::whereUserId($this->userId)
            ->whereSeasonId($this->seasonId)
            ->orderBy('created_at', 'asc')
            ->get();

        $maxWins = $this->computeGameResultStreak($gameStats, $winResultId);
        $maxDefeats = $this->computeGameResultStreak($gameStats, $defeatResultId);

        return [
            'max_wins' => $maxWins,
            'max_defeats' => $maxDefeats,
        ];
    }

    private function computeWinrate(): ?array
    {
        $generalWinratePercent = $this->gamesCount > 0 ? ($this->wins + $this->wo) / $this->gamesCount * 100 : 0;
        $winrateRealPercent = $this->gamesCount > 0 ? $this->wins / ($this->wins + $this->defeats) * 100 : 0;

        return [
            'general_winrate_percent' => round((float)$generalWinratePercent, 2),
            'winrate_real_percent' => round((float)$winrateRealPercent, 2),
        ];
    }

    private function computeDropPercentCount(): ?array
    {
        $dropPercent = $this->gamesCount > 0 ? $this->drop / $this->gamesCount * 100 : 0;

        return [
            'drop_count' => $this->drop,
            'drop_percent' => round((float)$dropPercent, 2),
        ];
    }

    private function computeDrawPercentCount(): ?array
    {
        $drawPercent = $this->gamesCount > 0 ? $this->draw / $this->gamesCount * 100 : 0;

        return [
            'drop_count' => $this->draw,
            'drop_percent' => round((float)$drawPercent, 2),
        ];
    }

    private function computeTotalWinStats(): ?array
    {
        return [
            'games_count' => $this->gamesCount,
            'general_stats_count' => "{$this->wins}W - {$this->defeats}L",
            'real_stats_count' => "{$this->wins}W - {$this->defeats}L - {$this->wo}WO",
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
