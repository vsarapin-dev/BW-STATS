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
        Schema::create('map_races', function (Blueprint $table) {
            $table->id();
            $table->foreignId('total_id')->constrained()->onDelete('cascade');
            $table->foreignId('map_id')->constrained()->onDelete('cascade');;
            $table->foreignId('enemy_race_id')->constrained('races')->onDelete('cascade');
            $table->foreignId('my_race_id')->constrained('races')->onDelete('cascade');
            $table->string('map_name');
            $table->string('matchup_shorthand');
            $table->bigInteger('total_losses');
            $table->bigInteger('total_wins');
            $table->float('win_percentage');
            $table->bigInteger('games_played');
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
        Schema::dropIfExists('map_races');
    }
};
