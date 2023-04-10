<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_stat_total_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('season_id')->constrained();
            $table->float('smurf_percent');
            $table->float('wo_percent');
            $table->integer('max_wins');
            $table->integer('max_defeats');
            $table->integer('total_games');
            $table->integer('general_wins');
            $table->integer('real_wins');

            $table->string('min_season_mmr')->nullable();
            $table->string('max_season_mmr')->nullable();
            $table->string('final_season_mmr')->nullable();
            $table->string('placement_matches')->nullable();
            $table->string('season_started')->nullable();
            $table->string('season_ended')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_stat_total_values');
    }
};
