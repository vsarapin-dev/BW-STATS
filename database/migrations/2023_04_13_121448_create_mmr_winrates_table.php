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
        Schema::create('mmr_winrates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('total_id')->constrained()->onDelete('cascade');
            $table->foreignId('mmr_rank_id')->constrained();
            $table->float('win_percentage');
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
        Schema::dropIfExists('mmr_winrates');
    }
};
