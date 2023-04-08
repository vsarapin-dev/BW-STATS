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
            $table->integer('user_id');
            $table->integer('total_games');
            $table->integer('real_wins');
            $table->integer('general_wins');
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
