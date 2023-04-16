<?php


namespace App\Facades\GeneralizedStats\StatsSaver;


use App\Models\BestMap;
use App\Models\GameStat;
use App\Models\GeneralStats;
use App\Models\MapRace;
use App\Models\MapWinrate;
use App\Models\MmrMapRace;
use App\Models\MmrWinrate;
use App\Models\RaceWinrate;
use Illuminate\Support\Facades\Auth;

abstract class BaseStatsSaver
{
    protected bool $dataRetrieved = false;

    protected int $seasonId;
    protected int|null|string $userId;
    protected int $totalTableId;
    protected int $gamesCount;
    protected int $wins;
    protected int $defeats;
    protected int $wo;
    protected int $noWO;
    protected int $drop;
    protected int $draw;

    abstract public function computeAndSaveData();

    public function retrieveData($seasonId, $totalTableId) {
        if (!$this->dataRetrieved) {
            $this->dataRetrieved = $this->setAllVariables($seasonId, $totalTableId);
            $this->removeExistingData();
        }
    }

    protected function removeExistingData()
    {
        GeneralStats::whereTotalId($this->totalTableId)->delete();
        BestMap::whereTotalId($this->totalTableId)->delete();
        MapWinrate::whereTotalId($this->totalTableId)->delete();
        RaceWinrate::whereTotalId($this->totalTableId)->delete();
        MmrWinrate::whereTotalId($this->totalTableId)->delete();
        MapRace::whereTotalId($this->totalTableId)->delete();
        MmrMapRace::whereTotalId($this->totalTableId)->delete();
    }

    private function setAllVariables($seasonId, $totalTableId)
    {
        $this->setSeasonId($seasonId);
        $this->setTotalTableId($totalTableId);
        $this->setUserId();
        $this->setGamesCount();
        $this->setWinsCount();
        $this->setLossCount();
        $this->setWOCount();
        $this->setNoWOCount();
        $this->setDropCount();
        $this->setDrawCount();

        return true;
    }

    private function setSeasonId($seasonId)
    {
        $this->seasonId = $seasonId;
    }

    private function setUserId()
    {
        $this->userId = Auth::id();
    }

    private function setTotalTableId($totalTableId)
    {
        $this->totalTableId = $totalTableId;
    }
    private function setGamesCount()
    {
        $this->gamesCount = GameStat::whereUserId($this->userId)->whereSeasonId($this->seasonId)->count();
    }
    private function setWinsCount()
    {
        $this->wins = GameStat::whereUserId($this->userId)
            ->whereSeasonId($this->seasonId)
            ->whereHas('result', function ($query) {
                return $query->where('name', 'W');
            })->count();
    }
    private function setLossCount()
    {
        $this->defeats = GameStat::whereUserId($this->userId)
            ->whereSeasonId($this->seasonId)
            ->whereHas('result', function ($query) {
                return $query->where('name', 'L');
            })->count();
    }
    private function setWOCount()
    {
        $this->wo = GameStat::whereUserId($this->userId)
            ->whereSeasonId($this->seasonId)
            ->whereHas('result', function ($query) {
                return $query->where('name', 'W/O');
            })->count();
    }
    private function setNoWOCount()
    {
        $this->noWO = GameStat::whereUserId($this->userId)
            ->whereSeasonId($this->seasonId)
            ->whereHas('result', function ($query) {
                return $query->where('name', '!=', 'W/O');
            })->count();
    }
    private function setDropCount()
    {
        $this->drop = GameStat::whereUserId($this->userId)
            ->whereSeasonId($this->seasonId)
            ->whereHas('result', function ($query) {
                return $query->where('name', 'DROP');
            })->count();
    }
    private function setDrawCount()
    {
        $this->draw = GameStat::whereUserId($this->userId)
            ->whereSeasonId($this->seasonId)
            ->whereHas('result', function ($query) {
                return $query->where('name', 'DRAW');
            })->count();
    }
}
