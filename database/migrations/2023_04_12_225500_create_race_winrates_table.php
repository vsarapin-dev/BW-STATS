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
        Schema::create('race_winrates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('total_id')->constrained()->onDelete('cascade');
            $table->bigInteger('my_race');
            $table->bigInteger('enemy_race');
            $table->float('win_percentage');
            $table->bigInteger('games_played');
            $table->string('matchup_shorthand');
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
        Schema::dropIfExists('race_winrates');
    }
};
