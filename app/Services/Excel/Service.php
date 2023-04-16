<?php


namespace App\Services\Excel;


use App\Models\GameStat;
use App\Models\Map;
use App\Models\Race;
use App\Models\Result;
use App\Models\Season;
use App\Models\Total;
use GeneralizedStats;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Throwable;

class Service
{
    public function importFile($excel_file, $season_number)
    {
        $data = $this->addSeasonColumnToData($excel_file);
        $season_id = Season::where('season_number', $season_number)->first()->id;
        $this->insertIntoDB($data, $season_id);

        return response()->json(['message' => 'success', 200]);
    }

    private function insertIntoDB($data, $season_id)
    {
        DB::beginTransaction();
        try {
            foreach ($data as $row) {
                GameStat::withoutEvents(function () use ($row, $season_id) {
                    $myRace = $this->getRaceId($row[2][0]);
                    $enemyRace = $this->getRaceId($row[2][2]);
                    $enemyRandomRace = $this->getRandomRace($row[2]);

                    $result_id = $this->getResultId($row[5]);
                    $map_id = $this->getMapId($row[1]);

                    $enemyCurrentMmr = intval($row[3]);
                    $enemyMaxMmr = intval($row[4]);
                    $isSmurf = false;
                    if ($enemyCurrentMmr > 0 && $enemyMaxMmr > 0) {
                        $isSmurf = $enemyMaxMmr - $enemyCurrentMmr >= 200;
                    }

                    GameStat::create([
                        'user_id' => Auth::id(),
                        'season_id' => $season_id,
                        'game_number' => intval($row[0]),
                        'map_id' => $map_id,
                        'my_race_id' => $myRace,
                        'enemy_random_race_id' => $enemyRandomRace,
                        'enemy_race_id' => $enemyRace,
                        'enemy_current_mmr' => $enemyCurrentMmr,
                        'enemy_max_mmr' => $enemyMaxMmr,
                        'result_id' => $result_id,
                        'result_comment' => $row[6],
                        'enemy_nickname' => $row[7],
                        'enemy_login' => strtolower($row[8]),
                        'global_comment' => $row[9],
                        'is_smurf' => $isSmurf,
                    ]);
                });
                DB::commit();
            }
        } catch (Throwable $e) {
            DB::rollback();
        }
        $total = Total::firstOrCreate([
            'user_id' => Auth::id(),
            'season_id' => $season_id,
        ]);
        GeneralizedStats::setAllBestStats($season_id, $total->id);
    }

    private function addSeasonColumnToData($excel_file): array
    {
        $spreadsheet = IOFactory::load($excel_file);

        $worksheet = $spreadsheet->getActiveSheet();
        $data = array_filter($worksheet->toArray(), function ($row) {
            return !empty(array_filter($row));
        });

        return $data;
    }

    private function getRaceId(string $raceLetter): ?string
    {
        $raceName = "";
        switch (strtoupper($raceLetter)) {
            case "P":
                $raceName = "Protoss";
                break;
            case "T":
                $raceName = "Terran";
                break;
            case "Z":
                $raceName = "Zerg";
                break;
            default:
                $raceName = "Random";
                break;
        }
        return Race::firstOrCreate([
            'name' => $raceName
        ])->id;
    }

    private function getRandomRace($string): ?string
    {
        $pattern = "/\((.*?)\)/";
        preg_match_all($pattern, $string, $matches);
        if (array_key_exists(1, $matches) &&
            array_key_exists(0, $matches[1])) {
            return $this->getRaceId($matches[1][0]);
        }
        return null;
    }

    private function getMapId($name): int
    {
        return Map::firstOrCreate([
            'name' => ucfirst(strtolower($name))
        ])->id;
    }

    private function getResultId($name): int
    {
        return Result::firstOrCreate([
            'name' => strtoupper($name)
        ])->id;
    }
}
