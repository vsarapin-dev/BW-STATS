<?php

namespace Database\Seeders;

use App\Models\Map;
use Illuminate\Database\Seeder;

class MapSeeder extends Seeder
{
    public array $maps = [
        'Fs',
        'Vermeer',
        'Polypoid',
        'Eclipse',
    ];
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->maps as $map)
        {
            Map::create([
                'name' => $map,
            ]);
        }
    }
}
