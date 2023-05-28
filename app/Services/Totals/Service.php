<?php


namespace App\Services\Totals;


use App\Http\Resources\BestMap\BestMapResource;
use App\Http\Resources\GeneralStats\GeneralStatsResource;
use App\Http\Resources\MapRace\MapRaceResource;
use App\Http\Resources\MapWinrate\MapWinrateResource;
use App\Http\Resources\MmrMapRace\MmrMapRaceResource;
use App\Http\Resources\MmrWinrate\MmrWinrateResource;
use App\Http\Resources\RaceWinrate\RaceWinrateResource;
use App\Http\Resources\Season\SeasonResource;
use App\Models\BestMap;
use App\Models\GameStat;
use App\Models\GeneralStats;
use App\Models\MapRace;
use App\Models\MapWinrate;
use App\Models\MmrMapRace;
use App\Models\MmrWinrate;
use App\Models\RaceWinrate;
use App\Models\Season;
use App\Models\Total;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Service
{
    public function generalStats(Request $data): JsonResponse
    {
        $generalStats = GeneralStats::whereTotalId($this->totalRow($data)?->id)->first();
        return response()->json([
            'general_stats' => $generalStats ? new GeneralStatsResource($generalStats) : null,
        ]);
    }

    public function seasons(): JsonResponse
    {
        $seasonId = GameStat::whereUserId(Auth::id())->max('season_id');
        $selectedSeason = new SeasonResource(Season::whereId($seasonId)->first());

        if ($selectedSeason == null)
        {
            $selectedSeason = GameStat::whereUserId(Auth::id())->max('season_id') ?? null;
        }

        return response()->json([
            'available_seasons' => SeasonResource::collection(
                Season::whereIn('id', function ($query) {
                    $query->select('season_id')->distinct()->from('game_stats');
                })->get()),
            'selected_season' => $selectedSeason,
        ]);
    }

    public function bestMaps(Request $data): JsonResponse
    {
        $bestMaps = BestMap::whereTotalId($this->totalRow($data)?->id)->get();
        return response()->json([
            'best_maps' => $bestMaps ? BestMapResource::collection($bestMaps) : null,
        ]);
    }

    public function mapsWinrate(Request $data): JsonResponse
    {
        $mapWinrate = MapWinrate::whereTotalId($this->totalRow($data)?->id)->get();
        return response()->json([
            'maps_winrate' => $mapWinrate ? MapWinrateResource::collection($mapWinrate) : null,
        ]);
    }

    public function mmrWinrate(Request $data): JsonResponse
    {
        $mmrWinrate = MmrWinrate::whereTotalId($this->totalRow($data)?->id)->get();
        return response()->json([
            'mmr_winrate' => $mmrWinrate ? MmrWinrateResource::collection($mmrWinrate) : null,
        ]);
    }

    public function mapRace(Request $data): JsonResponse
    {
        $mapRace = MapRace::whereTotalId($this->totalRow($data)?->id)->get();
        return response()->json([
            'map_race' => $mapRace ? MapRaceResource::collection($mapRace)->collection->groupBy('map_name') : null,
        ]);
    }

    public function mmpMapRace(Request $data): JsonResponse
    {
        $mmrMapRace = MmrMapRace::whereTotalId($this->totalRow($data)?->id)->get();
        return response()->json([
            'mmr_map_race' => $mmrMapRace ?
                MmrMapRaceResource::collection($mmrMapRace)
                    ->collection
                    ->groupBy('rank_name')
                    ->map(function ($item) {
                        return $item->groupBy('map_name');
                    }) :
                null,
        ]);
    }

    public function racesWinrate(Request $data): JsonResponse
    {
        $racesWinrate = RaceWinrate::whereTotalId($this->totalRow($data)?->id)->get();
        return response()->json([
            'races_winrate' => $racesWinrate ? RaceWinrateResource::collection($racesWinrate) : null,
        ]);
    }

    private function totalRow($data): Total
    {
        return Total::whereUserId(Auth::id())->whereSeasonId($data['season_id'])->first();
    }
}
