<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreamsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('streams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_id', false, true);
            $table->string('title');
            $table->string('streamer_id');
            $table->string('stream_id');
            $table->enum('service_name', ['twitch', 'youtube']);
            $table->string('language', 10)->nullable();
            $table->integer('viewers_count');
            $table->dateTime('period_from');
            $table->dateTime('period_to');
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->index(['stream_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('streams');
    }
}
