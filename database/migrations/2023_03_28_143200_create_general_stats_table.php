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
        Schema::create('general_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('total_id')->constrained()->onDelete('cascade');
            $table->bigInteger('games_count')->default(0);
            $table->string('general_stats_count')->default(0);
            $table->string('real_stats_count')->default(0);
            $table->float('general_winrate_percent')->default(0);
            $table->float('winrate_real_percent')->default(0);
            $table->bigInteger('smurfs_count')->default(0);
            $table->float('smurfs_percent')->default(0);
            $table->bigInteger('wo_count')->default(0);
            $table->float('wo_percent')->default(0);
            $table->bigInteger('drop_count')->default(0);
            $table->float('drop_percent')->default(0);
            $table->bigInteger('draw_count')->default(0);
            $table->float('draw_percent')->default(0);

            /*$table->float('smurf_percent');
            $table->float('wo_percent');
            $table->float('random_percent');
            $table->integer('max_wins');
            $table->integer('max_defeats');
            $table->integer('total_games');
            $table->string('general_wins');
            $table->string('real_wins');



            $table->string('min_season_mmr')->nullable();
            $table->string('max_season_mmr')->nullable();
            $table->string('final_season_mmr')->nullable();
            $table->string('placement_matches')->nullable();
            $table->string('season_started')->nullable();
            $table->string('season_ended')->nullable();*/

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
