<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('streams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('game_id',false, true);
            $table->string('title');
            $table->string('streamer_id');
            $table->string('stream_id');
            $table->enum('service_name', ['twitch', 'youtube']);
            $table->string('language', 10)->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->foreign('game_id')->references('id')->on('games')->onDelete('cascade');
            $table->index(['stream_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('streams');
    }
}