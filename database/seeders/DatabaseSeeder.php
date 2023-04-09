<?php

namespace Database\Seeders;

use App\Models\Result;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SeasonSeeder::class,
            MapSeeder::class,
            ResultSeeder::class,
        ]);
    }
}
