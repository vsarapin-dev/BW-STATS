<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameStatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_stats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('season_id')->constrained();
            $table->foreignId('result_id')->constrained();
            $table->foreignId('map_id')->constrained();
            $table->integer('game_number');
            $table->string('enemy_nickname')->nullable();
            $table->string('enemy_login')->nullable();
            $table->string('my_race_id');
            $table->string('enemy_random_race_id')->nullable();
            $table->string('enemy_race_id');
            $table->integer('enemy_current_mmr')->nullable();
            $table->integer('enemy_max_mmr')->nullable();
            $table->boolean('is_smurf')->default(false);
            $table->boolean('is_leaver')->default(false);
            $table->boolean('is_not_calibrated')->default(false);
            $table->boolean('is_dropped')->default(false);
            $table->string('result_comment')->nullable();
            $table->string('global_comment')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_stats');
    }
}
