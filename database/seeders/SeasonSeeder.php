<?php

namespace Database\Seeders;

use App\Models\Season;
use Illuminate\Database\Seeder;

class SeasonSeeder extends Seeder
{
    public int $startSeasonNumber = 10;
    public int $rowsCount = 30;
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = $this->startSeasonNumber; $i <= $this->rowsCount; $i++)
        {
            Season::create([
                'season_number' => $i,
            ]);
        }
    }
}
